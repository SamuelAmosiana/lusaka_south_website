@extends('layouts.admin')
@section('title', 'Home Counters')
@section('page-title', 'Home Page Counters')

@section('styles')
<style>
.preview-banner {
    background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
    padding: 30px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,.1);
}
.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}
.preview-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 20px 15px;
    text-align: center;
    color: #fff;
}
.preview-card.inactive {
    opacity: 0.45;
    position: relative;
}
.preview-card.inactive::after {
    content: 'INACTIVE';
    position: absolute;
    top: 6px;
    right: 8px;
    font-size: 0.65rem;
    font-weight: 800;
    background: #dc3545;
    padding: 2px 6px;
    border-radius: 4px;
}
.preview-card i {
    font-size: 1.8rem;
    color: var(--primary-orange);
    margin-bottom: 8px;
    display: block;
}
.preview-number {
    font-size: 2.2rem;
    font-weight: 800;
    line-height: 1.1;
}
.preview-number span {
    color: var(--primary-orange);
}
.preview-label {
    font-size: 0.95rem;
    opacity: 0.9;
    margin-top: 5px;
    font-weight: 600;
}
.icon-input-group {
    display: flex;
    align-items: center;
    gap: 8px;
}
.icon-preview-box {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    background: var(--gray-100);
    border: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-green);
    font-size: 1.2rem;
    flex-shrink: 0;
}
</style>
@endsection

@section('content')
<!-- Live Preview Section -->
<div class="card" style="padding-bottom: 15px;">
    <div class="card-header">
        <h3><i class="fa fa-eye me-2"></i>Live Website Preview</h3>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm"><i class="fa fa-external-link-alt"></i> View on Home Page</a>
    </div>
    <div class="preview-banner">
        <div class="preview-grid">
            @forelse($counters as $c)
            <div class="preview-card {{ $c->is_active ? '' : 'inactive' }}">
                <i class="fa {{ $c->icon }}"></i>
                <div class="preview-number">{{ $c->number }}<span>{{ $c->suffix }}</span></div>
                <div class="preview-label">{{ $c->label }}</div>
            </div>
            @empty
            <p style="color:#fff;text-align:center;grid-column:1/-1;">No counters configured yet.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Batch Edit Form -->
<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-sliders me-2"></i>Manage &amp; Customize Counters</h3>
        <a href="{{ route('admin.counters.create') }}" class="btn btn-orange"><i class="fa fa-plus"></i> Add New Counter</a>
    </div>

    <form method="POST" action="{{ route('admin.counters.batch-update') }}">
        @csrf
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">Order</th>
                    <th style="width: 70px;">Icon</th>
                    <th>Label</th>
                    <th style="width: 140px;">Number</th>
                    <th style="width: 100px;">Suffix</th>
                    <th style="width: 100px; text-align: center;">Active</th>
                    <th style="width: 110px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($counters as $counter)
                <tr>
                    <td>
                        <input type="number" name="counters[{{ $counter->id }}][sort_order]" value="{{ $counter->sort_order }}" class="form-control" style="padding: 6px 10px; text-align: center;" min="0">
                    </td>
                    <td>
                        <div class="icon-input-group">
                            <div class="icon-preview-box"><i class="fa {{ $counter->icon }}"></i></div>
                            <input type="hidden" name="counters[{{ $counter->id }}][icon]" value="{{ $counter->icon }}">
                        </div>
                    </td>
                    <td>
                        <input type="text" name="counters[{{ $counter->id }}][label]" value="{{ $counter->label }}" class="form-control" style="padding: 6px 10px;" required>
                    </td>
                    <td>
                        <input type="number" name="counters[{{ $counter->id }}][number]" value="{{ $counter->number }}" class="form-control" style="padding: 6px 10px;" min="0" required>
                    </td>
                    <td>
                        <input type="text" name="counters[{{ $counter->id }}][suffix]" value="{{ $counter->suffix }}" class="form-control" style="padding: 6px 10px;" placeholder="e.g. +">
                    </td>
                    <td style="text-align: center;">
                        <input type="hidden" name="counters[{{ $counter->id }}][is_active]" value="0">
                        <input type="checkbox" name="counters[{{ $counter->id }}][is_active]" value="1" {{ $counter->is_active ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: var(--primary-green); cursor: pointer;">
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.counters.edit', $counter) }}" class="btn btn-primary btn-sm" title="Full Edit"><i class="fa fa-pencil"></i></a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Delete counter {{ $counter->label }}?')) document.getElementById('delete-form-{{ $counter->id }}').submit();" title="Delete"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--gray-600);padding:30px">
                        No counters found. <a href="{{ route('admin.counters.create') }}">Create one</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($counters->isNotEmpty())
        <div style="margin-top: 25px; display: flex; justify-content: space-between; align-items: center;">
            <p style="color: var(--gray-600); font-size: 0.85rem;">
                <i class="fa fa-circle-info me-1"></i> Changes will immediately reflect on the home page counters banner.
            </p>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i> Save All Counter Changes
            </button>
        </div>
        @endif
    </form>
</div>

@foreach($counters as $counter)
<form id="delete-form-{{ $counter->id }}" method="POST" action="{{ route('admin.counters.destroy', $counter) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

@endsection
