<template lang="html">
    <header class="header" id="site-header">
        <div class="page-title">
            <h6>favourite page</h6>
        </div>
        <div class="header-content-wrapper">
            <form class="search-bar w-search notification-list friend-requests">
                <div class="form-group with-button">
                    <input class="form-control js-user-search" placeholder="Search here people or pages..." type="text">
                    <button>
                        <svg class="olymp-magnifying-glass-icon">
                            <use xlink:href="/frontend/icons/icons.svg#olymp-magnifying-glass-icon"></use>
                        </svg>
                    </button>
                </div>
            </form>
            <a href="#" class="link-find-friend">Find Friends</a>
            <div class="control-block" v-if="authenticated">
                <div class="control-icon more has-items">
                    <svg class="olymp-happy-face-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                    </svg>
                    <div class="label-avatar bg-blue">6</div>
                    <div class="more-dropdown more-with-triangle triangle-top-center">
                        <div class="ui-block-title ui-block-title-small">
                            <h6 class="title">FRIEND REQUESTS</h6>
                            <a href="#">Find Friends</a>
                            <a href="#">Settings</a>
                        </div>
                        <div class="mCustomScrollbar" data-mcs-theme="dark">
                            <ul class="notification-list friend-requests">
                                <li>
                                    <div class="author-thumb">
                                        <img src="/images/avatar55-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <a href="#" class="h6 notification-friend">Tamara Romanoff</a>
                                        <span class="chat-message-item">Mutual Friend: Sarah Hetfield</span>
                                    </div>
                                    <span class="notification-icon">
                                        <a href="#" class="accept-request">
                                            <span class="icon-add without-text">
                                                <svg class="olymp-happy-face-icon">
                                                    <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                                </svg>
                                            </span>
                                        </a>
                                        <a href="#" class="accept-request request-del">
                                            <span class="icon-minus">
                                                <svg class="olymp-happy-face-icon">
                                                    <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                                </svg>
                                            </span>
                                        </a>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                    </div>
                                </li>
                                <li>
                                    <div class="author-thumb">
                                        <img src="/images/avatar56-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <a href="#" class="h6 notification-friend">Tony Stevens</a>
                                        <span class="chat-message-item">4 Friends in Common</span>
                                    </div>
                                    <span class="notification-icon">
                                        <a href="#" class="accept-request">
                                            <span class="icon-add without-text">
                                                <svg class="olymp-happy-face-icon">
                                                    <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                                </svg>
                                            </span>
                                        </a>
                                        <a href="#" class="accept-request request-del">
                                            <span class="icon-minus">
                                                <svg class="olymp-happy-face-icon">
                                                    <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                                </svg>
                                            </span>
                                        </a>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                    </div>
                                </li>
                                <li class="accepted">
                                    <div class="author-thumb">
                                        <img src="/images/avatar57-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        You and
                                        <a href="#" class="h6 notification-friend">Mary Jane Stark</a> just became friends. Write on
                                        <a href="#" class="notification-link">her wall</a>.
                                    </div>
                                    <span class="notification-icon">
                                        <svg class="olymp-happy-face-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                        </svg>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                        <svg class="olymp-little-delete">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-little-delete"></use>
                                        </svg>
                                    </div>
                                </li>
                                <li>
                                    <div class="author-thumb">
                                        <img src="/images/avatar58-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <a href="#" class="h6 notification-friend">Stagg Clothing</a>
                                        <span class="chat-message-item">9 Friends in Common</span>
                                    </div>
                                    <span class="notification-icon">
                                        <a href="#" class="accept-request">
                                            <span class="icon-add without-text">
                                                <svg class="olymp-happy-face-icon">
                                                    <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                                </svg>
                                            </span>
                                        </a>
                                        <a href="#" class="accept-request request-del">
                                            <span class="icon-minus">
                                                <svg class="olymp-happy-face-icon">
                                                    <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                                </svg>
                                            </span>
                                        </a>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <a href="#" class="view-all bg-blue">Check all your Events</a>
                    </div>
                </div>
                <div class="control-icon more has-items">
                    <svg class="olymp-chat---messages-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-chat---messages-icon"></use>
                    </svg>
                    <div class="label-avatar bg-purple">{{ messages.length }}</div>
                    <div class="more-dropdown more-with-triangle triangle-top-center">
                        <div class="ui-block-title ui-block-title-small">
                            <h6 class="title">Chat / Messages</h6>
                            <a href="#">Mark all as read</a>
                            <a href="#">Settings</a>
                        </div>
                        <div class="mCustomScrollbar" data-mcs-theme="dark" id="notification_messages">
                            <ul class="notification-list chat-message">
                                <li v-for="mess in messages" :class="mess.class">
                                    <div class="author-thumb">
                                        <img :src="mess.showAvatar" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <a href="#" class="h6 notification-friend">{{ mess.showName }}</a>
                                        <span class="chat-message-item" v-html="mess.sendName + mess.message"></span>
                                        <span class="notification-date">
                                            <time class="entry-date updated" datetime="2004-07-24T18:18">{{ calendarTime(mess.time) }}</time>
                                        </span>
                                    </div>
                                    <span class="notification-icon">
                                        <svg class="olymp-chat---messages-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-chat---messages-icon"></use>
                                        </svg>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        <a href="javascript:void(0)" class="view-all bg-purple" @click="getMessagesNotification">
                            {{ $t('messages.more_messages') }}
                        </a>
                    </div>
                </div>
                <div class="control-icon more has-items">
                    <svg class="olymp-thunder-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-thunder-icon"></use>
                    </svg>
                    <div class="label-avatar bg-primary">8</div>
                    <div class="more-dropdown more-with-triangle triangle-top-center">
                        <div class="ui-block-title ui-block-title-small">
                            <h6 class="title">Notifications</h6>
                            <a href="#">Mark all as read</a>
                            <a href="#">Settings</a>
                        </div>
                        <div class="mCustomScrollbar" data-mcs-theme="dark">
                            <ul class="notification-list">
                                <li>
                                    <div class="author-thumb">
                                        <img src="/images/avatar62-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <div>
                                            <a href="#" class="h6 notification-friend">Mathilda Brinker</a> commented on your new
                                            <a href="#" class="notification-link">profile status</a>.</div>
                                        <span class="notification-date">
                                            <time class="entry-date updated" datetime="2004-07-24T18:18">4 hours ago</time>
                                        </span>
                                    </div>
                                    <span class="notification-icon">
                                        <svg class="olymp-comments-post-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-comments-post-icon"></use>
                                        </svg>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                        <svg class="olymp-little-delete">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-little-delete"></use>
                                        </svg>
                                    </div>
                                </li>
                                <li class="un-read">
                                    <div class="author-thumb">
                                        <img src="/images/avatar63-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <div>You and
                                            <a href="#" class="h6 notification-friend">Nicholas Grissom</a> just became friends. Write on
                                            <a href="#" class="notification-link">his wall</a>.</div>
                                        <span class="notification-date">
                                            <time class="entry-date updated" datetime="2004-07-24T18:18">9 hours ago</time>
                                        </span>
                                    </div>
                                    <span class="notification-icon">
                                        <svg class="olymp-happy-face-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                        </svg>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                        <svg class="olymp-little-delete">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-little-delete"></use>
                                        </svg>
                                    </div>
                                </li>
                                <li class="with-comment-photo">
                                    <div class="author-thumb">
                                        <img src="/images/avatar64-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <div>
                                            <a href="#" class="h6 notification-friend">Sarah Hetfield</a> commented on your
                                            <a href="#" class="notification-link">photo</a>.</div>
                                        <span class="notification-date">
                                            <time class="entry-date updated" datetime="2004-07-24T18:18">Yesterday at 5:32am</time>
                                        </span>
                                    </div>
                                    <span class="notification-icon">
                                        <svg class="olymp-comments-post-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-comments-post-icon"></use>
                                        </svg>
                                    </span>
                                    <div class="comment-photo">
                                        <img src={{ asset('images/comment-photo1.jpg') }} alt="photo">
                                        <span>“She looks incredible in that outfit! We should see each...”</span>
                                    </div>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                        <svg class="olymp-little-delete">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-little-delete"></use>
                                        </svg>
                                    </div>
                                </li>
                                <li>
                                    <div class="author-thumb">
                                        <img src="/images/avatar65-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <div>
                                            <a href="#" class="h6 notification-friend">Green Goo Rock</a> invited you to attend to his event Goo in
                                            <a href="#" class="notification-link">Gotham Bar</a>.</div>
                                        <span class="notification-date">
                                            <time class="entry-date updated" datetime="2004-07-24T18:18">March 5th at 6:43pm</time>
                                        </span>
                                    </div>
                                    <span class="notification-icon">
                                        <svg class="olymp-happy-face-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                        </svg>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                        <svg class="olymp-little-delete">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-little-delete"></use>
                                        </svg>
                                    </div>
                                </li>
                                <li>
                                    <div class="author-thumb">
                                        <img src="/images/avatar66-sm.jpg" alt="author">
                                    </div>
                                    <div class="notification-event">
                                        <div>
                                            <a href="#" class="h6 notification-friend">James Summers</a> commented on your new
                                            <a href="#" class="notification-link">profile status</a>.</div>
                                        <span class="notification-date">
                                            <time class="entry-date updated" datetime="2004-07-24T18:18">March 2nd at 8:29pm</time>
                                        </span>
                                    </div>
                                    <span class="notification-icon">
                                        <svg class="olymp-heart-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use>
                                        </svg>
                                    </span>
                                    <div class="more">
                                        <svg class="olymp-three-dots-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                        </svg>
                                        <svg class="olymp-little-delete">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-little-delete"></use>
                                        </svg>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <a href="#" class="view-all bg-primary">View All Notifications</a>
                    </div>
                </div>
                <div class="author-page author vcard inline-items more">
                    <div class="author-thumb">
                        <img alt="author" :src="user.image_thumbnail" class="avatar">
                        <span class="icon-status online"></span>
                        <div class="more-dropdown more-with-triangle">
                            <div class="ui-block-title ui-block-title-small">
                                <h6 class="title">Your Account</h6>
                            </div>
                            <ul class="account-settings">
                                <li>
                                    <router-link :to="{ name: 'setting.profile' }">
                                        <svg class="olymp-menu-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-menu-icon"></use>
                                        </svg>
                                        <span>Profile Settings</span>
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{ name: 'user.timeline', params: { id: user.id }}">
                                        <svg class="olymp-star-icon left-menu-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-star-icon"></use>
                                        </svg>
                                        <span>Your timeline</span>
                                    </router-link>
                                </li>
                                <li>
                                    <a @click="handleLogout">
                                        <svg class="olymp-logout-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-logout-icon"></use>
                                        </svg>
                                        <span>Log Out</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="ui-block-title ui-block-title-small">
                                <h6 class="title">Chat Settings</h6>
                            </div>
                            <ul class="chat-settings">
                                <li>
                                    <a href="#">
                                        <span class="icon-status online"></span>
                                        <span>Online</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="icon-status away"></span>
                                        <span>Away</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="icon-status disconected"></span>
                                        <span>Disconnected</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="icon-status status-invisible"></span>
                                        <span>Invisible</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="ui-block-title ui-block-title-small">
                                <h6 class="title">Custom Status</h6>
                            </div>
                            <form class="form-group with-button custom-status">
                                <input class="form-control" placeholder="" type="text" value="Space Cowboy">
                                <button class="bg-purple">
                                    <svg class="olymp-check-icon">
                                        <use xlink:href="/frontend/icons/icons.svg#olymp-check-icon"></use>
                                    </svg>
                                </button>
                            </form>
                            <div class="ui-block-title ui-block-title-small">
                                <h6 class="title">About Olympus</h6>
                            </div>
                            <ul>
                                <li>
                                    <a href="#">
                                        <span>Terms and Conditions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span>FAQs</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span>Careers</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span>Contact</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <router-link :to="{ name: 'user.timeline', params: { id: user.id } }" class="author-name fn">
                        <div class="author-title">
                            {{ user.name }}
                            <svg class="olymp-dropdown-arrow-icon">
                                <use xlink:href="/frontend/icons/icons.svg#olymp-dropdown-arrow-icon"></use>
                            </svg>
                        </div>
                        <span class="author-subtitle">{{ user.email }}</span>
                    </router-link>
                </div>
            </div>
            <!--End: control-block -->

            <div class="control-block" v-else>
                <router-link to="/login">Login</router-link> |
                <router-link to="/register">Register</router-link>
            </div>
        </div>
    </header>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { logout, showNotification } from '../../../router/router'
