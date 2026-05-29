@extends('layouts.app')

@section('body')
<div class="max-w-md mx-auto min-h-screen bg-[#f7f7f7] shadow-2xl flex flex-col">
    <header class="bg-white px-4 py-3 flex items-center justify-between border-b border-gray-100 sticky top-0 z-40">
        <x-hosty-logo href="{{ route('tenant.home') }}" class="!text-lg" />
        <a href="{{ route('tenant.profile') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </a>
    </header>
    <main class="flex-1 pb-24 p-4">@yield('content')</main>
    <nav class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white border-t border-gray-100 flex justify-around py-2 px-1 text-[10px] z-50 safe-area-pb">
        @foreach([
            ['route' => 'tenant.home', 'icon' => '🏠', 'label' => 'Trang chủ', 'match' => 'tenant.home'],
            ['route' => 'tenant.invoices', 'icon' => '🧾', 'label' => 'Hóa đơn', 'match' => 'tenant.invoices*'],
            ['route' => 'tenant.contract', 'icon' => '📄', 'label' => 'Hợp đồng', 'match' => 'tenant.contract'],
            ['route' => 'tenant.notifications', 'icon' => '🔔', 'label' => 'Thông báo', 'match' => 'tenant.notifications*'],
        ] as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-0.5 py-1 px-2 rounded-xl {{ $active ? 'font-semibold' : 'text-gray-400' }}" @if($active) style="color: var(--color-primary)" @endif>
                <span class="text-lg">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</div>
@endsection
