<div class="cards-grid">
    @foreach($page->activeItems('cards') as $card)
    <div class="card">
        <div class="card-icon"><i class="fa {{ $card->icon }}"></i></div>
        <h3>{{ $card->title }}</h3>
        <p>{{ $card->body }}</p>
    </div>
    @endforeach
</div>
