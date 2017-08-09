<template lang="html">
    <div class="row">
        <router-view></router-view>
        <left-owner-campaign></left-owner-campaign>
    </div>
</template>

<script>
    import LeftOwnerCampaign from './LeftOwnerCampaign.vue'

    export default {
        components: {
           LeftOwnerCampaign
        }
    }
</script>

<style lang="scss">
</style>
