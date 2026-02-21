<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Support;

class SupportController extends Controller
{
    public function index()
    {
        $supports = Support::latest()->get();
        return view('member.support.index', compact('supports'));
    }
}