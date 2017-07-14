<template>
    <div class="container">
        <div class="ui-block popup-chat col-md-4 col-md-offset-4"
            :style="'right:' + marginRight + 'px !important'">
            <div class="ui-block-title">
                <span class="icon-status online"></span>
                <h6 class="title">{{ receiveName }}</h6>
                <div class="more">
                    <p class="olymp-three-dots-icon">
                    </p>
                    <p class="olymp-little-delete" @click="closeComponent">
                    </p>
                </div>
            </div>
            <div class="mCustomScrollbar ps ps--theme_default ps--active-y"
                data-mcs-theme="dark"
                data-ps-id="08dcf30a-ed2f-f4fc-dd34-a543d06407f1">
                <ul class="notification-list chat-message chat-message-field">
                    <li v-for="(message, index) in messages"
                        :class="((user.id == message.userId) ? '' : 'li-friend')
                            + ((index > 0 && message.userId != messages[index - 1].userId) ? ' mrg-top' : '' )">
                        <div :class="((user.id == message.userId) ? ' img-auth' : '') + ' author-thumb'"
                            v-if="index > 0 && message.userId != messages[index - 1].userId">
                            <img class="avatar"
                                src="https://upload.wikimedia.org/wikipedia/commons/1/1e/Default-avatar.jpg"
                                alt="author">
                        </div>
                        <div class="notification-event">
                            <span :class="((user.id == message.userId) ? 'message-auth' : 'chat-friend') + ' chat-message-item'">
                                {{ message.message }}
                            </span>
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
                <div class="div-input-chat form-group label-floating is-empty">
                    <textarea class="form-control"
                        placeholder=""
                        name="message"
                        v-model="content"
                        @keyup.enter="handleChat">
                    </textarea>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { post, get } from '../../helpers/api'
import { sendMessage, showMessage } from '../../router/router'
import { mapState } from 'vuex'
import noty from '../../helpers/noty'

export default {
    props: {
        receive: null,
        name: null,
        list: null,
        marginRight: 0,
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
            right: this.marginRight
        }
    },
    watch: {
        listChat() {
            this.$emit('update:list', this.listChat)
        }
    },
    created () {
        if (this.receiveUser != '') {
            if (this.user.id > this.receiveUser) {
                this.groupChat = this.receiveUser + '-' + this.user.id
            } else {
                this.groupChat = this.user.id + '-' + this.receiveUser
            }

            this.getMessage()
        } else {
            const message = this.$i18n.t('chat.get_message_error')
            noty({ text: message, container: false, force: true})
        }
    },
    methods: {
        handleChat: _.debounce(function () {
            if (this.content.trim() == '') {
                this.content = this.content.trim()

                return
            }

            post(sendMessage, {
                message: this.content.trim(),
                receiveUser: this.receiveUser,
                channel: 'singleChat',
                groupKey: this.groupChat
            }).then(res => {
                    if (res.data.status == 200) {
                        let mess = {
                            userId: this.user.id,
                            message: this.content
                        }
                        console.log(mess)
                        this.messages.push(mess)
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
            get(showMessage + '/' + this.receiveUser)
                .then(res => {
                    if (res.data.status == 200) {
                        for (var index = 0; index < res.data.messages.length; index++ ) {
                            if (res.data.messages[index]) {
                                let mess = {
                                    userId: res.data.messages[index].userId,
                                    message: res.data.messages[index].message
                                }

                                this.messages.push(mess)
                            }
                        }
                    }
                })
                .catch(err => {
                    this.messages.push(this.$i18n.t('chat.get_message_error'))
                })
        },
        closeComponent() {
            this.$emit('deleteChatIndex', this.index)
        }
    },
    computed: {
        ...mapState('auth', {
            user: state => state.user
        })
    },
    sockets: {
        singleChat: function (data) {
            var socketData = JSON.parse(data)

            if (socketData.success
                && socketData.from == this.receiveUser
                && socketData.to == this.user.id
                && socketData.groupChat == this.groupChat
            ) {
                var message = JSON.parse(socketData.message)
                let mess = {
                    userId: socketData.from,
                    message: message.message
                }

                this.messages.push(mess)
            }
        }
    }
}
</script>
<style lang="scss">
.ps__scrollbar-x-rail {
    left: 0px;
    bottom: 0px;
}

.ps__scrollbar-x {
    left: 0px; width: 0px;
}

.ps__scrollbar-y-rail {
    top: 0px; height: 350px; right: 0px;
}

.ps__scrollbar-y {
    top: 0px; height: 247px;
}

.mCustomScrollbar.ps.ps--theme_default.ps--active-y {
    overflow-y: scroll !important;
}

.popup-chat .chat-message-field li:nth-child(2n) .author-thumb {
    float: initial;
}

.popup-chat .chat-message-field li:nth-child(2n) .chat-message-item {
    float: initial;
    background-color: #f0f4f9;
    color: #8b8da8;
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

.popup-chat .chat-message-field li:nth-child(2n) .notification-event {
    float: initial;
    padding-left: initial;
    padding-right: initial;
}

.popup-chat .chat-message-field li .notification-event {
    float: right !important;
    padding-left: initial;
    padding-right: initial;
}

.li-friend {

}

.chat-friend .chat-message-item {
    color: #767082;
}

.popup-chat .chat-message-field li {
    padding: 0px 20px 0px 10px;
}

.mrg-top {
    margin-top: 10px;
    // padding: 12px 12px 0px 0px !important;
}

.chat-friend {
    margin-left: -20px;
    margin-right: 45px;
}

.popup-chat {
    position: fixed !important;
    z-index: 100 !important;
    right: 69px !important;
    bottom: -17px !important;
    padding: 0px;
}

.div-input-chat {
    padding: 0px 10px;
}

.popup-chat .mCustomScrollbar {
    max-height: 350px !important;
}

.popup-chat .chat-message-field .li-friend:nth-child(2n) {
    padding: 15px 10px !important;
}
</style>
