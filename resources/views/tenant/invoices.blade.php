@extends('layouts.tenant')

@section('content')
<h1 class="text-xl font-bold mb-4">Hóa đơn</h1>
<div class="space-y-3">
@forelse($invoices as $invoice)
    <a href="{{ route('tenant.invoices.show', $invoice) }}" class="hosty-card flex items-center justify-between p-4">
        <div>
            <p class="font-semibold">Tháng {{ str_replace('-', '/', $invoice->billing_month) }}</p>
            <p class="text-sm mt-0.5" style="color: var(--color-primary)">{{ number_format($invoice->total_amount) }}đ</p>
        </div>
        @if($invoice->status->value === 'paid')
            <span class="hosty-badge-paid">Đã TT</span>
        @else
            <span class="hosty-badge-unpaid">{{ $invoice->status->label() }}</span>
        @endif
    </a>
@empty
    <p class="text-gray-500 text-center py-8">Chưa có hóa đơn.</p>
@endforelse
</div>
<div class="mt-4">{{ $invoices->links() }}</div>
@endsection
