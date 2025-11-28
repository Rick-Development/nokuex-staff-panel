<?php

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Entities\KycReview;
use Modules\Compliance\Entities\ComplianceFlag;
use App\Models\User;
use Modules\Core\Entities\Staff;

class ComplianceSeeder extends Seeder
{
    public function run()
    {
        $users = User::limit(10)->get();
        $staff = Staff::all();

        // Create KYC Reviews
        $kycStatuses = ['pending', 'in_review', 'approved', 'rejected'];
        foreach ($users->take(8) as $index => $user) {
            KycReview::create([
                'user_id' => $user->id,
                'reviewed_by' => $index > 3 ? $staff->random()->id : null,
                'review_type' => $index % 3 == 0 ? 'kyb' : 'kyc',
                'status' => $kycStatuses[$index % 4],
                'rejection_reason' => $index % 4 == 3 ? 'Document quality insufficient. Please resubmit clear photos.' : null,
                'notes' => $index > 3 ? 'Reviewed and verified all documents.' : null,
                'submitted_at' => now()->subDays(rand(1, 30)),
                'reviewed_at' => $index > 3 ? now()->subDays(rand(0, 10)) : null,
            ]);
        }

        // Create Compliance Flags
        $flagTypes = [
            'suspicious_transaction',
            'high_volume',
            'kyc_expired',
            'multiple_failed_logins',
            'unusual_activity',
            'duplicate_account',
        ];

        $severities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['pending', 'under_review', 'cleared', 'escalated'];

        foreach ($users->take(6) as $index => $user) {
            ComplianceFlag::create([
                'user_id' => $user->id,
                'reviewed_by' => $index > 2 ? $staff->random()->id : null,
                'flag_type' => $flagTypes[$index % count($flagTypes)],
                'severity' => $severities[$index % count($severities)],
                'status' => $statuses[$index % count($statuses)],
                'description' => $this->getFlagDescription($flagTypes[$index % count($flagTypes)]),
                'metadata' => json_encode(['auto_flagged' => true, 'threshold_exceeded' => rand(100, 500)]),
                'resolution_notes' => $index > 2 ? 'Investigated and verified. User contacted for clarification.' : null,
                'reviewed_at' => $index > 2 ? now()->subDays(rand(0, 5)) : null,
            ]);
        }

        $this->command->info('✅ Created KYC reviews and compliance flags');
    }

    private function getFlagDescription($type)
    {
        $descriptions = [
            'suspicious_transaction' => 'Multiple large transactions detected within short timeframe. Requires verification.',
            'high_volume' => 'Transaction volume exceeded normal threshold for this account type.',
            'kyc_expired' => 'KYC documents have expired and require renewal.',
            'multiple_failed_logins' => 'Detected 5+ failed login attempts from different IP addresses.',
            'unusual_activity' => 'Account activity pattern differs significantly from historical behavior.',
            'duplicate_account' => 'Potential duplicate account detected based on phone number match.',
        ];

        return $descriptions[$type] ?? 'Flagged for manual review.';
    }
}
