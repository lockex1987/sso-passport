export default {
    methods: {
        /**
         * Thực hiện đăng xuất.
         */
        async processLogout(app) {
            // Gọi API đăng xuất
            const { data } = await axios.post('/api/logout', { app: app });

            // Xóa session và chuyển đến trang login
            localStorage.removeItem('authToken');
            this.$store.commit('auth/setUser', null);

            if (!app) {
                this.$router.push({
                    name: 'login'
                });
            } else {
                if (data.redirectUrl) {
                    window.location = data.redirectUrl;
                } else {
                    this.$router.push({
                        name: 'login',
                        query: {
                            app: app
                        }
                    });
                }
            }
        }
    }
};
