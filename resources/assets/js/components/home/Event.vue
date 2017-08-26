<template>
    <div class="ui-block">
        <article class="hentry post has-post-thumbnail">
            <div class="post__author author vcard inline-items">
                <img src="/images/avatar5-sm.jpg" alt="author">
                <div class="author-date">
                    <router-link class="h6 post__author-name fn" :to="{ name: 'user.timeline', params: { slug: owner.slug }}">
                        {{ owner.name }}
                    </router-link>
                    {{ $t('homepage.create_a') }}

                    <router-link :to="{ name: 'event.index', params: { slug: event.slug }}">
                        {{ $t('homepage.event') }}
                        <b class="title-event">"{{ event.title }}"</b>
                    </router-link>

                    <div class="post__date">
                        <time class="published">
                            {{ event.timeAgo }}
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
            <p v-if="event.title.length < 851">{{ event.description }}</p>
            <p v-else>{{ event.description.substr(0, 850) }} ...</p>
            <div class="post-thumb">
                <img v-if="event.media.length" :src="event.media[0].image_medium" alt="photo">
            </div>
            <div class="post-additional-info inline-items" style="display: none;">
                <a href="#" class="post-add-icon inline-items">
                    <svg class="olymp-heart-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use>
                    </svg>
                    <span>49</span>
                </a>
                <ul class="friends-harmonic">
                    <li>
                        <a href="#"><img src="/images/friend-harmonic9.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#"><img src="/images/friend-harmonic10.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#"><img src="/images/friend-harmonic7.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#"><img src="/images/friend-harmonic8.jpg" alt="friend">
                        </a>
                    </li>
                    <li>
                        <a href="#"><img src="/images/friend-harmonic11.jpg" alt="friend">
                        </a>
                    </li>
                </ul>
                <div class="names-people-likes"><a href="#">Jimmy</a>, <a href="#">Andrea</a> and
                    <br>47 more liked this
                </div>
                <div class="comments-shared">
                    <a href="#" class="post-add-icon inline-items">
                        <svg class="olymp-speech-balloon-icon">
                            <use xlink:href="/frontend/icons/icons.svg#olymp-speech-balloon-icon"></use>
                        </svg>
                        <span>264</span>
                    </a>
                    <a href="#" class="post-add-icon inline-items">
                        <svg class="olymp-share-icon">
                            <use xlink:href="/frontend/icons/icons.svg#olymp-share-icon"></use>
                        </svg>
                        <span>37</span>
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
            event: {},
            owner: {}
        }
    }
</script>

<style lang="scss" scoped>
    .author-date {
        width: 90%;
    }
    .post-thumb {
        margin-top: 10px;
    }
    .title-event {
        color: #616373;
        &:hover {
            color: #fe5d39;
        }
    }
</style>
