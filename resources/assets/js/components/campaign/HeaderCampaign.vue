<template lang="html">
    <div>
        <div class="header-spacer"></div>

        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="ui-block">
                        <div class="top-header top-header-favorit">
                            <div class="top-header-thumb" v-if="campaign.campaign_images != null">
                                <img :src="campaign.campaign_images.image_default" alt="nature">
                                <div class="top-header-author">
                                    <div class="author-thumb" v-if="campaign.owner != null">
                                        <img :src="campaign.owner.image_thumbnail" alt="author">
                                    </div>
                                        <a href="#" class="h3 author-name">{{ campaign.title }}</a>
                                        <div class="country">#{{ campaign.hashtag }}</div>
                                </div>
                            </div>
                            <div class="profile-section">
                                <div class="row">
                                    <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                                        <ul class="profile-menu">
                                            <li>
                                                <router-link :to="{ name: 'campaign.timeline', params: { id: campaign.id }}"
                                                    :class="checkActiveUrl('campaign.timeline')">
                                                    {{ $t('campaigns.timeline') }}
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{ name: 'campaign.photo', params: { id: campaign.id }}"
                                                    :class="checkActiveUrl('campaign.photo')">
                                                    {{ $t('campaigns.photos') }}
                                                </router-link>
                                            </li>
                                            <li>
                                                <a href="#">{{ $t('campaigns.videos') }}</a>
                                            </li>
                                            <li>
                                                <a href="#">{{ $t('campaigns.statistics') }}</a>
                                            </li>
                                            <li>
                                                <a href="#">{{ $t('events.events') }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="control-block-button">
                                    <a href="#" class="btn btn-control bg-primary">
                                        <svg class="olymp-star-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-star-icon"></use>
                                        </svg>
                                    </a>

                                    <a href="#" class="btn btn-control bg-purple">
                                        <svg class="olymp-chat---messages-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-chat---messages-icon"></use>
                                        </svg>
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    computed: {
        ...mapState('auth', {
            authenticated: state => state.authenticated,
            user: state => state.user
        }),
        ...mapState('campaign', ['campaign'])
    },
    methods : {
        checkActiveUrl(name) {
            if (this.$route.name == name) {
                return 'active'
            }

            return ''
        }
    }
}
</script>

<style lang="scss">
</style>
