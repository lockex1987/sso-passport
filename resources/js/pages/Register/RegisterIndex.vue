<template>
    <div class="register-page d-flex justify-content-center align-items-center">
        <div class="register-box w-100 m-2 rounded py-5 px-4">
            <div class="mb-3 font-weight-500 text-info">
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
                        data-validation="required|email" />
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

                <div class="text-center">
                    <button class="btn btn-primary btn-ripple btn-block"
                        type="submit">
                        Đăng ký
                        <span class="spinner-border spinner-border-sm"
                            v-show="isProcessing"></span>
                    </button>
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
            // Validate
            if (CV.invalidForm(this.$el)) {
                return;
            }

            const params = {
                username: this.username,
                fullName: this.fullName,
                email: this.email,
                password: this.password
            };

            this.isProcessing = true;
            const { data } = await axios.post('/register', params);
            this.isProcessing = false;

            if (data.code == 1) {
                noti.error(data.message);
            } else if (data.code == 0) {
                noti.success('Bạn đã đăng ký thành công. Vui lòng kiểm tra email');
            }
        }
    }
};
</script>


<style scoped lang="scss">
.register-page {
    min-height: 100vh;
}

.register-box {
    max-width: 400px;
}
</style>
