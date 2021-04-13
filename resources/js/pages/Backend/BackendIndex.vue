<template>
    <div class="d-md-flex">
        <left-aside />

        <div class="flex-grow-1 vh-100 overflow-y-auto overflow-x-hidden custom-scrollbar px-3 pb-3">
            <router-view />
        </div>
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
