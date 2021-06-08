<template>
    <div class="login-background min-vh-100 d-flex justify-content-center align-items-center">
        <div class="verify-email-box w-100 m-2 rounded py-5 px-4">
            <div class="mb-3 font-weight-500 text-info text-center">
                Xác nhận email
            </div>

            <form @submit.prevent="verifyEmail()">
                <div class="mb-3 validate-container"
                    v-show="!autoFillToken">
                    <input type="text"
                        placeholder="Mã xác nhận"
                        v-model.trim="verifyToken"
                        class="form-control"
                        data-validation="required" />
                    <small class="form-text text-muted">
                        Mã xác nhận được gửi đến email của bạn
                    </small>
                </div>

                <div class="mt-3 text-center">
                    <button class="btn btn-primary btn-ripple btn-block"
                        type="submit">
                        Xác nhận
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
            // Token để reset mật khẩu
            verifyToken: '',
            // Token có phải lấy từ URL hay không
            autoFillToken: false
        };
    },

    created() {
        const token = this.$route.query.token;
        if (token) {
            this.verifyToken = token;
            this.autoFillToken = true;
        }
    },

    mounted() {
        if (this.autoFillToken) {
            this.verifyEmail();
        }
    },

    methods: {
        /**
         * Xác nhận email.
         */
        async verifyEmail() {
            if (CV.invalidForm(this.$el)) {
                return;
            }

            const params = {
                verifyToken: this.verifyToken
            };
            const { data } = await axios.post('/verify-email', params);
            if (data.code == 2) {
                noti.error(data.message);
                this.autoFillToken = false;
            } else if (data.code == 0) {
                // Chuyển đến trang đăng nhập
                noti.success('Bạn đã xác nhận email thành công');
                setTimeout(() => {
                    this.$router.push({
                        name: 'login'
                    });
                }, 2 * 1000);
            }
        }
    }
};
</script>
