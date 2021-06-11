const state = {
    // Các dự án được đánh dấu
    markedProjectList: [],

    // ID của dự án đang xem chi tiết
    projectId: null,

    // Từ khóa để lọc khi đang xem chi tiết
    searchText: '',

    // Khoảng thời gian (chung cho các biểu đồ) khi xem chi tiết
    dateRange: {}
};

const mutations = {
    /**
     * Lấy danh sách các dự án được đánh dấu (tối đa 5 phần tử).
     */
    async updateMarkedProjectList(state) {
        const { data: resp } = await axios.get('/api/project/get-info');
        const { data } = resp;
        if (data && data.list) {
            const list = data.list;

            // Sắp xếp theo thứ tự ưu tiên rồi đến alphabet
            list.sort((a, b) => {
                const aPriority = a.is_priority ? 0 : 1;
                const bPriority = b.is_priority ? 0 : 1;
                if (aPriority != bPriority) {
                    return aPriority - bPriority;
                }

                return a.name.localeCompare(b.name, 'vi');
            });

            state.markedProjectList = list;
        } else {
            state.markedProjectList = [];
        }
    },

    /**
     * Thiết lập ID của dự án đang xem chi tiết.
     */
    setProjectId(state, projectId) {
        state.projectId = projectId;
    },

    /**
     * Thiết lập từ khóa để lọc khi đang xem chi tiết.
     * Cần cập nhật lại các biểu đồ.
     */
    setSearchText(state, searchText) {
        state.searchText = searchText;
    },

    /**
     * Thiết lập khoảng thời gian (chung cho các biểu đồ) khi đang xem chi tiết.
     * Cần cập nhật lại các biểu đồ.
     */
    setDateRange(state, dateRange) {
        state.dateRange = dateRange;
    }
};

export default {
    state,
    mutations,
    namespaced: true
};
