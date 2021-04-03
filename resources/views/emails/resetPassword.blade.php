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
        Kính gửi đ/c: <span class="text-success">{{ $userFullName }}</span>,
    </p>
    <p>
        Đồng chí vừa thực hiện chức năng quên mật khẩu trên hệ thống xác thực Tập trung SSO.
        <br />
        Mã bí mật để thực hiện chức năng quên mật khẩu của đồng chí là: <b>{{ $resetToken }}</b>
    </p>
    <p>
        <a href="{{ $appUrl }}/reset-password?token={{ $resetToken }}">
            Link đặt lại mật khẩu
        </a>
    </p>
    <p>
        Nếu cần hỗ trợ, vui lòng liên hệ <a href="mailto:hotro_cntt@hdt.com.vn">hotro_cntt@hdt.com.vn</a>, SĐT: 1789 nhánh 2.
    </p>
</body>
</html>
