<?php

namespace App\Providers;

use View;
use App\Models\Contact;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env("APP_ENV") == 'production') {
            \URL::forceScheme('https');
        }
        $contact = Contact::get();
        View::share('contact', $contact);
    }
}
