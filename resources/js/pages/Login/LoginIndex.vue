<template>
    <div class="login-page d-flex justify-content-center align-items-center">
        <div class="login-box w-100 m-2 rounded py-5 px-4">
            <form ref="frm"
                @submit.prevent="processLogin()">
                <div class="mb-3 text-center text-danger"
                    v-if="errorMessage">
                    {{errorMessage}}
                </div>

                <div class="form-group validate-container">
                    <input v-model.trim="username"
                        type="text"
                        class="form-control"
                        placeholder="Tên đăng nhập"
                        data-validation="required"
                        autofocus
                        autocomplete="username" />
                </div>

                <div class="form-group validate-container">
                    <div class="input-group">
                        <input v-model.trim="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="form-control"
                            placeholder="Mật khẩu"
                            data-validation="required"
                            autocomplete="current-password">

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

                <div class="mb-3">
                    <button class="btn btn-primary btn-block btn-ripple"
                        type="submit">
                        Đăng nhập
                        <span class="spinner-border spinner-border-sm"
                            v-show="isProcessing"></span>
                    </button>
                </div>

                <div class="d-flex justify-content-between">
                    <router-link :to="{ name: 'forgetPassword' }">
                        Quên mật khẩu
                    </router-link>

                    <router-link :to="{ name: 'register' }">
                        Đăng ký
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
import logoutMixin from './logoutMixin.js';

export default {
    mixins: [
        // Hàm processLogout
        logoutMixin
    ],

    data() {
        return {
            // Đánh dấu đang xử lý
            isProcessing: false,
            // Thông báo lỗi
            errorMessage: '',
            // Tên đăng nhập và mật khẩu
            username: '',
            password: '',
            // Có hiển thị password hay không
            showPassword: false,
            // Ứng dụng đang login
            app: null
        };
    },

    created() {
        this.app = this.$route.query.app;
    },

    mounted() {
        if (this.$route.query.logout) {
            this.processLogout(this.app);
        }
    },

    methods: {
        /**
         * Xử lý đăng nhập.
         */
        async processLogin() {
            // Nếu đang thực hiện rồi thì dừng lại
            if (this.isProcessing) {
                return;
            }

            // Validate
            if (CV.invalidForm(this.$refs.frm)) {
                return;
            }

            // Đánh dấu đang thực hiện
            this.isProcessing = true;

            // Gọi API
            const params = {
                username: this.username,
                password: this.password,
                app: this.app
            };

            const { data } = await axios.post('/login', params);

            // Đánh dấu đã xử lý xong
            this.isProcessing = false;

            if (data.code == 0) {
                // Ẩn các thông báo lỗi cũ
                this.errorMessage = '';

                // Lưu token
                localStorage.setItem('authToken', data.token);

                // Lấy thông tin người dùng
                if (!this.app) {
                    const user = data.user;
                    this.$store.commit('auth/setUser', user);

                    // Chuyển đến trang dashboard
                    this.$router.push({
                        name: 'dashboard'
                    });
                } else {
                    window.location = data.redirectUrl;
                }
            } else {
                // Người dùng bị khóa do nhập sai mật khẩu nhiều
                this.errorMessage = data.message;
            }
        },

        /**
         * Hiển thị hộp thoại quên mật khẩu.
         */
        openModal() {
            this.$emit('open-forget-password-modal');
        },

        /**
         * Ẩn/hiện password.
         */
        togglePassword() {
            this.showPassword = !this.showPassword;
        }
    }
};
</script>


<style scoped lang="scss">
.login-page {
    min-height: 100vh;
}

.login-box {
    max-width: 400px;
}
</style>
