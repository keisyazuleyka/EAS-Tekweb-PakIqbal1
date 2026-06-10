<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mengirimkan data $users ke seluruh file blade otomatis
        View::composer('*', function ($view) {
            $view->with('users', User::all());
        });
    }
}
