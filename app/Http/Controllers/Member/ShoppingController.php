<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\RepurchaseProduct;
use App\Models\ProductCategory;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ShoppingController extends Controller
{
    /**
     * Display list of repurchase products (mall homepage).
     */
    public function index(Request $request)
    {
        $query = RepurchaseProduct::where('is_active', true)
                    ->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        $products = $query->orderBy('name')->paginate(12);
        $categories = ProductCategory::where('is_active', true)
                        ->withCount('repurchaseProducts')
                        ->get();

        return view('member.shopping.index', compact('products', 'categories'));
    }

    /**
     * Display all product categories.
     */
    public function categories()
    {
        $categories = ProductCategory::where('is_active', true)
                        ->withCount('repurchaseProducts')
                        ->get();

        return view('member.shopping.categories', compact('categories'));
    }

    /**
     * Show single product details.
     */
    public function show($id)
    {
        $product = RepurchaseProduct::with('category')->findOrFail($id);
        
        $relatedProducts = RepurchaseProduct::where('category_id', $product->category_id)
                            ->where('id', '!=', $product->id)
                            ->where('is_active', true)
                            ->limit(4)
                            ->get();

        return view('member.shopping.product', compact('product', 'relatedProducts'));
    }

    /**
     * View shopping cart.
     */
    public function cart()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $subtotal = 0;
        $bonus_earned = 0;

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = RepurchaseProduct::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $id => $quantity) {
                if (isset($products[$id])) {
                    $product = $products[$id];
                    $cartItems[$id] = [
                        'id'       => $product->id,
                        'name'     => $product->name,
                        'price'    => $product->price,
                        'pv'       => $product->pv_value,
                        'image'    => $product->image,
                        'quantity' => $quantity,
                    ];
                    $subtotal += $product->price * $quantity;
                    $bonus_earned += $quantity * 250;
                }
            }
        }

        return view('member.shopping.cart', compact('cartItems', 'subtotal', 'bonus_earned'));
    }

    /**
     * Add product to cart.
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:repurchase_products,id',
            'quantity'   => 'required|integer|min:1|max:99'
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;
        $quantity = $request->quantity;

        if (isset($cart[$productId])) {
            $cart[$productId] += $quantity;
        } else {
            $cart[$productId] = $quantity;
        }

        Session::put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => array_sum($cart),
                'message'    => 'Product added to cart'
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Update cart item quantity.
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:repurchase_products,id',
            'quantity'   => 'required|integer|min:1|max:99'
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId] = $request->quantity;
            Session::put('cart', $cart);
        }

        if ($request->wantsJson()) {
            $cartTotals = $this->calculateCartTotals();
            return response()->json(array_merge(
                ['success' => true],
                $cartTotals
            ));
        }

        return redirect()->route('member.shopping.cart')->with('success', 'Cart updated');
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:repurchase_products,id'
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        if ($request->wantsJson()) {
            $cartTotals = $this->calculateCartTotals();
            return response()->json(array_merge(
                ['success' => true],
                $cartTotals
            ));
        }

        return redirect()->route('member.shopping.cart')->with('success', 'Item removed');
    }

    /**
     * Process checkout – deduct from shopping wallet,
     * add repurchase bonus to shopping wallet AND to user's commission_wallet_balance,
     * create order, and update repurchase_bonus_total.
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('member.shopping.cart')->with('error', 'Your cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = RepurchaseProduct::whereIn('id', $productIds)->get()->keyBy('id');
        $subtotal = 0;
        $totalPv = 0;
        $items = [];

        foreach ($cart as $id => $quantity) {
            if (isset($products[$id])) {
                $product = $products[$id];
                $subtotal += $product->price * $quantity;
                $totalPv += $product->pv_value * $quantity;
                $items[] = [
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'price'      => $product->price,
                    'quantity'   => $quantity,
                    'pv'         => $product->pv_value,
                ];
            }
        }

        // Shopping wallet – auto‑creates if missing
        $shoppingWallet = $user->shoppingWallet;

        if (!$shoppingWallet || $shoppingWallet->balance < $subtotal) {
            return redirect()->route('member.shopping.cart')
                ->with('error', 'Insufficient shopping wallet balance. Please fund your wallet.');
        }

        $bonusAmount = collect($items)->sum(function ($item) {
            return $item['quantity'] * 250;
        });

        DB::beginTransaction();

        try {
            // 1. Deduct purchase amount from shopping wallet
            $shoppingWallet->balance -= $subtotal;
            $shoppingWallet->save();

            // 2. Create order
            $order = Order::create([
                'user_id'       => $user->id,
                'order_number'  => 'ORD-' . strtoupper(uniqid()),
                'subtotal'      => $subtotal,
                'total'         => $subtotal,
                'pv_total'      => $totalPv,
                'status'        => 'completed',
                'payment_status'=> 'paid',
                'payment_method'=> 'shopping_wallet',
                'items'         => json_encode($items),
            ]);

            // 3. Add repurchase bonus to shopping wallet (for future purchases)
            $shoppingWallet->balance += $bonusAmount;
            $shoppingWallet->save();

            // 4. Record debit transaction (purchase)
            WalletTransaction::create([
                'wallet_id'   => $shoppingWallet->id,
                'user_id'     => $user->id,
                'type'        => 'debit',
                'amount'      => $subtotal,
                'description' => 'Purchase from Repurchase Mall (Order #' . $order->order_number . ')',
                'reference'   => $order->order_number,
                'status'      => 'completed',
                'metadata'    => json_encode(['order_id' => $order->id]),
            ]);

            // 5. Record credit transaction (bonus)
            WalletTransaction::create([
                'wallet_id'   => $shoppingWallet->id,
                'user_id'     => $user->id,
                'type'        => 'credit',
                'amount'      => $bonusAmount,
                'description' => 'Repurchase Bonus (Order #' . $order->order_number . ')',
                'reference'   => $order->order_number . '-BONUS',
                'status'      => 'completed',
                'metadata'    => json_encode(['order_id' => $order->id]),
            ]);

            // 🆕 6. Update users table: track repurchase bonus total and add to commission wallet balance
            $user->repurchase_bonus_total += $bonusAmount;
            $user->commission_wallet_balance += $bonusAmount;
            $user->save();

            // 7. Clear the cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('member.shopping.receipt', ['order' => $order->id])
                ->with('success', 'Order placed successfully!')
                ->with('bonus_earned', $bonusAmount);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'cart'    => $cart
            ]);
            return redirect()->route('member.shopping.cart')
                ->with('error', 'Checkout failed. Please try again.');
        }
    }

    /**
     * Display order receipt.
     */
    public function receipt(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $bonus_earned = session('bonus_earned', 0);

        return view('member.shopping.receipt', compact('order', 'bonus_earned'));
    }

    /**
     * Private helper: calculate current cart totals for AJAX responses.
     */
    private function calculateCartTotals()
    {
        $cart = Session::get('cart', []);
        $cartCount = array_sum($cart);
        $subtotal = 0;
        $bonusEarned = 0;
        $items = [];

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = RepurchaseProduct::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $id => $quantity) {
                if (isset($products[$id])) {
                    $product = $products[$id];
                    $subtotal += $product->price * $quantity;
                    $bonusEarned += $quantity * 250;
                    $items[] = [
                        'id'       => $id,
                        'subtotal' => $product->price * $quantity
                    ];
                }
            }
        }

        return [
            'cart_count'   => $cartCount,
            'subtotal'     => $subtotal,
            'bonus_earned' => $bonusEarned,
            'items'        => $items
        ];
    }
}