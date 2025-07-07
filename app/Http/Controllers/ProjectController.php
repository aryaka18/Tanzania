<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeoAnalyzerService;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $projects = auth()->user()->projects()->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request, SeoAnalyzerService $analyzer)
    {
        $request->validate([
            'url' => 'required|url',
            'name' => 'nullable|string|max:255',
        ]);

        $analysisData = $analyzer->analyze($request->input('url'));

        if (isset($analysisData['error'])) {
            return back()->with('error', 'Failed to analyze URL: ' . $analysisData['error'])->withInput();
        }

        $project = Project::create([
            'user_id' => auth()->id(),
            'name' => $request->input('name') ?: parse_url($request->input('url'), PHP_URL_HOST),
            'url' => $request->input('url'),
            'analysis_results' => $analysisData,
            'seo_score' => $analysisData['seo_score'] ?? 0,
            'last_analyzed_at' => now(),
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Analysis complete!');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return view('projects.show', compact('project')); // <- This was the missing piece!
    }

    public function reanalyze(Project $project, SeoAnalyzerService $analyzer)
    {
        $this->authorize('update', $project);

        $analysisData = $analyzer->analyze($project->url);

        if (isset($analysisData['error'])) {
            return back()->with('error', 'Failed to re-analyze URL: ' . $analysisData['error']);
        }

        $project->update([
            'analysis_results' => $analysisData,
            'seo_score' => $analysisData['seo_score'] ?? 0,
            'last_analyzed_at' => now(),
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Re-analysis complete!');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}