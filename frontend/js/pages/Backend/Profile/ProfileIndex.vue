<template>
    <div>
        <top-header :paths="['Tài khoản']" />

        <div class="row">
            <div class="col-lg-6">
                <div class="h-100 mb-5">
                    <div class="text-info">
                        Thông tin tài khoản
                    </div>

                    <user-info />
                </div>
            </div>

            <div class="col-lg-6">
                <div class="h-100 mb-5">
                    <div class="text-info">
                        Đổi mật khẩu
                    </div>

                    <change-password />
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import UserInfo from './components/UserInfo.vue';
import ChangePassword from './components/ChangePassword.vue';

export default {
    components: {
        UserInfo,
        ChangePassword
    }
};
</script>
