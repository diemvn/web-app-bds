@extends('layouts.tenant')

@section('content')
<p class="text-gray-500 text-sm">Xin chào,</p>
<h1 class="text-2xl font-bold text-gray-900">{{ $tenant?->full_name }}</h1>

@if($contract)
<div class="mt-5 hosty-card p-4" style="background: var(--color-primary-soft)">
    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Phòng hiện tại</p>
    <p class="text-lg font-bold mt-1">Phòng {{ $contract->room?->room_number }}</p>
    <p class="text-sm text-gray-600">{{ $contract->room?->building?->name }}</p>
</div>
@endif

@if($currentInvoice)
<div class="mt-4 hosty-card p-5">
    <p class="text-sm text-gray-500">Tiền phòng tháng {{ str_replace('-', '/', $currentInvoice->billing_month) }}</p>
    <p class="text-4xl font-bold mt-2 tracking-tight" style="color: var(--color-primary)">{{ number_format($currentInvoice->total_amount) }}<span class="text-2xl">đ</span></p>
    <p class="text-xs text-gray-400 mt-2">Hạn thanh toán: {{ $currentInvoice->due_date->format('d/m/Y') }}</p>
    <a href="{{ route('tenant.invoices.show', $currentInvoice) }}" class="hosty-btn w-full mt-5">Thanh toán ngay</a>
</div>
@else
<div class="mt-6 hosty-card p-6 text-center text-gray-500 text-sm">Bạn không có hóa đơn chưa thanh toán.</div>
@endif
@endsection
