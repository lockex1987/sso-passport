<template>
    <div class="sidebar border-right vh-100 overflow-y-auto overflow-x-hidden custom-scrollbar flex-shrink-0"
        :class="{
            'sidebar--desktop-opened': showLeftAside
        }">

        <div class="sidebar__inner py-2">
            <!-- Người dùng đang đăng nhập -->
            <div class="text-center pt-3"
                v-if="loginUser">
                <img class="logo rounded-circle object-fit-cover"
                    :src="loginUser.avatar"
                    onerror="this.src = '/images/user-avatar.png'" />

                <div class="mt-3">
                    {{loginUser.username}}
                </div>
            </div>

            <!-- Menu đầy đủ -->
            <div class="menu mt-5">
                <ul>
                    <li>
                        <router-link :to="{ name: 'profile' }"
                            class="text-decoration-none sidebar-closer">
                            <i class="la la-user font-size-1.25 mr-2"></i>
                            Tài khoản
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'app' }"
                            class="text-decoration-none sidebar-closer">
                            <i class="lab la-app-store font-size-1.25 mr-2"></i>
                            Ứng dụng
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'loginLog' }"
                            class="text-decoration-none sidebar-closer">
                            <i class="la la-history font-size-1.25 mr-2"></i>
                            Lịch sử đăng nhập
                        </router-link>
                    </li>

                    <li>
                        <a href="#"
                            @click.prevent="processLogout()"
                            class="text-decoration-none sidebar-closer">
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
import logoutMixin from '~/pages/Login/logoutMixin.js';

export default {
    mixins: [
        // Hàm processLogout
        logoutMixin
    ],

    computed: {
        ...Vuex.mapState({
            showLeftAside: state => state.layout.showLeftAside
        })
    },

    mounted() {
        this.handleClickEvents();
    },

    methods: {
        /**
         * Lắng nghe các sự kiện chuột.
         */
        handleClickEvents() {
            document.addEventListener('click', (evt) => {
                let currentNode = evt.target;
                let clickOnBox = false;
                while (currentNode) {
                    if (currentNode.classList) {
                        // Click vào icon mở
                        if (currentNode.classList.contains('sidebar-opener')) {
                            document.body.classList.add('sidebar--mobile-opened');
                            return;
                        }

                        // Click vào icon đóng
                        if (currentNode.classList.contains('sidebar-closer')) {
                            document.body.classList.remove('sidebar--mobile-opened');
                            return;
                        }

                        if (currentNode.classList.contains('sidebar')) {
                            clickOnBox = true;
                            break;
                        }
                    }

                    currentNode = currentNode.parentNode;
                }

                // Nếu không click vào sidebar thì đóng
                if (!clickOnBox) {
                    document.body.classList.remove('sidebar--mobile-opened');
                }
            });
        }
    }
};
</script>


<style scoped lang="scss">
// Chiều rộng sidebar
$sidebarWidth: 250px;

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    // min-height: 100%;
    width: $sidebarWidth;
    overflow-x: hidden;
    z-index: 30;
    background-color: #fff;
    // Ẩn bên trái màn hình
    // Hiệu ứng chuyển động slide sang phải
    transition: all 0.4s ease-in-out;
    transform: translateX(-100%);

    @media (min-width: 768px) {
        position: relative;
        background: rgba(110, 174, 207, 0.15);
        position: relative;
        display: block;
        transform: translateX(0);
        width: 0px;

        // Trạng thái mở ở desktop
        &.sidebar--desktop-opened {
            width: $sidebarWidth;
        }
    }

    .sidebar__inner {
        // Phải cố định cả chiều rộng của của .sidebar__inner nếu không khi sidebar co lại thì chữ trong đó cũng bị co lại
        // Tránh trường hợp chiều rộng sidebar thay đổi làm nội dung bên trong bị co xuống dòng
        // Bằng kích thước của .sidebar--desktop-opened
        width: $sidebarWidth;

        .logo {
            width: 50px;
            height: 50px;
        }

        .menu {
            $orangeColor: #e06950;

            ul {
                padding: 0;

                li {
                    list-style-type: none;

                    a {
                        display: block;
                        padding: 10px 0 10px 5px;
                        border-left: solid transparent 3px;
                        // text-decoration: none;

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
}
</style>

<style lang="scss">
$sidebarWidth: 250px;

// Trạng thái mở ở mobile
body.sidebar--mobile-opened {
    // Hiển thị overlay
    &::before {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        content: " ";
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 20;
    }

    .sidebar {
        // width: $sidebarWidth;
        transform: translateX(0);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); // .shadow-sm
    }
}
</style>
