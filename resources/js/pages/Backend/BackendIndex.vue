<template>
    <div class="backend-layout d-flex">
        <left-aside />

        <router-view class="px-5 py-3 flex-1" />
    </div>
</template>


<script>
import LeftAside from './components/LeftAside.vue';

export default {
    components: {
        LeftAside
    }
};
</script>


<style scoped lang="scss">
.backend-layout {
    min-height: 100vh;
}
</style>
