{{-- Shared "Send Us a Message" form, used on the home page and /contact --}}
@if(session('success'))
<div class="alert-success"><i class="fa fa-circle-check" style="margin-right:8px"></i>{{ session('success') }}</div>
@endif
@if(session('contact_error'))
<div class="alert-error"><i class="fa fa-triangle-exclamation" style="margin-right:8px"></i>{{ session('contact_error') }}</div>
@endif
@if($errors->any())
<div class="alert-error"><i class="fa fa-triangle-exclamation" style="margin-right:8px"></i>Please fix the errors below.</div>
@endif
<form method="POST" action="{{ route('contact.store') }}">
    @csrf
    <div style="position:absolute;left:-9999px" aria-hidden="true">
        <label for="website">Leave this field empty</label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Your full name">
            @error('name')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="your@email.com">
            @error('email')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+260-XXX-XXX-XXX">
            @error('phone')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label for="subject">Subject *</label>
            <select id="subject" name="subject" required>
                <option value="">Select Subject</option>
                @foreach(['Admissions Inquiry', 'Program Information', 'Scholarships', 'Fees & Payment', 'Student Portal Support', 'Corporate Training', 'General Inquiry'] as $subject)
                <option value="{{ $subject }}" @selected(old('subject') === $subject)>{{ $subject }}</option>
                @endforeach
            </select>
            @error('subject')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="form-group">
        <label for="message">Message *</label>
        <textarea id="message" name="message" rows="5" required placeholder="How can we help you?">{{ old('message') }}</textarea>
        @error('message')<span style="color:#d32f2f;font-size:.85rem">{{ $message }}</span>@enderror
    </div>
    <button type="submit" class="submit-btn"><i class="fa fa-paper-plane" style="margin-right:8px"></i>Send Message</button>
</form>
