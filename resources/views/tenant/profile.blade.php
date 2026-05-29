@extends('layouts.tenant')

@section('content')
<h1 class="text-xl font-bold">Tài khoản</h1>
<div class="mt-4 space-y-2">
    <p><span class="text-gray-500">Họ tên:</span> {{ $tenant?->full_name }}</p>
    <p><span class="text-gray-500">SĐT:</span> {{ $tenant?->phone }}</p>
</div>
<form method="POST" action="{{ route('tenant.logout') }}" class="mt-8">
    @csrf
    <button type="submit" class="w-full border rounded-xl py-3 text-red-600">Đăng xuất</button>
</form>
@endsection
