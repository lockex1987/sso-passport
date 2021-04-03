<template>
    <div class="container-fluid">
        <div class="bg-white rounded p-2">
            <the-breadcrumb :paths="['Tài khoản']"/>

            <div class="row">
                <div class="col-lg-6">
                    <div class="h-100 mb-5">
                        <h4>
                            Thông tin tài khoản
                        </h4>

                        <user-info />
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="h-100 mb-5">
                        <h4>
                            Đổi mật khẩu
                        </h4>

                        <change-password />
                    </div>
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
