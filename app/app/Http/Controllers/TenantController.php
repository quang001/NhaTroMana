<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('tenants');

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('full_name', 'like', "%$kw%")
                  ->orWhere('phone', 'like', "%$kw%")
                  ->orWhere('id_card', 'like', "%$kw%");
            });
        }

        $tenants = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'phone'     => 'required',
            'id_card'   => 'required|unique:tenants',
        ]);

        DB::table('tenants')->insert([
            'full_name'         => $request->full_name,
            'id_card'           => $request->id_card,
            'phone'             => $request->phone,
            'email'             => $request->email,
            'address'           => $request->address,
            'date_of_birth'     => $request->date_of_birth,
            'gender'            => $request->gender,
            'occupation'        => $request->occupation,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone'   => $request->emergency_phone,
            'created_at'        => now(),
        ]);

        return redirect()->route('tenants.index')->with('success', 'Thêm khách thuê thành công!');
    }

    public function show($id)
    {
        $tenant = DB::table('tenants')->where('id', $id)->first();
        if (!$tenant) abort(404);

        $contracts = DB::table('contracts')
            ->join('properties', 'contracts.property_id', '=', 'properties.id')
            ->select('contracts.*', 'properties.title as property_title', 'properties.address')
            ->where('contracts.tenant_id', $id)
            ->orderBy('contracts.created_at', 'desc')
            ->get();

        return view('tenants.show', compact('tenant', 'contracts'));
    }

    public function edit($id)
    {
        $tenant = DB::table('tenants')->where('id', $id)->first();
        if (!$tenant) abort(404);
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required',
            'phone'     => 'required',
        ]);

        DB::table('tenants')->where('id', $id)->update([
            'full_name'         => $request->full_name,
            'phone'             => $request->phone,
            'email'             => $request->email,
            'address'           => $request->address,
            'occupation'        => $request->occupation,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone'   => $request->emergency_phone,
        ]);

        return redirect()->route('tenants.show', $id)->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        DB::table('tenants')->where('id', $id)->delete();
        return redirect()->route('tenants.index')->with('success', 'Đã xóa khách thuê!');
    }
}
