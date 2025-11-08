<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\Staff;

class CoreDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear existing non-admin roles to avoid duplicates
        Role::where('department', '!=', Role::DEPARTMENT_ADMIN)->delete();

        // Create department-based roles
        $departments = Role::getDepartments();
        
        foreach ($departments as $deptKey => $deptName) {
            // Skip admin department for regular department creation
            if ($deptKey === Role::DEPARTMENT_ADMIN) {
                continue;
            }

            // Create manager role
            $managerRole = Role::updateOrCreate(
                [
                    'name' => $deptName . ' Manager'
                ],
                [
                    'department' => $deptKey,
                    'description' => 'Manager for ' . $deptName . ' department',
                    'permissions' => $this->getDepartmentPermissions($deptKey, 'manager'),
                    'is_active' => true,
                ]
            );

            // Create manager user for each department
            $managerEmail = str_replace(' ', '_', strtolower($deptName)) . '_manager@nokuex.com';
            Staff::updateOrCreate(
                ['email' => $managerEmail],
                [
                    'name' => $deptName . ' Manager',
                    'password' => Hash::make('password'),
                    'role_id' => $managerRole->id,
                    'phone' => '+1234567890',
                    'is_active' => true,
                ]
            );

            // Create staff role for each department
            $staffRole = Role::updateOrCreate(
                [
                    'name' => $deptName . ' Staff'
                ],
                [
                    'department' => $deptKey,
                    'description' => 'Staff member for ' . $deptName . ' department',
                    'permissions' => $this->getDepartmentPermissions($deptKey, 'staff'),
                    'is_active' => true,
                ]
            );

            // Create staff user for each department
            $staffEmail = str_replace(' ', '_', strtolower($deptName)) . '_staff@nokuex.com';
            Staff::updateOrCreate(
                ['email' => $staffEmail],
                [
                    'name' => $deptName . ' Staff',
                    'password' => Hash::make('password'),
                    'role_id' => $staffRole->id,
                    'phone' => '+1234567891',
                    'is_active' => true,
                ]
            );
        }

        // Create/update super admin role
        $superAdminRole = Role::updateOrCreate(
            [
                'name' => 'Super Administrator'
            ],
            [
                'department' => Role::DEPARTMENT_ADMIN,
                'description' => 'System Administrator with full access to all departments',
                'permissions' => ['*'],
                'is_active' => true,
            ]
        );

        // Create/update super admin user
        Staff::updateOrCreate(
            ['email' => 'admin@nokuex.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'phone' => '+1234567899',
                'is_active' => true,
            ]
        );

        $this->command->info('All department roles and users created/updated successfully!');
        $this->command->info('Super Admin: admin@nokuex.com / password');
        
        foreach ($departments as $deptKey => $deptName) {
            if ($deptKey !== Role::DEPARTMENT_ADMIN) {
                $managerEmail = str_replace(' ', '_', strtolower($deptName)) . '_manager@nokuex.com';
                $staffEmail = str_replace(' ', '_', strtolower($deptName)) . '_staff@nokuex.com';
                $this->command->info($deptName . ' Manager: ' . $managerEmail . ' / password');
                $this->command->info($deptName . ' Staff: ' . $staffEmail . ' / password');
            }
        }
    }

    private function getDepartmentPermissions($department, $level)
    {
        $basePermissions = [
            'core.dashboard.view',
            'chat.access',
        ];

        $departmentPermissions = [
            Role::DEPARTMENT_CUSTOMER_CARE => [
                'customercare.dashboard.view',
                'customercare.crm.access',
                'customercare.tickets.access',
                'customercare.disputes.access',
            ],
            Role::DEPARTMENT_SALES => [
                'sales.dashboard.view', 
                'sales.leads.access',
                'sales.performance.access',
                'sales.followups.access',
            ],
            Role::DEPARTMENT_FINANCE => [
                'finance.dashboard.view',
                'finance.transactions.access',
                'finance.reports.access',
                'finance.reconciliation.access',
                'finance.blusalt.access',
            ],
            Role::DEPARTMENT_COMPLIANCE => [
                'compliance.dashboard.view',
                'compliance.freeze.access',
                'compliance.otc.access',
                'compliance.kyc.access',
                'compliance.kyb.access',
                'compliance.flagging.access',
            ],
        ];

        $managerPermissions = [
            $department . '.manage',
            $department . '.reports.view',
        ];

        if ($level === 'manager') {
            return array_merge($basePermissions, $departmentPermissions[$department] ?? [], $managerPermissions);
        }

        return array_merge($basePermissions, $departmentPermissions[$department] ?? []);
    }
}