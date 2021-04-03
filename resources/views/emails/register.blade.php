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
        Kính gửi đ/c: <span class="text-success">{{ $fullName }}</span>,
    </p>
    <p>
        Đồng chí vừa thực hiện chức năng đăng ký trên hệ thống xác thực Tập trung SSO.
        <br />
        Mã xác nhận email của đồng chí là: <b>{{ $verifyToken }}</b>
    </p>
    <p>
        <a href="{{ $appUrl }}/verify-email?token={{ $verifyToken }}">
            Link xác nhận
        </a>
    </p>
    <p>
        Nếu cần hỗ trợ, vui lòng liên hệ <a href="mailto:hotro_cntt@hdt.com.vn">hotro_cntt@hdt.com.vn</a>, SĐT: 1789 nhánh 2.
    </p>
</body>
</html>
