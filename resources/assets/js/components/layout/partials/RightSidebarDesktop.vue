<template lang="html">
    <div class="fixed-sidebar right" :class="{ open: isOpen }">
        <div class="fixed-sidebar-right sidebar--small" id="sidebar-right">
            <div class="mCustomScrollbar" data-mcs-theme="dark" id="listClose">
                <ul class="chat-users" v-for="friend in friends">
                    <li class="inline-items" @click="addChatComponent(friend.id, friend.name, true)">
                        <div class="author-thumb">
                            <img alt="author" :src="friend.image_thumbnail" class="avatar">
                            <span class="icon-status online"></span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="search-friend inline-items">
                <a href="javascript:void(0)" class="js-sidebar-open" @click="isOpen = true">
                    <svg class="olymp-menu-icon">
                        <use xlink:href='/frontend/icons/icons.svg#olymp-menu-icon'></use>
                    </svg>
                </a>
            </div>
            <a href="javascript:void(0)" class="olympus-chat inline-items">
                <svg class="olymp-chat---messages-icon">
                    <use xlink:href='/frontend/icons/icons.svg#olymp-chat---messages-icon'></use>
                </svg>
            </a>
        </div>
        <div class="fixed-sidebar-right sidebar--large" id="sidebar-right-1">
            <div class="mCustomScrollbar" data-mcs-theme="dark" id="listOpen">
                <div class="ui-block-title ui-block-title-small">
                    <a href="#" class="title">{{ $t('chat.close_friends') }}s</a>
                    <a href="#">{{ $t('chat.settings') }}</a>
                </div>
                <ul class="chat-users" v-for="friend in friends">
                    <li class="inline-items">
                        <div class="author-thumb">
                            <img alt="author" :src="friend.image_thumbnail"
                                class="avatar"
                                @click="addChatComponent(friend.id, friend.name, true)">
                            <span class="icon-status online"></span>
                        </div>
                        <div class="author-status">
                            <a href="#" class="h6 author-name">{{ friend.name }}</a>
                            <span class="status">ONLINE</span>
                        </div>
                        <div class="more">
                            <svg class="olymp-three-dots-icon">
                                <use xlink:href='/frontend/icons/icons.svg#olymp-three-dots-icon'></use>
                            </svg>
                            <ul class="more-icons">
                                <li>
                                    <svg data-toggle="tooltip"
                                        data-placement="top"
                                        data-original-title="START CONVERSATION"
                                        class="olymp-comments-post-icon"
                                        @click="addChatComponent(friend.id, friend.name, true)">
                                        <use xlink:href='/frontend/icons/icons.svg#olymp-comments-post-icon'></use>
                                    </svg>
                                </li>
                                <li>
                                    <svg data-toggle="tooltip" data-placement="top" data-original-title="ADD TO CONVERSATION" class="olymp-add-to-conversation-icon">
                                        <use xlink:href='/frontend/icons/icons.svg#olymp-add-to-conversation-icon'></use>
                                    </svg>
                                </li>
                                <li>
                                    <svg data-toggle="tooltip" data-placement="top" data-original-title="BLOCK FROM CHAT" class="olymp-block-from-chat-icon">
                                        <use xlink:href='/frontend/icons/icons.svg#olymp-block-from-chat-icon'></use>
                                    </svg>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
                <div class="ui-block-title ui-block-title-small">
                    <a href="#" class="title">{{ $t('chat.groups') }}</a>
                    <a href="#">{{ $t('chat.settings') }}</a>
                </div>
                <ul class="chat-users">
                    <li class="inline-items" v-for="group in groups">
                        <div class="author-thumb">
                            <img alt="author"
                                :src="group.media[0].image_thumbnail"
                                class="avatar"
                                @click="addChatComponent(group.hashtag, group.hashtag, false)">
                            <span class="icon-status online"></span>
                        </div>
                        <div class="author-status">
                            <a href="#" class="h6 author-name">{{ group.hashtag }}</a>
                            <span class="status"></span>
                        </div>
                        <div class="more">
                            <svg class="olymp-three-dots-icon">
                                <use xlink:href='/frontend/icons/icons.svg#olymp-three-dots-icon'></use>
                            </svg>
                            <ul class="more-icons">
                                <li>
                                    <svg data-toggle="tooltip"
                                        data-placement="top"
                                        data-original-title="START CONVERSATION"
                                        class="olymp-comments-post-icon"
                                        @click="addChatComponent(group.hashtag, group.hashtag, false)">
                                        <use xlink:href='/frontend/icons/icons.svg#olymp-comments-post-icon'></use>
                                    </svg>
                                </li>
                                <li>
                                    <svg data-toggle="tooltip" data-placement="top" data-original-title="ADD TO CONVERSATION" class="olymp-add-to-conversation-icon">
                                        <use xlink:href='/frontend/icons/icons.svg#olymp-add-to-conversation-icon'></use>
                                    </svg>
                                </li>
                                <li>
                                    <svg data-toggle="tooltip" data-placement="top" data-original-title="BLOCK FROM CHAT" class="olymp-block-from-chat-icon">
                                        <use xlink:href='/frontend/icons/icons.svg#olymp-block-from-chat-icon'></use>
                                    </svg>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="search-friend inline-items">
                <form class="form-group">
                    <input class="form-control" :placeholder="$t('chat.search_friends')" value="" type="text">
                </form>
                <a href="javascript:void(0)" class="settings">
                    <svg class="olymp-settings-icon">
                        <use xlink:href='/frontend/icons/icons.svg#olymp-settings-icon'></use>
                    </svg>
                </a>
                <a href="javascript:void(0)" class="js-sidebar-open" @click="isOpen = false">
                    <svg class="olymp-close-icon">
                        <use xlink:href='/frontend/icons/icons.svg#olymp-close-icon'></use>
                    </svg>
                </a>
            </div>
            <a href="javascript:void(0)" class="olympus-chat inline-items">
                <h6 class="olympus-chat-title">{{ $t('chat.campaign_chat') }}</h6>
                <svg class="olymp-chat---messages-icon">
                    <use xlink:href='/frontend/icons/icons.svg#olymp-chat---messages-icon'></use>
                </svg>
            </a>
        </div>

        <div>
            <chat v-for="(chat, index) in chats"
                :receive="chat.id"
                :key="chat.id"
                :name="chat.name"
                :type="chat.type"
                :list.sync="chats"
                :index="index"
                :marginRight="chat.marginRight"
                @deleteChatIndex="handleDeleteChatIndex">
            </chat>
        </div>
    </div>

