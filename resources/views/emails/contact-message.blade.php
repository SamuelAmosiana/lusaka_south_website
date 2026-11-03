<!DOCTYPE html>
<html>
<body style="margin:0;padding:24px;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;color:#343a40">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden">
        <tr><td style="background:#228B22;color:#fff;padding:20px 24px;font-size:18px;font-weight:bold">New message from the {{ config('site.short_name') }} website</td></tr>
        <tr><td style="padding:24px">
            <table width="100%" cellpadding="6" cellspacing="0" style="font-size:14px">
                <tr><td style="width:110px;color:#6c757d">Name</td><td><strong>{{ $data['name'] }}</strong></td></tr>
                <tr><td style="color:#6c757d">Email</td><td><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></td></tr>
                <tr><td style="color:#6c757d">Phone</td><td>{{ $data['phone'] ?: 'Not provided' }}</td></tr>
                <tr><td style="color:#6c757d">Subject</td><td>{{ $data['subject'] }}</td></tr>
            </table>
            <div style="margin-top:18px;padding:16px;background:#f8f9fa;border-left:4px solid #ff8c00;font-size:14px;line-height:1.6;white-space:pre-line">{{ $data['message'] }}</div>
            <p style="margin-top:18px;font-size:12px;color:#6c757d">Reply to this email to respond directly to {{ $data['name'] }}.</p>
        </td></tr>
    </table>
</body>
</html>
