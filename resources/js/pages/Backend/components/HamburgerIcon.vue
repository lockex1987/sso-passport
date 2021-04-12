<template>
    <div class="hamburger-icon ml-2 mr-2 cursor-pointer"
        :class="{ 'open': showLeftAside }"
        @click="toggleLeftAside()">
        <div class="line line1"></div>
        <div class="line line2"></div>
        <div class="line line3"></div>
    </div>
</template>


<script>
export default {
    computed: {
        ...Vuex.mapState({
            showLeftAside: state => state.layout.showLeftAside
        })
    },

    methods: {
        /**
         * Ẩn hiện sidebar bên trái.
         */
        toggleLeftAside() {
            this.$store.commit('layout/toggleLeftAside');

            // Trigger sự kiện resize để các biểu đồ nhận kích thước mới
            // Thời gian transition của .lef-aside là 300 ms (xem file LeftAside.vue)
            // Cứ 50 ms lại gọi hàm resize, gọi 10 lần, tổng cộng 500 ms
            let count = 0;
            const fireResizeEvent = () => {
                window.dispatchEvent(new Event('resize'));
                count++;
                if (count < 10) {
                    setTimeout(fireResizeEvent, 50);
                }
            };
            fireResizeEvent();
        }
    }
};
</script>


<style scoped lang="scss">
.hamburger-icon {
    $lineWidth: 24px;

    height: 40px;
    width: 40px;
    cursor: pointer;
    background-color: transparent; //#294a67;

    .line {
        height: 2px;
        width: $lineWidth;
        background-color: #444;
        position: relative;
        left: calc(50% - #{$lineWidth / 2});
        transition: width 0.3s ease-in-out;
    }

    .line1 {
        top: calc(50% - 6px);
    }

    .line2 {
        top: calc(50% - 1px);
    }

    .line3 {
        top: calc(50% + 4px);
    }

    &.open {
        .line1 {
            width: $lineWidth / 4;
        }

        .line2 {
            width: $lineWidth / 2;
        }
    }
}
</style>
