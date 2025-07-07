<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    <a href="{{ $project->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $project->url }}</a>
                    • Last analyzed: {{ $project->last_analyzed_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex space-x-3">
                <form action="{{ route('projects.reanalyze', $project) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Re-analyze
                    </button>
                </form>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <a href="{{ route('projects.show', $project) }}" 
                           class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('projects.show') && !request()->has('tab') ? 'border-blue-500 text-blue-600' : '' }}">
                            Overview
                        </a>
                        <!--  -->
                    </nav>
                </div>
            </div>
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- SEO Score Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">SEO Score Overview</h3>
                        <div class="flex items-center">
                            <div class="text-3xl font-bold {{ $project->getSeoScoreColorAttribute() }}">
                                {{ $project->seo_score }}/100
                            </div>
                            <div class="ml-4">
                                <div class="w-32 bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full {{ $project->seo_score >= 80 ? 'bg-green-500' : ($project->seo_score >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                         style="width: {{ $project->seo_score }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (isset($project->analysis_results['recommendations']) && count($project->analysis_results['recommendations']) > 0)
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Recommendations</h4>
                            <div class="space-y-2">
                                @foreach ($project->analysis_results['recommendations'] as $recommendation)
                                    <div class="flex items-start space-x-3 p-3 rounded-lg {{ $recommendation['type'] === 'critical' ? 'bg-red-50 border border-red-200' : ($recommendation['type'] === 'warning' ? 'bg-yellow-50 border border-yellow-200' : 'bg-blue-50 border border-blue-200') }}">
                                        <div class="flex-shrink-0">
                                            @if ($recommendation['type'] === 'critical')
                                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif ($recommendation['type'] === 'warning')
                                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm {{ $recommendation['type'] === 'critical' ? 'text-red-700' : ($recommendation['type'] === 'warning' ? 'text-yellow-700' : 'text-blue-700') }}">
                                                {{ $recommendation['message'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Analysis Results Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status Code</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->analysis_results['basic_info']['status_code'] == 200 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $project->analysis_results['basic_info']['status_code'] }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Page Title</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $project->analysis_results['basic_info']['title'] ?: 'Not found' }}
                                    @if ($project->analysis_results['basic_info']['title'])
                                        <span class="text-xs text-gray-500 ml-2">({{ $project->analysis_results['basic_info']['title_length'] }} characters)</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $project->analysis_results['basic_info']['meta_description'] ?: 'Not found' }}
                                    @if ($project->analysis_results['basic_info']['meta_description'])
                                        <span class="text-xs text-gray-500 ml-2">({{ $project->analysis_results['basic_info']['meta_description_length'] }} characters)</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Canonical URL</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['basic_info']['canonical_url'] ?: 'Not set' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Performance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Performance</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Page Size</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['performance']['page_size_formatted'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estimated Load Time</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['performance']['estimated_load_time'] }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Headings Structure -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Headings Structure</h3>
                        <div class="space-y-3">
                            @foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $heading)
                                @if (count($project->analysis_results['on_page_seo']['headings'][$heading]) > 0)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 uppercase">{{ $heading }} ({{ count($project->analysis_results['on_page_seo']['headings'][$heading]) }})</dt>
                                        <dd class="text-sm text-gray-900 mt-1">
                                            @foreach ($project->analysis_results['on_page_seo']['headings'][$heading] as $text)
                                                <div class="truncate py-1">{{ $text }}</div>
                                            @endforeach
                                        </dd>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if (collect($project->analysis_results['on_page_seo']['headings'])->flatten()->isEmpty())
                                <p class="text-sm text-gray-500">No headings found</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Images Analysis -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Images Analysis</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Images</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['on_page_seo']['images']['total_count'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Missing Alt Text</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="{{ $project->analysis_results['on_page_seo']['images']['missing_alt'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $project->analysis_results['on_page_seo']['images']['missing_alt'] }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Missing Title Attributes</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['on_page_seo']['images']['missing_title'] }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Links Analysis -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Links Analysis</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Internal Links</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['on_page_seo']['links']['internal_links'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">External Links</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['on_page_seo']['links']['external_links'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nofollow Links</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['on_page_seo']['links']['nofollow_links'] }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Content Analysis -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Content Analysis</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Word Count</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($project->analysis_results['content_analysis']['word_count']) }} words</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Character Count</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($project->analysis_results['content_analysis']['character_count']) }} characters</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Paragraphs</dt>
                                <dd class="text-sm text-gray-900">{{ $project->analysis_results['content_analysis']['paragraph_count'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Reading Time</dt>
                                <dd class="text-sm text-gray-900">~{{ $project->analysis_results['content_analysis']['reading_time'] }} minutes</dd>
                            </div>
                        </dl>
                        
                        @if (isset($project->analysis_results['content_analysis']['keyword_density']) && count($project->analysis_results['content_analysis']['keyword_density']) > 0)
                            <div class="mt-4">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Top Keywords</dt>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (array_slice($project->analysis_results['content_analysis']['keyword_density'], 0, 10) as $keyword => $count)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $keyword }} ({{ $count }})
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Technical SEO -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Technical SEO</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Basic Meta Tags</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Robots Meta</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['robots_meta'] ?: 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Viewport Meta</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['viewport_meta'] ?: 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Language Attribute</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['lang_attribute'] ?: 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Charset</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['charset'] ?: 'Not set' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Structured Data</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">JSON-LD Scripts</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['structured_data']['json_ld'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Microdata</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['structured_data']['microdata'] }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">RDFa</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['structured_data']['rdfa'] }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Open Graph</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">OG Title</dt>
                                    <dd class="text-sm text-gray-900 truncate">{{ $project->analysis_results['technical_seo']['open_graph']['og_title'] ?: 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">OG Description</dt>
                                    <dd class="text-sm text-gray-900 truncate">{{ $project->analysis_results['technical_seo']['open_graph']['og_description'] ?: 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">OG Image</dt>
                                    <dd class="text-sm text-gray-900 truncate">{{ $project->analysis_results['technical_seo']['open_graph']['og_image'] ?: 'Not set' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Twitter Card</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Card Type</dt>
                                    <dd class="text-sm text-gray-900">{{ $project->analysis_results['technical_seo']['twitter_card']['card'] ?: 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Twitter Title</dt>
                                    <dd class="text-sm text-gray-900 truncate">{{ $project->analysis_results['technical_seo']['twitter_card']['title'] ?: 'Not set' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Twitter Description</dt>
                                    <dd class="text-sm text-gray-900 truncate">{{ $project->analysis_results['technical_seo']['twitter_card']['description'] ?: 'Not set' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile SEO -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mobile SEO</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Viewport Configured</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->analysis_results['mobile_seo']['viewport_configured'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $project->analysis_results['mobile_seo']['viewport_configured'] ? 'Yes' : 'No' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Viewport Content</dt>
                            <dd class="text-sm text-gray-900">{{ $project->analysis_results['mobile_seo']['viewport_content'] ?: 'Not set' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Apple Mobile Web App Capable</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->analysis_results['mobile_seo']['mobile_friendly_tags']['apple_mobile_web_app_capable'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $project->analysis_results['mobile_seo']['mobile_friendly_tags']['apple_mobile_web_app_capable'] ? 'Yes' : 'No' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>