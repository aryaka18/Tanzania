<?php

namespace App\Services;

use App\Models\Keyword;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class KeywordResearchService
{
    private const GOOGLE_SUGGEST_URL = 'https://suggestqueries.google.com/complete/search';
    private const UBERSUGGEST_API_URL = 'https://app.neilpatel.com/api/suggestions';
    
    /**
     * Research keywords for a given seed keyword
     */
    public function researchKeywords(string $seedKeyword, int $limit = 50): array
    {
        $keywords = collect();
        
        // Get suggestions from Google Suggest
        $googleSuggestions = $this->getGoogleSuggestions($seedKeyword);
        $keywords = $keywords->merge($googleSuggestions);
        
        // Get related keywords
        $relatedKeywords = $this->getRelatedKeywords($seedKeyword);
        $keywords = $keywords->merge($relatedKeywords);
        
        // Get long-tail variations
        $longTailKeywords = $this->getLongTailKeywords($seedKeyword);
        $keywords = $keywords->merge($longTailKeywords);
        
        // Remove duplicates and process
        $uniqueKeywords = $keywords->unique()->take($limit);
        
        return $uniqueKeywords->map(function ($keyword) {
            return [
                'keyword' => $keyword,
                'search_volume' => $this->estimateSearchVolume($keyword),
                'difficulty' => $this->estimateKeywordDifficulty($keyword),
                'cpc' => $this->estimateCPC($keyword),
                'competition' => $this->getCompetitionLevel($keyword),
                'intent' => $this->determineSearchIntent($keyword),
                'related_keywords' => $this->getRelatedTerms($keyword, 5)
            ];
        })->toArray();
    }
    
    /**
     * Get keyword suggestions from Google Suggest API
     */
    private function getGoogleSuggestions(string $keyword): Collection
    {
        try {
            $response = Http::timeout(10)->get(self::GOOGLE_SUGGEST_URL, [
                'client' => 'chrome',
                'q' => $keyword,
                'hl' => 'en'
            ]);
            
            if ($response->successful()) {
                $data = $response->body();
                // Parse JSONP response
                $jsonStart = strpos($data, '[');
                if ($jsonStart !== false) {
                    $json = substr($data, $jsonStart, -1);
                    $suggestions = json_decode($json, true);
                    
                    if (isset($suggestions[1]) && is_array($suggestions[1])) {
                        return collect($suggestions[1])->flatten();
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error but continue
            \Log::warning('Google Suggest API failed: ' . $e->getMessage());
        }
        
        return collect();
    }
    
    /**
     * Generate related keywords using various techniques
     */
    private function getRelatedKeywords(string $keyword): Collection
    {
        $related = collect();
        
        // Question-based keywords
        $questionWords = ['what', 'how', 'why', 'where', 'when', 'who', 'which'];
        foreach ($questionWords as $question) {
            $related->push($question . ' is ' . $keyword);
            $related->push($question . ' to ' . $keyword);
            $related->push($keyword . ' ' . $question);
        }
        
        // Comparison keywords
        $comparisonWords = ['vs', 'versus', 'compared to', 'alternative', 'like', 'similar'];
        foreach ($comparisonWords as $comparison) {
            $related->push($keyword . ' ' . $comparison);
        }
        
        // Action-based keywords
        $actionWords = ['buy', 'purchase', 'download', 'install', 'learn', 'tutorial', 'guide', 'tips'];
        foreach ($actionWords as $action) {
            $related->push($action . ' ' . $keyword);
            $related->push($keyword . ' ' . $action);
        }
        
        // Modifier keywords
        $modifiers = ['best', 'top', 'free', 'cheap', 'premium', 'professional', 'easy', 'advanced'];
        foreach ($modifiers as $modifier) {
            $related->push($modifier . ' ' . $keyword);
        }
        
        return $related;
    }
    
    /**
     * Generate long-tail keyword variations
     */
    private function getLongTailKeywords(string $keyword): Collection
    {
        $longTail = collect();
        
        // Add year variations
        $currentYear = date('Y');
        $longTail->push($keyword . ' ' . $currentYear);
        $longTail->push($keyword . ' ' . ($currentYear + 1));
        
        // Add location-based variations
        $locations = ['near me', 'online', 'local', 'in usa', 'worldwide'];
        foreach ($locations as $location) {
            $longTail->push($keyword . ' ' . $location);
        }
        
        // Add problem-solving variations
        $problems = ['not working', 'error', 'fix', 'troubleshoot', 'solution'];
        foreach ($problems as $problem) {
            $longTail->push($keyword . ' ' . $problem);
        }
        
        // Add feature-based variations
        $features = ['with', 'without', 'including', 'plus', 'pro', 'lite'];
        foreach ($features as $feature) {
            $longTail->push($keyword . ' ' . $feature);
        }
        
        return $longTail;
    }
    
    /**
     * Estimate search volume based on keyword characteristics
     */
    private function estimateSearchVolume(string $keyword): int
    {
        // Simple estimation algorithm based on keyword length and common patterns
        $wordCount = str_word_count($keyword);
        $length = strlen($keyword);
        
        // Base volume inversely related to length
        $baseVolume = max(100, 10000 / $length);
        
        // Adjust for word count (longer phrases typically have lower volume)
        $wordCountMultiplier = 1 / $wordCount;
        
        // Boost for commercial intent keywords
        $commercialKeywords = ['buy', 'purchase', 'price', 'cost', 'cheap', 'best', 'review'];
        $hasCommercialIntent = collect($commercialKeywords)->contains(function ($commercial) use ($keyword) {
            return stripos($keyword, $commercial) !== false;
        });
        
        if ($hasCommercialIntent) {
            $baseVolume *= 1.5;
        }
        
        // Add some randomization to make it more realistic
        $randomFactor = rand(80, 120) / 100;
        
        return (int) ($baseVolume * $wordCountMultiplier * $randomFactor);
    }
    
    /**
     * Estimate keyword difficulty score (0-100)
     */
    private function estimateKeywordDifficulty(string $keyword): float
    {
        $wordCount = str_word_count($keyword);
        $length = strlen($keyword);
        
        // Shorter, more generic keywords are typically harder
        $baseDifficulty = max(10, min(90, 100 - ($length * 2)));
        
        // Long-tail keywords are easier
        if ($wordCount >= 4) {
            $baseDifficulty *= 0.6;
        } elseif ($wordCount >= 3) {
            $baseDifficulty *= 0.8;
        }
        
        // Commercial keywords are more competitive
        $commercialKeywords = ['buy', 'purchase', 'price', 'cost', 'cheap', 'best'];
        $hasCommercialIntent = collect($commercialKeywords)->contains(function ($commercial) use ($keyword) {
            return stripos($keyword, $commercial) !== false;
        });
        
        if ($hasCommercialIntent) {
            $baseDifficulty *= 1.3;
        }
        
        return round(min(100, max(1, $baseDifficulty)), 2);
    }
    
    /**
     * Estimate CPC (Cost Per Click)
     */
    private function estimateCPC(string $keyword): float
    {
        $commercialKeywords = ['buy', 'purchase', 'price', 'cost', 'insurance', 'loan', 'lawyer'];
        $hasCommercialIntent = collect($commercialKeywords)->contains(function ($commercial) use ($keyword) {
            return stripos($keyword, $commercial) !== false;
        });
        
        if ($hasCommercialIntent) {
            return round(rand(200, 2000) / 100, 2); // $2.00 to $20.00
        }
        
        return round(rand(10, 150) / 100, 2); // $0.10 to $1.50
    }
    
    /**
     * Determine competition level
     */
    private function getCompetitionLevel(string $keyword): string
    {
        $difficulty = $this->estimateKeywordDifficulty($keyword);
        
        if ($difficulty <= 30) {
            return 'Low';
        } elseif ($difficulty <= 60) {
            return 'Medium';
        } else {
            return 'High';
        }
    }
    
    /**
     * Determine search intent
     */
    private function determineSearchIntent(string $keyword): string
    {
        $keyword = strtolower($keyword);
        
        // Informational intent
        $informationalWords = ['what', 'how', 'why', 'where', 'when', 'guide', 'tutorial', 'tips'];
        foreach ($informationalWords as $word) {
            if (strpos($keyword, $word) !== false) {
                return 'Informational';
            }
        }
        
        // Commercial intent
        $commercialWords = ['buy', 'purchase', 'price', 'cost', 'cheap', 'best', 'review', 'compare'];
        foreach ($commercialWords as $word) {
            if (strpos($keyword, $word) !== false) {
                return 'Commercial';
            }
        }
        
        // Navigational intent
        $navigationalWords = ['login', 'sign in', 'website', 'official', 'homepage'];
        foreach ($navigationalWords as $word) {
            if (strpos($keyword, $word) !== false) {
                return 'Navigational';
            }
        }
        
        return 'Informational'; // Default
    }
    
    /**
     * Get related terms for a keyword
     */
    private function getRelatedTerms(string $keyword, int $limit = 5): array
    {
        // Simple related terms generation
        $words = explode(' ', $keyword);
        $related = [];
        
        // Synonyms and variations (simplified)
        $synonyms = [
            'best' => ['top', 'excellent', 'great', 'amazing'],
            'guide' => ['tutorial', 'how-to', 'tips', 'help'],
            'free' => ['gratis', 'no-cost', 'complimentary'],
            'software' => ['tool', 'application', 'program', 'app'],
        ];
        
        foreach ($words as $word) {
            if (isset($synonyms[strtolower($word)])) {
                foreach ($synonyms[strtolower($word)] as $synonym) {
                    $relatedKeyword = str_replace($word, $synonym, $keyword);
                    if ($relatedKeyword !== $keyword) {
                        $related[] = $relatedKeyword;
                    }
                }
            }
        }
        
        return array_slice(array_unique($related), 0, $limit);
    }
    
    /**
     * Add keywords to project tracking
     */
    public function addKeywordsToProject(Project $project, array $keywords): void
    {
        foreach ($keywords as $keywordData) {
            Keyword::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'keyword' => $keywordData['keyword']
                ],
                [
                    'search_volume' => $keywordData['search_volume'] ?? null,
                    'difficulty' => $keywordData['difficulty'] ?? null,
                    'tracking_data' => [
                        'cpc' => $keywordData['cpc'] ?? null,
                        'competition' => $keywordData['competition'] ?? null,
                        'intent' => $keywordData['intent'] ?? null,
                        'related_keywords' => $keywordData['related_keywords'] ?? [],
                        'added_at' => now()->toDateTimeString()
                    ]
                ]
            );
        }
    }
    
    /**
     * Track keyword positions for a project
     */
    public function trackKeywordPositions(Project $project): array
    {
        $keywords = $project->keywords;
        $results = [];
        
        foreach ($keywords as $keyword) {
            $position = $this->simulateKeywordPosition($keyword->keyword, $project->url);
            
            // Update tracking data
            $trackingData = $keyword->tracking_data ?? [];
            $trackingData['position_history'] = $trackingData['position_history'] ?? [];
            $trackingData['position_history'][] = [
                'date' => now()->toDateString(),
                'position' => $position,
                'checked_at' => now()->toDateTimeString()
            ];
            
            // Keep only last 30 days of data
            $trackingData['position_history'] = array_slice($trackingData['position_history'], -30);
            
            $keyword->update([
                'position' => $position,
                'tracking_data' => $trackingData
            ]);
            
            $results[] = [
                'keyword' => $keyword->keyword,
                'position' => $position,
                'previous_position' => $this->getPreviousPosition($keyword),
                'change' => $this->getPositionChange($keyword)
            ];
        }
        
        return $results;
    }
    
    /**
     * Simulate keyword position (in real implementation, use actual SERP API)
     */
    private function simulateKeywordPosition(string $keyword, string $url): ?int
    {
        // Simulate realistic position tracking
        $positions = [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30, 40, 50, 75, 100];
        $weights = [30, 5, 8, 10, 12, 10, 8, 6, 5, 4, 3, 8, 6, 4, 3, 2, 2, 1, 1]; // Higher weight for not ranking
        
        return $positions[array_rand(array_combine($positions, $weights))];
    }
    
    /**
     * Get previous position for comparison
     */
    private function getPreviousPosition(Keyword $keyword): ?int
    {
        $history = $keyword->tracking_data['position_history'] ?? [];
        if (count($history) >= 2) {
            return $history[count($history) - 2]['position'];
        }
        return null;
    }
    
    /**
     * Calculate position change
     */
    private function getPositionChange(Keyword $keyword): array
    {
        $current = $keyword->position;
        $previous = $this->getPreviousPosition($keyword);
        
        if (!$current || !$previous) {
            return ['change' => 0, 'direction' => 'neutral'];
        }
        
        $change = $previous - $current; // Positive means improvement (lower position number)
        
        return [
            'change' => abs($change),
            'direction' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral')
        ];
    }
    
    /**
     * Get keyword opportunities (gaps and potential)
     */
    public function getKeywordOpportunities(Project $project): array
    {
        $keywords = $project->keywords;
        $opportunities = [];
        
        foreach ($keywords as $keyword) {
            $opportunity = $this->analyzeKeywordOpportunity($keyword);
            if ($opportunity['score'] > 0) {
                $opportunities[] = $opportunity;
            }
        }
        
        // Sort by opportunity score
        usort($opportunities, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $opportunities;
    }
    
    /**
     * Analyze individual keyword opportunity
     */
    private function analyzeKeywordOpportunity(Keyword $keyword): array
    {
        $score = 0;
        $reasons = [];
        
        // High search volume, low competition
        if ($keyword->search_volume > 1000 && $keyword->difficulty < 30) {
            $score += 30;
            $reasons[] = 'High search volume with low competition';
        }
        
        // Currently ranking but not in top 10
        if ($keyword->position && $keyword->position > 10 && $keyword->position <= 50) {
            $score += 25;
            $reasons[] = 'Currently ranking but has room for improvement';
        }
        
        // Commercial intent with reasonable difficulty
        $intent = $keyword->tracking_data['intent'] ?? '';
        if ($intent === 'Commercial' && $keyword->difficulty < 70) {
            $score += 20;
            $reasons[] = 'Commercial intent keyword with manageable competition';
        }
        
        // Long-tail with decent volume
        if (str_word_count($keyword->keyword) >= 3 && $keyword->search_volume > 500) {
            $score += 15;
            $reasons[] = 'Long-tail keyword with good search volume';
        }
        
        return [
            'keyword' => $keyword->keyword,
            'score' => $score,
            'reasons' => $reasons,
            'search_volume' => $keyword->search_volume,
            'difficulty' => $keyword->difficulty,
            'current_position' => $keyword->position,
            'intent' => $intent
        ];
    }
}