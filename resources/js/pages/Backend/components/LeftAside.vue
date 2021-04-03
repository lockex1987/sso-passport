<template>
    <div class="lef-aside">
        <div class="wrapper py-2 h-100">
            <div class="text-center pt-3" v-if="user">
                <img class="logo rounded-circle object-fit-cover"
                        :src="'/storage/avatars/' + user.avatar"
                        onerror="this.src = '/images/user-avatar.png'"/>

                <div class="mt-3">{{user.username}}</div>
            </div>

            <!-- Menu đầy đủ -->
            <div class="menu mt-5">
                <ul class="menu" v-show="!filterProjectText">
                    <li>
                        <router-link :to="{ name: 'dashboard' }" class="text-decoration-none">
                            <i class="la la-dashboard font-size-1.25 mr-2"></i>
                            Dashboard
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'profile' }" class="text-decoration-none">
                            <i class="la la-user font-size-1.25 mr-2"></i>
                            Tài khoản
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'app' }" class="text-decoration-none">
                            <i class="lab la-app-store font-size-1.25 mr-2"></i>
                            Ứng dụng
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'loginLog' }" class="text-decoration-none">
                            <i class="la la-history font-size-1.25 mr-2"></i>
                            Lịch sử đăng nhập
                        </router-link>
                    </li>

                    <li>
                        <a href="javascript:;" @click="processLogout()" class="text-decoration-none">
                            <i class="la la-sign-out font-size-1.25 mr-2"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>


<script>
import logoutMixin from '~/pages/Login/logoutMixin';

export default {
    mixins: [
        // Hàm processLogout
        logoutMixin
    ],

    computed: {
        ...Vuex.mapState({
            user: state => state.auth.user
        })
    }
};
</script>


<style scoped lang="scss">
    .lef-aside {
        flex-shrink: 0;
        background: rgba(110, 174, 207, 0.15);
        border-top-right-radius: 0.25rem;
        width: 230px;

        .logo {
            width: 50px;
            height: 50px;
        }

        .menu {
            $orangeColor: #E06950;

            ul {
                padding: 0;

                li {
                    list-style-type: none;

                    a {
                        display: block;
                        padding: 10px 0 10px 5px;
                        border-left: solid transparent 3px;

                        &:hover {
                            color: $orangeColor;
                        }

                        // Menu đang active
                        &.router-link-active {
                            border-left-color: $orangeColor;
                            color: $orangeColor;
                        }

                        i {
                            min-width: 20px;
                        }
                    }
                }
            }
        }
    }
</style>
