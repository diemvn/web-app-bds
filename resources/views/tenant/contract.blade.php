@extends('layouts.tenant')

@section('content')
<h1 class="text-xl font-bold">Hợp đồng thuê</h1>
@if($contract)
<div class="mt-4 hosty-card p-5 space-y-4">
    <div>
        <p class="text-xs text-gray-400 uppercase">Phòng</p>
        <p class="text-lg font-bold">Phòng {{ $contract->room?->room_number }} — {{ $contract->room?->building?->name }}</p>
    </div>
    <div>
        <p class="text-xs text-gray-400 uppercase">Thời hạn</p>
        <p class="font-medium">{{ $contract->start_date->format('d/m/Y') }} – {{ $contract->end_date->format('d/m/Y') }}</p>
    </div>
    <div>
        <p class="text-xs text-gray-400 uppercase">Giá thuê</p>
        <p class="font-medium" style="color: var(--color-primary)">{{ number_format($contract->monthly_rent) }}đ/tháng</p>
    </div>
    <div class="grid grid-cols-2 gap-2 pt-2">
        <button type="button" class="rounded-xl border border-gray-200 py-3 text-sm font-medium text-gray-700">Xem hợp đồng</button>
        <button type="button" class="hosty-btn !py-3 text-sm">Tải PDF</button>
    </div>
</div>
@else
<p class="text-gray-500 mt-6">Chưa có hợp đồng đang hiệu lực.</p>
@endif
@endsection
