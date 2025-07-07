<?php

namespace App\Http\Controllers;

use App\Services\GlobalKeywordResearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class KeywordResearchController extends Controller
{
    public function __construct(
        private GlobalKeywordResearchService $keywordResearchService
    ) {}

    /**
     * Display keyword research page
     */
    public function index(): View
    {
        return view('keywords.research');
    }

    /**
     * Search for keywords globally
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|max:255',
            'limit' => 'integer|min:10|max:100',
            'search_type' => 'string|in:all,suggestions,related,long_tail',
            'include_related' => 'boolean',
            'include_long_tail' => 'boolean',
            'country' => 'string|size:2',
            'language' => 'string|size:2'
        ]);

        try {
            $options = [
                'limit' => $request->input('limit', 50),
                'search_type' => $request->input('search_type', 'all'),
                'include_related' => $request->input('include_related', true),
                'include_long_tail' => $request->input('include_long_tail', true),
                'country' => $request->input('country', 'id'),
                'language' => $request->input('language', 'id')
            ];

            $keywords = $this->keywordResearchService->searchKeywords(
                $request->input('query'),
                $options
            );

            return response()->json([
                'success' => true,
                'keywords' => $keywords,
                'count' => count($keywords),
                'query' => $request->input('query')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search keywords: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular keywords by category
     */
    public function popular(Request $request): JsonResponse
    {
        $request->validate([
            'category' => 'string|in:technology,business,health,education,lifestyle,finance,general',
            'limit' => 'integer|min:5|max:50'
        ]);

        try {
            $category = $request->input('category', 'general');
            $limit = $request->input('limit', 20);

            $keywords = $this->keywordResearchService->getPopularKeywords($category, $limit);

            return response()->json([
                'success' => true,
                'keywords' => $keywords,
                'count' => count($keywords),
                'category' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get popular keywords: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get suggestions for autocomplete
     */
    public function suggestions(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:1|max:100'
        ]);

        try {
            // Get quick suggestions for autocomplete
            $keywords = $this->keywordResearchService->searchKeywords(
                $request->input('q'),
                ['limit' => 10, 'search_type' => 'suggestions']
            );

            // Return just the keyword strings for autocomplete
            $suggestions = array_map(function($keyword) {
                return $keyword['keyword'];
            }, $keywords);

            return response()->json([
                'success' => true,
                'suggestions' => array_unique($suggestions)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'suggestions' => []
            ]);
        }
    }

    /**
     * Export keywords to CSV
     */
    public function export(Request $request)
    {
        $request->validate([
            'keywords' => 'required|array',
            'format' => 'string|in:csv,json'
        ]);

        $keywords = $request->input('keywords');
        $format = $request->input('format', 'csv');

        if ($format === 'csv') {
            $filename = 'keywords_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($keywords) {
                $file = fopen('php://output', 'w');
                
                // CSV headers
                fputcsv($file, [
                    'Keyword',
                    'Search Volume',
                    'Difficulty',
                    'CPC',
                    'Competition',
                    'Intent',
                    'Trend',
                    'Category'
                ]);
                
                // CSV data
                foreach ($keywords as $keyword) {
                    fputcsv($file, [
                        $keyword['keyword'] ?? '',
                        $keyword['search_volume'] ?? '',
                        $keyword['difficulty'] ?? '',
                        '$' . ($keyword['cpc'] ?? '0.00'),
                        $keyword['competition'] ?? '',
                        $keyword['intent'] ?? '',
                        $keyword['trend'] ?? '',
                        $keyword['category'] ?? ''
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // JSON export
        $filename = 'keywords_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($keywords)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}