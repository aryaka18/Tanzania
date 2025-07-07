<!-- resources\views\keywords\research.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Global Keyword Research
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Discover popular keywords and search trends from multiple sources globally
                </p>
            </div>
            <div class="flex gap-2">
                <select id="country-selector" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="id">🇮🇩 Indonesia</option>
                    <option value="us">🇺🇸 United States</option>
                    <option value="gb">🇬🇧 United Kingdom</option>
                    <option value="au">🇦🇺 Australia</option>
                    <option value="my">🇲🇾 Malaysia</option>
                    <option value="sg">🇸🇬 Singapore</option>
                    <option value="th">🇹🇭 Thailand</option>
                    <option value="in">🇮🇳 India</option>
                </select>
                <select id="language-selector" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="id">Bahasa Indonesia</option>
                    <option value="en">English</option>
                    <option value="ms">Bahasa Malaysia</option>
                    <option value="th">Thai</option>
                    <option value="hi">Hindi</option>
                </select>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Search Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col xl:flex-row gap-6">
                        <!-- Search Form -->
                        <div class="xl:w-2/3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Keyword Research</h3>
                            <form id="keyword-search-form" class="space-y-4">
                                <div class="relative">
                                    <div class="flex gap-4">
                                        <div class="flex-1 relative">
                                            <input type="text" 
                                                   id="search-query" 
                                                   name="query" 
                                                   placeholder="Enter keyword or phrase (e.g., 'bisnis online', 'teknologi ai')..." 
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                                   autocomplete="off">
                                            <div id="suggestions-dropdown" class="absolute z-20 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 hidden max-h-60 overflow-y-auto"></div>
                                        </div>
                                        <button type="submit" 
                                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
                                                id="search-btn">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            Search
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Advanced Options -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Search Options</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="include_related" checked class="mr-2 text-blue-600">
                                            Include related keywords
                                        </label>
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="include_long_tail" checked class="mr-2 text-blue-600">
                                            Include long-tail keywords
                                        </label>
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="include_questions" class="mr-2 text-blue-600">
                                            Include question keywords
                                        </label>
                                        <label class="flex items-center text-sm">
                                            <input type="checkbox" name="include_local" class="mr-2 text-blue-600">
                                            Include local variations
                                        </label>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-4 mt-4">
                                        <select name="search_type" class="px-3 py-2 border border-gray-300 rounded text-sm">
                                            <option value="all">All Sources</option>
                                            <option value="google">Google Only</option>
                                            <option value="youtube">YouTube Only</option>
                                            <option value="amazon">Amazon Only</option>
                                            <option value="suggestions">Suggestions Only</option>
                                            <option value="related">Related Only</option>
                                        </select>
                                        <select name="limit" class="px-3 py-2 border border-gray-300 rounded text-sm">
                                            <option value="25">25 results</option>
                                            <option value="50" selected>50 results</option>
                                            <option value="75">75 results</option>
                                            <option value="100">100 results</option>
                                            <option value="200">200 results</option>
                                        </select>
                                        <select name="difficulty_filter" class="px-3 py-2 border border-gray-300 rounded text-sm">
                                            <option value="">All Difficulties</option>
                                            <option value="easy">Easy (0-30)</option>
                                            <option value="medium">Medium (31-60)</option>
                                            <option value="hard">Hard (61-100)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="quick-search px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm hover:bg-purple-200" data-query="cara">cara...</button>
                                    <button type="button" class="quick-search px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm hover:bg-green-200" data-query="tips">tips...</button>
                                    <button type="button" class="quick-search px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200" data-query="harga">harga...</button>
                                    <button type="button" class="quick-search px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm hover:bg-yellow-200" data-query="review">review...</button>
                                    <button type="button" class="quick-search px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm hover:bg-red-200" data-query="terbaik">terbaik...</button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Popular Categories & Stats -->
                        <div class="xl:w-1/3 space-y-6">
                            <!-- Categories -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Popular Categories</h3>
                                <div class="grid grid-cols-2 gap-2">
                                    <button class="category-btn px-4 py-3 text-sm bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-lg text-left transition-all duration-200" data-category="technology">
                                        <div class="text-2xl mb-1">📱</div>
                                        <div class="font-medium">Technology</div>
                                        <div class="text-xs text-gray-600">AI, Apps, Software</div>
                                    </button>
                                    <button class="category-btn px-4 py-3 text-sm bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-lg text-left transition-all duration-200" data-category="business">
                                        <div class="text-2xl mb-1">💼</div>
                                        <div class="font-medium">Business</div>
                                        <div class="text-xs text-gray-600">Marketing, Startup</div>
                                    </button>
                                    <button class="category-btn px-4 py-3 text-sm bg-gradient-to-r from-red-50 to-red-100 hover:from-red-100 hover:to-red-200 rounded-lg text-left transition-all duration-200" data-category="health">
                                        <div class="text-2xl mb-1">🏥</div>
                                        <div class="font-medium">Health</div>
                                        <div class="text-xs text-gray-600">Diet, Fitness</div>
                                    </button>
                                    <button class="category-btn px-4 py-3 text-sm bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-lg text-left transition-all duration-200" data-category="education">
                                        <div class="text-2xl mb-1">📚</div>
                                        <div class="font-medium">Education</div>
                                        <div class="text-xs text-gray-600">Courses, Learning</div>
                                    </button>
                                    <button class="category-btn px-4 py-3 text-sm bg-gradient-to-r from-pink-50 to-pink-100 hover:from-pink-100 hover:to-pink-200 rounded-lg text-left transition-all duration-200" data-category="lifestyle">
                                        <div class="text-2xl mb-1">🎨</div>
                                        <div class="font-medium">Lifestyle</div>
                                        <div class="text-xs text-gray-600">Fashion, Travel</div>
                                    </button>
                                    <button class="category-btn px-4 py-3 text-sm bg-gradient-to-r from-yellow-50 to-yellow-100 hover:from-yellow-100 hover:to-yellow-200 rounded-lg text-left transition-all duration-200" data-category="finance">
                                        <div class="text-2xl mb-1">💰</div>
                                        <div class="font-medium">Finance</div>
                                        <div class="text-xs text-gray-600">Investment, Crypto</div>
                                    </button>
                                </div>
                            </div>

                            <!-- Search Stats -->
                            <div id="search-stats" class="bg-gray-50 p-4 rounded-lg hidden">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Search Statistics</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>Total Keywords:</span>
                                        <span id="total-keywords" class="font-medium">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Avg. Search Volume:</span>
                                        <span id="avg-volume" class="font-medium">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Avg. Difficulty:</span>
                                        <span id="avg-difficulty" class="font-medium">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>High Volume (>1k):</span>
                                        <span id="high-volume-count" class="font-medium">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div id="loading" class="hidden text-center py-12">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600"></div>
                    <p class="text-gray-600 mt-4 text-lg">Searching across multiple sources...</p>
                    <div id="loading-progress" class="mt-2">
                        <div class="flex space-x-2 text-sm text-gray-500">
                            <span id="progress-google" class="opacity-50">Google</span>
                            <span id="progress-youtube" class="opacity-50">YouTube</span>
                            <span id="progress-bing" class="opacity-50">Bing</span>
                            <span id="progress-amazon" class="opacity-50">Amazon</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="results-section" class="hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Search Results</h3>
                                <p id="results-summary" class="text-sm text-gray-600 mt-1"></p>
                                <div id="source-breakdown" class="text-xs text-gray-500 mt-1"></div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button id="bulk-actions" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                                    📝 Bulk Actions
                                </button>
                                <button id="export-csv" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                    📊 Export CSV
                                </button>
                                <button id="export-json" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                                    📋 Export JSON
                                </button>
                                <button id="save-to-project" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    💾 Save to Project
                                </button>
                            </div>
                        </div>

                        <!-- Enhanced Filter and Sort Options -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                                <select id="sort-by" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="search_volume">Sort by Volume</option>
                                    <option value="difficulty">Sort by Difficulty</option>
                                    <option value="cpc">Sort by CPC</option>
                                    <option value="keyword">Sort Alphabetically</option>
                                    <option value="competition">Sort by Competition</option>
                                </select>
                                <select id="filter-intent" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="">All Intents</option>
                                    <option value="Informational">Informational</option>
                                    <option value="Commercial">Commercial</option>
                                    <option value="Navigational">Navigational</option>
                                    <option value="Transactional">Transactional</option>
                                </select>
                                <select id="filter-competition" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="">All Competition</option>
                                    <option value="Low">Low Competition</option>
                                    <option value="Medium">Medium Competition</option>
                                    <option value="High">High Competition</option>
                                </select>
                                <select id="filter-source" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option value="">All Sources</option>
                                    <option value="google">Google</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="amazon">Amazon</option>
                                    <option value="bing">Bing</option>
                                </select>
                                <div class="flex items-center space-x-2">
                                    <input type="range" id="min-volume" min="0" max="10000" value="0" class="flex-1">
                                    <span class="text-xs text-gray-600 whitespace-nowrap">Vol: <span id="volume-display">0</span>+</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="range" id="max-difficulty" min="0" max="100" value="100" class="flex-1">
                                    <span class="text-xs text-gray-600 whitespace-nowrap">Diff: <span id="difficulty-display">100</span></span>
                                </div>
                            </div>
                            
                            <!-- Quick Keyword Search -->
                            <div class="mt-4">
                                <input type="text" id="keyword-filter" placeholder="Filter keywords..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            </div>
                        </div>

                        <!-- Keywords Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-4 py-3">
                                            <input type="checkbox" id="select-all" class="text-blue-600">
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" data-sort="keyword">
                                            Keyword & Related
                                            <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" data-sort="search_volume">
                                            Volume
                                            <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" data-sort="difficulty">
                                            Difficulty
                                            <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        </th>
                                        <th class="px-6 py-3 cursor-pointer hover:bg-gray-100" data-sort="cpc">
                                            CPC
                                            <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecape="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                        </th>
                                        <th class="px-6 py-3">Competition</th>
                                        <th class="px-6 py-3">Intent</th>
                                        <th class="px-6 py-3">Trend</th>
                                        <th class="px-6 py-3">Source</th>
                                        <th class="px-6 py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="keywords-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Dynamic content will be inserted here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div id="pagination" class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Showing <span id="showing-from">1</span> to <span id="showing-to">50</span> of <span id="total-results">0</span> results
                            </div>
                            <div class="flex space-x-2">
                                <button id="prev-page" class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50" disabled>Previous</button>
                                <span id="page-numbers" class="flex space-x-1"></span>
                                <button id="next-page" class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50" disabled>Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trending Keywords Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Trending Keywords</h3>
                        <button id="refresh-trending" class="text-blue-600 hover:text-blue-800 text-sm">
                            🔄 Refresh
                        </button>
                    </div>
                    <div id="trending-keywords" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        <!-- Trending keywords will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Keyword Ideas Section -->
            <div id="keyword-ideas-section" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Keyword Ideas & Variations</h3>
                    <div id="keyword-ideas" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Keyword ideas will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulk-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Bulk Actions</h3>
            <div class="space-y-3">
                <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded" onclick="bulkCopyKeywords()">📋 Copy Selected Keywords</button>
                <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded" onclick="bulkExportSelected()">📊 Export Selected</button>
                <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded" onclick="bulkDeleteSelected()">🗑️ Remove Selected</button>
                <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded" onclick="bulkAnalyzeSelected()">📈 Analyze Selected</button>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeBulkModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let currentKeywords = [];
        let filteredKeywords = [];
        let currentSortColumn = 'search_volume';
        let currentSortDirection = 'desc';
        let currentPage = 1;
        let itemsPerPage = 50;
        let selectedKeywords = new Set();

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadTrendingKeywords();
            setupEventListeners();
            initializeTooltips();
        });

        function setupEventListeners() {
            // Search form submission
            document.getElementById('keyword-search-form').addEventListener('submit', handleSearch);
            
            // Category buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const category = this.dataset.category;
                    searchByCategory(category);
                });
            });

            // Quick search buttons
            document.querySelectorAll('.quick-search').forEach(btn => {
                btn.addEventListener('click', function() {
                    const query = this.dataset.query;
                    document.getElementById('search-query').value = query;
                    document.getElementById('keyword-search-form').dispatchEvent(new Event('submit'));
                });
            });

            // Search input for autocomplete
            const searchInput = document.getElementById('search-query');
            searchInput.addEventListener('input', debounce(handleAutocomplete, 300));
            searchInput.addEventListener('focus', function() {
                const dropdown = document.getElementById('suggestions-dropdown');
                if (dropdown.children.length > 0) {
                    dropdown.classList.remove('hidden');
                }
            });

            // Keyword filter
            document.getElementById('keyword-filter').addEventListener('input', debounce(filterKeywords, 300));

            // Export buttons
            document.getElementById('export-csv').addEventListener('click', () => exportKeywords('csv'));
            document.getElementById('export-json').addEventListener('click', () => exportKeywords('json'));
            document.getElementById('bulk-actions').addEventListener('click', openBulkModal);
            
            // Refresh trending
            document.getElementById('refresh-trending').addEventListener('click', loadTrendingKeywords);

            // Sort and filter controls
            document.getElementById('sort-by').addEventListener('change', handleSort);
            document.getElementById('filter-intent').addEventListener('change', filterResults);
            document.getElementById('filter-competition').addEventListener('change', filterResults);
            document.getElementById('filter-source').addEventListener('change', filterResults);
            
            // Volume and difficulty sliders
            document.getElementById('min-volume').addEventListener('input', function() {
                document.getElementById('volume-display').textContent = this.value;
                filterResults();
            });
            
            document.getElementById('max-difficulty').addEventListener('input', function() {
                document.getElementById('difficulty-display').textContent = this.value;
                filterResults();
            });

            // Table header sorting
            document.querySelectorAll('th[data-sort]').forEach(header => {
                header.addEventListener('click', function() {
                    const column = this.dataset.sort;
                    sortTable(column);
                });
            });

            // Select all checkbox
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="keyword-select"]');
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                    if (this.checked) {
                        selectedKeywords.add(cb.value);
                    } else {
                        selectedKeywords.delete(cb.value);
                    }
                });
            });

            // Country and language selectors
            document.getElementById('country-selector').addEventListener('change', updateSearchContext);
            document.getElementById('language-selector').addEventListener('change', updateSearchContext);

            // Pagination
            document.getElementById('prev-page').addEventListener('click', () => changePage(currentPage - 1));
            document.getElementById('next-page').addEventListener('click', () => changePage(currentPage + 1));

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#search-query') && !e.target.closest('#suggestions-dropdown')) {
                    document.getElementById('suggestions-dropdown').classList.add('hidden');
                }
            });
        }

        // Handle search form submission
        async function handleSearch(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const query = formData.get('query').trim();
            
            if (!query) {
                showNotification('Please enter a keyword to search', 'warning');
                return;
            }

            const options = {
                query: query,
                limit: parseInt(formData.get('limit')) || 50,
                include_related: formData.get('include_related') === 'on',
                include_long_tail: formData.get('include_long_tail') === 'on',
                include_questions: formData.get('include_questions') === 'on',
                include_local: formData.get('include_local') === 'on',
                search_type: formData.get('search_type') || 'all',
                difficulty_filter: formData.get('difficulty_filter') || '',
                country: document.getElementById('country-selector').value,
                language: document.getElementById('language-selector').value
            };

            await performSearch(options);
        }

        // Perform the actual search
        async function performSearch(options) {
            showLoading(true);
            hideResults();
            
            try {
                const response = await fetch('/api/keywords/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(options)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    currentKeywords = data.keywords || [];
                    filteredKeywords = [...currentKeywords];
                    displayResults();
                    updateSearchStats();
                    showKeywordIdeas(options.query);
                    showNotification(`Found ${currentKeywords.length} keywords`, 'success');
                } else {
                    throw new Error(data.message || 'Search failed');
                }

            } catch (error) {
                console.error('Search error:', error);
                showNotification('Search failed: ' + error.message, 'error');
            } finally {
                showLoading(false);
            }
        }

        // Handle autocomplete suggestions
        async function handleAutocomplete(event) {
            const query = event.target.value.trim();
            if (query.length < 2) {
                document.getElementById('suggestions-dropdown').classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(`/api/keywords/suggestions?q=${encodeURIComponent(query)}&limit=8`);
                const data = await response.json();
                
                if (data.success && data.suggestions.length > 0) {
                    displaySuggestions(data.suggestions);
                }
            } catch (error) {
                console.error('Autocomplete error:', error);
            }
        }

        // Display autocomplete suggestions
        function displaySuggestions(suggestions) {
            const dropdown = document.getElementById('suggestions-dropdown');
            dropdown.innerHTML = '';
            
            suggestions.forEach(suggestion => {
                const div = document.createElement('div');
                div.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm';
                div.textContent = suggestion;
                div.addEventListener('click', function() {
                    document.getElementById('search-query').value = suggestion;
                    dropdown.classList.add('hidden');
                });
                dropdown.appendChild(div);
            });
            
            dropdown.classList.remove('hidden');
        }

        // Search by category
        async function searchByCategory(category) {
            showLoading(true);
            
            try {
                const response = await fetch(`/api/keywords/trending/${category}?limit=30`);
                const data = await response.json();
                
                if (data.success) {
                    currentKeywords = data.keywords || [];
                    filteredKeywords = [...currentKeywords];
                    displayResults();
                    updateSearchStats();
                    showNotification(`Loaded ${currentKeywords.length} trending ${category} keywords`, 'success');
                }
            } catch (error) {
                console.error('Category search error:', error);
                showNotification('Failed to load category keywords', 'error');
            } finally {
                showLoading(false);
            }
        }

        // Display search results
        function displayResults() {
            document.getElementById('results-section').classList.remove('hidden');
            
            // Update results summary
            const total = filteredKeywords.length;
            document.getElementById('results-summary').textContent = 
                `Found ${total} keywords for your search`;
            
            // Calculate source breakdown
            const sourceBreakdown = {};
            currentKeywords.forEach(kw => {
                const source = kw.source || 'unknown';
                sourceBreakdown[source] = (sourceBreakdown[source] || 0) + 1;
            });
            
            const breakdownText = Object.entries(sourceBreakdown)
                .map(([source, count]) => `${source}: ${count}`)
                .join(' • ');
            document.getElementById('source-breakdown').textContent = breakdownText;
            
            // Render table
            renderKeywordsTable();
            updatePagination();
        }

        // Render keywords table
        function renderKeywordsTable() {
            const tbody = document.getElementById('keywords-table-body');
            tbody.innerHTML = '';
            
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredKeywords.length);
            const pageKeywords = filteredKeywords.slice(startIndex, endIndex);
            
            pageKeywords.forEach((keyword, index) => {
                const row = createKeywordRow(keyword, startIndex + index);
                tbody.appendChild(row);
            });
        }

        // Create individual keyword row
        function createKeywordRow(keyword, index) {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors';
            
            const difficultyColor = getDifficultyColor(keyword.difficulty);
            const volumeFormatted = formatNumber(keyword.search_volume);
            const cpcFormatted = keyword.cpc ? `$${keyword.cpc.toFixed(2)}` : 'N/A';
            
            row.innerHTML = `
                <td class="px-4 py-3">
                    <input type="checkbox" name="keyword-select" value="${keyword.keyword}" 
                           class="text-blue-600" onchange="toggleKeywordSelection('${keyword.keyword}', this.checked)">
                </td>
                <td class="px-6 py-4">
                    <div>
                        <div class="font-medium text-gray-900">${escapeHtml(keyword.keyword)}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            ${keyword.category || 'General'} • ${keyword.keyword.length} chars
                        </div>
                        ${keyword.related_keywords && keyword.related_keywords.length > 0 ? 
                            `<div class="text-xs text-blue-600 mt-1">
                                Related: ${keyword.related_keywords.slice(0, 3).join(', ')}
                             </div>` : ''}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${volumeFormatted}</div>
                    <div class="text-xs text-gray-500">${getVolumeCategory(keyword.search_volume)}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-12 h-2 bg-gray-200 rounded-full mr-2">
                            <div class="h-2 rounded-full ${difficultyColor}" 
                                 style="width: ${keyword.difficulty}%"></div>
                        </div>
                        <span class="text-sm font-medium">${keyword.difficulty}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">${cpcFormatted}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full ${getCompetitionBadgeClass(keyword.competition)}">
                        ${keyword.competition}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full ${getIntentBadgeClass(keyword.intent)}">
                        ${keyword.intent}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm ${getTrendClass(keyword.trend)}">${getTrendIcon(keyword.trend)} ${keyword.trend}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs text-gray-500">${keyword.source || 'Multiple'}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex space-x-2">
                        <button onclick="copyKeyword('${keyword.keyword}')" 
                                class="text-blue-600 hover:text-blue-800 text-xs" title="Copy keyword">
                            📋
                        </button>
                        <button onclick="analyzeKeyword('${keyword.keyword}')" 
                                class="text-green-600 hover:text-green-800 text-xs" title="Analyze">
                            📊
                        </button>
                        <button onclick="searchRelated('${keyword.keyword}')" 
                                class="text-purple-600 hover:text-purple-800 text-xs" title="Find related">
                            🔍
                        </button>
                    </div>
                </td>
            `;
            
            return row;
        }

        // Utility functions for styling
        function getDifficultyColor(difficulty) {
            if (difficulty <= 30) return 'bg-green-500';
            if (difficulty <= 60) return 'bg-yellow-500';
            return 'bg-red-500';
        }

        function getVolumeCategory(volume) {
            if (volume >= 10000) return 'High Volume';
            if (volume >= 1000) return 'Medium Volume';
            if (volume >= 100) return 'Low Volume';
            return 'Very Low';
        }

        function getCompetitionBadgeClass(competition) {
            const classes = {
                'Low': 'bg-green-100 text-green-800',
                'Medium': 'bg-yellow-100 text-yellow-800',
                'High': 'bg-red-100 text-red-800'
            };
            return classes[competition] || 'bg-gray-100 text-gray-800';
        }

        function getIntentBadgeClass(intent) {
            const classes = {
                'Informational': 'bg-blue-100 text-blue-800',
                'Commercial': 'bg-green-100 text-green-800',
                'Navigational': 'bg-purple-100 text-purple-800',
                'Transactional': 'bg-orange-100 text-orange-800'
            };
            return classes[intent] || 'bg-gray-100 text-gray-800';
        }

        function getTrendClass(trend) {
            const classes = {
                'Rising': 'text-green-600',
                'Stable': 'text-blue-600',
                'Declining': 'text-red-600',
                'Seasonal': 'text-purple-600'
            };
            return classes[trend] || 'text-gray-600';
        }

        function getTrendIcon(trend) {
            const icons = {
                'Rising': '📈',
                'Stable': '➡️',
                'Declining': '📉',
                'Seasonal': '🔄'
            };
            return icons[trend] || '📊';
        }

        // Filter and sort functions
        function filterResults() {
            const intentFilter = document.getElementById('filter-intent').value;
            const competitionFilter = document.getElementById('filter-competition').value;
            const sourceFilter = document.getElementById('filter-source').value;
            const minVolume = parseInt(document.getElementById('min-volume').value);
            const maxDifficulty = parseInt(document.getElementById('max-difficulty').value);
            
            filteredKeywords = currentKeywords.filter(keyword => {
                if (intentFilter && keyword.intent !== intentFilter) return false;
                if (competitionFilter && keyword.competition !== competitionFilter) return false;
                if (sourceFilter && keyword.source !== sourceFilter) return false;
                if (keyword.search_volume < minVolume) return false;
                if (keyword.difficulty > maxDifficulty) return false;
                return true;
            });
            
            currentPage = 1;
            renderKeywordsTable();
            updatePagination();
        }

        function filterKeywords() {
            const filterText = document.getElementById('keyword-filter').value.toLowerCase();
            
            if (!filterText) {
                filteredKeywords = [...currentKeywords];
            } else {
                filteredKeywords = currentKeywords.filter(keyword => 
                    keyword.keyword.toLowerCase().includes(filterText)
                );
            }
            
            // Apply other filters
            filterResults();
        }

        function sortTable(column) {
            if (currentSortColumn === column) {
                currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortColumn = column;
                currentSortDirection = 'desc';
            }
            
            filteredKeywords.sort((a, b) => {
                let valueA = a[column];
                let valueB = b[column];
                
                // Handle different data types
                if (typeof valueA === 'string') {
                    valueA = valueA.toLowerCase();
                    valueB = valueB.toLowerCase();
                }
                
                if (currentSortDirection === 'asc') {
                    return valueA > valueB ? 1 : -1;
                } else {
                    return valueA < valueB ? 1 : -1;
                }
            });
            
            renderKeywordsTable();
        }

        function handleSort() {
            const sortBy = document.getElementById('sort-by').value;
            sortTable(sortBy);
        }

        // Pagination functions
        function updatePagination() {
            const totalResults = filteredKeywords.length;
            const totalPages = Math.ceil(totalResults / itemsPerPage);
            
            document.getElementById('total-results').textContent = totalResults;
            document.getElementById('showing-from').textContent = (currentPage - 1) * itemsPerPage + 1;
            document.getElementById('showing-to').textContent = Math.min(currentPage * itemsPerPage, totalResults);
            
            // Update pagination buttons
            document.getElementById('prev-page').disabled = currentPage <= 1;
            document.getElementById('next-page').disabled = currentPage >= totalPages;
            
            // Generate page numbers
            generatePageNumbers(totalPages);
        }

        function generatePageNumbers(totalPages) {
            const pageNumbers = document.getElementById('page-numbers');
            pageNumbers.innerHTML = '';
            
            const maxVisible = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
            let endPage = Math.min(totalPages, startPage + maxVisible - 1);
            
            if (endPage - startPage + 1 < maxVisible) {
                startPage = Math.max(1, endPage - maxVisible + 1);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement('button');
                button.textContent = i;
                button.className = `px-3 py-2 border border-gray-300 rounded text-sm ${
                    i === currentPage ? 'bg-blue-600 text-white' : 'hover:bg-gray-50'
                }`;
                button.addEventListener('click', () => changePage(i));
                pageNumbers.appendChild(button);
            }
        }

        function changePage(page) {
            if (page < 1 || page > Math.ceil(filteredKeywords.length / itemsPerPage)) return;
            
            currentPage = page;
            renderKeywordsTable();
            updatePagination();
            
            // Scroll to top of results
            document.getElementById('results-section').scrollIntoView({ behavior: 'smooth' });
        }

        // Load trending keywords
        async function loadTrendingKeywords() {
            try {
                const response = await fetch('/api/keywords/trending?limit=12');
                const data = await response.json();
                
                if (data.success) {
                    displayTrendingKeywords(data.keywords);
                }
            } catch (error) {
                console.error('Failed to load trending keywords:', error);
            }
        }

        function displayTrendingKeywords(keywords) {
            const container = document.getElementById('trending-keywords');
            container.innerHTML = '';
            
            keywords.forEach(keyword => {
                const div = document.createElement('div');
                div.className = 'bg-gradient-to-r from-blue-50 to-indigo-50 p-3 rounded-lg border border-blue-100 hover:shadow-md transition-shadow cursor-pointer';
                div.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <span class="font-medium text-gray-900 text-sm">${escapeHtml(keyword.keyword)}</span>
                        <span class="text-xs text-blue-600">${getTrendIcon(keyword.trend)}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600">
                        <span>Vol: ${formatNumber(keyword.search_volume)}</span>
                        <span>Diff: ${keyword.difficulty}</span>
                    </div>
                `;
                
                div.addEventListener('click', function() {
                    document.getElementById('search-query').value = keyword.keyword;
                    document.getElementById('keyword-search-form').dispatchEvent(new Event('submit'));
                });
                
                container.appendChild(div);
            });
        }

        // Show keyword ideas and variations
        function showKeywordIdeas(originalQuery) {
            const ideasSection = document.getElementById('keyword-ideas-section');
            const ideasContainer = document.getElementById('keyword-ideas');
            
            // Generate keyword variations
            const variations = generateKeywordVariations(originalQuery);
            
            ideasContainer.innerHTML = '';
            variations.forEach(variation => {
                const div = document.createElement('div');
                div.className = 'bg-gray-50 p-4 rounded-lg border hover:bg-gray-100 cursor-pointer transition-colors';
                div.innerHTML = `
                    <h4 class="font-medium text-gray-900 mb-2">${variation.type}</h4>
                    <div class="space-y-1">
                        ${variation.keywords.map(kw => 
                            `<div class="text-sm text-gray-700 hover:text-blue-600" onclick="searchKeyword('${kw}')">${kw}</div>`
                        ).join('')}
                    </div>
                `;
                ideasContainer.appendChild(div);
            });
            
            ideasSection.classList.remove('hidden');
        }

        function generateKeywordVariations(query) {
            const variations = [
                {
                    type: 'Question Keywords',
                    keywords: [
                        `apa itu ${query}`,
                        `bagaimana cara ${query}`,
                        `mengapa ${query}`,
                        `kapan ${query}`,
                        `dimana ${query}`
                    ]
                },
                {
                    type: 'Commercial Keywords',
                    keywords: [
                        `${query} terbaik`,
                        `${query} murah`,
                        `beli ${query}`,
                        `harga ${query}`,
                        `review ${query}`
                    ]
                },
                {
                    type: 'Long-tail Variations',
                    keywords: [
                        `${query} untuk pemula`,
                        `${query} gratis`,
                        `${query} online`,
                        `${query} indonesia`,
                        `${query} 2024`
                    ]
                }
            ];
            
            return variations;
        }

        // Utility functions
        function formatNumber(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            }
            if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Action functions
        function copyKeyword(keyword) {
            navigator.clipboard.writeText(keyword).then(() => {
                showNotification(`Copied "${keyword}" to clipboard`, 'success');
            });
        }

        function analyzeKeyword(keyword) {
            // Open keyword analysis modal or redirect
            window.open(`/keywords/analyze?q=${encodeURIComponent(keyword)}`, '_blank');
        }

        function searchRelated(keyword) {
            document.getElementById('search-query').value = keyword;
            document.getElementById('keyword-search-form').dispatchEvent(new Event('submit'));
        }

        function searchKeyword(keyword) {
            document.getElementById('search-query').value = keyword;
            document.getElementById('keyword-search-form').dispatchEvent(new Event('submit'));
        }

        // Selection and bulk actions
        function toggleKeywordSelection(keyword, checked) {
            if (checked) {
                selectedKeywords.add(keyword);
            } else {
                selectedKeywords.delete(keyword);
            }
        }

        function openBulkModal() {
            if (selectedKeywords.size === 0) {
                showNotification('Please select keywords first', 'warning');
                return;
            }
            document.getElementById('bulk-modal').classList.remove('hidden');
            document.getElementById('bulk-modal').classList.add('flex');
        }

        function closeBulkModal() {
            document.getElementById('bulk-modal').classList.add('hidden');
            document.getElementById('bulk-modal').classList.remove('flex');
        }

        function bulkCopyKeywords() {
            const keywords = Array.from(selectedKeywords).join('\n');
            navigator.clipboard.writeText(keywords).then(() => {
                showNotification(`Copied ${selectedKeywords.size} keywords to clipboard`, 'success');
                closeBulkModal();
            });
        }

        function bulkExportSelected() {
            const selectedData = currentKeywords.filter(kw => selectedKeywords.has(kw.keyword));
            exportData(selectedData, 'csv', 'selected_keywords');
            closeBulkModal();
        }

        function bulkDeleteSelected() {
            currentKeywords = currentKeywords.filter(kw => !selectedKeywords.has(kw.keyword));
            filteredKeywords = filteredKeywords.filter(kw => !selectedKeywords.has(kw.keyword));
            selectedKeywords.clear();
            renderKeywordsTable();
            updatePagination();
            showNotification('Selected keywords removed', 'success');
            closeBulkModal();
        }

        // Export functions
        function exportKeywords(format) {
            exportData(filteredKeywords, format, 'keywords');
        }

        function exportData(data, format, filename) {
            if (format === 'csv') {
                exportCSV(data, filename);
            } else if (format === 'json') {
                exportJSON(data, filename);
            }
        }

        function exportCSV(data, filename) {
            const headers = ['Keyword', 'Search Volume', 'Difficulty', 'CPC', 'Competition', 'Intent', 'Trend', 'Category'];
            const csvContent = [
                headers.join(','),
                ...data.map(kw => [
                    `"${kw.keyword}"`,
                    kw.search_volume,
                    kw.difficulty,
                    kw.cpc || 0,
                    `"${kw.competition}"`,
                    `"${kw.intent}"`,
                    `"${kw.trend}"`,
                    `"${kw.category}"`
                ].join(','))
            ].join('\n');
            
            downloadFile(csvContent, `${filename}.csv`, 'text/csv');
        }

        function exportJSON(data, filename) {
            const jsonContent = JSON.stringify(data, null, 2);
            downloadFile(jsonContent, `${filename}.json`, 'application/json');
        }

        function downloadFile(content, filename, mimeType) {
            const blob = new Blob([content], { type: mimeType });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // UI helper functions
        function showLoading(show) {
            const loading = document.getElementById('loading');
            if (show) {
                loading.classList.remove('hidden');
                // Simulate progress updates
                simulateLoadingProgress();
            } else {
                loading.classList.add('hidden');
            }
        }

        function simulateLoadingProgress() {
            const sources = ['google', 'youtube', 'bing', 'amazon'];
            sources.forEach((source, index) => {
                setTimeout(() => {
                    document.getElementById(`progress-${source}`).classList.remove('opacity-50');
                    document.getElementById(`progress-${source}`).classList.add('text-green-600');
                }, (index + 1) * 1000);
            });
        }

        function hideResults() {
            document.getElementById('results-section').classList.add('hidden');
            document.getElementById('keyword-ideas-section').classList.add('hidden');
        }

        function updateSearchStats() {
            if (currentKeywords.length === 0) return;
            
            const avgVolume = Math.round(
                currentKeywords.reduce((sum, kw) => sum + kw.search_volume, 0) / currentKeywords.length
            );
            const avgDifficulty = Math.round(
                currentKeywords.reduce((sum, kw) => sum + kw.difficulty, 0) / currentKeywords.length
            );
            const highVolumeCount = currentKeywords.filter(kw => kw.search_volume > 1000).length;
            
            document.getElementById('total-keywords').textContent = currentKeywords.length;
            document.getElementById('avg-volume').textContent = formatNumber(avgVolume);
            document.getElementById('avg-difficulty').textContent = avgDifficulty;
            document.getElementById('high-volume-count').textContent = highVolumeCount;
            
            document.getElementById('search-stats').classList.remove('hidden');
        }

        function updateSearchContext() {
            // This could trigger a new search with updated country/language
            const currentQuery = document.getElementById('search-query').value;
            if (currentQuery.trim()) {
                showNotification('Search context updated. Click search to refresh results.', 'info');
            }
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-500 transform translate-x-full`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-black',
                info: 'bg-blue-500 text-white'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 500);
            }, 3000);
        }

        function initializeTooltips() {
            // Add tooltips to various elements if needed
            // This can be expanded based on UI requirements
        }

        // Initialize everything when DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                loadTrendingKeywords();
                setupEventListeners();
                initializeTooltips();
            });
        }
</script>
    </x-app-layout>