</template>

<script>
import { get } from '../../../helpers/api'
import { follow } from '../../../router/router'
import { mapState, mapActions } from 'vuex'
import Chat from '../../../components/chat/Chat.vue'

export default {
    data: () => ({
        isOpen: false,
        chats: [],
        marginRight: 0,
        widthDefault: 320,
        marginDefault: 70,
        marginWhenOpen: 280,
        maxList: 3
    }),
    computed: {
        ...mapState('auth', {
            user: state => state.user,
            friends: state => state.listContact,
            groups: state => state.groups
        })
    },
    watch: {
        isOpen() {
            //set margin when open or close list choose friend
            if (!this.isOpen && this.chats.length) {
                this.chats[0].marginRight = this.marginDefault

                for (var index = 1; index < this.chats.length; index++) {
                    this.chats[index].marginRight = this.chats[index - 1].marginRight + this.widthDefault
                }
            }

            if (this.isOpen && this.chats.length) {
                this.chats[0].marginRight = this.marginWhenOpen

                for (var index = 1; index < this.chats.length; index++) {
                    this.chats[index].marginRight = this.chats[index - 1].marginRight + this.widthDefault
                }
            }
        }
    },
    created () {
        this.$socket.emit('register', { id: this.user.id, type: true })
        this.getListFollow()
    },
    methods: {
        ...mapActions('auth', [
            'getListFollow'
        ]),
        addChatComponent(id, name, singleChat) {
            var flag = true

            //check if conponent chat is exists in array chats
            for (var index = 0; index < this.chats.length; index++) {
                if (this.chats[index].id == id) {
                    flag = false
                }
            }

            if (flag) {
                if (this.chats.length) {
                    this.marginRight = this.chats[this.chats.length - 1].marginRight + this.widthDefault
                } else {
                    if (this.isOpen) {
                        this.marginRight = this.marginWhenOpen
                    } else {
                        this.marginRight = this.marginDefault
                    }
                }

                let chat = {
                    id: id,
                    name: name,
                    marginRight: this.marginRight,
                    type: singleChat,
                    visiable: true
                }

                this.chats.push(chat)

                if (this.chats.length > this.maxList) {
                    this.handleDeleteChatIndex(0)
                }
            }
        },
        handleDeleteChatIndex(index) {
            for (var i = index + 1; i < this.chats.length; i++) {
                this.chats[i].marginRight -= this.widthDefault
            }

            this.marginRight -= this.widthDefault
            this.chats.splice(index, 1)
        }
    },
    components: {
        Chat
    },
    sockets: {
        singleChat: function (data) {
            var socketData = JSON.parse(data)

            if (socketData.success && socketData.to == this.user.id) {
                this.addChatComponent(socketData.from, socketData.name, true)
            }

            if (socketData.success && socketData.from == this.user.id) {
                for (var index = 0; index < this.friends.length; index++) {
                    if (this.friends[index].id == socketData.to) {
                        this.addChatComponent(socketData.to, this.friends[index].name, true)
                        break
                    }
                }
            }
        }
    }
}
</script>

<style lang="scss">
#listOpen, #listClose {
    overflow-y: scroll;
}

#listClose::-webkit-scrollbar, #listOpen::-webkit-scrollbar
{
	width: 0px;
	background-color: #F5F5F5;
}

.fixed-sidebar-right.sidebar--large {
    width: 280px;
}
</style>
