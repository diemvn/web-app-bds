<div data-total="{{ $listings->total() }}">
@forelse($listings as $listing)
<x-stitch.listing-card :listing="$listing" :badge="$loop->first ? 'verified' : ($loop->index === 2 ? 'promo' : null)" />
@empty
<p class="text-center py-12 text-on-surface-variant stitch-stagger-item">Không tìm thấy phòng phù hợp.</p>
@endforelse
@if($listings->hasPages())
<div class="mt-4 text-center text-label-md text-on-surface-variant">{{ $listings->links() }}</div>
@endif
</div>
