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
        $admin = User::create([
            'name' => 'Bendahara Kelas XII PPLG 2',
            'email' => 'bendahara@kelas.com',
            'role' => 'admin',
            'password' => Hash::make('bendahara123'),
        ]);

        echo "✅ Admin created: bendahara@kelas.com / bendahara123\n\n";

        $students = [
            ['name' => 'Adryan Arda Abyakta', 'nis' => '2333607'],
            ['name' => 'Ahmad Faisal', 'nis' => '2333608'],
            ['name' => 'Amelia Chasanah', 'nis' => '2333609'],
            ['name' => 'Aprilia Cahaya Kintani', 'nis' => '2333610'],
            ['name' => 'Aura Nabila Malva Lena', 'nis' => '2333611'],
            ['name' => 'Chelsy Siska Dewi', 'nis' => '2333612'],
            ['name' => 'Dania Alika Agis Alzahra', 'nis' => '2333613'],
            ['name' => 'Devi Trinia Ningrum', 'nis' => '2333614'],
            ['name' => 'Dewi Syafina', 'nis' => '2333615'],
            ['name' => 'Dwi Alfiah Wahyunigrum', 'nis' => '2333616'],
            ['name' => 'Elvina Azzahra', 'nis' => '2333617'],
            ['name' => 'Erlan Eka Putra Susanto', 'nis' => '2333618'],
            ['name' => 'Imanuel Tegar Nur Inprastio Arbianto', 'nis' => '2333619'],
            ['name' => 'Kaela Putri Amelia', 'nis' => '2333620'],
            ['name' => 'Muhammad Miftahul Haq', 'nis' => '2333621'],
            ['name' => 'Martina Tri Rahmayanti', 'nis' => '2333622'],
            ['name' => 'Melia Natasya', 'nis' => '2333623'],
            ['name' => 'Ndaru Ady Prasetia', 'nis' => '2333625'],
            ['name' => 'Nisrina Ramadhani', 'nis' => '2333626'],
            ['name' => 'Nur Khalimah', 'nis' => '2333627'],
            ['name' => 'Nurfita Aprilianti', 'nis' => '2333628'],
            ['name' => 'Qian Akmal Hanafi', 'nis' => '2333629'],
            ['name' => 'Qisya Farabila', 'nis' => '2333630'],
            ['name' => 'Revans Satria Putra', 'nis' => '2333631'],
            ['name' => 'Risa Amelia', 'nis' => '2333632'],
            ['name' => 'Rismawati Yulia Andini', 'nis' => '2333633'],
            ['name' => 'Salma Salsabila ', 'nis' => '2333634'],
            ['name' => 'Sayyid Muhammad Toha Kailani', 'nis' => '2333635'],
            ['name' => 'Shinta Ainun Najwa', 'nis' => '2333636'],
            ['name' => 'Thoyibatul Maulida', 'nis' => '2333637'],
            ['name' => 'Wulida Izza Rahmayani', 'nis' => '2333638'],
            ['name' => 'Yovita Marta Dilla Rahmawati', 'nis' => '2333639'],
        ];

        echo "Creating 32 students...\n";
        $createdUsers = [];

        
        $defaultPassword = 'PPLGOKE123';

        foreach ($students as $index => $studentData) {
            $email = $studentData['nis']; 

            $user = User::create([
                'name' => $studentData['name'],
                'email' => $email,
                'nis' => $studentData['nis'],
                'role' => 'user',
                'password' => Hash::make($defaultPassword),
            ]);

            $createdUsers[] = $user;
            echo ($index + 1) . ". ✅ {$studentData['name']} - {$email} / {$defaultPassword}\n";
        }

        echo "\n✅ Total 33 students created successfully!\n\n";

       
        echo "Creating sample payments...\n";
        foreach ($createdUsers as $index => $user) {
            if ($index < 10) {
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'amount' => rand(10000, 50000),
                    'description' => 'Kas bulan ' . date('F Y'),
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

                echo "  💰 Payment from {$user->name}: Rp " . number_format($payment->amount, 0, ',', '.') . "\n";
            }
        }

       
        echo "\nCreating pending payments...\n";
        foreach ($createdUsers as $index => $user) {
            if ($index >= 10 && $index < 15) {
                Payment::create([
                    'user_id' => $user->id,
                    'amount' => rand(10000, 50000),
                    'description' => 'Kas bulan ' . date('F Y'),
                    'status' => 'pending',
                ]);

                echo "  ⏳ Pending payment from {$user->name}\n";
            }
        }

       
        echo "\nCreating sample expenses...\n";
        $expenses = [
            'Pembelian alat tulis kelas' => 75000,
            'Biaya fotokopi soal ujian' => 150000,
            'Pembelian perlengkapan kebersihan' => 100000,
            'Biaya konsumsi rapat kelas' => 200000,
            'Pembelian hiasan kelas' => 85000,
            'Biaya printer dan tinta' => 250000,
            'Pembelian buku absensi' => 45000,
        ];

        foreach ($expenses as $description => $amount) {
            Transaction::create([
                'type' => 'expense',
                'amount' => $amount,
                'description' => $description,
                'created_by' => $admin->id,
            ]);

            echo "  💸 {$description}: Rp " . number_format($amount, 0, ',', '.') . "\n";
        }

       
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        echo "\n" . str_repeat('=', 60) . "\n";
        echo "📊 SUMMARY:\n";
        echo str_repeat('=', 60) . "\n";
        echo "Total Users: " . (User::count()) . " (1 Admin + 33 Students)\n";
        echo "Total Payments: " . Payment::count() . "\n";
        echo "  - Approved: " . Payment::where('status', 'approved')->count() . "\n";
        echo "  - Pending: " . Payment::where('status', 'pending')->count() . "\n";
        echo "Total Transactions: " . Transaction::count() . "\n";
        echo "\nFinancial Summary:\n";
        echo "  Total Income: Rp " . number_format($totalIncome, 0, ',', '.') . "\n";
        echo "  Total Expense: Rp " . number_format($totalExpense, 0, ',', '.') . "\n";
        echo "  Balance: Rp " . number_format($balance, 0, ',', '.') . "\n";
        echo str_repeat('=', 60) . "\n\n";

        echo "🎉 Database seeding completed successfully!\n";
    }
}
