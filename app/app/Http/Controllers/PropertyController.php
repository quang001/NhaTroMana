<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    /**
     * Danh sách bất động sản với tìm kiếm và lọc
     */
    public function index(Request $request)
    {
        $query = DB::table('properties')
            ->leftJoin('categories', 'properties.category_id', '=', 'categories.id')
            ->leftJoin('locations', 'properties.location_id', '=', 'locations.id')
            ->select(
                'properties.*',
                'categories.name as category_name',
                'categories.icon as category_icon',
                'locations.city',
                'locations.district'
            );

        // Tìm kiếm theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('properties.title', 'like', "%{$keyword}%")
                  ->orWhere('properties.address', 'like', "%{$keyword}%")
                  ->orWhere('properties.description', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo loại
        if ($request->filled('category')) {
            $query->where('properties.category_id', $request->category);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('properties.status', $request->status);
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('properties.price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('properties.price', '<=', $request->max_price);
        }

        // Lọc theo diện tích
        if ($request->filled('min_area')) {
            $query->where('properties.area', '>=', $request->min_area);
        }

        $properties = $query->orderBy('properties.is_featured', 'desc')
                           ->orderBy('properties.created_at', 'desc')
                           ->paginate(9);

        $categories = DB::table('categories')->get();
        $stats = [
            'total' => DB::table('properties')->count(),
            'available' => DB::table('properties')->where('status', 'available')->count(),
            'rented' => DB::table('properties')->where('status', 'rented')->count(),
        ];

        return view('properties.index', compact('properties', 'categories', 'stats'));
    }

    /**
     * Chi tiết bất động sản
     */
    public function show($id)
    {
        $property = DB::table('properties')
            ->leftJoin('categories', 'properties.category_id', '=', 'categories.id')
            ->leftJoin('locations', 'properties.location_id', '=', 'locations.id')
            ->select(
                'properties.*',
                'categories.name as category_name',
                'categories.icon as category_icon',
                'locations.city',
                'locations.district',
                'locations.ward'
            )
            ->where('properties.id', $id)
            ->first();

        if (!$property) {
            abort(404, 'Không tìm thấy bất động sản');
        }

        // Tăng lượt xem
        DB::table('properties')->where('id', $id)->increment('views');

        // Bất động sản tương tự
        $similar = DB::table('properties')
            ->leftJoin('categories', 'properties.category_id', '=', 'categories.id')
            ->select('properties.*', 'categories.name as category_name', 'categories.icon as category_icon')
            ->where('properties.category_id', $property->category_id)
            ->where('properties.id', '!=', $id)
            ->limit(3)
            ->get();

        return view('properties.show', compact('property', 'similar'));
    }

    /**
     * Form tạo mới
     */
    public function create()
    {
        $categories = DB::table('categories')->get();
        $locations = DB::table('locations')->get();
        return view('properties.create', compact('categories', 'locations'));
    }

    /**
     * Lưu bất động sản mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|min:10|max:255',
            'category_id' => 'required|integer',
            'address'     => 'required',
            'price'       => 'required|numeric|min:100000',
            'area'        => 'required|numeric|min:5',
            'owner_name'  => 'required',
            'owner_phone' => 'required',
        ]);

        $slug = \Str::slug($request->title) . '-' . time();

        DB::table('properties')->insert([
            'title'        => $request->title,
            'slug'         => $slug,
            'description'  => $request->description,
            'category_id'  => $request->category_id,
            'location_id'  => $request->location_id,
            'address'      => $request->address,
            'price'        => $request->price,
            'area'         => $request->area,
            'bedrooms'     => $request->bedrooms ?? 1,
            'bathrooms'    => $request->bathrooms ?? 1,
            'max_tenants'  => $request->max_tenants ?? 2,
            'status'       => $request->status ?? 'available',
            'has_wifi'     => $request->has('has_wifi') ? 1 : 0,
            'has_parking'  => $request->has('has_parking') ? 1 : 0,
            'has_ac'       => $request->has('has_ac') ? 1 : 0,
            'has_washing'  => $request->has('has_washing') ? 1 : 0,
            'has_kitchen'  => $request->has('has_kitchen') ? 1 : 0,
            'owner_name'   => $request->owner_name,
            'owner_phone'  => $request->owner_phone,
            'owner_email'  => $request->owner_email,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('properties.index')
            ->with('success', 'Đăng tin bất động sản thành công!');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $property = DB::table('properties')->where('id', $id)->first();
        if (!$property) abort(404);
        $categories = DB::table('categories')->get();
        $locations = DB::table('locations')->get();
        return view('properties.edit', compact('property', 'categories', 'locations'));
    }

    /**
     * Cập nhật
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|min:10',
            'address' => 'required',
            'price'   => 'required|numeric',
        ]);

        DB::table('properties')->where('id', $id)->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'address'     => $request->address,
            'price'       => $request->price,
            'area'        => $request->area,
            'bedrooms'    => $request->bedrooms,
            'bathrooms'   => $request->bathrooms,
            'status'      => $request->status,
            'has_wifi'    => $request->has('has_wifi') ? 1 : 0,
            'has_parking' => $request->has('has_parking') ? 1 : 0,
            'has_ac'      => $request->has('has_ac') ? 1 : 0,
            'owner_name'  => $request->owner_name,
            'owner_phone' => $request->owner_phone,
            'owner_email' => $request->owner_email,
            'updated_at'  => now(),
        ]);

        return redirect()->route('properties.show', $id)
            ->with('success', 'Cập nhật thành công!');
    }

    /**
     * Xóa bất động sản
     */
    public function destroy($id)
    {
        DB::table('properties')->where('id', $id)->delete();
        return redirect()->route('properties.index')
            ->with('success', 'Đã xóa bất động sản!');
    }
}
