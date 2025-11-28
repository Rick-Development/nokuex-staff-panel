<?php

namespace Modules\Sales\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Sales\Entities\CrmLead;
use Modules\Core\Entities\Staff;

class LeadsSeeder extends Seeder
{
    public function run()
    {
        $staff = Staff::all();
        
        $leads = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+234 801 234 5678',
                'status' => 'new',
                'source' => 'website',
                'notes' => 'Interested in crypto trading platform. Requested demo.',
                'assigned_to' => $staff->random()->id ?? null,
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@company.com',
                'phone' => '+234 802 345 6789',
                'status' => 'contacted',
                'source' => 'referral',
                'notes' => 'Referred by existing customer. Looking for business account.',
                'assigned_to' => $staff->random()->id ?? null,
                'last_contact_at' => now()->subDays(2),
                'next_follow_up_at' => now()->addDays(3),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'mchen@tech.io',
                'phone' => '+234 803 456 7890',
                'status' => 'qualified',
                'source' => 'social_media',
                'notes' => 'Tech startup founder. High volume potential. Discussed enterprise features.',
                'assigned_to' => $staff->random()->id ?? null,
                'last_contact_at' => now()->subDays(1),
                'next_follow_up_at' => now()->addDays(1),
            ],
            [
                'name' => 'Amina Ibrahim',
                'email' => 'amina.ibrahim@business.ng',
                'phone' => '+234 804 567 8901',
                'status' => 'converted',
                'source' => 'direct',
                'notes' => 'Successfully onboarded. Started with premium plan.',
                'assigned_to' => $staff->random()->id ?? null,
                'last_contact_at' => now()->subDays(5),
            ],
            [
                'name' => 'David Williams',
                'email' => 'dwilliams@email.com',
                'phone' => '+234 805 678 9012',
                'status' => 'lost',
                'source' => 'website',
                'notes' => 'Went with competitor. Price was main concern.',
                'assigned_to' => $staff->random()->id ?? null,
                'last_contact_at' => now()->subDays(10),
            ],
            [
                'name' => 'Fatima Yusuf',
                'email' => 'fatima.y@enterprise.com',
                'phone' => '+234 806 789 0123',
                'status' => 'new',
                'source' => 'referral',
                'notes' => 'Enterprise client. Needs custom integration.',
                'assigned_to' => $staff->random()->id ?? null,
            ],
            [
                'name' => 'Robert Taylor',
                'email' => 'rtaylor@startup.io',
                'phone' => null,
                'status' => 'contacted',
                'source' => 'social_media',
                'notes' => 'Responded to LinkedIn campaign. Scheduled call for next week.',
                'assigned_to' => $staff->random()->id ?? null,
                'last_contact_at' => now()->subHours(12),
                'next_follow_up_at' => now()->addDays(7),
            ],
            [
                'name' => 'Chioma Okafor',
                'email' => 'chioma.okafor@trading.ng',
                'phone' => '+234 807 890 1234',
                'status' => 'qualified',
                'source' => 'website',
                'notes' => 'Professional trader. Interested in API access and advanced features.',
                'assigned_to' => $staff->random()->id ?? null,
                'last_contact_at' => now()->subDays(3),
                'next_follow_up_at' => now()->addDays(2),
            ],
        ];

        foreach ($leads as $lead) {
            CrmLead::create($lead);
        }

        $this->command->info('✅ Created ' . count($leads) . ' sample leads');
    }
}
