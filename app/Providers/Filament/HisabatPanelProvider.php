<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class HisabatPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn (): string => Blade::render('<x-dua-navigation-script />')
        );
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('hisabat')
            ->path('hisabat')
            ->login()
            ->databaseNotifications()
            ->brandName('EHITISAB DAILY')
            ->colors([
                'danger' => [
                    50 => 'oklch(0.950 0.040 32.727)',
                    100 => 'oklch(0.920 0.060 32.727)',
                    200 => 'oklch(0.880 0.080 32.727)',
                    300 => 'oklch(0.820 0.100 32.727)',
                    400 => 'oklch(0.720 0.130 32.727)',
                    500 => 'oklch(0.5523 0.1927 32.7272)',
                    600 => 'oklch(0.500 0.180 32.727)',
                    700 => 'oklch(0.420 0.160 32.727)',
                    800 => 'oklch(0.350 0.140 32.727)',
                    900 => 'oklch(0.3123 0.0852 29.7877)',
                    950 => 'oklch(0.250 0.060 32.727)',
                ],
                'gray' => [
                    50 => 'oklch(0.9911 0 0)',
                    100 => 'oklch(0.9731 0 0)',
                    200 => 'oklch(0.9461 0 0)',
                    300 => 'oklch(0.9037 0 0)',
                    400 => 'oklch(0.7348 0 0)',
                    500 => 'oklch(0.5452 0 0)',
                    600 => 'oklch(0.4386 0 0)',
                    700 => 'oklch(0.3132 0 0)',
                    800 => 'oklch(0.2809 0 0)',
                    900 => 'oklch(0.2435 0 0)',
                    950 => 'oklch(0.2046 0 0)',
                ],
                'info' => [
                    50 => 'oklch(0.950 0.030 259.814)',
                    100 => 'oklch(0.900 0.050 259.814)',
                    200 => 'oklch(0.850 0.070 259.814)',
                    300 => 'oklch(0.780 0.100 259.814)',
                    400 => 'oklch(0.700 0.130 259.814)',
                    500 => 'oklch(0.6231 0.1880 259.8145)',
                    600 => 'oklch(0.570 0.170 259.814)',
                    700 => 'oklch(0.7137 0.1434 254.6240)',
                    800 => 'oklch(0.450 0.130 259.814)',
                    900 => 'oklch(0.380 0.100 259.814)',
                    950 => 'oklch(0.300 0.080 259.814)',
                ],
                'primary' => [
                    50 => 'oklch(0.950 0.020 160.908)',
                    100 => 'oklch(0.900 0.040 160.908)',
                    200 => 'oklch(0.850 0.060 160.908)',
                    300 => 'oklch(0.780 0.080 160.908)',
                    400 => 'oklch(0.700 0.100 160.908)',
                    500 => 'oklch(0.620 0.140 160.908)',
                    600 => 'oklch(0.550 0.150 160.908)',
                    700 => 'oklch(0.480 0.160 160.908)',
                    800 => 'oklch(0.4365 0.1044 156.7556)',
                    900 => 'oklch(0.380 0.090 160.908)',
                    950 => 'oklch(0.2626 0.0147 166.4589)',
                ],
                'success' => [
                    50 => 'oklch(0.950 0.020 160.908)',
                    100 => 'oklch(0.900 0.040 160.908)',
                    200 => 'oklch(0.850 0.060 160.908)',
                    300 => 'oklch(0.780 0.080 160.908)',
                    400 => 'oklch(0.700 0.100 160.908)',
                    500 => 'oklch(0.6959 0.1491 162.4796)',
                    600 => 'oklch(0.620 0.130 160.908)',
                    700 => 'oklch(0.550 0.120 160.908)',
                    800 => 'oklch(0.480 0.100 160.908)',
                    900 => 'oklch(0.400 0.080 160.908)',
                    950 => 'oklch(0.320 0.060 160.908)',
                ],
                'warning' => [
                    50 => 'oklch(0.950 0.030 70.080)',
                    100 => 'oklch(0.900 0.050 70.080)',
                    200 => 'oklch(0.850 0.080 70.080)',
                    300 => 'oklch(0.800 0.110 70.080)',
                    400 => 'oklch(0.750 0.140 70.080)',
                    500 => 'oklch(0.7686 0.1647 70.0804)',
                    600 => 'oklch(0.700 0.150 70.080)',
                    700 => 'oklch(0.8369 0.1644 84.4286)',
                    800 => 'oklch(0.580 0.130 70.080)',
                    900 => 'oklch(0.500 0.110 70.080)',
                    950 => 'oklch(0.400 0.090 70.080)',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->navigationItems([
                NavigationItem::make('Back to Home')
                    ->url('/')
                    ->icon('heroicon-o-arrow-left')
                    ->sort(-2),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
