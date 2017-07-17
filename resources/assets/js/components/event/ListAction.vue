<template>
    <div class="container">
        <div class="load-search" v-if="load_search"></div>
        <div class="empty center-block" v-if="isEmpty">
            <h2>
                {{ $t('events.not_found_action') }}
            </h2>
        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-xs-12" v-for="action in actions.data">
                <div class="ui-block video-item">
                    <div class="video-player">
                        <slider animation="fade" :height="height" :interval="100000">
                            <slider-item v-for="(i, index) in action.media" :key="index" :auto='false'>
                                <div :style="style">
                                    <img :src="i.image_medium">
                                </div>
                            </slider-item>
                        </slider>
                        <div class="more">
                            <svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                            <ul class="more-dropdown more-with-triangle">
                                <a href="javascript:void(0)" class="post-add-icon inline-items">
                                </a>
                            </ul>
                        </div>
                    </div>

                    <div class="ui-block-content video-content info-action">
                        <router-link :to="{ name: 'user.timeline', params: { id: action.user.id } }">
                            <a href="javascript:void(0)" class="h6 title">{{ action.user.name }}</a>
                        </router-link>
                        &nbsp
                        <time class="published">
                            {{ timeAgo(action.created_at) }}
                            <a href="javascript:void(0)" class="post-add-icon inline-items">
                                &nbsp
                                <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-speech-balloon-icon"></use></svg>
                                <span>{{ action.comments.length }}</span>
                            </a>
                            <a href="javascript:void(0)" class="post-add-icon inline-items">
                                &nbsp
                                <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use></svg>
                                <span>{{ action.likes.length }}</span>
                            </a>
                        </time>
                    </div>
                </div>
            </div>
        </div>
        <div class="row loading-more" v-if="load_paginate"></div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import { Slider, SliderItem } from 'vue-easy-slider'
    export default {
        data: () => ({
            style: { width: '100%', height: '100%' },
            height: '200px'
        }),

        computed: {
            ...mapState('event', [
                'actions',
                'flag_search',
                'key_search',
                'load_search',
                'load_paginate'
            ]),

            isEmpty() {
                return !this.actions.data.length
            }
        },

        mounted() {
            $(window).scroll(() => {
                if ($(document).height() - $(window).height() < $(window).scrollTop() + 1) {
                    this.load_action({
                        event_id: this.$route.params.event_id,
                        actions: this.actions,
                        flag_search: this.flag_search,
                        key: this.key_search
                    })
                }
            })
        },

        methods: {
            timeAgo(time) {
                return moment(time, "YYYY-MM-DD h:mm:ss").fromNow()
            },
            ...mapActions('event', [
                'load_action'
            ])
        },

        components : {
            Slider,
            SliderItem
        }
    }
</script>

<style lang="scss">
    .info-action {
        padding: 5px;
        text-align: center;
    }
    .more {
        z-index: 20 !important;
    }
    .load-search {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 999;
        background: url(/images/loading.gif) 50% 10% no-repeat rgba(235, 242, 242, 0.66);
        opacity: .6;
        background-size: 120px 120px;
    }
    .loading-more {
        background: url(/images/loading.gif) 50% 10% no-repeat rgba(235, 242, 242, 0.66);
        opacity: 1;
        background-size: 40px 40px;
        width: 100%;
        height: 60px;
    }
    .empty {
        z-index: 999;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        h2 {
            color: #c2c5d9;
            text-align: center;
            margin-top: 50px ;
        }
    }
</style>
