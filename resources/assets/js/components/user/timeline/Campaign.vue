<template>
    <article class="hentry post has-post-thumbnail thumb-full-width">
        <div class="post__author author vcard inline-items">
            <img :src="currentPageUser.url_file" alt="author" class="image-auth">
            <div class="author-date">
                <a class="h6 post__author-name fn" href="javascript:void(0)">{{ currentPageUser.name }}</a>
                <span v-if="type == 'create'">{{ $t('post.campaign.created_a') }}</span>
                <span v-else>{{ $t('post.campaign.joined') }}</span>
                <a href="#">{{ $t('post.campaign.new_campaign') }}</a>
                <div class="post__date">
                    <time class="published">
                        {{ campaignDetail.created_at }}
                    </time>
                </div>
            </div>
            <div class="more">
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
        <div class="post-thumb">
            <img :src="campaignDetail.media[0].image_medium" alt="photo">
        </div>
        <router-link data-toggle="modal" data-target="#blog-post-popup" class="h2 post-title"
            :to="{ name: 'campaign.timeline', params: { slug: campaignDetail.slug }}">{{ campaignDetail.title }}</router-link>
        <p v-html="campaignDetail.description"></p>
        <a href="#" style="display: none;"
            data-toggle="modal"
            data-target="#blog-post-popup"
            class="btn btn-md-2 btn-border-think c-grey btn-transparent custom-color">
            {{ $t('post.read_more') }}
        </a>
        <like :type="'likeInfo'"
            :checkLike="campaignDetail.checkLike"
            :likes="campaignDetail.likes"
            :model="'campaign'"
            :modelId="campaignDetail.id"
        ></like>
        <div class="control-block-button post-control-button">
            <like :likes="campaignDetail.likes"
                :checkLike="campaignDetail.checkLike"
                :model="'campaign'"
                :type="'likePost'"
                :modelId="campaignDetail.id"
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
    </article>
</template>
<script>
   import { mapState, mapActions } from 'vuex'
   import Like from './Like.vue'

   export default {
        created() {
            this.setDetailcampaign(this.campaign)
        },
        props: {
            campaign: {},
            type: ''
        },
        components: {
            Like
        },
        computed: {
            ...mapState('user', {
                currentPageUser: state => state.currentPageUser,
            }),
            ...mapState('campaign', ['detailCampaign']),
            campaignDetail () {
                return this.detailCampaign[this.campaign.id]
            }
        },
        methods: {
            ...mapActions('campaign', ['setDetailcampaign']),
        }
    }
</script>
