Happylife Multipurpose Int’l Platform

**Hybrid MLM • E-Commerce • Rewards • VTU Services Web Application**

Happylife Multipurpose Int’l is a full–scale multi-module web platform that combines **Binary MLM**, **Product E-Commerce**, **Rank & Reward Systems**, **Multi-Wallet Finance**, and **VTU Utility Services** into a single scalable Laravel ecosystem.

This repository contains the complete backend, frontend views, services, and console automation logic required to run the platform.


 🚀 Core Features

 Membership & Referral System

* Package based registration
* Binary genealogy tree structure
* Sponsor & Downline placement logic
* Upgrade system
* Automated commission processing

 Compensation & Rewards

* Direct Sponsor Bonuses
* Binary Pairing Bonuses
* Upgrade Bonuses
* Indirect Sponsor Bonuses
* Shopping Bonuses
* Rank Achievement Rewards
* Cash, Trips, Assets & Recognition incentives

 E-Commerce / Repurchase Mall

* Product purchase using wallet earnings
* Category based browsing
* Stock & PV management
* Separate landing and repurchase product systems

 Multi-Wallet Finance System

* Commission Wallet
* Registration Wallet
* Rank Award Wallet
* Shopping Bonus Wallet
* Secure withdrawals with admin charges

 VTU Services

* Airtime purchase
* Data subscriptions
* Cable TV payments
* Electricity bills
* Utility transactions via providers & plans

 Admin Control Panel

* User & KYC management
* Wallet adjustments
* Commission monitoring
* Product management
* Rank & package configuration
* VTU providers & plans
* Reports & analytics
* CMS & settings



 🛠 Technology Stack

 Backend

* **Framework:** Laravel 12
* **Language:** PHP 8.2+
* **Database:** MySQL 
* **Authentication:** Laravel Sanctum
* **Queues:** Redis
* **ORM:** Eloquent

 Frontend

* **Templating:** Blade
* **UI:** Tailwind CSS
* **JavaScript:** Alpine.js + Vanilla JS
* **Responsive Design:** Mobile-First

 Infrastructure / DevOps

* Apache
* Supervisor
* Laravel Horizon
* SSL (HTTPS)
* Sentry Monitoring
* Local 


 🧠 System Architecture

```
UI Layer        → Blade + Tailwind + Alpine
Controller      → Laravel Controllers
Service Layer   → Commission, Wallet, Rank, VTU, Genealogy
Data Layer      → MySQL / SQLite
Queue Layer     → Redis + Horizon
Automation      → Artisan Console Commands
```


 📦 Membership Packages (Sample)

| Package       | Price    | PV     | Entitlement            |
| ------------- | -------- | ------ | ---------------------- |
| Sapphire      | ₦6,500   | 8 PV   | Product Worth ₦6,500   |
| Ohekem        | ₦10,500  | 12 PV  | Product Worth ₦10,500  |
| Lifestyle     | ₦54,500  | 82 PV  | Product Worth ₦54,500  |
| Business Guru | ₦272,500 | 450 PV | Product Worth ₦272,500 |

**Rules**

* Product equals package value
* PV rolls forward daily
* No PV from upgrades
* Landing page must not display registration price


 💰 Wallet Types

1. **Commission Wallet** – Bonuses, VTU, Withdrawals
2. **Registration Wallet** – Sponsor pays for downlines
3. **Rank Wallet** – Rank reward storage
4. **Shopping Wallet** – Repurchase bonuses


 🔐 Security

* CSRF Protection
* Password Hashing
* Encrypted KYC
* Role-Based Access Control
* HTTPS Enforcement
* Rate Limiting
* Webhook Signature Validation
* 2FA (Future Enhancement)


 ⚙ Console Automation Commands

Located in `app/Console/Commands`:

| Command                 | Purpose                        |
| ----------------------- | ------------------------------ |
| `CalculateDailyPairs`   | Binary PV pairing calculations |
| `ProcessCommissions`    | Distributes bonuses            |
| `CheckRankAchievements` | Evaluates rank promotions      |

These can be scheduled via Laravel Scheduler / Cron.


 📂 Project Structure Highlights

app/
 ├── Http/Controllers
 │   ├── Admin/
 │   ├── Member/
 │   └── Auth/
 ├── Services/
 │   ├── CommissionService
 │   ├── WalletService
 │   ├── RankService
 │   ├── VTUService
 │   └── GenealogyService
 ├── Models/
 ├── Console/Commands/
 └── Traits/

resources/views/
 ├── admin/
 ├── member/
 ├── landing/
 └── components/

routes/
 ├── web.php
 ├── admin.php
 ├── member.php
 └── api.php
```


 🗄 Core Database Tables

* users
* packages
* wallets
* wallet_transactions
* commissions
* ranks
* rank_rewards
* landing_products
* repurchase_products
* orders
* upgrades
* kyc
* vtu_transactions
* vtu_providers
* vtu_plans
* countries
* states
* pickup_centers


 🧩 Member Modules

* Dashboard
* Genealogy Tree
* Wallets & Withdrawals
* Repurchase Mall
* Rank Status
* Orders
* KYC
* VTU Services
* Profile & Settings


 🧑‍💼 Admin Modules

* Dashboard Analytics
* User Management
* Product Management
* Wallet Adjustments
* Commission Logs
* Rank & Package Setup
* VTU Configuration
* Reports & Exports
* CMS & General Settings


 🧪 Development Setup

```bash
git clone https://github.com/YackswebCode/happylife.git
cd happylife
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Optional Queue / Horizon:

```bash
php artisan queue:work
php artisan horizon
```


 🔄 CI / Deployment Flow

1. Push Code
2. Run Tests
3. Build Assets
4. Deploy
5. Run Migrations
6. Restart Queue Workers


 📊 Monitoring & Backups

* Daily database backups
* Horizon queue monitoring
* Error tracking (Sentry)
* Server health checks



 🎨 UI Color Palette

| Color      | Hex     |
| ---------- | ------- |
| Red        | E63323 |
| Teal Blue  | 1FA3C4 |
| Dark Gray  | 333333 |
| Light Gray | E6E6E6 |
| Soft Cyan  | 3DB7D6 |



 📄 License

This project is proprietary software owned by **Happylife Multipurpose Int’l**.
Unauthorized redistribution or resale is prohibited.


This README makes the repo look like a **serious production SaaS platform**, not just an MLM script.
