<template>
    <div class="reset-password-page d-flex justify-content-center align-items-center">
        <div class="reset-password-box w-100 m-2 rounded py-5 px-4">
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="font-weight-500 text-info">
                        Đặt lại mật khẩu
                    </div>
                </div>
            </div>

            <form @submit.prevent="resetPassword()">
                <div class="mb-3 validate-container" v-show="!autoFillToken">
                    <input type="text"
                            placeholder="Mã bí mật"
                            v-model.trim="resetToken"
                            class="form-control"
                            data-validation="required"/>
                    <small class="form-text text-muted">
                        Mã bí mật được gửi đến email của bạn
                    </small>
                </div>

                <div class="mb-3 validate-container">
                    <div class="input-group">
                        <input v-model.trim="newPassword"
                                :type="showNewPassword ? 'text' : 'password'"
                                class="form-control"
                                placeholder="Mật khẩu mới"
                                data-validation="required|password|passwordStrong|maxLength:50"
                                autocomplete="new-password">

                        <div class="input-group-append">
                            <span class="input-group-text cursor-pointer"
                                    @click="toggleNewPassword()"
                                    :title="showNewPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'">
                                <i class="la" :class="[showNewPassword ? 'la-eye' : 'la-eye-slash']"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mb-3 text-center">
                    <button class="btn btn-primary btn-ripple btn-block" type="submit">
                        Đặt lại mật khẩu
                    </button>
                </div>

                <div class="text-center">
                    <router-link :to="{ name: 'forgetPassword' }">
                        Lấy lại mã
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
            // Mật khẩu mới
            newPassword: '',
            // Hiển thị mật khẩu
            showNewPassword: false,
            // Token để reset mật khẩu
            resetToken: '',
            // Token có phải lấy từ URL hay không
            autoFillToken: false
        };
    },

    created() {
        const token = this.$route.query.token;
        if (token) {
            this.resetToken = token;
            this.autoFillToken = true;
        }
    },

    methods: {
        /**
         * Ẩn / hiện password mới.
         */
        toggleNewPassword() {
            this.showNewPassword = !this.showNewPassword;
        },

        /**
         * Reset mật khẩu mới.
         */
        async resetPassword() {
            if (CV.invalidForm(this.$el)) {
                return;
            }

            const params = {
                resetToken: this.resetToken,
                newPassword: this.newPassword
            };
            const { data } = await axios.post('/reset-password', params);
            if (data.code == 1) {
                noti.error(data.message);
                this.autoFillToken = false;
            } else if (data.code == 0) {
                noti.success('Bạn đã đặt lại mật khẩu thành công');

                // Chuyển đến trang đăng nhập
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


<style scoped lang="scss">
.reset-password-page {
    min-height: 100vh;
}

.reset-password-box {
    max-width: 400px;
}
</style>
