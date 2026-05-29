<x-filament-widgets::widget>
    <x-filament::section heading="Công nợ quá hạn">
        <div class="overflow-x-auto -mx-2">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="pb-3 px-2 font-medium">Khách thuê</th>
                        <th class="pb-3 px-2 font-medium">Phòng</th>
                        <th class="pb-3 px-2 font-medium">Số tiền</th>
                        <th class="pb-3 px-2 font-medium">Hạn TT</th>
                        <th class="pb-3 px-2 font-medium">Quá hạn</th>
                        <th class="pb-3 px-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->getDebts() as $debt)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="py-3 px-2 font-medium">{{ $debt['tenant'] }}</td>
                            <td class="py-3 px-2">{{ $debt['room'] }}</td>
                            <td class="py-3 px-2 font-semibold text-rose-600">{{ number_format($debt['amount']) }}đ</td>
                            <td class="py-3 px-2">{{ $debt['due_date']?->format('d/m/Y') }}</td>
                            <td class="py-3 px-2 text-rose-500">{{ $debt['days_overdue'] }} ngày</td>
                            <td class="py-3 px-2">
                                <span class="inline-flex rounded-full bg-rose-50 px-2 py-0.5 text-xs font-medium text-rose-600">Quá hạn</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-8 text-center text-gray-400">Không có công nợ quá hạn 🎉</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
