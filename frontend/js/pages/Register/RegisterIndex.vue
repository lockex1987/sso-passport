<template>
    <div class="login-background min-vh-100 d-flex justify-content-center align-items-center">
        <div class="register-box w-100 m-2 rounded py-5 px-4">
            <div class="mb-3 font-weight-500 text-info text-center">
                Đăng ký
            </div>

            <form @submit.prevent="sendResetToken()"
                novalidate>
                <div class="mb-3 validate-container">
                    <input type="text"
                        placeholder="Tên đăng nhập"
                        v-model.trim="username"
                        class="form-control"
                        data-validation="required|minLength:4|maxLength:50"
                        data-validation-regex="^[a-zA-Z0-9_\.]+$"
                        data-validation-regex-message="Tên tài khoản là ký tự tiếng Việt không dấu, chữ số (a-z, A-Z, 0-9), không chứa dấu cách, có thể chứa ký tự đặc biệt là gạch dưới (_) hoặc dấu chấm (.)" />
                </div>

                <div class="mb-3 validate-container">
                    <input type="text"
                        placeholder="Tên hiển thị"
                        v-model.trim="fullName"
                        class="form-control"
                        data-validation="required|maxLength:100" />
                </div>

                <div class="mb-3 validate-container">
                    <input type="text"
                        placeholder="Email"
                        v-model.trim="email"
                        class="form-control"
                        data-validation="required|email|maxLength:100" />
                </div>

                <div class="mb-3 validate-container">
                    <input type="text"
                        placeholder="Số điện thoại"
                        v-model.trim="phone"
                        class="form-control"
                        data-validation="phone|maxLength:20" />
                </div>

                <div class="mb-3 validate-container">
                    <div class="input-group">
                        <input v-model.trim="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="form-control"
                            placeholder="Mật khẩu"
                            data-validation="required|password|passwordStrong|maxLength:50"
                            autocomplete="new-password">

                        <div class="input-group-append">
                            <span class="input-group-text cursor-pointer"
                                @click="togglePassword()"
                                :title="showPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'">
                                <i class="la"
                                    :class="[showPassword ? 'la-eye' : 'la-eye-slash']"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <button class="btn btn-primary btn-ripple btn-block"
                        type="submit">
                        Đăng ký
                        <span class="spinner-border spinner-border-sm"
                            v-show="isProcessing"></span>
                    </button>
                </div>

                <div class="text-center">
                    <router-link :to="{ name: 'login' }">
                        Quay lại đăng nhập
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Các thông tin của form
            username: '',
            fullName: '',
            email: '',
            password: '',
            phone: '',

            // Đang xử lý
            isProcessing: false,

            // Hiển thị mật khẩu
            showPassword: false
        };
    },

    methods: {
        /**
         * Ẩn / hiện password.
         */
        togglePassword() {
            this.showPassword = !this.showPassword;
        },

        /**
         * Gửi token reset mật khẩu
         */
        async sendResetToken() {
            if (CV.invalidForm(this.$el)) {
                return;
            }

            const params = {
                username: this.username,
                fullName: this.fullName,
                email: this.email,
                phone: this.phone,
                password: this.password
            };

            this.isProcessing = true;
            const { data } = await axios.post('/api/register', params);
            this.isProcessing = false;

            if (data.code == 0) {
                noti.success('Lưu thông tin thành công. Vui lòng kiểm tra email để hoàn thành việc đăng ký.');
            } else if (data.code == 2) {
                noti.error(data.message);
            }
        }
    }
};
</script>
