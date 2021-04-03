const state = {
    user: null
};

const mutations = {
    setUser(state, userObj) {
        state.user = userObj;
    }
};

export default {
    state,
    mutations,
    namespaced: true,
    getters: {
        // Phải thêm getters ở đây thì ở checkAuthentication.js mới lấy được
        user: state => state.user
    }
};
