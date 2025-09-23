<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user (bendahara)
        $admin = User::create([
            'name' => 'Bendahara Kelas XII-A',
            'email' => 'bendahara@kelas.com',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // Create sample students
        $students = [
            ['name' => 'Ahmad Rizki', 'email' => 'ahmad@siswa.com', 'nis' => '2024001'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@siswa.com', 'nis' => '2024002'],
            ['name' => 'Budi Santoso', 'email' => 'budi@siswa.com', 'nis' => '2024003'],
            ['name' => 'Maya Sari', 'email' => 'maya@siswa.com', 'nis' => '2024004'],
            ['name' => 'Andi Wijaya', 'email' => 'andi@siswa.com', 'nis' => '2024005'],
        ];

        foreach ($students as $studentData) {
            User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'nis' => $studentData['nis'],
                'role' => 'user',
                'password' => Hash::make('password123'),
            ]);
        }

        // Create sample approved payments and transactions
        $users = User::where('role', 'user')->get();
        
        foreach ($users->take(3) as $user) {
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => rand(10000, 50000),
                'description' => 'Kas bulan ' . fake()->monthName(),
                'status' => 'approved',
                'approved_at' => now()->subDays(rand(1, 30)),
                'approved_by' => $admin->id,
            ]);

            Transaction::create([
                'type' => 'income',
                'amount' => $payment->amount,
                'description' => 'Pembayaran kas dari ' . $user->name,
                'payment_id' => $payment->id,
                'created_by' => $admin->id,
            ]);
        }

        // Create sample pending payments
        foreach ($users->skip(3) as $user) {
            Payment::create([
                'user_id' => $user->id,
                'amount' => rand(10000, 50000),
                'description' => 'Kas bulan ' . fake()->monthName(),
                'status' => 'pending',
            ]);
        }

        // Create sample expense transactions
        $expenses = [
            'Pembelian alat tulis kelas',
            'Biaya fotokopi soal ujian',
            'Pembelian perlengkapan kebersihan',
            'Biaya konsumsi rapat kelas',
            'Pembelian hiasan kelas',
        ];

        foreach ($expenses as $expense) {
            Transaction::create([
                'type' => 'expense',
                'amount' => rand(25000, 100000),
                'description' => $expense,
                'created_by' => $admin->id,
            ]);
        }
    }
}