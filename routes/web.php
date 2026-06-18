<?php

use App\Http\Controllers\Auth\PlatformAuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

use App\Livewire\App\Campaigns\CampaignCreate;
use App\Livewire\App\Campaigns\CampaignEdit;
use App\Livewire\App\Campaigns\CampaignIndex;
use App\Livewire\App\Dashboard as AppDashboard;
use App\Livewire\App\Locations\LocationCreate;
use App\Livewire\App\Locations\LocationEdit;
use App\Livewire\App\Locations\LocationIndex;
use App\Livewire\App\Media\MediaIndex;
use App\Livewire\App\Playlists\PlaylistCreate;
use App\Livewire\App\Playlists\PlaylistEdit;
use App\Livewire\App\Playlists\PlaylistIndex;
use App\Livewire\App\Screens\ScreenEdit;
use App\Livewire\App\Screens\ScreenIndex;
use App\Livewire\App\Screens\ScreenShow;
use App\Livewire\App\SetupWizard;
use App\Livewire\App\Users\UserCreate;
use App\Livewire\App\Users\UserEdit;
use App\Livewire\App\Users\UserIndex;
use App\Livewire\Profile\Edit;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WebController;

Route::get('/', [WebController::class, 'home'])->name('web.home');
Route::get('/pricing', [WebController::class, 'pricing'])->name('web.pricing');
Route::get('/features', [WebController::class, 'features'])->name('web.features');
Route::get('/privacy', [WebController::class, 'page'])->defaults('slug', 'privacy')->name('web.privacy');
Route::get('/terms', [WebController::class, 'page'])->defaults('slug', 'terms')->name('web.terms');
Route::get('/about', [WebController::class, 'page'])->defaults('slug', 'about')->name('web.about');
Route::get('/blogs', [WebController::class, 'blogs'])->name('web.blogs');
Route::get('/blogs/{slug}', [WebController::class, 'post'])->name('web.post');
Route::get('/page/{slug}', [WebController::class, 'page'])->name('web.page');

// Admin login (no auth middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        if (auth('platform')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    Route::middleware('guest:platform')->group(function () {
        Route::get('/login', [PlatformAuthController::class, 'create'])->name('login');
        Route::post('/login', [PlatformAuthController::class, 'store']);
    });

    Route::post('/logout', [PlatformAuthController::class, 'destroy'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth:platform'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/organizations', [\App\Http\Controllers\Admin\OrganizationController::class, 'index'])->name('organizations.index');
    Route::get('/organizations/create', [\App\Http\Controllers\Admin\OrganizationController::class, 'create'])->name('organizations.create');
    Route::get('/organizations/{organization}', [\App\Http\Controllers\Admin\OrganizationController::class, 'show'])->name('organizations.show');
    Route::get('/organizations/{organization}/edit', [\App\Http\Controllers\Admin\OrganizationController::class, 'edit'])->name('organizations.edit');

    Route::get('/tickets', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])->name('tickets.show');

    Route::get('/plans', [\App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/create', [\App\Http\Controllers\Admin\PlanController::class, 'create'])->name('plans.create');
    Route::get('/plans/{plan}/edit', [\App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit');

    Route::get('/pages', [\App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [\App\Http\Controllers\Admin\PageController::class, 'create'])->name('pages.create');
    Route::get('/pages/{page}/edit', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('pages.edit');

    Route::get('/blogs', [\App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [\App\Http\Controllers\Admin\BlogController::class, 'create'])->name('blogs.create');
    Route::get('/blogs/{post}/edit', [\App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('blogs.edit');

    Route::get('/platform-users', [\App\Http\Controllers\Admin\PlatformUserController::class, 'index'])->name('platform-users.index');
    Route::get('/platform-users/create', [\App\Http\Controllers\Admin\PlatformUserController::class, 'create'])->name('platform-users.create');
    Route::get('/platform-users/{user}/edit', [\App\Http\Controllers\Admin\PlatformUserController::class, 'edit'])->name('platform-users.edit');
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/settings', [\App\Http\Controllers\Admin\PlatformSettingsController::class, 'index'])->name('settings.index');

    Route::post('/impersonate/{user}', [\App\Http\Controllers\Admin\ImpersonationController::class, 'start'])->name('impersonate.start');
});

Route::prefix('app')->name('app.')->middleware(['auth', 'org.auth'])->group(function () {
    // Onboarding flow
    Route::get('/onboarding', \App\Livewire\App\Onboarding::class)->name('onboarding');
});

Route::prefix('app')->name('app.')->middleware(['auth', 'org.auth', 'manager.scope', 'onboarded'])->group(function () {
    Route::post('/impersonate/stop', [\App\Http\Controllers\Admin\ImpersonationController::class, 'stop'])->name('impersonate.stop');

    // Setup Wizard (Livewire)
    Route::get('/setup-wizard', SetupWizard::class)->name('setup.wizard');

    // Main App routes, requires onboarding
    Route::get('/dashboard', AppDashboard::class)->name('dashboard');

    // Livewire Locations
    Route::get('/locations', LocationIndex::class)->name('locations.index');
    Route::get('/locations/create', LocationCreate::class)->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::get('/locations/{location}/edit', LocationEdit::class)->name('locations.edit');

    // Livewire Screens
    Route::get('/screens', ScreenIndex::class)->name('screens.index');
    Route::get('/screens/{screen}', ScreenShow::class)->name('screens.show');
    Route::get('/screens/{screen}/edit', ScreenEdit::class)->name('screens.edit');

    // Livewire Media
    Route::get('/media', MediaIndex::class)->name('media.index');

    // Livewire Playlists
    Route::get('/playlists', PlaylistIndex::class)->name('playlists.index');
    Route::get('/playlists/create', PlaylistCreate::class)->name('playlists.create');
    Route::get('/playlists/{playlist}/edit', PlaylistEdit::class)->name('playlists.edit');

    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', UserEdit::class)->name('users.edit');

    // Campaigns (Livewire)
    Route::get('/campaigns', CampaignIndex::class)->name('campaigns.index');
    Route::get('/campaigns/create', CampaignCreate::class)->name('campaigns.create');
    Route::get('/campaigns/{campaign}/edit', CampaignEdit::class)->name('campaigns.edit');

    // Support Tickets (Livewire)
    Route::get('/support', \App\Livewire\App\Support\SupportIndex::class)->name('support.index');
    Route::get('/support/create', \App\Livewire\App\Support\SupportCreate::class)->name('support.create');
    Route::get('/support/{ticket}', \App\Livewire\App\Support\SupportShow::class)->name('support.show');

    // Organization Settings
    Route::get('/settings', \App\Livewire\App\Settings\SettingsIndex::class)->name('settings.index');
    Route::get('/billing', \App\Livewire\App\Settings\Billing::class)->name('billing.index');

    // Complete setup wizard (programmatic/testing support)
    Route::post('/setup/complete', function () {
        $organization = auth()->user()->organization;
        $organization->update(['is_onboarded' => true]);

        return response()->json(['status' => 'success']);
    })->name('setup.complete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', Edit::class)->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
