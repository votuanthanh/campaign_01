<template lang="html">
    <div>
        <like
            v-if="type == 'like'"
            :likes="likes"
            :checkLiked="checkLiked"
            :flag="flag"
            :modelId="modelId"
            :numberOfComments="numberOfComments"
            :numberOfLikes="numberOfLikes"
            :showMore="showMore"
            :modelClosed="modelClosed">
        </like>

        <like-infor
            v-if="type == 'like-infor'"
            :flag="flag"
            :modelId="modelId"
            :numberOfLikes="numberOfLikes"
            :modelClosed="modelClosed">
        </like-infor>

    </div>
</template>

<script>
    import Like from './Like.vue'
    import LikeInfor from './LikeInfor.vue'
    import { mapActions, mapState } from 'vuex'

    export default {
        data() {
            return {
                modelClosed: this.$i18n.t('messages.model_closed')
            }
        },
        created() {
            this.setLike({
                likes: this.likes,
                checkLike: this.checkLiked,
                modelId: this.modelId,
                flag: this.flag,
                numberOfLikes: this.numberOfLikes,
                numberOfComments: this.numberOfComments,
                deleteDate: this.deleteDate,
            })
        },
        props: {
            likes: {},
            modelId: 0,
            checkLiked: {},
            flag: '',
            type: '',
            numberOfLikes: 0,
            numberOfComments: 0,
            showMore: true,
            deleteDate: null,
        },
        methods: {
            ...mapActions('like', [
                'setLike',
            ]),
        },
        components: {
           Like,
           LikeInfor,
        }
    }
</script>

<style lang="scss" scoped>
</style>
