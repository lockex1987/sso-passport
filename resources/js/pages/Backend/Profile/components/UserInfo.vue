<template>
    <form @submit.prevent="updateInfo()">
        <div style="max-width: 300px">
            <div class="pb-4 mb-4 mt-5 text-center">
                <label class="d-block mb-0 cursor-pointer">
                    <img class="rounded-circle avatar object-fit-cover"
                        :src="'/storage/avatars/' + user.avatar"
                        title="Đổi ảnh đại diện"
                        onerror="this.src = '/images/user-avatar.png'"
                        ref="theImage" />

                    <input type="file"
                        ref="avatarFile"
                        @change="previewAvatar()"
                        accept="image/*,.png,.jpeg,.jpg,.gif;capture=camera"
                        class="d-none">
                </label>

                <div class="text-muted font-size-0.75 mt-3">
                    * Click vào ảnh đại diện để đổi ảnh
                </div>
            </div>
        </div>

        <div class="form-group mt-4">
            <label>
                Tài khoản
            </label>
            <div class="text-info">
                {{user.username}}
            </div>
        </div>

        <div class="form-group validate-container mt-4">
            <label class="required">
                Tên hiển thị
            </label>

            <input type="text"
                v-model.trim="fullName"
                class="form-control form-control-max-width"
                data-validation="required" />
        </div>

        <div class="form-group validate-container mt-4">
            <label class="required">
                Email
            </label>

            <input type="text"
                v-model.trim="email"
                class="form-control form-control-max-width"
                data-validation="required|email" />
        </div>

        <div class="mt-2">
            <button class="btn btn-primary"
                type="submit">
                Lưu thông tin
            </button>

            <button class="btn btn-outline-secondary"
                type="button"
                @click="initInfo()">
                Hủy
            </button>
        </div>
    </form>
</template>


<script>
export default {
    data() {
        return {
            fullName: '',
            email: '',
            avatar: null
        };
    },

    computed: {
        ...Vuex.mapState({
            user: state => state.auth.user
        })
    },

    mounted() {
        this.initInfo();
    },

    methods: {
        initInfo() {
            this.fullName = this.user.full_name;
            this.email = this.user.email;
            this.avatar = null;
            this.$refs.avatarFile.value = '';
        },

        /**
         * Cập nhật lại thông tin.
         */
        async updateInfo() {
            // Validate
            if (CV.invalidForm(this.$el)) {
                return;
            }

            // Gọi lên server (API)
            const params = new FormData();
            params.append('fullName', this.fullName);
            params.append('email', this.email);
            if (this.avatar) {
                params.append('avatar', this.avatar);
            }

            const { data } = await axios.post('/user', params);
            if (data.code == 0) {
                // Cập nhật lại vuex (thông tin email)
                this.user.email = this.email;
                this.user.full_name = this.fullName;
                this.user.avatar = data.avatar;
                this.$store.commit('auth/setUser', this.user);

                this.initInfo();

                noti.success('Cập nhật thông tin thành công');
            } else {
                noti.error(data.message);
            }
        },

        previewAvatar() {
            this.avatar = this.$refs.avatarFile.files[0];
            this.$refs.theImage.src = URL.createObjectURL(this.avatar);
        }
    }
};
</script>


<style scoped>
.avatar {
    width: 100px;
    height: 100px;
}
</style>
