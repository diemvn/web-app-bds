@props(['items' => []])

<nav aria-label="Breadcrumb" class="py-4 overflow-x-auto hide-scrollbar">
    <ol class="flex items-center gap-2 text-sm text-on-surface-variant min-w-max">
        <li>
            <a href="{{ route('home') }}" class="flex items-center hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">home</span>
            </a>
        </li>
        @foreach($items as $item)
            <li>
                <span class="material-symbols-outlined text-[16px] text-outline-variant">chevron_right</span>
            </li>
            <li>
                @if($loop->last || empty($item['url']))
                    <span class="text-on-surface font-medium" aria-current="page">{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="hover:text-primary transition-colors">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

@if(!empty($items))
@section('structured_data')
<script type="application/ld+json">
@php
    $itemListElement = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Trang chủ',
            'item' => route('home'),
        ]
    ];
    $count = count($items);
    foreach($items as $index => $item) {
        $element = [
            '@type' => 'ListItem',
            'position' => $index + 2,
            'name' => $item['label'],
        ];
        if ($index < $count - 1 && !empty($item['url'])) {
            $element['item'] = $item['url'];
        }
        $itemListElement[] = $element;
    }
    
    echo json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemListElement,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp
</script>
@endsection
@endif
