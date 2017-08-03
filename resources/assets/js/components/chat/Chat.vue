<template>
    <div class="container">
        <div class="ui-block popup-chat col-md-4 col-md-offset-4"
            :style="'right:' + marginRight + 'px !important'">
            <div class="ui-block-title">
                <span class="icon-status online"></span>
                <h6 class="title">{{ receiveName }}</h6>
                <div class="more">
                    <svg class="olymp-three-dots-icon">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                    <svg class="olymp-little-delete" @click="closeComponent">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/frontend/icons/icons.svg#olymp-little-delete"></use>
                    </svg>
                </div>
            </div>
            <div class="mCustomScrollbar ps ps--theme_default ps--active-y"
                data-mcs-theme="dark"
                data-ps-id="08dcf30a-ed2f-f4fc-dd34-a543d06407f1"
                :id="replaceSpace(receiveUser)">
                <ul class="notification-list chat-message chat-message-field">
                    <li v-for="(subMessage, index) in organizedMessage"
                        :class="subMessage[0].userId == user.id ? 'popup-chat-myself' : 'popup-chat-friend'">
                        <div class="author-thumb">
                            <img :src="subMessage[0].avatar" alt="author">
                        </div>
                        <div class="box-notification-event">
                            <div class="notification-event">
                                <span class="chat-message-item" v-for="mess in subMessage" v-html="mess.message"></span>
                                <span class="notification-date">
                                    <time class="entry-date updated"
                                        datetime="2004-07-24T18:18"
                                        v-if="index == (organizedMessage.length - 1)
                                            && read
                                            && messages[messages.length - 1].userId == user.id ">
                                        {{ $t('messages.read_at') + calendarTime(readAt) }}
                                    </time>
                                    <time class="entry-date updated" datetime="2004-07-24T18:18"v-else>
                                        {{ calendarTime(organizedMessage[index][subMessage.length - 1].time) }}
                                    </time>
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="ps__scrollbar-x-rail">
                    <div class="ps__scrollbar-x" tabindex="0"></div>
                </div>
                <div class="ps__scrollbar-y-rail">
                    <div class="ps__scrollbar-y" tabindex="0"></div>
                </div>
            </div>
            <form>
                <div class="div-input-chat form-group label-floating">
                    <textarea class="form-control"
                        name="message"
                        v-model="content"
                        @keyup.enter="handleChat"
                        @keyup.prevent="markRead"
                        @click.prevent="markRead"
                        :placeholder="$t('chat.send_message')">
                    </textarea>
                    <div class="add-options-message">
                        <a href="#" class="options-message">
                            <svg class="olymp-computer-icon">
                                <use xlink:href="/frontend/icons/icons.svg#olymp-computer-icon"></use>
                            </svg>
                        </a>
                        <div class="options-message smile-block">
                            <svg class="olymp-happy-sticker-icon">
                                <use xlink:href="/frontend/icons/icons.svg#olymp-happy-sticker-icon"></use>
                            </svg>
                            <ul class="more-dropdown more-with-triangle triangle-bottom-right">
                                <li v-for="number in 27">
                                    <a href="#">
                                        <img :src="'/images/icon-chat' + number + '.png'"
                                            alt="icon"
                                            @click="addIcon(number)">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <span class="material-input"></span>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { post, get } from '../../helpers/api'
import { sendMessage, sendMessageToGroup, showMessage } from '../../router/router'
import { mapState } from 'vuex'
import noty from '../../helpers/noty'

