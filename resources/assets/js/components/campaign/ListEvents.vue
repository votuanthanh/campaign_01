<template lang="html">
    <div id="newsfeed-items-grid" v-if="events != null">
        <div class="ui-block" v-for="event in events">
            <article class="hentry post">
                <div class="post__author author vcard inline-items">
                    <img :src="event.media[0].url_file" alt="event">
                    <div class="author-date">
                        <router-link :to="{ name: 'event.index', params: { event_id: event.id }}">{{ event.title }}</router-link>
                        <div class="post__date">
                            <timeago
                                :max-time="86400 * 365"
                                class="published"
                                :since="event.created_at">
                            </timeago>
                        </div>
                    </div>

                    <div class="more"><svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                        <ul class="more-dropdown">
                            <li>
                                <a href="#">{{ $t('events.edit-event') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <show-text
                    :text="event.description"
                    :show_char=100
                    :show="$t('events.show_more')"
                    :hide="$t('events.show_less')">
                </show-text>

                <div class="post-additional-info inline-items">

                    <a href="#" class="post-add-icon inline-items">
                        <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use></svg>
                        <span v-if="event.likes">{{ event.likes.total }}</span>
                    </a>

                    <ul class="friends-harmonic">
                        <li v-for="(like, index) in event.likes.data" v-if="index <= 10">
                            <a href="#">
                                <img :src="like.user.url_file" alt="friend">
                            </a>
                        </li>
                    </ul>

                    <div class="names-people-likes">
                        <a href="#">You</a>, <a href="#">Elaine</a> and
                        <br> 34 more liked this
                    </div>

                    <div class="comments-shared">
                        <a href="javascript:void(0)" class="post-add-icon inline-items">
                            <svg class="olymp-speech-balloon-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-speech-balloon-icon"></use></svg>
                            <span v-if="event.comments != null">{{ event.comments.total }}</span>
                        </a>
                    </div>
                </div>

                <div class="control-block-button post-control-button">

                    <a href="#" class="btn btn-control">
                        <svg class="olymp-like-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-like-post-icon"></use></svg>
                    </a>

                    <a href="#" class="btn btn-control">
                        <svg class="olymp-comments-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-comments-post-icon"></use></svg>
                    </a>

                    <a href="#" class="btn btn-control">
                        <svg class="olymp-share-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-share-icon"></use></svg>
                    </a>

                </div>

            </article>

            <comment
                :comments="event.comments"
                :model-id ="event.id"
                :flag="model"
                :classListComment="''"
                :classFormComment="''">
            </comment>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import axios from 'axios'
import Comment from '../comment/Comment.vue'
import ShowText from '../libs/ShowText.vue'

export default {
    data: () => ({
        model: 'event',
        flagComments: []
    }),
    computed: {
        ...mapState('campaign', ['campaign', 'events', 'loading']),
        ...mapState('auth', {
            authenticated: state => state.authenticated,
            user: state => state.user
        })
    },
    methods: {
        //
    },
    components: {
        Comment,
        ShowText
    }
}
</script>

<style lang="scss">
</style>
