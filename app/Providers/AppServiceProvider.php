<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Event;
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
        
		Event::listen(Authenticated::class, function (Authenticated $event) {
            $user = $event->user;
            if ($user->timezone) {
                $timezone = $user->timezone;
                app('config')->set('app.timezone', $timezone);
				date_default_timezone_set($timezone);
            }
        });
		
		//
        User::saving(function ($model) {
            $user = User::whereEmail($model->email)->where("id", "!=", $model->id)->first();
            if ($user) {
                throw new \Exception("This email is already in use");
            }
        });

        Trainer::saving(function ($model) {
            $trainer = Trainer::whereEmail($model->email)->where("id", "!=", $model->id)->first();
            if ($trainer) {
                throw new \Exception("This email is already in use");
            }
        });

        \Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');
    }
}
