<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = DB::table('contracts')
            ->join('properties', 'contracts.property_id', '=', 'properties.id')
            ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
            ->select(
                'contracts.*',
                'properties.title as property_title',
                'properties.address as property_address',
                'tenants.full_name as tenant_name',
                'tenants.phone as tenant_phone'
            )
            ->orderBy('contracts.created_at', 'desc')
            ->paginate(10);

        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $properties = DB::table('properties')->where('status', 'available')->get();
        $tenants = DB::table('tenants')->get();
        return view('contracts.create', compact('properties', 'tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id'  => 'required',
            'tenant_id'    => 'required',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after:start_date',
            'monthly_rent' => 'required|numeric',
            'deposit'      => 'required|numeric',
        ]);

        DB::table('contracts')->insert([
            'property_id'  => $request->property_id,
            'tenant_id'    => $request->tenant_id,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'monthly_rent' => $request->monthly_rent,
            'deposit'      => $request->deposit,
            'status'       => 'active',
            'notes'        => $request->notes,
            'created_at'   => now(),
        ]);

        // Cập nhật trạng thái phòng thành "đã thuê"
        DB::table('properties')->where('id', $request->property_id)
            ->update(['status' => 'rented']);

        return redirect()->route('contracts.index')
            ->with('success', 'Tạo hợp đồng thành công!');
    }

    public function show($id)
    {
        $contract = DB::table('contracts')
            ->join('properties', 'contracts.property_id', '=', 'properties.id')
            ->join('tenants', 'contracts.tenant_id', '=', 'tenants.id')
            ->select(
                'contracts.*',
                'properties.title as property_title',
                'properties.address as property_address',
                'properties.price as property_price',
                'tenants.full_name as tenant_name',
                'tenants.phone as tenant_phone',
                'tenants.id_card as tenant_id_card',
                'tenants.email as tenant_email'
            )
            ->where('contracts.id', $id)
            ->first();

        if (!$contract) abort(404);
        return view('contracts.show', compact('contract'));
    }
}
