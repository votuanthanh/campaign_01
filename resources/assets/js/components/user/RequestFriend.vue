<template>
    <a href="javascript:void(0)" v-if="userRequest.is_friend" class="btn-choose more">
        <i class="fa fa-smile-o" aria-hidden="true"></i>
        {{ $t('user.search.friends') }}
        <ul :class="className">
            <li>
                <a href="javascript:void(0)" @click="unfriend">
                    {{ $t('user.search.unfriend') }}
                </a>
            </li>
        </ul>
    </a>

    <a href="javascript:void(0)" v-else-if="userRequest.has_pending_request" class="btn-choose more">
        <i class="fa fa-share" aria-hidden="true"></i>
        {{ $t('user.search.friend_request_send') }}
        <ul :class="className">
            <li>
                <a href="javascript:void(0)" @click.stop="cancelRequest">
                    {{ $t('user.search.cancel_request') }}
                </a>
            </li>
        </ul>
    </a>

    <a href="javascript:void(0)"
        @click.stop="acceptRequest"
        v-else-if="userRequest.has_send_request"
        class="confirm btn-choose more">
        <i class="fa fa-reply-all" aria-hidden="true"></i>
        {{ $t('user.search.confirm') }}
        <ul :class="className">
            <li>
                <a href="javascript:void(0)" @click.stop="denyRequest">
                    {{ $t('user.search.delete_request') }}
                </a>
            </li>
        </ul>
    </a>

    <a href="javascript:void(0)" @click="sendRequest" v-else class="confirm btn-choose more">
        <i class="fa fa-user-plus" aria-hidden="true"></i>
        {{ $t('user.search.add_friend') }}
    </a>
</template>

<script>
    import Noty from 'noty'
    import noty from '../../helpers/noty'
    import { get, post } from '../../helpers/api'
    import { mapState } from 'vuex'

    export default {
        data: () => ({
            userRequest: {},
            className: '',
        }),
        computed: {
            ...mapState('auth',[
                'user',
            ])
        },
        props: ['friend', 'classTemp'],
        created() {
            this.userRequest = this.friend
            this.className = 'more-dropdown ' + this.classTemp
        },
        methods: {
            modal(text, callback) {
                let n = new Noty({
                    text: text,
                    modal: true,
                    layout: 'center',
                    closeWith: 'button',
                    buttons: [
                        Noty.button(this.$t('events.donation.yes'), 'btn-upper btn btn-primary btn--half-width', () => {
                            callback()
                            n.close()
                        }),
                        Noty.button(this.$t('events.donation.no'), 'btn-upper btn btn-secondary btn--half-width', () => {
                            n.close()
                        })
                    ]
                }).show();
            },
            unfriend() {
                this.modal(`<h4 class="text-center">${this.$t('user.quote.unfriend_msg')}</h4>`, () => {
                    post(`unfriend/${this.userRequest.id}`)
                        .then(() => {
                            this.$socket.emit('unfriend', {
                                userId: this.user.id,
                                unfriendId: this.userRequest.id
                            })
                        })
                })
            },
            sendRequest() {
                post(`send-friend-request/${this.userRequest.id}`)
                    .then(() => {
                        this.$socket.emit('sendRequest', {
                            userId: this.user.id,
                            acceptId: this.userRequest.id
                        })
                    })
            },
            cancelRequest() {
                post(`send-friend-request/${this.userRequest.id}`)
                    .then(() => {
                        this.$socket.emit('cancelRequest', {
                            userId: this.user.id,
                            cancelId: this.userRequest.id
                        })
                    })
            },
            acceptRequest() {
                post(`accept-friend-requset/${this.userRequest.id}`)
                    .then(res => {
                        this.$socket.emit('acceptRequest', {
                            userId: this.userRequest.id,
                            acceptId: this.user.id,
                            avatar: this.user.image_thumbnail,
                            name: this.user.name,
                            receiveAvatar: this.userRequest.image_thumbnail,
                            receiveName: this.userRequest.name
                        })
                    })
            },
            denyRequest() {
                this.modal(`<h4 class="text-center">${this.$t('user.quote.deny_request_msg')}</h4>`, () => {
                    post(`deny-friend-request/${this.userRequest.id}`)
                        .then(() => {
                            this.$socket.emit('rejectRequest', {
                                userId: this.user.id,
                                rejectId: this.userRequest.id,
                                index: -1
                            })
                        })
                })
            },
            changeStatusFriend(pending, send, isFriend)
            {
                this.userRequest.has_pending_request = pending
                this.userRequest.has_send_request = send
                this.userRequest.is_friend = isFriend
            }
        },
        sockets: {
            acceptRequestSuccess: function (data) {
                if ((this.userRequest.id == data.data.acceptId && this.user.id == data.data.userId)
                    || (this.userRequest.id == data.data.userId && this.user.id == data.data.acceptId)
                ) {
                    this.changeStatusFriend(false, false, true)
                }
            },
            // cancel request or reject request from other user
            rejectRequestSuccess: function (data) {
                if ((this.userRequest.id == data.data.userId && this.user.id == data.data.rejectId)
                    || (this.userRequest.id == data.data.rejectId && this.user.id == data.data.userId)
                    || (this.userRequest.id == data.data.userId && this.user.id == data.data.cancelId)
                ) {
                    this.changeStatusFriend(false, false, false)
                }
            },
            sendRequestSuccess: function (data) {
                if (this.userRequest.id == data.data.acceptId) {
                    this.changeStatusFriend(true, false, false)
                }

                if (this.userRequest.id == data.data.userId) {
                    this.changeStatusFriend(false, true, false)
                }

            },
            cancelRequestSuccess: function (data) {
                if (this.userRequest.id == data.data.cancelId || this.userRequest.id == data.data.userId) {
                    this.changeStatusFriend(false, false, false)
                }
            },
            unfriendSuccess: function (data) {
                if ((this.userRequest.id == data.data.unfriendId && this.user.id == data.data.userId)
                    || (this.userRequest.id == data.data.userId && this.user.id == data.data.unfriendId)
                ) {
                    this.changeStatusFriend(false, false, false)
                }
            }
        }
    }

</script>

<style lang="scss" scoped>
    .more-dropdown {
        padding: 5px 20px;
        width: 130px;
        &.home {
            top: 50px;
        }
        &.search {
            top: 35px;
        }
    }

    .confirm {
        &:hover {
            background: #ff5e3a;
             box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.14), 0 -1px 1px -1px rgba(0, 0, 0, 0.12), 0 1px 3px 0 rgba(0, 0, 0, 0.2);
        }
    }

    .notification-list {
        li {
            padding: 0px;
            border-bottom: 0px
        }
    }
</style>
