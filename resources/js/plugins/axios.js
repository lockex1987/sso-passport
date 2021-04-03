
// Request interceptor
axios.interceptors.request.use(request => {
    const token = localStorage.getItem('authToken');
    if (token) {
        request.headers.common.Authorization = `Bearer ${token}`;
    }

    // Thiết lập thế này để phương thức $request->ajax() trả về true
    // request.headers['X-Requested-With'] = 'XMLHttpRequest';

    // Axios đã tự lấy XSRF token ở cookie và đẩy vào header?

    return request;
});

// Response interceptor
axios.interceptors.response.use(
    // Khi thành công thì trả về response luôn
    response => response,

    // Khi có lỗi thì xử lý lỗi chung
    error => {
        const { status } = error.response;

        if (status >= 500) {
            noti.error('Đã có lỗi xảy ra');
        }

        if (status == 403) {
            noti.error('Bạn không có quyền thực hiện chức năng này');
        }

        // https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        // Session đã hết hạn, CSRF token không hợp lệ, chưa đăng nhập
        // Token không hợp lệ, hết hạn
        // Chưa được phân quyền
        if ([440, 419, 401].includes(status)) {
            // Xóa token cũ đã hết hạn
            localStorage.removeItem('authToken');

            // Về trang chủ
            window.location = '/';
        }

        // Xử lý lỗi validate do Laravel trả về
        if (status == 422) {
            const errors = error.response.data.errors;
            let message = '';
            for (const key in errors) {
                const arr = errors[key];
                arr.forEach(s => {
                    message += s + '<br />';
                });
            }
            noti.error(message.trim());
        }

        // return Promise.reject(error);
        return Promise.resolve({
            data: {
                code: status
            }
        });
    }
);
