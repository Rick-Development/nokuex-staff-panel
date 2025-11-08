<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Entities\Staff;
use Modules\Core\Entities\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function create()
{
    $permissions = $this->getAllPermissions();
    return view('core::role.create', compact('permissions'));
}

public function edit(Role $role)
{
    $permissions = $this->getAllPermissions();
    return view('core::role.edit', compact('role', 'permissions'));
}

private function getAllPermissions()
{
    return [
        // Core permissions
        'core.dashboard.view',
        'core.staff.view', 'core.staff.create', 'core.staff.edit', 'core.staff.delete',
        'core.role.view', 'core.role.create', 'core.role.edit', 'core.role.delete',
        'core.notification.view', 'core.notification.create', 'core.notification.edit', 'core.notification.delete',

        // Chat permissions
        'chat.access', 'chat.create_channel', 'chat.manage_members',

        // Customer Care permissions
        'customercare.dashboard.view', 'customercare.crm.access', 'customercare.tickets.access', 'customercare.disputes.access',

        // Sales permissions
        'sales.dashboard.view', 'sales.leads.access', 'sales.performance.access', 'sales.followups.access',

        // Finance permissions
        'finance.dashboard.view', 'finance.transactions.access', 'finance.reports.access', 'finance.reconciliation.access', 'finance.blusalt.access',

        // Compliance permissions
        'compliance.dashboard.view', 'compliance.freeze.access', 'compliance.otc.access', 'compliance.kyc.access', 'compliance.kyb.access', 'compliance.flagging.access',
    ];
}
}