<template lang="html">
    <div class="wrapper">
        <left-sidebar-desktop></left-sidebar-desktop>
        <left-sidebar-mobile></left-sidebar-mobile>

        <right-sidebar-desktop></right-sidebar-desktop>
        <right-sidebar-mobile></right-sidebar-mobile>

        <header-desktop></header-desktop>
        <header-mobile></header-mobile>

        <div class="header-spacer header-spacer-small"></div>

        <div class="middle-container">
            <router-view></router-view>
        </div>

        <popup-chat></popup-chat>
    </div>
</template>

<script>
import LeftSidebarDesktop from './partials/LeftSidebarDesktop.vue'
import LeftSidebarMobile from './partials/LeftSidebarMobile.vue'
import RightSidebarDesktop from './partials/RightSidebarDesktop.vue'
import RightSidebarMobile from './partials/RightSidebarMobile.vue'
import HeaderDesktop from './partials/HeaderDesktop.vue'
import HeaderMobile from './partials/HeaderMobile.vue'
import PopupChat from './partials/PopupChat.vue'
import { mapState, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState('auth', {
            user: state => state.user
        })
    },
    components: {
        LeftSidebarDesktop,
        LeftSidebarMobile,
        RightSidebarDesktop,
        RightSidebarMobile,
        HeaderDesktop,
        HeaderMobile,
        PopupChat
    },
    mounted() {
        $.material.init()
    },
}
</script>

<style lang="scss">
.middle-container {
    min-height: 1150px;
}
</style>
