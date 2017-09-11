<template lang="html">
    <div>
        <div class="main-header bg-account">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 col-md-8 offset-md-2 col-sm-12 col-xs-12">
                        <div class="main-header-content">
                            <h1>Your Account Dashboard</h1>
                            <p>Welcome to your account dashboard! Here you’ll find everything you need to change your
                                profile information, settings, read notifications and requests, view your latest messages,
                                change your pasword and much more! Also you can create or manage your own favourite page, have fun!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <img class="img-bottom" src="/images/account-bottom.png" alt="friends">
        </div>

        <div class="container">
            <div class="row">
                <router-view></router-view>
                <div class="col-xl-3 pull-xl-9 col-lg-3 pull-lg-9 col-md-12 col-sm-12 col-xs-12">
                    <div class="ui-block">
                        <div class="your-profile">
                            <div class="ui-block-title ui-block-title-small">
                                <h6 class="title">Your PROFILE</h6>
                            </div>

                            <div id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="card">
                                    <div class="card-header" role="tab" id="headingOne">
                                        <h6 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Profile Settings
                                                <svg class="olymp-dropdown-arrow-icon"><use xlink:href="icons/icons.svg#olymp-dropdown-arrow-icon"></use></svg>
                                            </a>
                                        </h6>
                                    </div>

                                    <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                        <ul class="your-profile-menu">
                                            <li>
                                                <router-link to="/settings/profile">Personal Information</router-link>
                                            </li>
                                            <li>
                                                <router-link to="/settings/settings">Account Settings</router-link>
                                            </li>
                                            <li>
                                                <router-link to="/settings/password">Change password</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            <div class="ui-block-title">
                                <a href="#" class="h6 title">Notifications</a>
                                <a href="#" class="items-round-little bg-primary">8</a>
                            </div>
                            <div class="ui-block-title">
                                <a href="#" class="h6 title">Chat / Messages</a>
                            </div>
                            <div class="ui-block-title">
                                <a href="#" class="h6 title">Friend Requests</a>
                                <a href="#" class="items-round-little bg-blue">4</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    metaInfo() {
        return {
            title: this.$t('user.label.account_setting')
        }
    }
}
</script>

<style lang="scss" scoped>
    .router-link-active {
        color: #515365;
    }
</style>
