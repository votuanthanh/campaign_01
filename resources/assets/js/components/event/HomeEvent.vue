<template>
    <div v-if="loading">
        <show-event></show-event>
        <search-action></search-action>
        <router-view></router-view>
    </div>
</template>

<script>
    import ShowEvent from './ShowEvent.vue'
    import SearchAction from './SearchAction.vue'
    import ListAction from './ListAction.vue'
    import { mapState, mapActions } from 'vuex'
    export default {
        computed: {
            ...mapState([
                'loading'
            ])
        },

        created() {
            this.$Progress.start()
            this.get_event(this.$route.params.event_id)
            .then(sucess => {
                this.$Progress.finish()
            })
            .catch(err => {
                this.$Progress.fail()
            })
        },

        methods: {
            ...mapActions('event', [
                'get_event'
            ])
        },

        components: {
            ShowEvent,
            SearchAction
        }
    }
</script>
<style type="scss">

</style>
