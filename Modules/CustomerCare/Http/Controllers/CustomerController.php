<?php

namespace Modules\CustomerCare\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CustomerCare\Entities\Customer;
use Modules\CustomerCare\Entities\SupportTicket;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getCustomerData($request);
        }
        
        return view('customercare::crm.index');
    }

    public function create()
    {
        return view('customercare::crm.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'redirect' => route('customercare.crm')
        ]);
    }

    public function show(Customer $customer)
    {
        $tickets = $customer->tickets()->with('assignedTo')->latest()->get();
        $disputes = $customer->disputes()->with('assignedTo')->latest()->get();
        
        return response()->json([
            'success' => true,
            'customer' => $customer,
            'tickets' => $tickets,
            'disputes' => $disputes
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('customercare::crm.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'redirect' => route('customercare.crm')
        ]);
    }

    private function getCustomerData(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('actions', function($customer) {
                return '
                    <button onclick="viewCustomer(' . $customer->id . ')" class="btn btn-primary btn-sm">View</button>
                    <button onclick="editCustomer(' . $customer->id . ')" class="btn btn-warning btn-sm">Edit</button>
                ';
            })
            ->addColumn('tickets_count', function($customer) {
                return $customer->tickets->count();
            })
            ->addColumn('disputes_count', function($customer) {
                return $customer->disputes->count();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}