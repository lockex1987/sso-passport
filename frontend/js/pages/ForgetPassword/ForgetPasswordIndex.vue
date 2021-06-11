<template>
    <div class="login-background min-vh-100 d-flex justify-content-center align-items-center">
        <div class="forget-password-box w-100 m-2 rounded py-5 px-4">
            <div class="mb-3 font-weight-500 text-info text-center">
                Quên mật khẩu
            </div>

            <form @submit.prevent="sendResetToken()">
                <div class="mb-3 validate-container">
                    <input type="text"
                        v-model.trim="email"
                        class="form-control"
                        placeholder="Email"
                        data-validation="required|email" />
                </div>

                <div class="mb-3 text-center">
                    <button class="btn btn-primary btn-ripple btn-block"
                        type="submit">
                        Lấy mã bí mật
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
            // Địa chỉ email
            email: '',
            // Đang gửi mã OTP
            isProcessing: false
        };
    },

    methods: {
        /**
         * Gửi token reset mật khẩu
         */
        async sendResetToken() {
            // Validate
            if (CV.invalidForm(this.$el)) {
                return;
            }

            const params = {
                email: this.email
            };
            this.isProcessing = true;
            const { data } = await axios.post('/api/send-reset-password-token', params);
            this.isProcessing = false;
            if (data.code == 1) {
                noti.error(data.message);
            } else if (data.code == 0) {
                noti.success('Mã bí mật đã được gửi đến email của bạn');

                // Chuyển đến trang đăng nhập
                setTimeout(() => {
                    this.$router.push({
                        name: 'resetPassword'
                    });
                }, 2 * 1000);
            }
        }
    }
};
</script>
