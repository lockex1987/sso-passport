<template>
    <div class="login-page min-vh-100 d-flex justify-content-center align-items-center">
        <div class="login-box w-100 m-2 rounded py-5 px-4">
            <form ref="frm"
                @submit.prevent="processLogin()">
                <div class="mb-3 text-center text-danger"
                    v-if="errorMessage">
                    {{errorMessage}}
                </div>

                <div class="mb-3 validate-container">
                    <input v-model.trim="username"
                        type="text"
                        class="form-control"
                        placeholder="Tên đăng nhập"
                        data-validation="required"
                        autofocus
                        autocomplete="username" />
                </div>

                <div class="mb-3 validate-container">
                    <div class="input-group">
                        <input v-model.trim="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="form-control"
                            placeholder="Mật khẩu"
                            data-validation="required"
                            autocomplete="current-password"
                            @keydown="handleCapsLockWarning($event)">

                        <div class="input-group-append">
                            <span class="input-group-text cursor-pointer"
                                @click="togglePassword()"
                                :title="showPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'">
                                <i class="la"
                                    :class="[showPassword ? 'la-eye' : 'la-eye-slash']"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mt-1 font-size-0.875 text-warning"
                        v-show="isCapsLockOn">* Đang bật Caps Lock</div>
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
            // Tên đăng nhập
            username: '',
            // Mật khẩu
            password: '',
            // Có hiển thị password hay không
            showPassword: false,
            // Ứng dụng đang login
            app: null,
            // Có phải đang bật Caps Lock hay không
            isCapsLockOn: false
        };
    },

    created() {
        this.app = this.$route.query.app;
    },

    mounted() {
        // Nếu là link logout (có tham số logout=) thì thực hiện logout
        if (this.$route.query.logout) {
            this.processLogout(this.app);
        }
    },

    methods: {
        /**
         * Khi Caps Lock đang được bật thì cảnh báo người dùng.
         * Đơn giản, nhưng rất hữu ích.
         */
        handleCapsLockWarning(evt) {
            // Thêm đoạn kiểm tra getModifierState vì khi focus thì bị lỗi
            if (evt.getModifierState) {
                this.isCapsLockOn = evt.getModifierState('CapsLock');
            }
        },

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

                    // Chuyển đến trang mặc định
                    this.$router.push({
                        name: 'profile'
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
         * Ẩn / hiện password.
         */
        togglePassword() {
            this.showPassword = !this.showPassword;
        }
    }
};
</script>


<style scoped lang="scss">
.login-page {
    background: linear-gradient(90deg, #ffffff 19px, transparent 1%) center,
        linear-gradient(#ffffff 19px, transparent 1%) center, #f0f0f0;
    background-size: 22px 22px;
}

.min-vh-100 {
    min-height: 100vh;
}

.login-box {
    max-width: 400px;
}
</style>
