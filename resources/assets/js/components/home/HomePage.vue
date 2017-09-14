<template>
    <div v-if="!user">
        <index-page></index-page>
    </div>
    <div class="hompage container" v-else>
        <div class="row">
            <!-- Main Content -->
            <main class="col-xl-6 push-xl-3 col-lg-12 push-lg-0 col-md-12 col-sm-12 col-xs-12">
                <div v-if="listActivity.length">
                    <div id="newsfeed-items-grid">
                        <div v-for="activity in listActivity">
                            <Campaign v-if="activity.activitiable_type === 'App\\Models\\Campaign'"
                                :type="activity.name"
                                :campaign="activity.activitiable"
                                :owner="activity.user">
                            </Campaign>
                            <Event v-if="activity.activitiable_type === 'App\\Models\\Event'"
                                :event="activity.activitiable"
                                :owner="activity.user">
                            </Event>
                        </div>
                    </div>
                    <div class="div-more">
                        <span v-show="loading">
                            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            <span>{{ $t('homepage.loading') }}</span>
                        </span>
                    </div>
                </div>
                <div v-else>
                    <div class="main-header bg-events">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12 col-xs-12">
                                    <div class="main-header-content">
                                        <h1>{{ $t('homepage.welcome_web') }}</h1>
                                        <p>{{ $t('homepage.description_web') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img src="/images/account-bottom.png" alt="friends" class="img-bottom">
                    </div>
                    <div class="page-description">
                        <div class="icon">
                            <svg class="olymp-star-icon left-menu-icon">
                                <use xlink:href="/frontend/icons/icons.svg#olymp-star-icon"></use>
                            </svg>
                        </div>
                        <span>{{ $t('homepage.intro') }}</span>
                    </div>
                </div>
            </main>
            <!-- end Main Content -->

            <!-- Left Sidebar -->
            <left-sidebar></left-sidebar>
            <!-- end Left Sidebar -->

            <!-- Right Sidebar -->
             <right-sidebar></right-sidebar>
            <!-- end Right Sidebar -->
        </div>
    </div>
</template>

<script>
    import noty from '../../helpers/noty'
    import { get, post } from '../../helpers/api'
    import Campaign from './Campaign.vue'
    import Event from './Event.vue'
    import { mapState } from 'vuex'
    import IndexPage from './IndexPage.vue'
    import LeftSidebar from './LeftSidebar.vue'
    import RightSidebar from './RightSidebar.vue'
    import sideWaypoint from '../../helpers/mixin/sideWaypoint'

    export default {
        metaInfo() {
            return {
                title: this.$t('homepage.home')
            }
        },
        data: () => ({
            listActivity: [],
            loading: false,
            page: 1,
            totalPage: 1
        }),
        mixins: [sideWaypoint],
        created() {
            if (this.user) {
                this.getDataHomePage()
            }
        },
        beforeDestroy() {
            $(window).off()
        },
        computed: {
            ...mapState('auth',[
                'user',
            ])
        },
        mounted() {
            $(window).scroll(() => {
                if ($(document).height() - $(window).height() < $(window).scrollTop() + 1) {
                    this.loadActivity()
                }
            })
        },
        methods: {
            getDataHomePage() {
                get('news-feed')
                    .then(res => {
                        this.totalPage = res.data.listActivity.infoPaginate.last_page
                        this.listActivity = res.data.listActivity.data
                    })
                    .catch(err => {
                        noty({
                            text: this.$i18n.t('messages.connection_error'),
                            container: false,
                            force: true
                        })
                    })
            },
            loadActivity() {
                if (this.page < this.totalPage) {
                    this.loading = true
                    get(`news-feed?page=${++this.page}`)
                        .then(res => {
                            this.listActivity = this.listActivity.concat(res.data.listActivity.data)
                            this.loading = false
                        })
                        .catch(err => {
                            this.loading = false
                            noty({
                                text: this.$i18n.t('messages.connection_error'),
                                container: false,
                                force: true
                            })
                        })
                }
            },
            addNewActivity(data) {
                let newActivity = {
                    activitiable: data.info,
                    activitiable_type: data.type,
                    user: data.user,
                    name: data.name,
                    created_at: null,
                    deleted_at: null,
                    id: null,
                    number_of_comments: 0,
                    number_of_likes: 0,
                    updated_at: null
                }

                this.listActivity.unshift(newActivity)
            }
        },
        components: {
            Campaign,
            Event,
            LeftSidebar,
            RightSidebar,
            IndexPage
        },
        sockets: {
            createCampaignSuccess: function (data) {
                let campaign = data.data
                this.addNewActivity(campaign)
            },
            createEventSuccess: function (data) {
                let event = data.data
                this.addNewActivity(event)
            }
        }
    }

</script>

<style lang="scss" scoped>
    .hompage {
        margin-top: 20px;
        .div-more {
            padding: 20px;
            text-align: center;
            i {
                font-size: 25px;
            }
        }
    }
    .page-description {
        font-size: 14px;
    }
    .main-header {
        padding: 3px 0px 175px 0;
        max-width: 100%;
        border-radius: 5px;
        margin: 0 auto 30px;
        >img {
            margin-bottom: 15px;
        }
    }
</style>
