@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('content')
<div class="stat-cards">
    <div class="stat-card"><i class="fa fa-newspaper"></i><h2>{{ $stats['total_events'] }}</h2><p>Total News &amp; Events</p></div>
    <div class="stat-card" style="border-left-color:var(--primary-green)"><i class="fa fa-calendar" style="color:var(--primary-green)"></i><h2>{{ $stats['upcoming_events'] }}</h2><p>Upcoming Events</p></div>
    <div class="stat-card" style="border-left-color:#1565c0"><i class="fa fa-circle-question" style="color:#1565c0"></i><h2>{{ $stats['total_faqs'] }}</h2><p>FAQs</p></div>
    <div class="stat-card" style="border-left-color:#7b1fa2"><i class="fa fa-download" style="color:#7b1fa2"></i><h2>{{ $stats['total_downloads'] }}</h2><p>Downloads</p></div>
    <div class="stat-card" style="border-left-color:var(--primary-orange)"><i class="fa fa-chart-simple" style="color:var(--primary-orange)"></i><h2>{{ $stats['total_counters'] }}</h2><p>Home Counters</p></div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-clock-rotate-left me-2"></i>Recent News &amp; Events</h3>
        <a href="{{ route('admin.events.create') }}" class="btn btn-orange"><i class="fa fa-plus"></i>Add New</a>
    </div>
    <table>
        <thead><tr><th>Title</th><th>Category</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($recentEvents as $event)
            <tr>
                <td><strong>{{ Str::limit($event->title,50) }}</strong></td>
                <td>
                    @php $badge=['Latest News'=>'badge-blue','Upcoming Event'=>'badge-green','Past Event'=>'badge-red','Job Vacancy'=>'badge-orange'][$event->category]??'badge-blue'; @endphp
                    <span class="badge {{ $badge }}">{{ $event->category }}</span>
                </td>
                <td>{{ $event->date->format('M j, Y') }}</td>
                <td>
                    <a href="{{ route('admin.events.edit',$event) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('admin.events.destroy',$event) }}" style="display:inline" onsubmit="return confirm('Delete this entry?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:var(--gray-600);padding:30px">No events yet. <a href="{{ route('admin.events.create') }}">Add one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:15px"><a href="{{ route('admin.events.index') }}" class="btn btn-outline">View All Events <i class="fa fa-arrow-right ms-1"></i></a></div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px">
    <div class="card">
        <div class="card-header"><h3><i class="fa fa-circle-question me-2"></i>FAQs</h3><a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>Add FAQ</a></div>
        <p style="color:var(--gray-600)">{{ $stats['total_faqs'] }} FAQs in the database. <a href="{{ route('admin.faqs.index') }}">Manage FAQs</a></p>
    </div>
    <div class="card">
        <div class="card-header"><h3><i class="fa fa-download me-2"></i>Downloads</h3><a href="{{ route('admin.downloads.index') }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>View All</a></div>
        <p style="color:var(--gray-600)">{{ $stats['total_downloads'] }} documents available for download. <a href="{{ route('admin.downloads.index') }}">Manage Downloads</a></p>
    </div>
    <div class="card">
        <div class="card-header"><h3><i class="fa fa-chart-simple me-2"></i>Home Counters</h3><a href="{{ route('admin.counters.index') }}" class="btn btn-orange btn-sm"><i class="fa fa-sliders"></i>Customize</a></div>
        <p style="color:var(--gray-600)">{{ $stats['total_counters'] }} stats counters configured. <a href="{{ route('admin.counters.index') }}">Customize Counters</a></p>
    </div>
</div>
@endsection
