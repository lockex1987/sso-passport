@php
    $appUrl = config('app.url');
@endphp

<html>
<head>
    <style>
        .text-success {
            color: green;
        }
    </style>
</head>

<body>
    <p>
        Kính gửi đ/c
        <span class="text-success">{{ $fullName }}</span>,
    </p>
    <p>
        Đồng chí vừa thực hiện chức năng đăng ký trên hệ thống xác thực Tập trung SSO.
        <br />
        Mã xác nhận email của đồng chí là: <b>{{ $verifyToken }}</b>
    </p>
    <p>
        Đồng chí vui lòng click vào link sau để hoàn thành việc đăng ký:
    </p>
    <p>
        <a href="{{ $appUrl }}/verify-email?token={{ $verifyToken }}">
            Link xác nhận
        </a>
    </p>
    <p>
        Nếu cần hỗ trợ, vui lòng liên hệ <a href="mailto:support@hdt.com.vn">support@hdt.com.vn</a>, SĐT: 0386519125.
    </p>
</body>
</html>
