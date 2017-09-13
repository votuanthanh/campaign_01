<template>
    <aside class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="right-home-box">
            <div class="ui-block" style="display: none;">
                <div class="widget w-birthday-alert">
                    <div class="icons-block">
                        <svg class="olymp-cupcake-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-cupcake-icon"></use></svg>
                        <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg></a>
                    </div>
                    <div class="content">
                        <div class="author-thumb">
                            <img src="/images/avatar48-sm.jpg" alt="author">
                        </div>
                        <span>Today is</span>
                        <a href="#" class="h4 title">Marina Valentine’s Birthday!</a>
                        <p>Leave her a message with your best wishes on her profile page!</p>
                    </div>
                </div>
            </div>
            <div class="ui-block" v-if="listUser.length">
                <div class="ui-block-title">
                    <h6 class="title">{{ $t('homepage.friend_suggestions') }}</h6>
                </div>
                <ul class="widget w-friend-pages-added notification-list friend-requests">
                    <li class="inline-items" v-for="(user, index) in listUser">
                        <div class="author-thumb">
                            <img :src="user.image_thumbnail" alt="author">
                        </div>
                        <div class="notification-event">
                            <router-link class="h6 otification-friend" :to="{ name: 'user.timeline', params: { slug: user.slug }}">
                                {{ user.name }}
                            </router-link>
                            <span class="chat-message-item">
                                {{ numberMutualFriend[index] + ' ' + $t('homepage.friends') + ' ' + $t('homepage.in_common') }}
                            </span>
                        </div>
                        <request-friend :friend="user" :classTemp="'home'"></request-friend>
                    </li>
                </ul>
            </div>
            <div class="ui-block" style="display: none;">
                <div class="ui-block-title">
                    <h6 class="title">Activity Feed</h6>
                    <a href="#" class="more">
                        <svg class="olymp-three-dots-icon">
                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                        </svg>
                    </a>
                </div>
                <ul class="widget w-activity-feed notification-list">
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar49-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Marina Polson</a> commented on Jason Mark’s <a href="#" class="notification-link">photo.</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">2 mins ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar9-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Jake Parker </a> liked Nicholas Grissom’s <a href="#" class="notification-link">status update.</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">5 mins ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar50-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Mary Jane Stark </a> added 20 new photos to her <a href="#" class="notification-link">gallery album.</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">12 mins ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar51-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Nicholas Grissom </a> updated his profile <a href="#" class="notification-link">photo</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">1 hour ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar48-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Marina Valentine </a> commented on Chris Greyson’s <a href="#" class="notification-link">status update</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">1 hour ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar52-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Green Goo Rock </a> posted a <a href="#" class="notification-link">status update</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">1 hour ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar10-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Elaine Dreyfuss  </a> liked your <a href="#" class="notification-link">blog post</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">2 hours ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar10-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Elaine Dreyfuss </a> commented on your <a href="#" class="notification-link">blog post</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">2 hours ago</time></span>
                        </div>
                    </li>
                    <li>
                        <div class="author-thumb">
                            <img src="/images/avatar53-sm.jpg" alt="author">
                        </div>
                        <div class="notification-event">
                            <a href="#" class="h6 notification-friend">Bruce Peterson </a> changed his <a href="#" class="notification-link">profile picture</a>.
                            <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">15 hours ago</time></span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="ui-block" style="display: none;">
            <div class="widget w-action">
                <img src="/images/logo.png" alt="Olympus">
                <div class="content">
                    <h4 class="title">OLYMPUS</h4>
                    <span>THE BEST SOCIAL NETWORK THEME IS HERE!</span>
                    <a href="01-LandingPage.html" class="btn btn-bg-secondary btn-md">Register Now!</a>
                </div>
            </div>
        </div>
        </div>
    </aside>
</template>

<script>
    import noty from '../../helpers/noty'
    import { get } from '../../helpers/api'
    import RequestFriend from '../user/RequestFriend.vue'

    export default {
        data: () => ({
            listUser: [],
            numberMutualFriend: []
        }),
        created() {
            this.getFriendSuggest()
        },
        methods: {
            getFriendSuggest() {
                get('friends-suggest')
                    .then(res => {
                        this.listUser = res.data.data.friendSuggests
                        this.numberMutualFriend = res.data.data.countMutualFriends
                    })
                    .catch(err => {
                        noty({
                            text: this.$i18n.t('messages.connection_error'),
                            container: false,
                            force: true
                        })
                    })
            },
        },
        components: {
            RequestFriend
        }
    }
</script>

<style lang="scss" scoped>
    .icon-add:after {
        content: '\f068';
    }

    .author-thumb img {
        height: 36px;
        width: 36px;
    }

    .w-friend-pages-added {
        .notification-event {
            padding-left: 8px;
            max-width: 48%;
        }
        .chat-message-item {
            display: inline-block;
        }
    }

    .btn-choose {
        background: #38a9ff;
        margin-bottom: 0;
        float: right;
        padding: 7px 10px;
        font-size: 11px;
        font-weight: bold;
        border-radius: 0.2rem;
    }

    .notification-list {
        li {
            padding: 17px 15px 17px 15px;
            border-bottom: 1px solid #e6ecf5;
            display: block;
        }

        .more {
            color: #ffffff;
            position: initial;
            right: initial;
            top: initial;
            font-size: 11px;
            color: #ffffff;
            opacity: 1;
        }
    }
    .more-dropdown {
        top: 50px;
    }
</style>