export default {
    props: {
        receive: null,
        name: null,
        list: null,
        marginRight: 0,
        type: Boolean,
        index: {
            type: Number
        }
    },
    data: function () {
        return {
            messages: [],
            content: '',
            receiveUser: this.receive,
            receiveName: this.name,
            groupChat: '',
            listChat: this.list,
            right: this.marginRight,
            paginate: 0,
            isLoad: false,
            continue: true,
            read: false,
            readAt: ''
        }
    },
    watch: {
        listChat() {
            this.$emit('update:list', this.listChat)
        }
    },
    created () {
        if (this.receiveUser != '') {
            if (this.type) {
                if (this.user.id > this.receiveUser) {
                    this.groupChat = this.receiveUser + '-' + this.user.id
                } else {
                    this.groupChat = this.user.id + '-' + this.receiveUser
                }
            } else {
                this.groupChat = this.receiveUser
                this.$socket.emit('register', { id: this.groupChat, type: false })
            }

            this.getMessage()
        } else {
            const message = this.$i18n.t('chat.get_message_error')
            noty({ text: message, container: false, force: true})
        }
    },
    updated() {
        if (!this.isLoad) {
            $('#' + this.replaceSpace(this.receiveUser))
                .scrollTop($('#' + this.replaceSpace(this.receiveUser))[0].scrollHeight)
            this.isLoad = true
        }
    },
    methods: {
        handleChat: _.debounce(function () {
            if (this.content.trim() == '') {
                this.content = this.content.trim()

                return
            }

            let channel = null
            let link = null

            if (this.type) {
                channel = 'singleChat'
                link = sendMessage
            } else {
                channel = 'groupChat'
                link = sendMessageToGroup
            }

            post(link, {
                message: this.content.trim(),
                receiveUser: this.receiveUser,
                channel: channel,
                groupKey: this.groupChat
            }).then(res => {
                    if (res.data.status == 200) {
                        this.content = ''
                    } else {
                        this.messages.push(this.$i18n.t('chat.send_message_error'))
                    }
                })
                .catch(err => {
                    this.messages.push(this.$i18n.t('chat.send_message_error'))
                })
        }, 300),
        getMessage() {
            if (this.continue) {
                get(`${showMessage}/${this.receiveUser}?type=${this.type}&paginate=${this.paginate}`)
                    .then(res => {
                        if (res.data.status == 200) {
                            this.read = res.data.read
                            this.readAt = res.data.time

                            for (var index = 0; index < res.data.messages.length; index++ ) {
                                let data = res.data.messages[index]

                                if (data) {
                                    let mess = {
                                        userId: data.userId,
                                        message: data.message,
                                        avatar: data.avatar,
                                        time: data.time
                                    }

                                    this.messages.unshift(mess)
                                }
                            }

                            this.paginate = res.data.paginate
                        }

                        this.continue = res.data.continue
                    })
                    .catch(err => {
                        this.messages.push(this.$i18n.t('chat.get_message_error'))
                    })
            }
        },
        closeComponent() {
            this.$emit('deleteChatIndex', this.index)
        },
        replaceSpace(str) {
            let replaceStr = String(str)

            if (replaceStr.indexOf(' ')) {
                replaceStr.replace(' ', '')
            }

            return replaceStr
        },
        receiveMessage(data) {
            var socketData = JSON.parse(data)

            if (socketData.success && socketData.groupChat == this.groupChat) {
                var message = JSON.parse(socketData.message)
                let mess = {
                    userId: socketData.from,
                    message: message.message,
                    avatar: message.avatar,
                    time: message.time
                }

                this.messages.push(mess)
                this.paginate++
                this.read = false
                $('#' + this.replaceSpace(this.receiveUser))
                    .scrollTop($('#' + this.replaceSpace(this.receiveUser))[0].scrollHeight)
            }
        },
        calendarTime(time) {
            return moment(time).calendar()
        },
        addIcon(number) {
            this.content += "<img src='/images/icon-chat" + number + ".png' alt='icon'>"
        },
        markRead() {
            if (this.messages[this.messages.length - 1].userId != this.user.id) {
                this.$socket.emit('markRead', {
                    receive: this.receiveUser,
                    send: this.user.id
                })
            }
        }
    },
    mounted() {
        $('#' + this.replaceSpace(this.receiveUser)).scroll(() => {
            if ($('#' + this.replaceSpace(this.receiveUser)).scrollTop() == 0) {
                this.getMessage()
            }
        })
    },
    computed: {
        ...mapState('auth', {
            user: state => state.user
        }),
        organizedMessage() {
            let messages = []
            let childMessage = []

            for (let i = 0; i < this.messages.length; i++) {
                if (this.messages.length != i + 1 && this.messages[i].userId != this.messages[i + 1].userId) {
                    childMessage.push(this.messages[i])
                    messages.push(childMessage)
                    childMessage = []
                } else {
                    childMessage.push(this.messages[i])

                    if (this.messages.length == i + 1) {
                        messages.push(childMessage)
                    }
                }
            }

            return messages
        }
    },
    sockets: {
        singleChat: function (data) {
            this.receiveMessage(data)
        },
        groupChat: function (data) {
            this.receiveMessage(data)
        },
        read: function (data) {
            this.read = data.status
            this.readAt = data.time
        }
    }
}
</script>
<style lang="scss" scoped>
.ps__scrollbar-x-rail {
    left: 0px;
    bottom: 0px;
}

.ps__scrollbar-x {
    left: 0px; width: 0px;
}

.ps__scrollbar-y-rail {
    top: 0px; height: 10px; right: 0px;
}

.ps__scrollbar-y {
    top: 0px; height: 10px;
}

.mCustomScrollbar.ps.ps--theme_default.ps--active-y {
    overflow-y: scroll !important;
}

.notification-list {
    min-height: 360px;
}

.popup-chat {
    .chat-message-field {
        li {
            &:nth-child(2n) {
                .notification-event {
                    float: none;
                    padding: 0;
                }
            }
        }
        .box-notification-event {
            width: 75%;
        }
        .popup-chat-friend {
            display: flex;
            flex-direction: row;
            .box-notification-event {
                padding-left: 10px;
            }
            .notification-event {
                display: flex;
                flex-flow: column wrap;
                align-items: flex-start;
            }
            .chat-message-item {
                background-color: #f0f4f9;
                color: #888da8;
            }
        }
        .popup-chat-myself {
            display: flex;
            flex-direction: row-reverse;
            .box-notification-event {
                padding-right: 10px;
            }
            .notification-event {
                display: flex;
                flex-flow: column wrap;
                align-items: flex-end;
            }
            .chat-message-item {
                background-color: #7c5ac2;
                color: #fff;
            }
        }
        .notification-event {
            width: 100%;
            max-width: 100%;
            padding-left: 0px;
        }
        .chat-message-item {
            padding: 5px 12px;
            word-break: break-all;
        }
        .author-thumb {
            img {
                width: 26px;
                height: 26px;
            }
        }
    }

    .mCustomScrollbar {
        max-height: 350px !important;
    }

    textarea {
        &:focus {
            min-height: 60px;
        }
    }

    position: fixed !important;
    z-index: 999 !important;
    right: 69px !important;
    bottom: -17px !important;
    padding: 0px;
}

.message-auth {
    float: right !important;
    color: white !important;
    background-color: #7c5ac2 !important;
    margin-right: 12px;
}

.img-auth {
    float: right !important;
}

.chat-friend {
    margin-left: -20px;
    margin-right: 45px;

    .chat-message-item {
        color: #767082;
    }
}

.mrg-top {
    margin-top: 10px;
}
</style>
