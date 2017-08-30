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
    import slug from '../../helpers/mixin/getFullSlug'
    export default {
        mixins: [slug],
        data() {
            return {
                pageType: 'campaign'
            }
        },
        watch: {
            '$route' : 'intialize',
        },
        created: function () {
            this.intialize()
        },
        methods: {
            ...mapActions('campaign', [
                'campaignDetail',
            ]),
            intialize() {
                this.campaignDetail(this.pageId)
                .then(status => {
                    //
                })
                .catch( err => {
                    if (err.response.data.http_status.code == 404 ||
                        err.response.data.http_status.code == 401) {
                        this.$router.push({ name: 'not_found' })
                    }
                })
            }
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
