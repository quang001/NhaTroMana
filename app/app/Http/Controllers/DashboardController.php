<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_properties'  => DB::table('properties')->count(),
            'available'         => DB::table('properties')->where('status', 'available')->count(),
            'rented'            => DB::table('properties')->where('status', 'rented')->count(),
            'total_tenants'     => DB::table('tenants')->count(),
            'active_contracts'  => DB::table('contracts')->where('status', 'active')->count(),
            'total_revenue'     => DB::table('contracts')->where('status', 'active')->sum('monthly_rent'),
        ];

        $recent_properties = DB::table('properties')
            ->leftJoin('categories', 'properties.category_id', '=', 'categories.id')
            ->select('properties.*', 'categories.name as category_name', 'categories.icon as category_icon')
            ->orderBy('properties.created_at', 'desc')
            ->limit(5)
            ->get();

        $featured_properties = DB::table('properties')
            ->leftJoin('categories', 'properties.category_id', '=', 'categories.id')
            ->leftJoin('locations', 'properties.location_id', '=', 'locations.id')
            ->select(
                'properties.*',
                'categories.name as category_name',
                'categories.icon as category_icon',
                'locations.district',
                'locations.city'
            )
            ->where('properties.is_featured', 1)
            ->where('properties.status', 'available')
            ->limit(6)
            ->get();

        $category_stats = DB::table('categories')
            ->leftJoin('properties', 'categories.id', '=', 'properties.category_id')
            ->select('categories.name', 'categories.icon', DB::raw('COUNT(properties.id) as count'))
            ->groupBy('categories.id', 'categories.name', 'categories.icon')
            ->get();

        return view('dashboard.index', compact(
            'stats', 'recent_properties', 'featured_properties', 'category_stats'
        ));
    }
}
