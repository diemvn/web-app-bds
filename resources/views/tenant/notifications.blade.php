@extends('layouts.tenant')

@section('content')
<h1 class="text-xl font-bold mb-4">Thông báo</h1>
<div class="space-y-3">
@forelse($notifications as $notification)
    <div class="hosty-card p-4 {{ $notification->read_at ? 'opacity-70' : 'ring-2 ring-[var(--color-primary)]/20' }}">
        <div class="flex gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-lg" style="background: var(--color-primary-soft)">🔔</span>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm">{{ $notification->title }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ $notification->body }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                @if(!$notification->read_at)
                <form method="POST" action="{{ route('tenant.notifications.read', $notification) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="text-xs font-medium" style="color: var(--color-primary)">Đánh dấu đã đọc</button>
                </form>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="hosty-card p-8 text-center text-gray-500 text-sm">Không có thông báo mới.</div>
@endforelse
</div>
<div class="mt-4">{{ $notifications->links() }}</div>
@endsection
