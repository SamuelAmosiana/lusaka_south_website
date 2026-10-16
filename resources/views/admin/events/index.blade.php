@extends('layouts.admin')
@section('title','News & Events')
@section('page-title','News & Events')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-newspaper me-2"></i>All News &amp; Events ({{ $events->total() }})</h3>
        <a href="{{ route('admin.events.create') }}" class="btn btn-orange"><i class="fa fa-plus"></i>Add New</a>
    </div>
    <table>
        <thead><tr><th>Title</th><th>Category</th><th>Date</th><th>Featured</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td><strong>{{ Str::limit($event->title,55) }}</strong><br><small style="color:var(--gray-600)">{{ Str::limit($event->short_description,80) }}</small></td>
                <td>@php $badge=['Latest News'=>'badge-blue','Upcoming Event'=>'badge-green','Past Event'=>'badge-red','Job Vacancy'=>'badge-orange'][$event->category]??'badge-blue'; @endphp
                    <span class="badge {{ $badge }}">{{ $event->category }}</span></td>
                <td>{{ $event->date->format('M j, Y') }}</td>
                <td>@if($event->featured)<span class="badge badge-orange"><i class="fa fa-star"></i> Yes</span>@else<span style="color:var(--gray-600)">No</span>@endif</td>
                <td style="white-space:nowrap">
                    <a href="{{ route('admin.events.edit',$event) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                    <a href="{{ route('news.show',$event) }}" class="btn btn-outline btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                    <form method="POST" action="{{ route('admin.events.destroy',$event) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:var(--gray-600)">No events yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $events->links() }}</div>
</div>
@endsection
