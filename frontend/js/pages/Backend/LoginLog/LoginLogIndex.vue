<template>
    <div>
        <top-header :paths="['Lịch sử đăng nhập']" />

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                ref="searchResult"
                v-show="logList.length > 0">
                <thead>
                    <tr>
                        <th class="text-center"
                            style="width: 50px">
                            #
                        </th>
                        <th class="text-center">
                            Thời gian
                        </th>
                        <th class="text-center">
                            IP
                        </th>
                        <th class="text-center">
                            User agent (TODO: Browser, OS)
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="log in logList"
                        :key="log.id">
                        <td class="text-center">
                            {{log.stt}}
                        </td>
                        <td class="text-center">
                            {{log.created_at}}
                        </td>
                        <td>
                            {{log.ip}}
                        </td>
                        <td>
                            {{log.user_agent}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Danh sách ứng dụng
            logList: [],

            // Đối tượng datatable
            datatable: null
        };
    },

    mounted() {
        this.initDatatable();
    },

    methods: {
        /**
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, size, sortColumn, sortDirection) => {
                    const params = {
                        search: this.searchText,
                        page: page,
                        size: size
                    };
                    return axios.get('/login-logs', { params });
                },
                bindItemsCallback: (items) => {
                    this.logList = items;
                },
                getTotalAndData: ({ data }) => {
                    return {
                        total: data.total,
                        data: data.data
                    };
                },
                showLoading: true
            });
        }
    }
};
</script>
