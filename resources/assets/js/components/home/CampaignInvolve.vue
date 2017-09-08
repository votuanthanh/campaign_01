<template>
    <div class="ui-block" v-if="listCampaign.length">
        <div class="ui-block-title">
            <h6 class="title">{{ $t('homepage.campaigns_may_join') }}</h6>
        </div>
        <ul class="widget w-friend-pages-added notification-list friend-requests">
            <li class="inline-items" v-for="campaign in listCampaign">
                <div class="author-thumb">
                    <img :src="campaign.media[0].image_medium" alt="author">
                </div>
                <div class="notification-event">
                    <router-link class="h6 notification-friend" :to="{ name: 'campaign.timeline', params: { slug: campaign.slug }}">
                        <span v-if="campaign.title.length < 51">{{ campaign.title }}</span>
                        <span v-else>{{ campaign.title.substr(0, 50) }}...</span>
                    </router-link>
                    <span class="chat-message-item">
                        <span class="tag-info" v-for="tag in campaign.tags">{{ tag.name }}</span>
                    </span>
                </div>
                <span class="notification-icon">
                    <router-link :to="{ name: 'campaign.timeline', params: { slug: campaign.slug }}">
                        <svg class="olymp-star-icon">
                            <use xlink:href="/frontend/icons/icons.svg#olymp-star-icon"></use>
                        </svg>
                    </router-link>
                </span>
            </li>
        </ul>
    </div>
</template>

<script>
    import noty from '../../helpers/noty'
    import { get } from '../../helpers/api'

    export default {
        data: () => ({
            listCampaign: []
        }),
        created() {
            this.getCampaignInvolve()
        },
        methods: {
            getCampaignInvolve() {
                get('campaign/involve')
                    .then(res => {
                        this.listCampaign = res.data.campaignInvolve
                    })
                    .catch(err => {
                        noty({
                            text: this.$i18n.t('messages.connection_error'),
                            container: false,
                            force: true
                        })
                    })
            },
        }
    }
</script>

<style lang="scss" scoped>
    .author-thumb img {
        width: 36px;
        height: 36px;
    }

    .tag-info {
        padding: 2px 7px;
        color: white;
        display: inline-block;
        margin: 3px 1px 3px 1px;
        border-radius: 4px;
        background: #3f4257;
        font-weight: bold;
    }

    .notification-list {
        li {
            padding: 15px 15px;
        }
    }
</style>
