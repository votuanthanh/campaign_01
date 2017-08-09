<template lang="html">
    <div class="campaign-container">
        <header-campaign></header-campaign>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import HeaderCampaign from './HeaderCampaign.vue'
    import { mapActions } from 'vuex'
    export default {
        created: function () {
            this.campaignDetail(this.$route.params.id)
        },
        methods: {
            ...mapActions('campaign', [
                'campaignDetail',
            ])
        },
        components: {
           HeaderCampaign
        }
    }
</script>

<style lang="scss" scoped>
    .campaign-container {
        background: #edf2f6;
    }
</style>
