<template>
    <a href="javascript:void(0)" v-if="userRequest.is_friend" class="btn-choose more">
        <i class="fa fa-smile-o" aria-hidden="true"></i>
        {{ $t('user.search.friends') }}
        <ul class="more-dropdown">
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
        <ul class="more-dropdown" >
            <li>
                <a href="javascript:void(0)" @click.stop="sendRequest">
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
        <ul class="more-dropdown">
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
    import { mapState, mapActions, mapMutations } from 'vuex'

    export default {
        data: () => ({
            userRequest: {},
        }),
        computed: {
            ...mapState('auth',[
                'user',
            ])
        },
        props: ['friend'],
        created() {
            this.userRequest = this.friend
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
                            this.userRequest.is_friend = false
                        })
                })
            },
            // send request or cancel request
            sendRequest(id) {
                post(`send-friend-request/${this.userRequest.id}`)
                    .then(() => {
                        this.userRequest.has_pending_request = !this.userRequest.has_pending_request
                    })
            },
            acceptRequest() {
                post(`accept-friend-requset/${this.userRequest.id}`)
                    .then(res => {
                        this.userRequest.has_send_request = false
                        this.userRequest.is_friend = true
                    })
            },
            denyRequest() {
                this.modal(`<h4 class="text-center">${this.$t('user.quote.deny_request_msg')}</h4>`, () => {
                    post(`deny-friend-request/${this.userRequest.id}`)
                        .then(() => {
                            this.userRequest.has_send_request = false
                        })
                })
            },
        }
    }

</script>

<style lang="scss" scoped>
    .more-dropdown {
        top: 35px;
        padding: 5px 20px;
        width: 130px;
    }

    .confirm {
        &:hover {
            background: #ff5e3a;
             box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.14), 0 -1px 1px -1px rgba(0, 0, 0, 0.12), 0 1px 3px 0 rgba(0, 0, 0, 0.2);
        }
    }
</style>
