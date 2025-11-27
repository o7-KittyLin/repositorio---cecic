<?php

namespace App\Providers;

use App\Services\DoubleHash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Hash::extend('double', function () {
            return new class {
                protected $doubleHash;

                public function __construct()
                {
                    $this->doubleHash = new \App\Services\DoubleHash();
                }

                public function make($value, array $options = [])
                {
                    return $this->doubleHash->make($value);
                }

                public function check($value, $hashedValue, array $options = [])
                {
                    return $this->doubleHash->check($value, $hashedValue);
                }

                public function needsRehash($hashedValue, array $options = [])
                {
                    return false;
                }
            };
        });

    }
}
