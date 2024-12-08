<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    public function optimize()
    {
        Artisan::call('optimize');
        return "Application optimized!";
    }
    public function clearCache()
    {
        Artisan::call('cache:clear');
        return "Cache cleared!";
    }
    public function generateMigrationAndModel($tableName) {
        // Generate migration
        \Artisan::call('make:migration', [
            'name' => "create_{$tableName}_table",
        ]);
    
        // Generate model
        \Artisan::call('make:model', [
            'name' => ucfirst($tableName),
        ]);
        echo 'migration created';
    }
    function runSpecificMigration($migrationClassName) {
        \Artisan::call('migrate', [
            '--path' => "database/migrations/{$migrationClassName}.php",
        ]);
        echo 'migration done';
    }
    
}
