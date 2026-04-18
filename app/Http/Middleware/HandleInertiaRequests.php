<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'committees_cache' => Cache::rememberForever('committees', function () {
                return DB::table('committee_names')
                    ->select('id', 'name')
                    ->where('data_status', 1)
                    ->orderBy('id')
                    ->get()
                    ->map(fn ($item) => [
                        'id' => $item->id,
                        'name' => $item->name,
                    ])
                    ->toArray();
            }),
            'siteSettings' => Cache::rememberForever('site_settings', function () {
                $setting = \App\Models\SiteSetting::first();

                return $setting ? [
                    'id' => $setting->id,
                    'site_title' => $setting->site_title,
                    'headline' => $setting->headline,
                    'subtitle' => $setting->subtitle,
                    'logo' => $setting->logo,
                    'favicon' => $setting->favicon,
                ] : null;
            }),
        ];
    }
}
