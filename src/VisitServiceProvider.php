<?php

namespace Dinhdjj\Visit;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class VisitServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('visit')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_visits_table');
    }
}
