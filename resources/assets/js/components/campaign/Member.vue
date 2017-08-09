<!-- Window-popup Create Friends Group Add Friends -->
<template lang="html">
    <transition name="modal">
        <div class="v-modal-mask" @click="closeListMember" v-show="flagShowListMember">
            <div class="v-modal-container" @click.stop>
                <div class="v-modal-body">
                    <div class="modal-dialog ui-block window-popup create-friend-group create-friend-group-add-friends">
                        <a href="javascript:void(0)" class="close icon-close" data-dismiss="modal" aria-label="Close"
                            @click="closeListMember()">
                            <svg class="olymp-close-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-close-icon"></use></svg>
                        </a>
                        <div class="ui-block-title">
                            <h6 class="title">{{ $t('messages.members') }}</h6>
                        </div>
                        <div class="ui-block-content">
                            <form class="form-group label-floating is-select">
                                <plugin></plugin>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="v-modal-footer text-right">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import Plugin from './Plugin.vue'
    import Modal from '../libs/Modal.vue'
    import { mapState } from 'vuex'

    export default {
        methods: {
            closeListMember() {
                this.$emit('update:flagShowListMember', false)
            }
        },
        props: ['flagShowListMember'],
        components: {
            Plugin,
            Modal
        }
    }
</script>

<style lang="scss" scoped>
    .v-modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        transition: opacity .3s ease;
        .v-modal-container {
            @media screen and (min-width: 992px) {
                width: 500px;
            }
            @media screen and (min-width: 768px) {
               width: 600px;
            }
            margin: 30px auto;
            margin-top: 70px;
            .v-modal-header h3 {
                color: #42b983;
            }

        }
    }
    .modal-enter {
        opacity: 0;
    }
    .modal-leave-active {
        opacity: 0;
    }
    .modal-enter .v-modal-container,
    .modal-leave-active .v-modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
</style>
