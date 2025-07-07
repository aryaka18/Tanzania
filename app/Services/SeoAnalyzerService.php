<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class SeoAnalyzerService
{
    /**
     * Analyze a given URL for comprehensive SEO metrics.
     */
    public function analyze(string $url): array
    {
        try {
            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                return ['error' => 'Could not fetch the URL. Status code: ' . $response->status()];
            }

            $html = $response->body();
            $crawler = new Crawler($html);

            $analysis = [
                'basic_info' => $this->analyzeBasicInfo($response, $crawler),
                'on_page_seo' => $this->analyzeOnPageSeo($crawler),
                'technical_seo' => $this->analyzeTechnicalSeo($crawler, $html),
                'performance' => $this->analyzePerformance($html),
                'mobile_seo' => $this->analyzeMobileSeo($crawler),
                'content_analysis' => $this->analyzeContent($crawler, $html),
            ];

            $analysis['seo_score'] = $this->calculateSeoScore($analysis);
            $analysis['recommendations'] = $this->generateRecommendations($analysis);

            return $analysis;

        } catch (\Exception $e) {
            return ['error' => 'An exception occurred: ' . $e->getMessage()];
        }
    }

    private function analyzeBasicInfo($response, Crawler $crawler): array
    {
        return [
            'status_code' => $response->status(),
            'title' => $crawler->filter('title')->count() ? $crawler->filter('title')->first()->text() : null,
            'title_length' => $crawler->filter('title')->count() ? strlen($crawler->filter('title')->first()->text()) : 0,
            'meta_description' => $crawler->filter('meta[name="description"]')->count() ? $crawler->filter('meta[name="description"]')->attr('content') : null,
            'meta_description_length' => $crawler->filter('meta[name="description"]')->count() ? strlen($crawler->filter('meta[name="description"]')->attr('content')) : 0,
            'canonical_url' => $crawler->filter('link[rel="canonical"]')->count() ? $crawler->filter('link[rel="canonical"]')->attr('href') : null,
        ];
    }

    private function analyzeOnPageSeo(Crawler $crawler): array
    {
        return [
            'headings' => [
                'h1' => $crawler->filter('h1')->each(fn ($node) => $node->text()),
                'h2' => $crawler->filter('h2')->each(fn ($node) => $node->text()),
                'h3' => $crawler->filter('h3')->each(fn ($node) => $node->text()),
                'h4' => $crawler->filter('h4')->each(fn ($node) => $node->text()),
                'h5' => $crawler->filter('h5')->each(fn ($node) => $node->text()),
                'h6' => $crawler->filter('h6')->each(fn ($node) => $node->text()),
            ],
            'images' => [
                'total_count' => $crawler->filter('img')->count(),
                'missing_alt' => $crawler->filter('img:not([alt])')->count(),
                'missing_title' => $crawler->filter('img:not([title])')->count(),
            ],
            'links' => [
                'internal_links' => $crawler->filter('a[href^="/"], a[href*="' . parse_url($crawler->getUri(), PHP_URL_HOST) . '"]')->count(),
                'external_links' => $crawler->filter('a[href^="http"]:not([href*="' . parse_url($crawler->getUri(), PHP_URL_HOST) . '"])')->count(),
                'nofollow_links' => $crawler->filter('a[rel*="nofollow"]')->count(),
            ],
        ];
    }

    private function analyzeTechnicalSeo(Crawler $crawler, string $html): array
    {
        return [
            'robots_meta' => $crawler->filter('meta[name="robots"]')->count() ? $crawler->filter('meta[name="robots"]')->attr('content') : null,
            'viewport_meta' => $crawler->filter('meta[name="viewport"]')->count() ? $crawler->filter('meta[name="viewport"]')->attr('content') : null,
            'lang_attribute' => $crawler->filter('html[lang]')->count() ? $crawler->filter('html[lang]')->attr('lang') : null,
            'charset' => $crawler->filter('meta[charset]')->count() ? $crawler->filter('meta[charset]')->attr('charset') : null,
            'structured_data' => [
                'json_ld' => $crawler->filter('script[type="application/ld+json"]')->count(),
                'microdata' => $crawler->filter('[itemscope]')->count(),
                'rdfa' => $crawler->filter('[typeof]')->count(),
            ],
            'open_graph' => [
                'og_title' => $crawler->filter('meta[property="og:title"]')->count() ? $crawler->filter('meta[property="og:title"]')->attr('content') : null,
                'og_description' => $crawler->filter('meta[property="og:description"]')->count() ? $crawler->filter('meta[property="og:description"]')->attr('content') : null,
                'og_image' => $crawler->filter('meta[property="og:image"]')->count() ? $crawler->filter('meta[property="og:image"]')->attr('content') : null,
            ],
            'twitter_card' => [
                'card' => $crawler->filter('meta[name="twitter:card"]')->count() ? $crawler->filter('meta[name="twitter:card"]')->attr('content') : null,
                'title' => $crawler->filter('meta[name="twitter:title"]')->count() ? $crawler->filter('meta[name="twitter:title"]')->attr('content') : null,
                'description' => $crawler->filter('meta[name="twitter:description"]')->count() ? $crawler->filter('meta[name="twitter:description"]')->attr('content') : null,
            ],
        ];
    }

    private function analyzePerformance(string $html): array
    {
        return [
            'page_size' => strlen($html),
            'page_size_formatted' => $this->formatBytes(strlen($html)),
            'estimated_load_time' => $this->estimateLoadTime(strlen($html)),
        ];
    }

    private function analyzeMobileSeo(Crawler $crawler): array
    {
        $viewportMeta = $crawler->filter('meta[name="viewport"]')->count() ? $crawler->filter('meta[name="viewport"]')->attr('content') : null;
        
        return [
            'viewport_configured' => $viewportMeta !== null,
            'viewport_content' => $viewportMeta,
            'mobile_friendly_tags' => [
                'apple_mobile_web_app_capable' => $crawler->filter('meta[name="apple-mobile-web-app-capable"]')->count() > 0,
                'apple_mobile_web_app_status_bar_style' => $crawler->filter('meta[name="apple-mobile-web-app-status-bar-style"]')->count() > 0,
            ],
        ];
    }

    private function analyzeContent(Crawler $crawler, string $html): array
    {
        $textContent = strip_tags($html);
        $words = str_word_count($textContent, 1);
        
        return [
            'word_count' => count($words),
            'character_count' => strlen($textContent),
            'paragraph_count' => $crawler->filter('p')->count(),
            'reading_time' => ceil(count($words) / 200), // Average reading speed: 200 words per minute
            'keyword_density' => $this->calculateKeywordDensity($words),
        ];
    }

    private function calculateKeywordDensity(array $words): array
    {
        $wordFrequency = array_count_values(array_map('strtolower', $words));
        arsort($wordFrequency);
        
        return array_slice($wordFrequency, 0, 10); // Top 10 most frequent words
    }

    private function calculateSeoScore(array $analysis): int
    {
        $score = 0;
        $maxScore = 100;

        // Title optimization (20 points)
        if ($analysis['basic_info']['title']) {
            $titleLength = $analysis['basic_info']['title_length'];
            if ($titleLength >= 30 && $titleLength <= 60) {
                $score += 20;
            } elseif ($titleLength > 0) {
                $score += 10;
            }
        }

        // Meta description (15 points)
        if ($analysis['basic_info']['meta_description']) {
            $metaLength = $analysis['basic_info']['meta_description_length'];
            if ($metaLength >= 120 && $metaLength <= 160) {
                $score += 15;
            } elseif ($metaLength > 0) {
                $score += 8;
            }
        }

        // Headings structure (15 points)
        $h1Count = count($analysis['on_page_seo']['headings']['h1']);
        if ($h1Count === 1) {
            $score += 15;
        } elseif ($h1Count > 0) {
            $score += 8;
        }

        // Images optimization (10 points)
        $totalImages = $analysis['on_page_seo']['images']['total_count'];
        $missingAlt = $analysis['on_page_seo']['images']['missing_alt'];
        if ($totalImages > 0) {
            $altRatio = ($totalImages - $missingAlt) / $totalImages;
            $score += intval($altRatio * 10);
        }

        // Technical SEO (20 points)
        if ($analysis['technical_seo']['viewport_meta']) $score += 5;
        if ($analysis['technical_seo']['lang_attribute']) $score += 5;
        if ($analysis['technical_seo']['charset']) $score += 5;
        if ($analysis['basic_info']['canonical_url']) $score += 5;

        // Content quality (10 points)
        $wordCount = $analysis['content_analysis']['word_count'];
        if ($wordCount >= 300) {
            $score += 10;
        } elseif ($wordCount >= 100) {
            $score += 5;
        }

        // Social media optimization (10 points)
        if ($analysis['technical_seo']['open_graph']['og_title']) $score += 5;
        if ($analysis['technical_seo']['open_graph']['og_description']) $score += 5;

        return min($score, $maxScore);
    }

    private function generateRecommendations(array $analysis): array
    {
        $recommendations = [];

        // Title recommendations
        if (!$analysis['basic_info']['title']) {
            $recommendations[] = ['type' => 'critical', 'message' => 'Add a page title tag'];
        } elseif ($analysis['basic_info']['title_length'] < 30) {
            $recommendations[] = ['type' => 'warning', 'message' => 'Page title is too short (less than 30 characters)'];
        } elseif ($analysis['basic_info']['title_length'] > 60) {
            $recommendations[] = ['type' => 'warning', 'message' => 'Page title is too long (more than 60 characters)'];
        }

        // Meta description recommendations
        if (!$analysis['basic_info']['meta_description']) {
            $recommendations[] = ['type' => 'critical', 'message' => 'Add a meta description'];
        } elseif ($analysis['basic_info']['meta_description_length'] < 120) {
            $recommendations[] = ['type' => 'warning', 'message' => 'Meta description is too short (less than 120 characters)'];
        } elseif ($analysis['basic_info']['meta_description_length'] > 160) {
            $recommendations[] = ['type' => 'warning', 'message' => 'Meta description is too long (more than 160 characters)'];
        }

        // Heading recommendations
        $h1Count = count($analysis['on_page_seo']['headings']['h1']);
        if ($h1Count === 0) {
            $recommendations[] = ['type' => 'critical', 'message' => 'Add an H1 heading to your page'];
        } elseif ($h1Count > 1) {
            $recommendations[] = ['type' => 'warning', 'message' => 'Multiple H1 tags found. Use only one H1 per page'];
        }

        // Image recommendations
        if ($analysis['on_page_seo']['images']['missing_alt'] > 0) {
            $recommendations[] = ['type' => 'warning', 'message' => $analysis['on_page_seo']['images']['missing_alt'] . ' images are missing alt attributes'];
        }

        // Technical SEO recommendations
        if (!$analysis['technical_seo']['viewport_meta']) {
            $recommendations[] = ['type' => 'critical', 'message' => 'Add a viewport meta tag for mobile optimization'];
        }

        if (!$analysis['technical_seo']['lang_attribute']) {
            $recommendations[] = ['type' => 'warning', 'message' => 'Add a language attribute to your HTML tag'];
        }

        // Content recommendations
        if ($analysis['content_analysis']['word_count'] < 300) {
            $recommendations[] = ['type' => 'info', 'message' => 'Consider adding more content. Pages with 300+ words typically perform better'];
        }

        // Social media recommendations
        if (!$analysis['technical_seo']['open_graph']['og_title']) {
            $recommendations[] = ['type' => 'info', 'message' => 'Add Open Graph meta tags for better social media sharing'];
        }

        return $recommendations;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function estimateLoadTime(int $bytes): string
    {
        // Estimate based on average broadband speed (10 Mbps)
        $loadTimeSeconds = $bytes / (10 * 1024 * 1024 / 8);
        return round($loadTimeSeconds, 2) . 's';
    }
}
