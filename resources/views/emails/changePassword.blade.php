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
        Đồng chí vừa thực hiện thay đổi mật khẩu thành công trên hệ thống xác thực Tập trung SSO.
    </p>
    <p>
        Nếu cần hỗ trợ, vui lòng liên hệ <a href="mailto:hotro_cntt@hdt.com.vn">hotro_cntt@hdt.com.vn</a>, SĐT: 1789 nhánh 2.
    </p>
    <p>
        <img src="{{ $message->embed($pathToImage) }}">
    </p>
</body>
</html>
