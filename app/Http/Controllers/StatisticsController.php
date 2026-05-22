<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function track(Request $request)
    {
        $validated = $request->validate([
            'visitor_id' => 'required|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'device_type' => 'required|string',
            'browser' => 'required|string',
            'os' => 'required|string',
            'page_url' => 'nullable|string',
            'referrer' => 'nullable|string'
        ]);
        
        $validated['ip'] = $request->ip();
        
        $visit = Visit::create($validated);
        
        return response()->json(['success' => true, 'visit_id' => $visit->id]);
    }
    
    public function stats()
    {
        $hourlyStats = Visit::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(DISTINCT visitor_id) as unique_visitors'),
                DB::raw('COUNT(*) as total_visits')
            )
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        $cityStats = Visit::select('city', DB::raw('COUNT(DISTINCT visitor_id) as unique_visitors'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('unique_visitors', 'desc')
            ->limit(10)
            ->get();
        
        $totalStats = [
            'total_visits' => Visit::count(),
            'unique_visitors' => Visit::distinct('visitor_id')->count('visitor_id'),
            'today_visits' => Visit::whereDate('created_at', Carbon::today())->count(),
            'devices' => Visit::select('device_type', DB::raw('count(*) as count'))
                ->groupBy('device_type')
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'hourly' => $hourlyStats,
            'cities' => $cityStats,
            'total' => $totalStats
        ]);
    }
    
    public function dashboard()
    {
        return view('statistics.dashboard');
    }
}
