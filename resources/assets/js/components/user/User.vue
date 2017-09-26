<template lang="html">
    <div>
        <user-header :user="user"/>
        <div class="container">
            <div class="row">
                <router-view></router-view>
                <div class="col-xl-3 pull-xl-9 col-lg-3 pull-lg-9 col-md-12 col-sm-12 col-xs-12">
                    <div class="ui-block">
                        <div class="your-profile">
                            <div class="ui-block-title ui-block-title-small">
                                <h6 class="title">{{ $t('user.profile.your_profile') }}</h6>
                            </div>

                            <div id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="card">
                                    <div class="card-header" role="tab" id="headingOne">
                                        <h6 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                {{ $t('user.profile.profile_setting') }}
                                                <svg class="olymp-dropdown-arrow-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-dropdown-arrow-icon"></use></svg>
                                            </a>
                                        </h6>
                                    </div>

                                    <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                        <ul class="your-profile-menu">
                                            <li>
                                                <router-link to="/settings/profile">{{ $t('user.profile.personal_info') }}</router-link>
                                            </li>
                                            <li>
                                                <router-link to="/settings/password">{{ $t('user.profile.change_pass') }}</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import UserHeader from './Header'
export default {
    metaInfo() {
        return {
            title: this.$t('user.label.account_setting')
        }
    },
    computed: mapState('auth', ['user']),
    components: {
        UserHeader
    }
}
</script>

<style lang="scss" scoped>
    .router-link-active {
        color: #515365;
    }
    .top-header-thumb {
        >img {
            max-height: 500px;
            object-fit: cover;
        }
    }
</style>
