<template lang="html">
    <div class="col-xl-3 pull-xl-9 col-lg-3 pull-lg-9 col-md-12 col-sm-12 col-xs-12">
        <div class="ui-block">
            <div class="your-profile">
                <div class="ui-block-title-small ui-block-title">
                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                    <h6 class="">{{ $t('campaigns.campaign-management') }}</h6>
                </div>

                <div :class="`ui-block-title ${checkActiveUrl('campaign.list_member')}`" v-if="checkOwner || checkAdmin">
                    <router-link
                        :to="{ name: 'campaign.list_member', params: { slug: campaign.slug }}"
                        class="h6 title">
                        {{ $t('campaigns.list-members') }}
                    </router-link>
                </div>

                <div :class="`ui-block-title ${checkActiveUrl('campaign.member_request')}`" v-if="!campaign.deleted_at">
                    <router-link
                        :to="{ name: 'campaign.member_request', params: { slug: campaign.slug }}"
                        class="h6 title">
                        {{ $t('campaigns.member-request') }}
                    </router-link>
                </div>

                <div :class="`ui-block-title ${checkActiveUrl('campaign.update')}`" v-if="(checkOwner || checkAdmin) && !campaign.deleted_at">
                    <router-link
                        :to="{ name: 'campaign.update', params: { slug: campaign.slug }}"
                        class="h6 title">
                        {{ $t('campaigns.update_campaign') }}
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState, mapGetters } from 'vuex'

    export default {
        computed: {
            ...mapState('campaign', [
                'campaign'
            ]),
            ...mapState('auth', [
                'checkAdmin',
                'user'
            ]),
            ...mapGetters('campaign', [
                'checkPermission',
                'checkOwner'
            ]),
        },
        methods: {
            checkActiveUrl(name) {
                if (this.$route.name == name) {
                    return 'active'
                }

                return ''
            }
        }
    }
</script>

<style lang="scss" scoped>
    .your-profile {
        padding-bottom: 20px;
        .ui-block-title-small {
            padding-bottom: 10px !important;
            .fa-list-alt {
                font-size: 30px;
                color: #747685;
                margin-right: -5px;
            }
        }
        .ui-block-title {
            padding: 17px 10px 0px 25px;
            a {
                padding-bottom: 12px;
            }
        }
        .active {
            background-color: #f8f8f8;
        }
    }
</style>
