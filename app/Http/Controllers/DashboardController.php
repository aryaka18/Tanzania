<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        $stats = [
            'projects' => $user->projects()->count(),
            'avg_seo_score' => $user->projects()->avg('seo_score') ?: 0,
            'last_analysis' => $user->projects()->latest('last_analyzed_at')->first()?->last_analyzed_at,
            'total_keywords' => $user->projects()->withCount('keywords')->get()->sum('keywords_count'),
        ];

        $recentProjects = $user->projects()->latest()->take(5)->get();
        $topProjects = $user->projects()->orderBy('seo_score', 'desc')->take(5)->get();

        return view('dashboard', compact('stats', 'recentProjects', 'topProjects'));
    }
}
