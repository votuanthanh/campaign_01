<template lang="html">
    <form class="comment-form inline-items" @submit.prevent="addComment" :class="classFormComment">
        <div class="post__author author vcard inline-items">
            <img :src="user.url_file" :alt="user.name">
        </div>
        <div class="form-group with-icon-right">
            <textarea
                class="form-control"
                name="content"
                :placeholder="$t('campaigns.write-comment')"
                v-model="comment.content"
                v-validate="'required'"
                @keydown="addComments">
            </textarea>
        </div>

    </form>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import axios from 'axios'
import noty from '../../helpers/noty'

export default {
    data: () => ({
       comment: { content: '' },
       flagAction: 'create'
    }),
    props: [
        'modelId',
        'commentParentId',
        'flag',
        'classFormComment'
    ],
    computed: {
        ...mapState('auth', {
            authenticated: state => state.authenticated,
            user: state => state.user
        }),
    },
    methods: {
        ...mapActions('comment', ['addComment']),
        addComments(e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                if (this.comment.content !== '') {
                    this.addComment({
                        modelId: this.modelId,
                        commentParentId: this.commentParentId,
                        flag: this.flag,
                        comment: this.comment,
                        flagAction: this.flagAction
                    })
                    .then(status => {
                        if (status) {
                            this.comment.content = ''
                        }
                    })
                    .catch(err => {
                        //
                    })
                } else {
                    const message = this.$i18n.t('messages.comment_not_empty')
                    noty({ text: message, force: true, container: false })
                }
            }
        }
    },
    components: {
        //
    }
}
</script>

<style lang="scss">
</style>
