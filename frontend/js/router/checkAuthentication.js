import store from '../store/index.js';


/**
 * Kiểm tra token ở localStorage, user ở Vuex storage.
 */
export default async (to, from, next) => {
    let user = store.getters['auth/user'];
    const token = localStorage.getItem('authToken');
    const app = to.query.app;
    const path = to.path;
    const shouldLogout = to.query.logout;

    // Nếu là link logout thì tiếp tục luôn
    if (shouldLogout) {
        next();
        return;
    }

    // Nếu có token mà chưa có user thì lấy thông tin user
    if (token && !user) {
        const params = {
            app
        };
        const { data } = await axios.get('/me', { params });
        if (data.code == 0) {
            if (!app) {
                user = data.user;
                store.commit('auth/setUser', user);
            } else {
                window.location = data.redirectUrl;
            }
        } else {
            // token đã hết hạn
            localStorage.removeItem('authToken');
        }
    }

    // Kiểm tra các đường dẫn
    if (user) {
        // Nếu người dùng đã đăng nhập thì chuyển đến trang mặc định
        if (path == '/' || path == '/login') {
            next({
                name: 'profile'
            });
            return;
        }
    } else {
        // Nếu người dùng chưa đăng nhập thì chuyển đến trang login
        if (path.startsWith('/backend') || path == '/') {
            next({
                name: 'login'
            });
            return;
        }
    }

    next();
};
