<?php

namespace Modules\Finance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Finance\Entities\Transaction;
use Modules\Finance\Entities\BlusaltTransaction;
use Modules\Finance\Entities\Reconciliation;
use Modules\CustomerCare\Entities\Customer;
use Modules\Core\Entities\Staff;
use Carbon\Carbon;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding Finance module data...');

        // Get or create sample customers
        $customers = Customer::take(10)->get();
        if ($customers->count() === 0) {
            $this->command->warn('No customers found. Creating sample customers...');
            $customers = $this->createSampleCustomers();
        }

        // Get a staff member for reconciliations
        $staff = Staff::first();
        if (!$staff) {
            $this->command->error('No staff found. Please run Core seeder first.');
            return;
        }

        // Create transactions
        $this->createTransactions($customers);

        // Create reconciliations
        $this->createReconciliations($staff);

        // Create Blusalt OTC transactions
        $this->createBlusaltTransactions();

        $this->command->info('Finance module seeded successfully!');
    }

    private function createSampleCustomers()
    {
        $customers = collect();
        for ($i = 1; $i <= 10; $i++) {
            $customers->push(Customer::create([
                'name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
                'phone' => '+234' . rand(7000000000, 9999999999),
                'address' => rand(1, 100) . ' Sample Street, Lagos, Nigeria',
                'status' => 'active',
                'metadata' => [
                    'account_number' => '100000000' . $i,
                    'bvn' => '2' . str_pad($i, 10, '0', STR_PAD_LEFT)
                ]
            ]));
        }
        return $customers;
    }

    private function createTransactions($customers)
    {
        $this->command->info('Creating sample transactions...');

        $types = ['deposit', 'withdrawal', 'transfer'];
        $statuses = ['pending', 'completed', 'failed', 'processing'];
        $currencies = ['NGN', 'USD', 'EUR', 'GBP'];

        // Create transactions for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $transactionsPerDay = rand(5, 15);
            
            for ($i = 0; $i < $transactionsPerDay; $i++) {
                $customer = $customers->random();
                $type = $types[array_rand($types)];
                $status = $this->getWeightedStatus($statuses);
                $currency = $currencies[array_rand($currencies)];
                $amount = $this->generateAmount($currency);

                $transaction = Transaction::create([
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'type' => $type,
                    'amount' => $amount,
                    'currency' => $currency,
                    'status' => $status,
                    'customer_id' => $customer->id,
                    'description' => $this->generateDescription($type),
                    'metadata' => [
                        'ip_address' => $this->generateIP(),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                        'reference' => 'REF-' . strtoupper(uniqid())
                    ],
                    'processed_at' => $status === 'completed' ? Carbon::now()->subDays($day)->subHours(rand(0, 23)) : null,
                    'created_at' => Carbon::now()->subDays($day)->subHours(rand(0, 23)),
                    'updated_at' => Carbon::now()->subDays($day)->subHours(rand(0, 23))
                ]);

                // Create Blusalt transaction for some OTC transactions
                if ($type === 'transfer' && rand(0, 2) === 0) {
                    BlusaltTransaction::create([
                        'blusalt_reference' => 'BLU-' . strtoupper(uniqid()),
                        'transaction_id' => $transaction->id,
                        'status' => $status,
                        'blusalt_response' => [
                            'response_code' => $status === 'completed' ? '00' : '99',
                            'message' => $status === 'completed' ? 'Transaction successful' : 'Transaction pending',
                            'timestamp' => now()->toIso8601String()
                        ]
                    ]);
                }
            }
        }

        $this->command->info('Created ' . Transaction::count() . ' transactions');
    }

    private function createReconciliations($staff)
    {
        $this->command->info('Creating sample reconciliations...');

        // Create reconciliations for the last 15 days
        for ($day = 15; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            
            // Get daily totals
            $dailyTotal = Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('amount');

            if ($dailyTotal > 0) {
                // Introduce some variance for realism
                $variance = rand(-5000, 5000) / 100; // Random variance between -50 and 50
                $actualAmount = $dailyTotal + $variance;

                Reconciliation::create([
                    'reconciliation_date' => $date,
                    'expected_amount' => $dailyTotal,
                    'actual_amount' => $actualAmount,
                    'variance' => $variance,
                    'status' => abs($variance) < 10 ? 'completed' : 'pending',
                    'notes' => abs($variance) < 10 ? 'Auto-reconciled successfully' : 'Variance detected, manual review required',
                    'processed_by' => $staff->id,
                    'created_at' => $date->copy()->addHours(23),
                    'updated_at' => $date->copy()->addHours(23)
                ]);
            }
        }

        $this->command->info('Created ' . Reconciliation::count() . ' reconciliations');
    }

    private function createBlusaltTransactions()
    {
        // Already created with transactions
        $this->command->info('Created ' . BlusaltTransaction::count() . ' Blusalt OTC transactions');
    }

    private function getWeightedStatus($statuses)
    {
        // 70% completed, 15% pending, 10% processing, 5% failed
        $random = rand(1, 100);
        if ($random <= 70) return 'completed';
        if ($random <= 85) return 'pending';
        if ($random <= 95) return 'processing';
        return 'failed';
    }

    private function generateAmount($currency)
    {
        $amounts = [
            'NGN' => rand(5000, 5000000),
            'USD' => rand(50, 10000),
            'EUR' => rand(50, 10000),
            'GBP' => rand(50, 10000)
        ];

        return $amounts[$currency] ?? rand(1000, 100000);
    }

    private function generateDescription($type)
    {
        $descriptions = [
            'deposit' => [
                'Bank deposit via GTBank',
                'Card funding',
                'Transfer from Zenith Bank',
                'Wallet top-up',
                'Cash deposit'
            ],
            'withdrawal' => [
                'ATM withdrawal',
                'Transfer to bank account',
                'Cash withdrawal',
                'Payment to merchant',
                'Wallet transfer'
            ],
            'transfer' => [
                'P2P transfer',
                'International transfer',
                'Bill payment',
                'Merchant payment',
                'OTC payment'
            ]
        ];

        $typeDescriptions = $descriptions[$type] ?? ['Transaction'];
        return $typeDescriptions[array_rand($typeDescriptions)];
    }

    private function generateIP()
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 255);
    }
}