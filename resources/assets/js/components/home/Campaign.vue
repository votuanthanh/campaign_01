<template>
    <div class="ui-block">
        <article class="hentry post video">
            <div class="post__author author vcard inline-items">
                <img :src="owner.image_thumbnail" alt="author">
                <div class="author-date">
                    <router-link class="h6 post__author-name fn" :to="{ name: 'user.timeline', params: { slug: owner.slug }}">
                        {{ owner.name }}
                    </router-link>
                    {{ $t('homepage.create_a') }}
                    <router-link :to="{ name: 'campaign.timeline', params: { slug: campaign.slug }}">
                        {{ $t('homepage.new_campaign') }}
                    </router-link>
                    <div class="post__date">
                        <time class="published">
                            {{ timeAgo(campaign.created_at) }}
                        </time>
                    </div>
                </div>
                <div class="more" style="display: none;">
                    <svg class="olymp-three-dots-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                    <ul class="more-dropdown">
                        <li>
                            <a href="javascript:void(0)">Edit Post</a>
                        </li>
                    </ul>
                </div>
            </div>
            <article class="hentry blog-post blog-post-v3">
                <div class="post-thumb">
                    <img :src="campaign.media[0].image_medium" alt="photo">
                    <p class="post-category">
                        <span class="span-tags" v-for="tag in campaign.tags" v-if="campaign.tags">{{ tag.name }}</span>
                    </p>
                </div>
                <div class="post-content">
                    <router-link class="h3 post-title" :to="{ name: 'campaign.timeline', params: { slug: campaign.slug }}">
                        <span v-if="campaign.title.length < 51">{{ campaign.title }}</span>
                        <span v-else>{{ campaign.title.substr(0, 50) }}...</span>
                    </router-link>
                    <p v-if="campaign.description.length < 141" v-html="campaign.description"></p>
                    <p v-else v-html="campaign.description.substr(0, 140) + '...'"></p>
                </div>
            </article>
            <div class="post-additional-info inline-items" style="display: none;">
                <a href="#" class="post-add-icon inline-items">
                    <svg class="olymp-heart-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use>
                    </svg>
                    <span>15</span>
                </a>
                <ul class="friends-harmonic">
                    <li>
                        <a href="#">
                            <img src="/images/friend-harmonic9.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="/images/friend-harmonic10.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="/images/friend-harmonic7.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="/images/friend-harmonic8.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <img src="/images/friend-harmonic11.jpg" alt="friend">
                        </a>
                    </li>
                </ul>
                <div class="names-people-likes">
                    <a href="#">Jenny</a>, <a href="#">Robert</a> and
                    <br>13 more liked this
                </div>
                <div class="comments-shared">
                    <a href="#" class="post-add-icon inline-items">
                        <svg class="olymp-speech-balloon-icon">
                            <use xlink:href="/frontend/icons/icons.svg#olymp-speech-balloon-icon"></use>
                        </svg>
                        <span>1</span>
                    </a>
                    <a href="#" class="post-add-icon inline-items">
                        <svg class="olymp-share-icon">
                            <use xlink:href="/frontend/icons/icons.svg#olymp-share-icon"></use>
                        </svg>
                        <span>16</span>
                    </a>
                </div>
            </div>
            <div class="control-block-button post-control-button" style="display: none;">
                <a href="#" class="btn btn-control">
                    <svg class="olymp-like-post-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-like-post-icon"></use>
                    </svg>
                </a>
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
    </div>
</template>
<script>
   import { mapState, mapActions } from 'vuex'

   export default {
        props: {
            campaign: {},
            owner: {}
        },
        methods: {
            timeAgo(time) {
                return moment(time, "YYYY-MM-DD h:mm:ss").fromNow()
            }
        }
    }
</script>
<style lang="scss" scoped>
    .blog-post-v3 {
        .post-content {
            width: 50%;
            padding: 0px 10px 0px 30px;
        }
        .post-category {
            padding: 0px;
        }
        .span-tags {
            display: inline-block;
            background: #00b7ff;
            padding: 5px;
            border-radius: 3px;
            margin: 0px 1px 3px 1px;
            &:first-child {
                border-radius: 0px 3px 3px 0px;
            }
        }
    }
    .author-date {
        font-size: 14px;
        .published {
            font-size: 13px;
        }
    }
</style>
