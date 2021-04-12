<template>
    <div class="d-md-flex">
        <left-aside />

        <div class="flex-grow-1">
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
