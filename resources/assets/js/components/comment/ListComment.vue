<template lang="html">
    <div v-if="comments[modelId] != null " :class="classListComment">
        <a ref="loadmore" href="javascript:void(0)" class="btn btn-control btn-more" data-container="newsfeed-items-grid" v-show="loading == modelId">
            <i class="fa fa-spinner fa-spin"></i>
            <div class="ripple-container"></div>
        </a>
        <a href="javascript:void(0)" class="more-comments" v-show="paginates[modelId].total > comments[modelId].length"
            @click="handelLoadMoreParentComment({
                modelId: modelId,
                pageCurrent: paginates[modelId].page_current,
                lastPage: paginates[modelId].last_page
            })">
             {{ $t('campaigns.more-comment') }}
             <span>+</span>
        </a>
        <ul class="comments-list">
            <li v-for="(comment, index) in comments[modelId]" class="has-children comment">
                <div class="post__author author vcard inline-items" v-if="comment.user != null">
                    <img :src="comment.user.url_file" alt="author">

                    <div class="author-date">
                        <a class="h6 post__author-name fn" href="#"> {{ comment.user.name }}</a>
                        <div class="post__date">
                            <timeago
                                :max-time="86400 * 365"
                                class="published"
                                :since="comment.created_at">
                            </timeago>
                        </div>
                    </div>

                    <div class="more" v-if="comment.user.id == user.id">
                        <svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                        <ul class="more-dropdown" >
                            <li>
                                <a href="javascript:void(0)" @click="editComments(comment, index)">{{ $t('form.edit') }}</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)"
                                    @click="deleteComment({
                                        commentId: comment.id,
                                        modelId: modelId,
                                        commentParentId: 0 })">
                                    {{ $t('form.delete') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <show-text
                    :text="comment.content"
                    :show_char=100
                    :show="$t('events.show_more')"
                    :hide="$t('events.show_less')"
                    v-if="flagEdit != comment.id">
                </show-text>
                <form-comment-edit
                    :parentComment="comment"
                    v-if="flagEdit == comment.id"
                    :flagEdit="flagEdit"
                    :classFormComment="''"
                    @changeFlagEdit="changeFlagEdit">
                </form-comment-edit>

                <a href="#" class="post-add-icon inline-items">
                    <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use></svg>
                    <span v-if="comment.likes != null">{{ comment.likes.total }}</span>
                </a>

                <a href="javascript:void(0)" @click="showSubComment(comment, index)" class="reply">{{ $t('campaigns.reply') }}</a>

                <a href="javascript:void(0)"
                    @click="showSubComment(comment, index)"
                    v-if ="comment.sub_comment"
                    class="reply">
                    <span>
                        {{ comment.sub_comment.total }}
                        {{ $t('form.answers') }}
                    </span>
                </a>

                <a href="javascript:void(0)"
                    @click="hideSubComment()"
                    class="reply"
                    v-if="flagReply == comment.id">
                {{ $t('form.hidden') }}</a>

                <ul class="children" v-if ="comment.sub_comment" >
                    <li v-show="loading == comment.id">
                        <a ref="loadmore" href="javascript:void(0)" class="btn btn-control btn-more" data-container="newsfeed-items-grid" >
                            <i class="fa fa-spinner fa-spin"></i>
                            <div class="ripple-container"></div>
                        </a>
                    </li>
                    <li v-if="comment.sub_comment.data.length < comment.sub_comment.total && flagReply == comment.id">
                        <a href="javascript:void(0)" class="more-comments"
                            @click="handelLoadMoreSubComment({
                                commentParentId: comment.id,
                                modelId: modelId,
                                pageCurrent: comment.sub_comment.current_page,
                                lastPage: comment.sub_comment.last_page
                            })" >
                            {{ $t('campaigns.more-comment') }}
                            <span>+</span>
                        </a>
                    </li>
                    <li v-for="subComment in comment.sub_comment.data" v-if="flagReply == comment.id">
                        <div class="post__author author vcard inline-items">
                            <img :src="subComment.user.url_file" alt="author">

                            <div class="author-date">
                                <a class="h6 post__author-name fn" href="#">{{ subComment.user.name }}</a>
                                <div class="post__date">
                                    <timeago
                                        :max-time="86400 * 365"
                                        class="published"
                                        :since="subComment.created_at">
                                    </timeago>
                                </div>
                            </div>

                            <div class="more" v-if="subComment.user.id == user.id">
                                <svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                                <ul class="more-dropdown" >
                                    <li>
                                        <a href="javascript:void(0)" @click="editComments(subComment, index)">{{ $t('form.edit') }}</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"
                                            @click="deleteComment({
                                                commentId: subComment.id,
                                                modelId: modelId,
                                                commentParentId: subComment.parent_id })">
                                            {{ $t('form.delete') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <show-text
                            :text="subComment.content"
                            :show_char=100
                            :show="$t('events.show_more')"
                            :hide="$t('events.show_less')"
                            v-if="flagEdit != subComment.id">
                        </show-text>

                        <form-comment-edit
                            :parentComment="subComment"
                            v-if="flagEdit == subComment.id"
                            :flagEdit="flagEdit"
                            :classFormComment="''"
                            @changeFlagEdit="changeFlagEdit">
                        </form-comment-edit>

                        <a href="#" class="post-add-icon inline-items">
                            <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use></svg>
                            <span v-if="subComment.likes != null">{{ subComment.likes.length }}</span>
                        </a>
                    </li>
                </ul>

                <form-comment
                    :model-id="modelId"
                    :comment-parent-id="comment.id"
                    :flag="flag"
                    :classFormComment="''"
                    v-if="flagReply == comment.id">
                </form-comment>
            </li>
        </ul>

    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import FormComment from './FormComment.vue'
import FormCommentEdit from './FormCommentEdit.vue'
import ShowText from '../libs/ShowText.vue'

export default {
    data: () => ({
        flagEdit: '',
        flagReply: '',
        loading: ''
    }),
    props: [
        'modelId',
        'flag',
        'classListComment'
    ],
    computed: {
        ...mapState('comment', ['comments', 'paginates']),
        ...mapState('auth', {
            authenticated: state => state.authenticated,
            user: state => state.user
        })
    },
    methods: {
        ...mapActions('comment', [
            'commentDetail',
            'editComment',
            'deleteComment',
            'loadMoreParentComment',
            'loadMoreSubComment'
        ]),
        showSubComment(comment, index) {
            this.flagReply = comment.id
        },
        editComments(comment, index) {
            this.flagEdit = comment.id
        },
        changeFlagEdit() {
            this.flagEdit = ''
        },
        hideSubComment() {
            this.flagReply = ''
        },
        handelLoadMoreParentComment(data) {
            this.$Progress.start()
            this.loading = data.modelId

            this.loadMoreParentComment(data)
                .then(res => {
                    this.$Progress.finish()
                    this.loading = ''
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.loading = ''
                })
        },
        handelLoadMoreSubComment(data) {
            this.$Progress.start()
            this.loading = data.commentParentId

            this.loadMoreSubComment(data)
                .then(res => {
                    this.$Progress.finish()
                    this.loading = ''
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.loading = ''
                })
        }
    },
    components: {
        FormComment,
        FormCommentEdit,
        ShowText
    }
}
</script>

<style lang="scss">
</style>
