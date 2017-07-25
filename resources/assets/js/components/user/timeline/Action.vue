<template>
   <div>
        <article class="hentry post">
            <div class="post__author author vcard inline-items">
                <img :src="currentPageUser.url_file" alt="author" class="image-auth">
                <div class="author-date">
                    <a class="h6 post__author-name fn" href="javascript:void(0)">{{ currentPageUser.name }}</a>
                    <span class="title-action"> {{ $t('post.created_a_post') }} </span>
                    <div class="post__date">
                        <time class="published">
                            {{ timeAgo(actionDetail.created_at) }}
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
            <p>{{ action.description }}</p>
            <like :type="'likeInfo'"
                :checkLike="actionDetail.checkLike"
                :numberOfComments ="actionDetail.comments.total"
                :likes="actionDetail.likes"
                :model="'action'"
                :modelId="actionDetail.id"
            ></like>
            <div class="control-block-button post-control-button">
                <like :likes="actionDetail.likes"
                    :checkLike="actionDetail.checkLike"
                    :model="'action'"
                    :type="'likePost'"
                    :modelId="actionDetail.id"
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
                :comments="actionDetail.comments"
                :model-id="actionDetail.id"
                :classListComment="''"
                :classFormComment="''"
                :flag="'action'"
            ></comment>
        </article>
   </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import Like from './Like.vue'
import Comment from '../../comment/Comment.vue'

export default {
    created() {
        this.setDetailAction(this.action)
   },
    props: ['action'],
    components: {
        Like,
        Comment
    },
    computed: {
        ...mapState('user', {
            currentPageUser: state => state.currentPageUser,
        }),
        ...mapState('action', ['detailAction']),
        actionDetail() {
            return this.detailAction[this.action.id]
        }
    },
    methods: {
        ...mapActions('action', ['setDetailAction']),

        timeAgo(time) {
            return moment(time, "YYYY-MM-DD h:mm:ss").fromNow()
        },
    }
}
</script>
