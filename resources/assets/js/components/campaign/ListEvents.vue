<template lang="html">
    <div id="newsfeed-items-grid" v-if="events != null">
        <div class="ui-block" v-for="event in events">
            <article class="hentry post">
                <div class="post__author author vcard inline-items">
                    <router-link :to="{ name: 'event.index', params: { event_id: event.id }}">
                        <img :src="event.media[0].image_thumbnail" :alt="event.name">
                    </router-link>
                    <div class="author-date">
                        <router-link
                            :to="{ name: 'event.index', params: { event_id: event.id }}">
                            {{ event.title }}
                        </router-link>

                        <div class="post__date">
                            <timeago
                                :max-time="86400 * 365"
                                class="published date-format"
                                :since="event.created_at">
                            </timeago>
                        </div>
                    </div>

                    <div class="more"><svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                        <ul class="more-dropdown">
                            <li>
                                <a href="javascript:void(0)">{{ $t('events.edit-event') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <show-text
                    :type="false"
                    :text="event.description"
                    :show_char=100
                    :show="$t('events.show_more')"
                    :hide="$t('events.show_less')"
                    :number_char_show=70>
                </show-text>
                 <like :type="'likeInfo'"
                        :checkLike="event.checkLike"
                        :likes="event.likes"
                        :model="'event'"
                        :modelId="event.id"
                        :numberOfComments="event.comments.total"
                    ></like>

                <div class="control-block-button post-control-button">

                    <a href="javascript:void(0)" class="btn btn-control">
                        <svg class="olymp-like-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-like-post-icon"></use></svg>
                    </a>

                    <a href="javascript:void(0)" class="btn btn-control">
                        <svg class="olymp-comments-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-comments-post-icon"></use></svg>
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
import Like from '../user/timeline/Like.vue'

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
        ShowText,
        Like
    }
}
</script>

<style lang="scss">
    .date-format {
        font-size: 13px;
        color: #bbb;
    }
</style>
