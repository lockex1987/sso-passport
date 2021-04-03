<template>
    <div class="" v-if="user">
        {{user.username}}
    </div>
</template>


<script>
export default {
    computed: {
        ...Vuex.mapState({
            user: state => state.auth.user
        })
    },

    data() {
        return {

        };
    },

    mounted() {

    },


    methods: {

    }
};
</script>
