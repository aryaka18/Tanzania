{{-- resources/views/keywords/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Keywords') }} - {{ $project->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Track and manage keywords for your project
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('keywords.research', $project) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Research Keywords
                </a>
                <button onclick="trackPositions()" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Track Positions
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Position Trends Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Improved</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $positionData['improved'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Declined</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $positionData['declined'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Stable</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $positionData['stable'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">New</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $positionData['new'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keywords Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Keywords Overview</h3>
                        <button onclick="getOpportunities()" 
                                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm">
                            View Opportunities
                        </button>
                    </div>

                    @if($keywords->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Keyword
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Position
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Search Volume
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Difficulty
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Intent
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($keywords as $keyword)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $keyword->keyword }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($keyword->position)
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">
                                                            #{{ $keyword->position }}
                                                        </span>
                                                        @php
                                                            // Instead of calling $this->getPositionChange($keyword)
                                                            // Create a helper method or compute this in the controller
                                                            $trackingData = $keyword->tracking_data ?? [];
                                                            $history = $trackingData['position_history'] ?? [];
                                                            
                                                            $change = ['change' => 0, 'direction' => 'new'];
                                                            if (count($history) >= 2) {
                                                                $current = end($history)['position'] ?? null;
                                                                $previous = prev($history)['position'] ?? null;
                                                                
                                                                if ($current && $previous) {
                                                                    $changeValue = $previous - $current;
                                                                    $change = [
                                                                        'change' => abs($changeValue),
                                                                        'direction' => $changeValue > 0 ? 'up' : ($changeValue < 0 ? 'down' : 'neutral')
                                                                    ];
                                                                }
                                                            }
                                                        @endphp
                                                        @if($change['direction'] === 'up')
                                                            <svg class="w-4 h-4 text-green-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                            </svg>
                                                        @elseif($change['direction'] === 'down')
                                                            <svg class="w-4 h-4 text-red-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500">Not ranking</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $keyword->search_volume ? number_format($keyword->search_volume) : 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($keyword->difficulty)
                                                    <div class="flex items-center">
                                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-{{ $keyword->difficulty > 70 ? 'red' : ($keyword->difficulty > 40 ? 'yellow' : 'green') }}-500 
                                                                        h-2 rounded-full" 
                                                                 style="width: {{ $keyword->difficulty }}%"></div>
                                                        </div>
                                                        <span class="text-sm text-gray-900">{{ $keyword->difficulty }}%</span>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500">N/A</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $intent = $keyword->tracking_data['intent'] ?? 'Unknown';
                                                    $intentColor = match($intent) {
                                                        'Commercial' => 'bg-green-100 text-green-800',
                                                        'Informational' => 'bg-blue-100 text-blue-800',
                                                        'Navigational' => 'bg-purple-100 text-purple-800',
                                                        default => 'bg-gray-100 text-gray-800'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $intentColor }}">
                                                    {{ $intent }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="deleteKeyword({{ $keyword->id }})" 
                                                        class="text-red-600 hover:text-red-900">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $keywords->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No keywords</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by researching keywords for your project.</p>
                            <div class="mt-6">
                                <a href="{{ route('keywords.research', $project) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Research Keywords
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Opportunities Modal -->
    <div id="opportunitiesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Keyword Opportunities</h3>
                    <button onclick="closeOpportunities()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="opportunitiesContent">
                    <!-- Opportunities will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function trackPositions() {
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Tracking...';
            button.disabled = true;

            fetch(`/projects/{{ $project->id }}/keywords/track-positions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Position tracking completed successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while tracking positions.');
            })
            .finally(() => {
                button.textContent = originalText;
                button.disabled = false;
            });
        }

        function deleteKeyword(keywordId) {
            if (confirm('Are you sure you want to delete this keyword?')) {
                fetch(`/projects/{{ $project->id }}/keywords/${keywordId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the keyword.');
                });
            }
        }

        function getOpportunities() {
            document.getElementById('opportunitiesModal').classList.remove('hidden');
            document.getElementById('opportunitiesContent').innerHTML = '<div class="text-center py-4">Loading opportunities...</div>';

            fetch(`/projects/{{ $project->id }}/keywords/opportunities`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayOpportunities(data.opportunities);
                    } else {
                        document.getElementById('opportunitiesContent').innerHTML = '<div class="text-center py-4 text-red-600">Error loading opportunities</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('opportunitiesContent').innerHTML = '<div class="text-center py-4 text-red-600">Error loading opportunities</div>';
                });
        }

        function displayOpportunities(opportunities) {
            if (opportunities.length === 0) {
                document.getElementById('opportunitiesContent').innerHTML = '<div class="text-center py-4">No opportunities found</div>';
                return;
            }

            let html = '<div class="space-y-4">';
            opportunities.forEach(opportunity => {
                html += `
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-medium text-gray-900">${opportunity.keyword}</h4>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                Score: ${opportunity.score}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-2">
                            <div>Volume: ${opportunity.search_volume ? opportunity.search_volume.toLocaleString() : 'N/A'}</div>
                            <div>Difficulty: ${opportunity.difficulty}%</div>
                            <div>Position: ${opportunity.current_position ? '#' + opportunity.current_position : 'Not ranking'}</div>
                            <div>Intent: ${opportunity.intent}</div>
                        </div>
                        <div class="text-sm">
                            <strong>Reasons:</strong>
                            <ul class="list-disc list-inside mt-1">
                                ${opportunity.reasons.map(reason => `<li>${reason}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                `;
            });
            html += '</div>';

            document.getElementById('opportunitiesContent').innerHTML = html;
        }

        function closeOpportunities() {
            document.getElementById('opportunitiesModal').classList.add('hidden');
        }
    </script>
</x-app-layout>