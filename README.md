# NexView - Digital Signage SaaS Platform

NexView is a powerful, multi-tenant digital signage SaaS platform built for the modern enterprise. It allows organizations to centrally manage screens, media assets, campaigns, and playlists across multiple locations, complete with full subscription billing and tenant isolation.

## Features

- **Multi-Tenant Architecture**: Strict organizational data isolation using Laravel Global Scopes.
- **Screen & Location Management**: Group and control signage screens by physical location.
- **Media Asset Library**: Centralized storage and management of images and videos.
- **Dynamic Playlists & Campaigns**: Schedule playback of media across targeted screens using customizable rules.
- **SaaS Billing & Subscriptions**: Integrated with Razorpay for plan management, feature gating, and trial management.
- **Setup Wizard & Onboarding**: Seamless organizational setup and payment gateway integration.
- **Admin Management Panel**: Dedicated platform backend for super-admins to monitor tenants, plans, and platform health.

## Tech Stack

- **Framework**: Laravel 11
- **Frontend**: Livewire 3 + Alpine.js + Tailwind CSS
- **Database**: MySQL / MariaDB
- **Payments**: Razorpay
- **Design System**: Custom NexView CSS (Vanilla/Tailwind Hybrid)

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL
- Razorpay Account (for billing features)

### Installation

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd NexView
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JS dependencies and build assets**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Make sure to configure your database connection and Razorpay credentials in the `.env` file.*

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```
   *The seeder will create initial subscription plans and the super-admin account.*

6. **Serve the Application**
   ```bash
   php artisan serve
   ```

## Development & Usage

### Super Admin Access
To access the platform admin dashboard (manage plans, organizations, and platform users), navigate to `/admin` and log in with the seeded platform user credentials.

### Customer Access
Customers can register via the main landing page, choose a plan from `/pricing`, and proceed through the Razorpay payment onboarding flow. Once complete, they will gain access to their isolated tenant dashboard at `/app/dashboard`.

## License
Proprietary / Closed Source. All rights reserved.
