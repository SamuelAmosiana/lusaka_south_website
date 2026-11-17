<div class="cards-grid" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr));text-align:center">
    @foreach($page->activeItems($group) as $person)
    <div class="card" style="text-align:center">
        <div class="staff-image-container">
            @if($person->image_url)
            <img src="{{ $person->image_url }}" alt="{{ $person->title }}" class="staff-image">
            @else
            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--gray-100);color:var(--primary-green);font-size:2.6rem"><i class="fa fa-user"></i></div>
            @endif
        </div>
        <h3>{{ $person->title }}</h3>
        <p style="color:var(--primary-orange);font-weight:600;{{ $person->body ? 'margin-bottom:10px' : '' }}">{{ $person->subtitle }}</p>
        @if($person->body)<p>{{ $person->body }}</p>@endif
    </div>
    @endforeach
</div>
