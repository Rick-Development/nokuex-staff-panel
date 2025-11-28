<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Entities\Staff;
use Modules\Core\Entities\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    public function index()
    {
        return view('core::staff.index');
    }

    public function getData(Request $request)
    {
        $staffs = Staff::with('role')->select('staffs.*');
        
        return DataTables::of($staffs)
            ->addColumn('actions', function($staff) {
                return '
                    <a href="'.route('core.staff.show', $staff->id).'" class="btn btn-primary btn-sm">View</a>
                    <a href="'.route('core.staff.edit', $staff->id).'" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteStaff('.$staff->id.')">Delete</button>
                ';
            })
            ->addColumn('status_badge', function($staff) {
                return $staff->is_active ? 
                    '<span class="badge badge-success">Active</span>' : 
                    '<span class="badge badge-danger">Inactive</span>';
            })
            ->rawColumns(['actions', 'status_badge'])
            ->make(true);
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        return view('core::staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $staff = Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff created successfully',
            'redirect' => route('core.staff.index')
        ]);
    }

    public function show(Staff $staff)
    {
        return response()->json([
            'success' => true,
            'data' => $staff->load('role', 'auditLogs', 'notifications')
        ]);
    }

    public function edit(Staff $staff)
    {
        $roles = Role::where('is_active', true)->get();
        return view('core::staff.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staffs,email,' . $staff->id,
            'role_id' => 'required|exists:roles,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->password) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Staff updated successfully',
            'redirect' => route('core.staff.index')
        ]);
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return response()->json([
            'success' => true,
            'message' => 'Staff deleted successfully'
        ]);
    }
}