@extends('layouts.tenant')

@section('content')
<h1 class="text-xl font-bold">Hóa đơn {{ $invoice->billing_month }}</h1>
<div class="mt-4 space-y-2 text-sm">
    <div class="flex justify-between"><span>Tiền phòng</span><span>{{ number_format($invoice->room_amount) }}đ</span></div>
    <div class="flex justify-between"><span>Điện</span><span>{{ number_format($invoice->electric_amount) }}đ</span></div>
    <div class="flex justify-between"><span>Nước</span><span>{{ number_format($invoice->water_amount) }}đ</span></div>
    <div class="flex justify-between"><span>Phí DV</span><span>{{ number_format($invoice->service_amount) }}đ</span></div>
    <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2"><span>Tổng</span><span style="color: var(--color-primary)">{{ number_format($invoice->total_amount) }}đ</span></div>
</div>
@if($invoice->status->value !== 'paid')
<p class="mt-6 text-xs text-gray-500 text-center">Thanh toán qua chuyển khoản — SePay (giả lập). Liên hệ chủ trọ để xác nhận.</p>
@endif
@endsection
