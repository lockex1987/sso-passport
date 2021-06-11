<template>
    <div>
        <top-header :paths="['Lịch sử đăng nhập']" />

        <table class="table table-bordered"
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
                    <th class="text-center d-none">
                        User agent
                    </th>
                    <th class="text-center">
                        Trình duyệt
                    </th>
                    <th class="text-center">
                        Hệ điều hành
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(log, idx) in logList"
                    :key="log.id">
                    <td class="text-center">
                        {{pagi.from + idx}}
                    </td>
                    <td class="text-center">
                        {{log.created_at}}
                    </td>
                    <td>
                        {{log.ip}}
                    </td>
                    <td class="d-none">
                        {{log.user_agent}}
                    </td>
                    <td>
                        {{log.browser}}
                    </td>
                    <td>
                        {{log.os}}
                    </td>
                </tr>
            </tbody>
        </table>

        <pagi @change="search"
            v-model="pagi"></pagi>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Danh sách log
            logList: [],

            // Đối tượng Pagi
            pagi: {}
        };
    },

    mounted() {
        this.search();
    },

    methods: {
        /**
         * Tìm kiếm danh sách log.
         */
        async search(page = 1) {
            const params = {
                page: page,
                size: 10
            };
            const { data } = await axios.get('/api/login-logs', { params });
            this.logList = data.data;
            this.pagi = data;
        }
    }
};
</script>
