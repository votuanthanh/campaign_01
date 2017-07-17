<template lang="html">
    <transition name="modal">
        <div class="modal v-modal-mask" id="choose-from-my-photo" style="display: block" v-show="show" @click="onClose" transition="modal">
            <div class="modal-dialog ui-block window-popup choose-from-my-photo" @click.stop>
                <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close" @click="onClose">
                    <svg class="olymp-close-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-close-icon"></use></svg>
                </a>
                <div class="ui-block-title">
                    <h6 class="title"><slot name="header"></slot></h6>
                </div>
                <div class="tab-content">
                    <slot></slot>
                </div>
                <slot name="footer"></slot>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: ['show'],
    methods: {
        onClose() {
            this.$emit('update:show', false)
        },
    },
    mounted() {
        document.addEventListener("keydown", (e) => {
            if (this.show && e.keyCode == 27) {
                this.onClose()
            }
        });
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
      display: table;
      transition: opacity .3s ease;
    }
    .modal-dialog {
        overflow-y: initial !important
    }
    .tab-content {
        max-height: 50vh;
        overflow-y: auto;
    }
    .ui-block-content, .modal-footer {
        padding-bottom: 0;
    }
</style>
