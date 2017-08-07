<template>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="ui-block features-video wrap-event">
                    <div class="slider-event video-player">
                        <slider animation="fade" height="100%" :interval="300000">
                            <slider-item v-for="(i, index) in event.media" :key="index">
                                <div :style="style">
                                    <img :src="i.image_slider" class="image-event">
                                </div>
                            </slider-item>
                        </slider>
                    </div>

                    <div class="features-video-content">

                        <article class="hentry post info-event">

                            <div class="post__author author vcard inline-items user-event">
                                <img :src="event.user.image_thumbnail" alt="author">

                                <div class="author-date">
                                    <router-link :to="{ name: 'user.timeline', params: { id: event.user.id } }">
                                        <a class="h6 post__author-name fn" href="javascript.void(0)">{{ event.user.name }}</a>
                                    </router-link>
                                    <div class="post__date">
                                        <time class="published" datetime="2017-03-24T18:18">
                                            {{ timeAgo(event.created_at) }}
                                        </time>
                                    </div>
                                </div>

                                <div class="more">
                                    <svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                                    <ul class="more-dropdown">
                                        <li>
                                            <a href="#">{{ $t('events.edit-event') }}</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" @click="show()">{{ $t('events.manage_expense') }}</a>
                                        </li>
                                        <li>
                                            <a href="#">Turn Off Notifications</a>
                                        </li>
                                        <li>
                                            <a href="#">Select as Featured</a>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <p class="title-event">{{ event.title }}</p>

                            <div class="post-additional-info inline-items like-comment-event">

                                <a href="javascript:void(0)" :class="{ 'post-add-icon': true, 'inline-items': true, 'color-like': isLiked }">
                                    <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use></svg>
                                    <span>{{ numberLike }}</span>
                                </a>

                                <div class="float-comment-shared comments-shared">
                                    <a href="javascript:void(0)" class="post-add-icon inline-items">
                                        <svg class="olymp-speech-balloon-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-speech-balloon-icon"></use></svg>
                                        <span>{{ event.comments.total }}</span>
                                    </a>
                                </div>

                            </div>

                            <div class="control-block-button post-control-button">

                                <a href="javascript:void(0)" @click="likeEvent" :class=" { btn : true, 'btn-control' : true, liked : isLiked }">
                                    <svg class="olymp-like-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-like-post-icon"></use></svg>
                                </a>

                                <a href="javascript:void(0)" class="btn btn-control">
                                    <svg class="olymp-share-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-share-icon"></use></svg>
                                </a>

                            </div>

                        </article>
                        <comment
                            :comments="event.comments"
                            :model-id ="event.id"
                            :flag="model"
                            :classListComment="'list-comment-event'"
                            :classFormComment="'input-comment-event'">
                        </comment>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="ui-block">
                    <span class="ui-block-title">
                        <show-text
                            :text="event.description"
                            :show_char=500
                            :number_char_show=300
                            :show="$t('events.show_more')"
                            :hide="$t('events.show_less')">
                        </show-text>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Slider, SliderItem } from 'vue-easy-slider'
    import { mapState, mapActions } from 'vuex'
    import ShowText from '../libs/ShowText.vue'
    import Comment from '../comment/Comment.vue'

    export default {
        data :() => ({
            style: { width: '100%', height: '100%' },
            isLiked: null,
            numberLike: 0,
            model: 'event',
            showExpense: false
        }),

        computed : {
            ...mapState('event', [
                'event',
            ]),

            ...mapState('auth', {
                user: state => state.user
            }),

            checkLike() {
                this.isLiked = this.event.likes.some(item => this.user.id == item.user_id)
            }
        },

        mounted() {
            this.checkLike
            this.numberLike = this.event.likes.length
            this.setScrollBar()
            this.eventPostComment()
        },

        methods: {
            timeAgo(time) {
                return moment(time, "YYYY-MM-DD h:mm:ss").fromNow()
            },

            ...mapActions('event', [
                'like_event'
            ]),

            likeEvent() {
                this.numberLike += this.isLiked ? - 1 : 1
                this.isLiked = !this.isLiked
                this.like_event(this.$route.params.event_id)
            },

            setScrollBar() {
                $(".list-comment-event")[0].scrollTop = $(".list-comment-event")[0].scrollHeight;
            },

            eventPostComment() {
                $(".input-comment-event textarea").on('keyup', function(e) {
                    if(e.keyCode == 13) {
                        $(".list-comment-event")[0].scrollTop = $(".list-comment-event")[0].scrollHeight;
                    }
                })
            },

            show() {
                this.showExpense = true
            }
        },

        components: {
            Slider,
            SliderItem,
            ShowText,
            Comment
        }
    }
</script>

<style lang="scss">
    .wrap-event {
        z-index: 20 !important;
        margin-top: 10px;
        margin-bottom: 0px;
        align-items: initial !important;
        .slider-event {
            height: 420px !important;
            button {
                z-index: 20 !important;
            }
            .image-event {
                width: 100% !important;
                height: 100% !important;
            }
            .slider-item {
                z-index: 19 !important;
            }
        }
        .info-event {
            padding: 5px 14px !important;
            .user-event {
                padding-bottom: 5px !important;
                margin-bottom: 0px;
                padding: 0;
                .author-date {
                    width: 60%;
                }
                .more {
                    margin-right: 5px;
                }
            }
            .title-event {
                padding: 0px !important;
                margin: 2px 0 !important;
            }
            .like-comment-event {
                padding:0px!important;
                .float-comment-shared {
                    float: left !important;
                    margin-right: 10px !important;
                }
            }
            .liked {
                background-color: #ff5e3a !important;
                &:hover {
                    background-color: #9a9fbf !important;
                }
            }
        }
        .list-comment-event {
            height: 231px;
            overflow-y: scroll;
            &::-webkit-scrollbar {
                display: none;
            }
            .has-children {
                div {
                    margin: 0px;
                }
                span {
                    padding: 10px 0px;
                }
            }
        }
        .input-comment-event {
            padding: 3px 14px;
            textarea {
                min-height: 60px !important
            }
        }
    }
    .show-comment {
        max-height: 300px;
        overflow-y: scroll;
    }
    .color-like {
        fill: #ff5e3a !important;
        &:hover {
            fill: #9a9fbf !important;
        }
    }
</style>
