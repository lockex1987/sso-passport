<template>
    <form @submit.prevent="changePassword()">
        <div class="mb-3 validate-container mt-4">
            <label class="required">
                Mật khẩu cũ
            </label>

            <div class="input-group">
                <input v-model.trim="oldPassword"
                    :type="showOldPassword ? 'text' : 'password'"
                    class="form-control form-control-max-width"
                    placeholder="Mật khẩu cũ"
                    data-validation="required|minLength:6|maxLength:50"
                    autocomplete="new-password">

                <div class="input-group-append">
                    <span class="input-group-text cursor-pointer"
                        @click="toggleOldPassword()"
                        :title="showOldPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'">
                        <i class="la"
                            :class="[showOldPassword ? 'la-eye' : 'la-eye-slash']"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-3 validate-container mt-4">
            <label class="required">
                Mật khẩu mới
            </label>

            <div class="input-group">
                <input v-model.trim="newPassword"
                    :type="showNewPassword ? 'text' : 'password'"
                    class="form-control form-control-max-width"
                    placeholder="Mật khẩu mới"
                    data-validation="required|password|passwordStrong|maxLength:50"
                    autocomplete="new-password"
                    @keydown="handleCapsLockWarning($event)">

                <div class="input-group-append">
                    <span class="input-group-text cursor-pointer"
                        @click="toggleNewPassword()"
                        :title="showNewPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'">
                        <i class="la"
                            :class="[showNewPassword ? 'la-eye' : 'la-eye-slash']"></i>
                    </span>
                </div>
            </div>

            <div class="mt-1 font-size-0.875 text-warning"
                v-show="isCapsLockOn">* Đang bật Caps Lock</div>
        </div>

        <div class="mt-2">
            <button class="btn btn-primary btn-ripple"
                type="submit">
                Đổi mật khẩu
                <span class="spinner-border spinner-border-sm"
                    v-show="isProcessing"></span>
            </button>

            <button class="btn btn-outline-secondary btn-ripple"
                type="button"
                @click="cancelForm()">
                Hủy
            </button>
        </div>
    </form>
</template>


<script>
export default {
    data() {
        return {
            oldPassword: '',
            showOldPassword: false,
            newPassword: '',
            showNewPassword: false,
            // Đánh dấu đang xử lý
            isProcessing: false,
            // Có phải đang bật Caps Lock hay không
            isCapsLockOn: false
        };
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
         * Ẩn / hiện password cũ.
         */
        toggleOldPassword() {
            this.showOldPassword = !this.showOldPassword;
        },

        /**
         * Ẩn / hiện password mới.
         */
        toggleNewPassword() {
            this.showNewPassword = !this.showNewPassword;
        },

        /**
         * Đổi mật khẩu.
         */
        async changePassword() {
            // Nếu đang thực hiện rồi thì dừng lại
            if (this.isProcessing) {
                return;
            }

            // Validate
            if (CV.invalidForm(this.$el)) {
                return;
            }

            // Đánh dấu đang thực hiện
            this.isProcessing = true;

            // Gọi lên server (API)
            const params = {
                oldPassword: this.oldPassword,
                newPassword: this.newPassword
            };
            const { data: resp } = await axios.post('/change-password', params);

            // Đánh dấu đã xử lý xong
            this.isProcessing = false;

            if (resp.code == 0) {
                // Nếu thành công thì đóng reset form
                this.cancelForm();

                // Thông báo cho người dùng
                noti.success('Đổi mật khẩu thành công');
            } else {
                // noti.error('Mật khẩu cũ không chính xác');
                noti.error(resp.message);
            }
        },

        /**
         * Reset lại form.
         */
        resetForm() {
            this.oldPassword = '';
            this.newPassword = '';
            this.showOldPassword = false;
            this.showNewPassword = false;
        },

        /**
         * Nhấn nút hủy.
         */
        cancelForm() {
            // Xóa các thông báo lỗi (nếu có)
            CV.clearErrorMessages(this.$el);

            this.resetForm();
        }
    }
};
</script>
