@extends('layouts.admin')
@section('title','FAQs')
@section('page-title','Manage FAQs')
@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-circle-question me-2"></i>All FAQs ({{ $faqs->total() }})</h3>
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-orange"><i class="fa fa-plus"></i>Add FAQ</a>
    </div>
    <table>
        <thead><tr><th>#</th><th>Question</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($faqs as $faq)
            <tr>
                <td>{{ $faq->sort_order }}</td>
                <td><strong>{{ Str::limit($faq->question,60) }}</strong><br><small style="color:var(--gray-600)">{{ Str::limit($faq->answer,100) }}</small></td>
                <td><span class="badge badge-blue">{{ $faq->category }}</span></td>
                <td>@if($faq->is_active)<span class="badge badge-green"><i class="fa fa-circle-check"></i> Active</span>@else<span class="badge badge-red">Inactive</span>@endif</td>
                <td style="white-space:nowrap">
                    <a href="{{ route('admin.faqs.edit',$faq) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                    <form method="POST" action="{{ route('admin.faqs.destroy',$faq) }}" style="display:inline" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')<button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:var(--gray-600)">No FAQs yet. <a href="{{ route('admin.faqs.create') }}">Add one</a>.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px">{{ $faqs->links() }}</div>
</div>
@endsection
