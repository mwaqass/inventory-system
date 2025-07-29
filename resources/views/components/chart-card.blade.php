<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        @if(isset($action))
        <a href="{{ $action['url'] }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            {{ $action['text'] }}
        </a>
        @endif
    </div>

    <div class="space-y-3">
        @foreach($data as $item)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="h-8 w-8 rounded-full {{ $item['color'] ?? 'bg-blue-100' }} flex items-center justify-center">
                    @if(isset($item['icon']))
                    {!! $item['icon'] !!}
                    @else
                    <span class="text-sm font-medium {{ $item['textColor'] ?? 'text-blue-600' }}">{{ substr($item['label'], 0, 1) }}</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $item['label'] }}</p>
                    @if(isset($item['subtitle']))
                    <p class="text-xs text-gray-500">{{ $item['subtitle'] }}</p>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium {{ $item['valueColor'] ?? 'text-gray-900' }}">{{ $item['value'] }}</p>
                @if(isset($item['change']))
                <p class="text-xs {{ $item['change'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $item['change'] >= 0 ? '+' : '' }}{{ $item['change'] }}%
                </p>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if(empty($data))
    <div class="text-center py-8">
        <div class="text-gray-400 mb-2">
            <svg class="mx-auto h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </div>
        <p class="text-sm text-gray-500">No data available</p>
    </div>
    @endif
</div>
