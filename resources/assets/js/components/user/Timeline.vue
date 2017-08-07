<template>
    <div class="container">
        <div class="row">

            <!-- Main Content -->
            <div class="col-xl-6 push-xl-3 col-lg-12 push-lg-0 col-md-12 col-sm-12 col-xs-12">
                <div id="newsfeed-items-grid">
                    <div class="ui-block" v-for="(activity, index) in listActivity.data">

                        <Campaign v-if="activity.activitiable_type === 'App\\Models\\Campaign'" :type="activity.name" :campaign="activity.activitiable"></Campaign>
                        <Event v-if="activity.activitiable_type === 'App\\Models\\Event'" :event="activity.activitiable"></Event>
                        <Action v-if="activity.activitiable_type === 'App\\Models\\Action'" :action="activity.activitiable"></Action>
                    </div>
                </div>
                <a href="javascript:void(0)" class="btn btn-control btn-more" data-load-link="items-to-load.html" data-container="newsfeed-items-grid">
                    <i class="fa fa-spinner fa-pulse fa-spin fa-5x" v-if="loading"></i>
                </a>
            </div>
            <!-- end Main Content -->

            <!-- Left Sidebar -->
            <Left-sidebar></Left-sidebar>
            <!-- end Left Sidebar -->

            <!-- Right Sidebar -->
            <Right-sidebar></Right-sidebar>
            <!-- end Right Sidebar -->

        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { get, post, del } from '../../helpers/api'
    import noty from '../../helpers/noty'
    import LeftSidebar from './timeline/Left-sidebar.vue'
    import RightSidebar from './timeline/Right-sidebar.vue'
    import Campaign from './timeline/Campaign.vue'
    import Event from './timeline/Event.vue'
    import Action from './timeline/Action.vue'


    export default {
        components: {
            LeftSidebar,
            RightSidebar,
            Campaign,
            Event,
            Action
        },
        computed: {
            ...mapState('user', ['listActivity', 'loading']),
            ...mapState('auth', {
                user: state => state.user
            })
        },
        mounted() {
            $(window).scroll(() => {
                if ($(document).height() - $(window).height() < $(window).scrollTop() + 1) {
                    this.loadMore({ id: this.$route.params.id, infoPaginate: this.listActivity })
                }
            })
        },

        methods: {
            ...mapActions('user', ['loadMore']),

            timeAgo(time) {
                return moment(time, "YYYY-MM-DD h:mm:ss").fromNow()
            },
        }
    }
</script>

<style lang="scss">
    .h6 .post__author-name .fn {
        font-size: 16px !important;
    }

    .title-action {
        font-size: 17px !important;
    }

    .name-action {
        font-size: 17px !important;
        font-weight: bold !important;
    }

    .img-auth, .article-more {
        width: 3%;
    }

    .author-date {
        width: 80%;
    }

    .post-additional-info {
        border-bottom: 1px solid #e6ecf5;
        padding-bottom: 15px;
    }

    .post__author {
        margin-bottom: 10px;
    }

    .post__date {
        margin-top: 5px;
    }
</style>
