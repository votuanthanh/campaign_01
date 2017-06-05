<template lang="html">
    <div class="wrapper">
        <left-sidebar-desktop></left-sidebar-desktop>
        <left-sidebar-mobile></left-sidebar-mobile>

        <right-sidebar-desktop></right-sidebar-desktop>
        <right-sidebar-mobile></right-sidebar-mobile>

        <header-desktop></header-desktop>
        <header-mobile></header-mobile>

        <div class="header-spacer header-spacer-small"></div>

        <div class="container"></div>

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
import { mapState } from 'vuex'
import axios from 'axios'

export default {
    computed: {
        ...mapState('auth', {
            authenticated: state => state.authenticated
        }),
    },
    created() {
        const accessToken = `eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjMyMjJiOWJkNTE0ZmM0ZWE2YWM2MjI5NmZkODRhZTRkZGEzOTBiYTZlNDkzN2U3NTk0ZmE1MjgwZTQyY2U0YmM0M2YwZTdhZjYxYTJkZTU2In0.eyJhdWQiOiIxIiwianRpIjoiMzIyMmI5YmQ1MTRmYzRlYTZhYzYyMjk2ZmQ4NGFlNGRkYTM5MGJhNmU0OTM3ZTc1OTRmYTUyODBlNDJjZTRiYzQzZjBlN2FmNjFhMmRlNTYiLCJpYXQiOjE0OTY1NDE5MTMsIm5iZiI6MTQ5NjU0MTkxMywiZXhwIjoxNTI4MDc3OTEzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.mI3dlsoTBMhZl9EG0FsQmliob2mH8Vi1fOajPmiGalrT17Rj6jvnaww1WLa2OAqntdtoXYO5I2QhLcmc-k3Ax9-0xuT3hKhRCwVf5qpgBpJ9bXdjyHUrVI1xkJGZSuZNO8qdni6VB80Xkpg-WviYzLZyjr39M2DgkxXxgIsoe-PE9QZVWB3s6mZQIuTvB7OJc83fElYxZqGKwvZ8qSx20H9o_Sa5Fne4paiPgiiKw5PGQCqcs9N3eG3jNIyT6YcJlocrCLFwAKla7shhEnYLcb5RgFlTvVjhfbg2j0sK-T5mQedPzhoaUOG4ldJticUQ8OWzNAG6ejgJKoVkeUDT_CZc6kHaNxZ6IuFHMaxSEgiHFP8Kia2d11KNRsAMVUf7ucKYLiFpXBUGV-Abp0ny0_aqzxPXeV1rZ9pxgKJzlXfmze7XM2Cb_W-vO2-cGt8YGmEo6fopWWvCNNljcA7EbrvwwzSHmtah-8rML_n0HAtjAOhWarl29LI_tREH5wisEfA404E-OSafOpX7Tu7ox7ASZ5RoWVdmdWboVFROR0-4J41hZE9ZTGHy36rgJWVFt-1fZIxZUnLtzxQYuP1et2IZkaVI78ap6Au8SLU7hd_UKCUE4DfWDfONFYA-csEeaJAQfGaRIios-CHYKg1kJOkAY4KADYmGZQahVxlqM0Y`

        this.$store.dispatch('auth/login', accessToken)
    },
    components: {
        LeftSidebarDesktop,
        LeftSidebarMobile,
        RightSidebarDesktop,
        RightSidebarMobile,
        HeaderDesktop,
        HeaderMobile,
        PopupChat
    }
}
</script>

<style lang="scss">
</style>
