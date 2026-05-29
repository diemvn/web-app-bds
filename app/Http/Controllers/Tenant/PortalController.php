<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    protected function tenant()
    {
        return Auth::user()->tenant;
    }

    public function home()
    {
        $tenant = $this->tenant();
        $contract = $tenant?->activeContract?->load('room.building');
        $currentInvoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant?->id))
            ->whereIn('status', ['sent', 'overdue'])
            ->latest('billing_month')
            ->first();

        return view('tenant.home', compact('tenant', 'contract', 'currentInvoice'));
    }

    public function invoices()
    {
        $invoices = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $this->tenant()?->id))
            ->orderByDesc('billing_month')
            ->paginate(12);

        return view('tenant.invoices', compact('invoices'));
    }

    public function showInvoice(Invoice $invoice)
    {
        abort_unless($invoice->contract?->tenant_id === $this->tenant()?->id, 403);

        return view('tenant.invoice-show', compact('invoice'));
    }

    public function contract()
    {
        $contract = $this->tenant()?->activeContract?->load('room.building');

        return view('tenant.contract', compact('contract'));
    }

    public function notifications()
    {
        $notifications = Notification::query()
            ->where('tenant_id', $this->tenant()?->id)
            ->latest()
            ->paginate(20);

        return view('tenant.notifications', compact('notifications'));
    }

    public function markNotificationRead(Notification $notification)
    {
        abort_unless($notification->tenant_id === $this->tenant()?->id, 403);
        $notification->markAsRead();

        return back();
    }

    public function profile()
    {
        return view('tenant.profile', ['tenant' => $this->tenant(), 'user' => Auth::user()]);
    }
}
