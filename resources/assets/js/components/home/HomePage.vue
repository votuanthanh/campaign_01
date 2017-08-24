<template>
    <div class="hompage container">
        <div class="row">
            <!-- Main Content -->
            <main class="col-xl-6 push-xl-3 col-lg-12 push-lg-0 col-md-12 col-sm-12 col-xs-12">
                <div id="newsfeed-items-grid">
                    <div v-for="(activity, index) in listActivity">
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
    import LeftSidebar from './LeftSidebar.vue'
    import RightSidebar from './RightSidebar.vue'

    export default {
        data: () => ({
            listActivity: [],
            loading: false,
            page: 1,
            totalPage: 1
        }),
        created() {
            this.getDataHomePage()
        },
        beforeDestroy() {
            $(window).off()
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
                get('homepage')
                    .then(res => {
                        this.totalPage = res.data.listActivity.last_page
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
                    get(`homepage?page=${++this.page}`)
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
            }
        },
        components: {
            Campaign,
            Event,
            LeftSidebar,
            RightSidebar
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
</style>
