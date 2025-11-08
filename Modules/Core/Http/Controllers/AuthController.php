<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Core\Entities\Staff;
use Modules\Core\Entities\Role;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('core::auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $staff = Staff::where('email', $request->email)->where('is_active', true)->first();

        if ($staff && Hash::check($request->password, $staff->password)) {
            Auth::guard('staff')->login($staff, $request->remember ?? false);
            $request->session()->regenerate();

            // Set department in session for quick access
            session(['department' => $staff->department()]);

            // Log the login action
            \Modules\Core\Entities\AuditLog::create([
                'action' => 'login',
                'description' => 'Staff member logged in',
                'staff_id' => $staff->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'performed_at' => now(),
            ]);

            // Redirect to department-specific dashboard
            $redirectUrl = $this->getDepartmentDashboard($staff);

            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials or account inactive'
        ], 401);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('staff')->check()) {
            // Log the logout action
            \Modules\Core\Entities\AuditLog::create([
                'action' => 'logout',
                'description' => 'Staff member logged out',
                'staff_id' => Auth::guard('staff')->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'performed_at' => now(),
            ]);

            Auth::guard('staff')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('core.login');
    }

    /**
     * Get the appropriate dashboard URL based on user's department
     */
    private function getDepartmentDashboard(Staff $staff)
    {
        $department = $staff->department();
        
        switch ($department) {
            case Role::DEPARTMENT_CUSTOMER_CARE:
                return route('customercare.dashboard');
                
            case Role::DEPARTMENT_SALES:
                return route('sales.dashboard');
                
            case Role::DEPARTMENT_FINANCE:
                return route('finance.dashboard');
                
            case Role::DEPARTMENT_COMPLIANCE:
                return route('compliance.dashboard');
                
            case Role::DEPARTMENT_ADMIN:
            default:
                return route('core.dashboard');
        }
    }
}