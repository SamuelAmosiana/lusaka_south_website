@extends('layouts.admin')
@section('title','News & Events')
@section('page-title','News & Events')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-newspaper me-2"></i>All News &amp; Events ({{ $events->total() }})</h3>
        <a href="{{ route('admin.events.create') }}" class="btn btn-orange"><i class="fa fa-plus me-1"></i>Add New</a>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:70px">Media</th>
                <th>Title</th>
                <th>Category</th>
                <th>Date</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td>
                    <div style="width:60px;height:45px;border-radius:6px;overflow:hidden;background:#f1f5f9;border:1px solid #e2e8f0;display:flex;align-items:center;justify-content:center">
                        @if(!empty($event->image) && file_exists(public_path($event->image)))
                            <img src="{{ asset($event->image) }}" alt="Cover" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <i class="fa fa-newspaper" style="color:#94a3b8"></i>
                        @endif
                    </div>
                </td>
                <td>
                    <strong>{{ Str::limit($event->title,55) }}</strong>
                    @if($event->images->isNotEmpty())
                        <span style="display:inline-block;background:#e0f2fe;color:#0369a1;padding:2px 8px;border-radius:12px;font-size:.75rem;font-weight:600;margin-left:6px">
                            <i class="fa fa-camera me-1"></i>{{ $event->images->count() }}
                        </span>
                    @endif
                    <br>
                    <small style="color:var(--gray-600)">{{ Str::limit($event->short_description,80) }}</small>
                </td>
                <td>
                    @php $badge=['Latest News'=>'badge-blue','Upcoming Event'=>'badge-green','Past Event'=>'badge-red','Job Vacancy'=>'badge-orange'][$event->category]??'badge-blue'; @endphp
                    <span class="badge {{ $badge }}">{{ $event->category }}</span>
                </td>
                <td>{{ $event->date->format('M j, Y') }}</td>
                <td>
                    @if($event->featured)
                        <span class="badge badge-orange"><i class="fa fa-star me-1"></i>Yes</span>
                    @else
                        <span style="color:var(--gray-600)">No</span>
                    @endif
                </td>
                <td style="white-space:nowrap">
                    <a href="{{ route('admin.events.edit',$event) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                    <a href="{{ route('news.show',$event) }}" class="btn btn-outline btn-sm" target="_blank" title="Preview"><i class="fa fa-eye"></i></a>
                    <form method="POST" action="{{ route('admin.events.destroy',$event) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this event and its photos?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--gray-600)">No events yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $events->links() }}</div>
</div>
@endsection
