@extends('layouts.admin')
@section('title','Downloads')
@section('page-title','Manage Downloads')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-download me-2"></i>All Downloads ({{ $downloads->total() }})</h3>
        <a href="{{ route('admin.downloads.create') }}" class="btn btn-orange"><i class="fa fa-plus"></i>Add Download</a>
    </div>
    <table>
        <thead><tr><th>#</th><th>Title</th><th>Filename</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($downloads as $doc)
            <tr>
                <td>{{ $doc->sort_order }}</td>
                <td><strong>{{ $doc->title }}</strong><br><small style="color:var(--gray-600)">{{ Str::limit($doc->description,80) }}</small></td>
                <td><code style="font-size:.82rem">{{ $doc->filename }}</code></td>
                <td><span class="badge badge-blue">{{ $doc->category }}</span></td>
                <td>@if($doc->is_active)<span class="badge badge-green"><i class="fa fa-circle-check"></i> Active</span>@else<span class="badge badge-red">Inactive</span>@endif</td>
                <td style="white-space:nowrap">
                    <a href="{{ route('admin.downloads.edit',$doc) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                    <a href="{{ asset('docs/'.$doc->filename) }}" class="btn btn-outline btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                    <form method="POST" action="{{ route('admin.downloads.destroy',$doc) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--gray-600)">No downloads yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $downloads->links() }}</div>
</div>
@endsection
