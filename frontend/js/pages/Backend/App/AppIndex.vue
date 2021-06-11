<template>
    <div>
        <top-header :paths="['Danh sách ứng dụng của người dùng']" />

        <div v-if="appList">
            <div v-if="appList.length == 0"
                class="text-danger">
                Không tồn tại bản ghi
            </div>

            <table v-else
                class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center"
                            style="width: 50px">
                            #
                        </th>
                        <th class="text-center">
                            Mã ứng dụng
                        </th>
                        <th class="text-center">
                            Tên ứng dụng
                        </th>
                        <th class="text-center">
                            Chuyển tới ứng dụng
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(app, idx) in appList"
                        :key="app.id">
                        <td class="text-center">
                            {{idx + 1}}
                        </td>
                        <td>
                            {{app.code}}
                        </td>
                        <td>
                            {{app.name}}
                        </td>
                        <td class="text-center">
                            <a :href="app.login_redirect"><i class="la la-link"></i></a>
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
            appList: null
        };
    },

    mounted() {
        this.getAppList();
    },

    methods: {
        /**
         * Lấy danh sách ứng dụng.
         */
        async getAppList() {
            const { data } = await axios.get('/apps');
            this.appList = data;
        }
    }
};
</script>
