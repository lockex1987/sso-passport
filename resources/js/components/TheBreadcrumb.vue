<template>
    <nav>
        <ol class="the-breadcrumb breadcrumb bg-transparent pt-0 pl-0 pb-0 text-danger">
            <li class="breadcrumb-item" v-for="p in paths">
                {{p}}
            </li>
        </ol>
    </nav>
</template>


<script>
export default {
    props: {
        paths: {
            type: Array,
            default: []
        }
    }
};
</script>


<style lang="scss">
    .the-breadcrumb {
        font-size: 1.25rem;
    }
</style>
