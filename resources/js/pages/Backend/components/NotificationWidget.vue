<template>
    <!-- Cảnh báo sự kiện hàng năm sắp diễn ra -->
    <div class="ml-auto notification-widget">
        <div class="dropdown" v-show="list.length > 0">
            <span class="position-relative dropdown-toggle no-caret-right-down cursor-pointer pr-2 hover:text-warning" data-toggle="dropdown">
                <i class="la la-bell la-lg"></i>
                <span class="badge badge-warning position-absolute notification-number">
                    {{list.length}}
                </span>
            </span>

            <div class="dropdown-menu dropdown-menu-right mt-2 shadow-sm overflow-auto custom-scrollbar" ref="listDiv">
                <span class="caret-up"></span>

                <div class="dropdown-item text-wrap pr-2"
                        :class="[e.is_read ? 'bg-white' : 'bg-light', idx == list.length - 1 ? '' : 'border-bottom']"
                        v-for="(e, idx) in list"
                        :key="e.id"
                        @click="processClickNotification(e)">
                    <div class="w-100 d-flex align-items-end">
                        <span class="flex-1">{{e.title}}</span>

                        <i class="la la-trash cursor-pointer text-danger delete-icon"
                                title="Xóa"
                                @click.stop="deleteNotification(e)"></i>
                    </div>
                </div>

                <!-- Icon đang load tiếp -->
                <div v-show="isLoadingMore" class="text-center">
                    <span class="spinner-border spinner-border-sm text-primary"></span>
                </div>
            </div>
        </div>

        <div v-show="list.length == 0">
            <i class="la la-bell la-lg"></i>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Danh sách thông báo
            list: [],
            // Chỉ số trang hiện tại
            page: 0,
            // Đánh dấu có đang xử lý hay không (để không xử lý nhiều lần)
            isLoadingMore: false
        };
    },

    mounted() {
        this.getNotificationList();

        const div = this.$refs.listDiv;
        div.addEventListener('scroll', this.checkLoadMoreNotifications);
    },

    methods: {
        /**
             * Lấy danh sách cảnh báo.
             */
        async getNotificationList() {
            // Đánh dấu đang xử lý
            this.isLoadingMore = true;

            // Gọi API
            const params = {
                page: this.page,
                size: 10
            };
            const { data: resp } = await axios.get('/notify/get-info', { params });
            const data = resp.data || { total: 0 };
            const list = data.list || [];

            // Tăng chỉ số trang
            this.page++;

            // Thêm vào danh sách
            this.list = this.list ? this.list.concat(list) : list;

            // Kiểm tra đã load hết chưa
            // console.log(this.list.length, data.total);
            if (this.list.length >= data.total) {
                const div = this.$refs.listDiv;
                div.removeEventListener('scroll', this.checkLoadMoreNotifications);
            }

            // Đánh dấu đã xử lý xong
            this.isLoadingMore = false;
        },

        /**
             * Xử lý khi click vào cảnh báo.
             */
        async processClickNotification(notification) {
            this.markAsRead(notification);
            this.gotoProjectDetailPage(notification);
        },

        /**
             * Đánh dấu là đã đọc.
             */
        async markAsRead(notification) {
            const params = {
                id: notification.id,
                is_read: true
            };
            // const { data } =
            await axios.post('/notify/update', params);
            // console.log(resp);

            // Load lại danh sách
            // Gọi lại luôn thế này chưa ăn is_read bằng true
            // this.getNotificationList();

            notification.is_read = true;
        },

        /**
             * Chuyển đến trang xem chi tiết.
             */
        async gotoProjectDetailPage(notification) {
            // Cần lấy tên của project
            const projectId = notification.project_id;
            const params = {
                id: projectId
            };
            const { data: resp } = await axios.get('/project/get-info-detail', { params });
            if (resp.code != 0) {
                return;
            }

            const project = resp.data;
            const projectName = project.name;
            this.$router.push({
                name: 'projectDetailSummary',
                params: {
                    id: projectId
                },
                query: {
                    name: projectName
                }
            });
        },

        /**
             * Xóa thông báo.
             */
        async deleteNotification(notification) {
            const params = {
                id: notification.id
            };
            const { data: resp } = await axios.delete('/notify/update', { data: params });
            if (resp.code == 0) {
                // Load lại danh sách
                this.getNotificationList();
            } else {
                noti.error('Đã có lỗi xảy ra');
            }
        },

        /**
             * Kiểm tra load bản ghi khi scroll.
             */
        checkLoadMoreNotifications() {
            // Nếu đang xử lý rồi thì thôi
            if (this.isLoadingMore) {
                return;
            }

            // Tính toán xem nếu scroll đến gần cuối trang thì load thêm bản ghi
            const div = this.$refs.listDiv;
            const heightToBottom = div.scrollHeight - (div.scrollTop + div.clientHeight);
            const threshold = 100;
            if (heightToBottom < threshold) {
                this.getNotificationList();
            }
        }
    }
};
</script>


<style scoped lang="scss">
    .notification-widget {
        .notification-number {
            right: 0;
            top: 0;
        }

        .dropdown-menu {
            width: 260px;
            max-height: 500px;
        }

        .caret-up {
            top: -7px;
            left: 238px;
        }

        .delete-icon {
            right: 10px;
            bottom: 5px;
        }

        .bg-light {
            background-color: #edf2fa !important;
        }
    }
</style>
