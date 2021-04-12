<template>
    <div class="d-flex">
        <div class="vh-100 overflow-y-auto overflow-x-hidden custom-scrollbar flex-shrink-0">
            <left-aside />
        </div>

        <div class="flex-grow-1 d-flex flex-column">
            <top-header />

            <div class="adjust-height overflow-y-auto overflow-x-hidden custom-scrollbar px-3 pb-3">
                <router-view />
            </div>
        </div>
    </div>
</template>


<script>
import LeftAside from './components/LeftAside.vue';
import TopHeader from './components/TopHeader.vue';

export default {
    components: {
        LeftAside,
        TopHeader
    }
};
</script>


<style scoped lang="scss">
.adjust-height {
    height: calc(100vh - 70px);
}
</style>

<style lang="scss">
.backend-layout-min-height {
    min-height: calc(100vh - 70px - 16px);
}
</style>
