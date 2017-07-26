<template>
    <article class="hentry post">
        <div class="post__author author vcard inline-items">
            <img :src="currentPageUser.url_file" alt="author" class="image-auth">
            <div class="author-date">
                <a class="h6 post__author-name fn" href="javascript:void(0)">
                    {{ currentPageUser.name }}
                </a>
                <span class="title-action"> {{ $t('post.created_a_event') }} </span>
                <span class="name-action">"{{ event.title }}"</span>
                <div class="post__date">
                    <time class="published" datetime="2017-03-24T18:18">
                        {{ timeAgo(event.created_at) }}
                    </time>
                </div>
            </div>
            <div class="article-more more">
                <svg class="olymp-three-dots-icon">
                    <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                </svg>
                <ul class="more-dropdown">
                    <li>
                        <a href="#">Edit Post</a>
                    </li>
                    <li>
                        <a href="#">Delete Post</a>
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
        <p v-html="eventDetail.description"></p>

        <like :type="'likeInfo'"
            :checkLike="eventDetail.checkLike"
            :numberOfComments ="eventDetail.comments.total"
            :likes="eventDetail.likes"
            :model="'event'"
            :modelId="eventDetail.id"
        ></like>
        <div class="control-block-button post-control-button">
            <like :type="'likePost'"
                :checkLike="eventDetail.checkLike"
                :likes="eventDetail.likes"
                :model="'event'"
                :modelId="eventDetail.id"
            ></like>
            <a href="#" class="btn btn-control">
                <svg class="olymp-comments-post-icon">
                    <use xlink:href="/frontend/icons/icons.svg#olymp-comments-post-icon"></use>
                </svg>
            </a>
            <a href="#" class="btn btn-control">
                <svg class="olymp-share-icon">
                    <use xlink:href="/frontend/icons/icons.svg#olymp-share-icon"></use>
                </svg>
            </a>
        </div>
        <comment
            :comments="event.comments"
            :model-id ="event.id"
            :classListComment="''"
            :classFormComment="''"
            :flag="'event'"
        ></comment>
    </article>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import Like from './Like.vue'
import Comment from '../../comment/Comment.vue'

export default {
    created() {
        this.setDetailEvent(this.event)
   },
    props: ['event'],
    components: {
        Like,
        Comment
    },
    computed: {
        ...mapState('user', {
            currentPageUser: state => state.currentPageUser,
        }),
        ...mapState('event', ['detailEvent']),
        eventDetail() {
            return this.detailEvent[this.event.id]
        }
    },
    methods: {
        ...mapActions('event', ['setDetailEvent']),

        timeAgo(time) {
            return moment(time, "YYYY-MM-DD h:mm:ss").fromNow()
        },
    }
}
</script>
