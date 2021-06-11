<template>
    <div>
        <div class="top-header d-flex align-items-center">
            <div class="hamburger-icon mr-2 cursor-pointer d-none d-md-block"
                :class="{ 'opened': showLeftAside }"
                @click="toggleLeftAside()">
                <div class="line line1"></div>
                <div class="line line2"></div>
                <div class="line line3"></div>
            </div>

            <div class="font-size-1.25">
                <nav>
                    <ol class="breadcrumb bg-transparent pt-0 pl-0 pb-0 mb-0 text-danger">
                        <li v-for="p in paths"
                            class="breadcrumb-item">
                            {{p}}
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="hamburger-icon ml-auto cursor-pointer d-block d-md-none sidebar-opener">
                <div class="line line1"></div>
                <div class="line line2"></div>
                <div class="line line3"></div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        paths: {
            type: Array,
            default: []
        }
    },

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
// @use "sass:math";

.top-header {
    height: 70px;
}

.hamburger-icon {
    $lineWidth: 24px;

    height: 40px;
    width: 40px;
    cursor: pointer;
    background-color: transparent; //#294a67;

    .line {
        height: 2px;
        width: $lineWidth;
        background-color: #6c757d; // giống .text-muted
        position: relative;
        // left: calc(50% - math.div($lineWidth, 2));
        left: calc(50% - #{$lineWidth} / 2);
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

    &.opened {
        .line1 {
            // width: math.div($lineWidth, 4);
            width: calc(#{$lineWidth} / 4);
        }

        .line2 {
            // width: math.div($lineWidth, 2);
            width: calc(#{$lineWidth} / 4);
        }
    }
}
</style>
