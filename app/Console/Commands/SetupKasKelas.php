<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupKasKelas extends Command
{
    protected $signature = 'kas-kelas:setup';
    protected $description = 'Setup aplikasi kas kelas dengan data awal';

    public function handle()
    {
        $this->info('🚀 Setup Aplikasi Kas Kelas...');
        
        // Clear cache
        $this->info('Membersihkan cache...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        // Run migrations
        $this->info('Menjalankan migrasi database...');
        Artisan::call('migrate:fresh');
        
        // Seed database
        $this->info('Mengisi data awal...');
        Artisan::call('db:seed');
        
        // Generate key if not exists
        if (empty(config('app.key'))) {
            $this->info('Generate application key...');
            Artisan::call('key:generate');
        }
        
        $this->info('✅ Setup selesai!');
        $this->newLine();
        $this->info('Login credentials:');
        $this->info('Bendahara: bendahara@kelas.com / password123');
        $this->info('Siswa: ahmad@siswa.com / password123');
        $this->newLine();
        $this->info('Jalankan: php artisan serve');
    }
}