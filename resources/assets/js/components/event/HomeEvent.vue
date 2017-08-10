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
    import { mapState, mapActions, mapMutations } from 'vuex'
    export default {
        computed: {
            ...mapState([
                'loading'
            ]),
            ...mapState('event', [ 'event' ])
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
            ]),
            ...mapMutations('event', {
                change_status: 'CHANGE_DONATION_STATUS'
            })
        },

        components: {
            ShowEvent,
            SearchAction
        },
        sockets: {
            accept_donation(data) {
                const goal = this.event.complete_percent.filter(goal => goal.id == data.goal_id)[0]
                let updatedData = goal.donations.filter(donate => donate.id == data.donate_id)[0]
                updatedData.status = data.status
                this.change_status(updatedData)
            }
        }
    }
</script>
<style type="scss">

</style>
