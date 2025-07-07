<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Keyword;
use App\Services\KeywordResearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class KeywordController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct(
        private KeywordResearchService $keywordResearchService
    ) {}

    /**
     * Display keywords for a project
     */
    public function index(Project $project): View
    {
        $this->authorize('view', $project);
        
        $keywords = $project->keywords()->paginate(20);
        
        // Get position changes and trends
        $positionData = $this->getPositionTrends($project);
        
        return view('keywords.index', compact('project', 'keywords', 'positionData'));
    }

    /**
     * Show keyword research form
     */
    public function research(Project $project): View
    {
        $this->authorize('update', $project);
        
        return view('keywords.research', compact('project'));
    }

    /**
     * Perform keyword research
     */
    public function performResearch(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);
        
        $request->validate([
            'seed_keyword' => 'required|string|max:255',
            'limit' => 'integer|min:10|max:100'
        ]);

        try {
            $keywords = $this->keywordResearchService->researchKeywords(
                $request->seed_keyword,
                $request->limit ?? 50
            );

            return response()->json([
                'success' => true,
                'keywords' => $keywords ?? [],
                'count' => count($keywords ?? [])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to research keywords: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add keywords to project
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);
        
        $request->validate([
            'keywords' => 'required|array|min:1',
            'keywords.*.keyword' => 'required|string',
            'keywords.*.search_volume' => 'nullable|integer',
            'keywords.*.difficulty' => 'nullable|numeric',
            'keywords.*.cpc' => 'nullable|numeric',
            'keywords.*.competition' => 'nullable|string',
            'keywords.*.intent' => 'nullable|string',
            'keywords.*.related_keywords' => 'nullable|array'
        ]);

        try {
            $this->keywordResearchService->addKeywordsToProject($project, $request->keywords);

            return response()->json([
                'success' => true,
                'message' => 'Keywords added successfully',
                'count' => count($request->keywords ?? [])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add keywords: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track keyword positions
     */
    public function trackPositions(Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        try {
            $results = $this->keywordResearchService->trackKeywordPositions($project);

            return response()->json([
                'success' => true,
                'message' => 'Position tracking completed',
                'results' => $results ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to track positions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get keyword opportunities
     */
    public function opportunities(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        try {
            $opportunities = $this->keywordResearchService->getKeywordOpportunities($project);

            return response()->json([
                'success' => true,
                'opportunities' => $opportunities ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get opportunities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete keyword
     */
    public function destroy(Project $project, Keyword $keyword): JsonResponse
    {
        $this->authorize('update', $project);
        
        if ($keyword->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'Keyword does not belong to this project'
            ], 403);
        }

        try {
            $keyword->delete();

            return response()->json([
                'success' => true,
                'message' => 'Keyword deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete keyword: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get position trends data - FIXED with null checks
     */
    private function getPositionTrends(Project $project): array
    {
        // Add null check for keywords relationship
        $keywords = $project->keywords ?? collect();
        
        $trends = [
            'improved' => 0,
            'declined' => 0,
            'stable' => 0,
            'new' => 0
        ];

        // Check if keywords is iterable before foreach
        if ($keywords && (is_array($keywords) || is_object($keywords))) {
            foreach ($keywords as $keyword) {
                $change = $this->getPositionChange($keyword);
                
                if ($change['direction'] === 'up') {
                    $trends['improved']++;
                } elseif ($change['direction'] === 'down') {
                    $trends['declined']++;
                } elseif ($change['direction'] === 'neutral') {
                    $trends['stable']++;
                } else {
                    $trends['new']++;
                }
            }
        }

        return $trends;
    }

    /**
     * Get position change for a keyword - FIXED with null checks
     */
    private function getPositionChange(Keyword $keyword): array
    {
        // Add null checks for tracking_data
        $trackingData = $keyword->tracking_data ?? [];
        $history = $trackingData['position_history'] ?? [];
        
        if (!is_array($history) || count($history) < 2) {
            return ['change' => 0, 'direction' => 'new'];
        }

        $current = end($history)['position'] ?? null;
        $previous = prev($history)['position'] ?? null;

        if (!$current || !$previous) {
            return ['change' => 0, 'direction' => 'neutral'];
        }

        $change = $previous - $current; // Positive means improvement

        return [
            'change' => abs($change),
            'direction' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral')
        ];
    }

    public function selectProject(): View
    {
        // Add null check for user projects
        $user = auth()->user();
        $projects = $user ? $user->projects()->get() : collect();
        
        return view('keywords.select-project', compact('projects'));
    }
}