<template>
    <div>
        <div class="loader" v-if="loading"></div>
        <!-- Top Header -->
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="ui-block">
                        <div class="top-header">
                            <div class="top-header-thumb">
                                <img :src="currentPageUser.head_photo" alt="nature">
                            </div>
                            <div class="profile-section">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 ">
                                        <ul class="profile-menu">
                                            <li>
                                                <router-link :to="'/user/' + userId">Timeline</router-link>
                                            </li>
                                            <li>
                                                <router-link :to="'/user/' + userId + '/about'">About</router-link>
                                            </li>
                                            <li>
                                                <router-link :to="'/user/' + userId + '/followers'">Followers</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-5 offset-lg-2 col-md-5 offset-md-2">
                                        <ul class="profile-menu">
                                            <li>
                                                <router-link :to="'/user/' + userId + '/photo'">Photos</router-link>
                                            </li>
                                            <li>
                                                <router-link :to="'/user/' + userId + '/video'">Videos</router-link>
                                            </li>
                                            <li>
                                                <div class="more">
                                                    <svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                                                    <ul class="more-dropdown more-with-triangle">
                                                        <li>
                                                            <a href="#">Report Profile</a>
                                                        </li>
                                                        <li>
                                                            <a href="#">Block Profile</a>
                                                        </li>
                                                        <li>
                                                            <router-link :to="`/user/${$route.params.id}/following-campaign`">{{ $t('user.label.following_campaign') }}</router-link>
                                                        </li>
                                                        <li>
                                                            <router-link :to="`/user/${$route.params.id}/joined-campaign`">{{ $t('user.label.joined_campaign') }}</router-link>
                                                        </li>
                                                        <li>
                                                            <router-link :to="`/user/${$route.params.id}/owned-campaign`">{{ $t('user.label.owned_campaign') }}</router-link>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="control-block-button">
                                    <a href="35-YourAccount-FriendsRequests.html" class="btn btn-control bg-blue">
                                        <svg class="olymp-happy-face-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use></svg>
                                    </a>
                                    <a href="#" class="btn btn-control bg-purple">
                                        <svg class="olymp-chat---messages-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-chat---messages-icon"></use></svg>
                                    </a>
                                    <div class="btn btn-control bg-primary more">
                                        <svg class="olymp-settings-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-settings-icon"></use></svg>
                                        <ul class="more-dropdown more-with-triangle triangle-bottom-right">
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#update-header-photo">Update Profile Photo</a>
                                            </li>
                                            <li>
                                                <a href="#" data-toggle="modal" data-target="#update-header-photo">Update Header Photo</a>
                                            </li>
                                            <li>
                                                <router-link :to="'/settings/profile'">Account Settings</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="top-header-author">
                                <a href="02-ProfilePage.html" class="author-thumb">
                                    <img :src="currentPageUser.url_file" alt="author">
                                </a>
                                <div class="author-content">
                                    <a href="02-ProfilePage.html" class="h4 author-name">{{ currentPageUser.name }}</a>
                                    <div class="country">{{ currentPageUser.address }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    import noty from '../../helpers/noty'
    import { mapState } from 'vuex'
    import { get } from '../../helpers/api'

    export default {
        data: () => ({
            currentPageUser: {},
            loading: true
        }),
        created() {
            this.getUser();
        },
        computed: {
            userId: function () {
                return this.$route.params.id
            }
        },
        watch: {
            // call again the method if the route changes
            '$route': 'getUser'
        },
        methods: {
            getUser() {
                this.loading = true
                get('user/' + this.userId + '/get-user')
                    .then(response => {
                        this.loading = false
                        this.currentPageUser = response.data.data
                    })
                    .catch(function(error) {
                       noty({
                            text: this.$i18n.t('messages.create_fail'),
                            type: 'error',
                            force: false,
                            container: false
                        })
                    });
            },
        }
    }
</script>

<style lang="scss">
    .friend-avatar {
        .author-thumb {
            >img {
                width: 92px;
                height: 92px;
            }
        }
    }
</style>