import { post, get } from '../../../helpers/api'
import noty from '../../../helpers/noty'

export default {
    data: () => ({
        messages: [],
        paginate: 0,
        continue: true
    }),
    created () {
        this.getMessagesNotification()
    },
    computed: {
        ...mapState('auth', {
            authenticated: state => state.authenticated,
            user: state => state.user,
            groups: state => state.groups
        })
    },
    methods: {
        ...mapActions('auth', [
            'logout',
        ]),
        handleLogout() {
            post(logout).then(res => {
                this.logout()
                this.$router.push('/login')
            }).catch(err => {
                this.$router.push('/')
            })
        },
        getMessagesNotification() {
            if (this.continue) {
                get(`${showNotification}?paginate=${this.paginate}`)
                    .then(res => {
                        if (res.data.status == 200) {
                            let noty = res.data.notifications

                            for (var index = 0; index < noty.length; index++) {
                                let isSendToUser = Number.isInteger(Number(noty[index].receive))
                                let mess = {
                                    form: noty[index].userId,
                                    sendName: (noty[index].userId == this.user.id)
                                        ? this.$i18n.t('messages.you')
                                        : noty[index].name + ": ",
                                    to: noty[index].receive,
                                    groupChat: noty[index].groupKey,
                                    message: noty[index].message,
                                    showName: (noty[index].userId == this.user.id || !isSendToUser)
                                        ? noty[index].nameReceive
                                        : noty[index].name,
                                    showAvatar: (noty[index].userId == this.user.id || !isSendToUser)
                                        ? noty[index].avatarReceive
                                        : noty[index].avatar,
                                    class: isSendToUser ? "" : "group-chat",
                                    time: noty[index].time
                                }

                                this.messages.push(mess)
                            }

                            this.paginate = res.data.paginate
                        }

                        this.continue = res.data.continue
                    })
                    .catch(err => {
                        const message = this.$i18n.t('messages.load_notification_message_fail')
                        noty({ text: message, container: false, force: true})
                    })
            }
        },
        receiveMessage(data, option) {
            var socketData = JSON.parse(data)

            if (socketData.success ) {
                var message = JSON.parse(socketData.message)
                let index = this.messages.findIndex(mess => mess.groupChat == socketData.groupChat)

                let mess = {
                    from: socketData.from,
                    sendName: (socketData.from == this.user.id)
                        ? this.$i18n.t('messages.you')
                        : socketData.name + ": ",
                    to: socketData.to,
                    groupChat: socketData.groupChat,
                    message: message.message,
                    showName: (socketData.from == this.user.id || !option)
                        ? message.nameReceive
                        : message.name,
                    showAvatar: (socketData.from == this.user.id || !option)
                        ? message.avatarReceive
                        : message.avatar,
                    class: Number.isInteger(socketData.to) ? "" : "group-chat",
                    time: message.time
                }

                if (index == -1) {
                    this.messages.unshift(mess)
                } else {
                    this.messages.splice(index, 1)
                    this.messages.unshift(mess)
                }

                this.paginate++
            }
        },
        calendarTime(time)
        {
            return moment(time).calendar()
        },
    },
    mounted() {
        const vm = this
        $('#notification_messages').on('scroll', function() {
            if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                vm.getMessagesNotification()
            }
        })
    },
    sockets: {
        singleChat: function (data) {
            this.receiveMessage(data, true)
        },
        groupChat: function (data) {
            this.receiveMessage(data, false)
        }
    }
}
</script>

<style lang="scss">
.author-thumb {
    .avatar {
        width: 36px;
        height: 36px;
    }
}

#notification_messages {
    overflow-y: scroll !important;
}
</style>
