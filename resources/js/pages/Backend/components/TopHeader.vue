<template>
    <div>
        <div class="top-header d-flex align-items-center pr-3">
            <hamburger-icon />

            <div class="font-size-1.25 text-uppercase">
                <router-link :to="{ name: 'dashboard' }"
                    title="Về trang chủ"
                    class="text-white text-decoration-none">
                    Hệ thống SSO
                </router-link>
            </div>
        </div>
    </div>
</template>


<script>
import HamburgerIcon from './HamburgerIcon.vue';

export default {
    components: {
        HamburgerIcon
    }
};
</script>


<style scoped lang="scss">
.top-header {
    height: 70px;

    .logo {
        width: 50px;
        height: 50px;
    }
}
</style>
