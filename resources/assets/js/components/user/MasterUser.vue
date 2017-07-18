<template>
    <div>
        <div class="loader" v-if="loading"></div>
        <!-- Top Header -->
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="ui-block">
                        <div class="top-header">
                            <div class="top-header-thumb clickable" @click="showHeader = true" v-if="authUser.id == currentPageUser.id">
                                <img :src="authUser.head_photo" alt="nature">
                            </div>
                            <div class="top-header-thumb" v-else>
                                <img :src="currentPageUser.head_photo" alt="nature">
                            </div>
                            <div class="profile-section">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 ">
                                        <ul class="profile-menu">
                                            <li>
                                                <router-link :to="{ name: 'user.timeline', params: { userId }}">Timeline</router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{ name: 'user.about', params: { userId }}">About</router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{ name: 'user.followers', params: { userId }}">Followers</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-5 offset-lg-2 col-md-5 offset-md-2">
                                        <ul class="profile-menu">
                                            <li>
                                                <router-link :to="{ name: 'user.photo', params: { userId }}">Photos</router-link>
                                            </li>
                                            <li>
                                                <router-link :to="{ name: 'user.video', params: { userId }}">Videos</router-link>
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
                                                            <router-link :to="{ name: 'user.campaign', params: { id: userId, path: 'following-campaign' }}">{{ $t('user.label.following_campaign') }}</router-link>

                                                        </li>
                                                        <li>
                                                            <router-link :to="{ name: 'user.campaign', params: { id: userId, path: 'joined-campaign' }}">{{ $t('user.label.joined_campaign') }}</router-link>
                                                        </li>
                                                        <li>
                                                            <router-link :to="{ name: 'user.campaign', params: { id: userId, path: 'owned-campaign' }}">{{ $t('user.label.owned_campaign') }}</router-link>
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
                                    <div class="btn btn-control bg-primary more" v-show="authUser.id == currentPageUser.id">
                                        <svg class="olymp-settings-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-settings-icon"></use></svg>
                                        <ul class="more-dropdown more-with-triangle triangle-bottom-right">
                                            <li>
                                                <a href="#" @click.prevent="showAvatar = true">{{ $t('user.upload.update_avatar') }}</a>
                                            </li>
                                            <li>
                                                <a href="#" @click.prevent="showHeader = true">{{ $t('user.upload.update_header_photo') }}</a>
                                            </li>
                                            <li>
                                                <router-link :to="{ name: 'setting.profile' }">Account Settings</router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="top-header-author">
                                <a href="#" class="author-thumb">
                                    <img :src="authUser.url_file" alt="author" v-if="authUser.id == currentPageUser.id" @click.prevent="showAvatar = true">
                                    <img :src="currentPageUser.url_file" alt="author" v-else @click.prevent>
                                </a>
                                <div class="author-content">
                                    <a href="02-ProfilePage.html" class="h4 author-name">{{ currentPageUser.name }}</a>
                                    <div class="country">{{ currentPageUser.address }}</div>
                                </div>
                                <input
                                    type="file"
                                    name="url_file"
                                    style="display: none"
                                    id="url_file"
                                    multiple=""
                                    v-validate="'image|size:1024'"
                                    @change="filesChange($event, 'avatar')">
                                <input
                                    type="file"
                                    name="head_photo"
                                    style="display: none"
                                    id="head_photo"
                                    multiple=""
                                    v-validate="'image|size:1024'"
                                    @change="filesChange($event, 'header')">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <router-view></router-view>

        <image-modal :show.sync="showAvatar">
            <template slot="header">{{ $t('user.upload.update_avatar') }}</template>
            <a href="#" class="upload-photo-item" @click.prevent="showUpload('#url_file')">
                <svg class="olymp-computer-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-computer-icon"></use></svg>

                <h6>{{ $t('user.upload.upload_photo') }}</h6>
                <span>{{ $t('user.upload.upload_photo_desc') }}</span><br/>
                <span v-show="errors.has('url_file')" class="material-input text-danger">
                    {{ errors.first('url_file') }}
                </span>
            </a>

            <a href="#" class="upload-photo-item" @click.prevent="showAllAvatar = true">

                <svg class="olymp-photos-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-photos-icon"></use></svg>

                <h6>{{ $t('user.upload.choose_from_my_photo') }}</h6>
                <span>{{ $t('user.upload.choose_from_my_photo_desc') }}</span>
            </a>
        </image-modal>

        <image-modal :show.sync="showAllAvatar">
            <template slot="header">{{ $t('user.upload.select_photo') }}</template>
            <div class="ui-block-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-expanded="true" v-if="authUser.media.length">
                    <div class="choose-photo-item" data-mh="choose-item" v-for="image in authUser.media">
                        <div class="radio">
                            <label class="custom-radio">
                                <img :src="image.image_default" alt="photo">
                                <input type="radio" name="optionsRadios" v-model="selectImage.url_file" :value="image.image_default">
                            </label>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>{{ $t('user.upload.no_photo_msg') }}</p>
                </div>
            </div>
            <div class="modal-footer" slot="footer">
                <a href="#"
                    class="btn btn-secondary btn-lg btn--half-width"
                    @click.prevent="showAllAvatar = false">
                    {{ $t('user.upload.cancel') }}
                </a>
                <a href="#"
                    class="btn btn-primary btn-lg btn--half-width"
                    @click.prevent="handleUpdate('avatar ')"
                    v-if="authUser.media.length">
                    {{ $t('user.upload.confirm') }}
                </a>
            </div>
        </image-modal>

        <image-modal :show.sync="showHeader">
            <template slot="header">{{ $t('user.upload.update_header_photo') }}</template>
            <a href="#" class="upload-photo-item" @click.prevent="showUpload('#head_photo')">
                <svg class="olymp-computer-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-computer-icon"></use></svg>

                <h6>{{ $t('user.upload.upload_photo') }}</h6>
                <span>{{ $t('user.upload.upload_photo_desc') }}</span><br/>
                <span v-show="errors.has('head_photo')" class="material-input text-danger">
                    {{ errors.first('head_photo') }}
                </span>
            </a>

            <a href="#" class="upload-photo-item" @click.prevent="showAllImage = true">

                <svg class="olymp-photos-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-photos-icon"></use></svg>

                <h6>{{ $t('user.upload.choose_from_my_photo') }}</h6>
                <span>{{ $t('user.upload.choose_from_my_photo_desc') }}</span>
            </a>
        </image-modal>
        <image-modal :show.sync="showAllImage">
            <template slot="header">{{ $t('user.upload.select_photo') }}</template>
            <div class="ui-block-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-expanded="true" v-if="authUser.media.length">
                    <div class="choose-photo-item" data-mh="choose-item" v-for="image in authUser.media">
                        <div class="radio">
                            <label class="custom-radio">
                                <img :src="image.image_default" alt="photo">
                                <input type="radio" name="optionsRadios" v-model="selectImage.url_file" :value="image.image_default">
                            </label>
                        </div>
                    </div>
                </div>
                <div v-else class="tab-pane active">
                    <p>{{ $t('user.upload.no_photo_msg') }}</p>
                </div>
            </div>
            <div class="modal-footer" slot="footer">
                <a href="#"
                    class="btn btn-secondary btn-lg btn--half-width"
                    @click.prevent="showAllImage = false">
                    {{ $t('user.upload.cancel') }}
                </a>
                <a href="#"
                    class="btn btn-primary btn-lg btn--half-width"
                    @click.prevent="handleUpdate('header')"
                    v-if="authUser.media.length">
                    {{ $t('user.upload.confirm') }}
                </a>
            </div>
        </image-modal>
    </div>
</template>

<script>
    import noty from '../../helpers/noty'
    import { mapState, mapActions } from 'vuex'
    import { get } from '../../helpers/api'
    import ImageModal from '../libs/SelectImageModal.vue'

    export default {
        data: () => ({
            currentPageUser: {},
            loading: true,
            showAvatar: false,
            showAllAvatar: false,
            showHeader: false,
            showAllImage: false,
            selectImage: {
                url_file: ''
            }

        }),
        created() {
            this.getUser();
        },
        computed: {
            ...mapState('auth', {
                authUser: 'user'
            }),
            userId: function () {
                return this.$route.params.id
            }
        },
        watch: {
            // call again the method if the route changes
            '$route': 'getUser'
        },
        methods: {
            ...mapActions('auth', ['updateHeaderPhoto', 'changeAvatar', 'uploadImage']),
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
            showUpload(el) {
                $(el).click()
            },
            filesChange(event, path) {
                this.$validator.validateAll().then(() => {
                    let formData = new FormData()
                    $.each(event.currentTarget.files, (i, file) => {
                        formData.append('uploads[]', file)
                    })
                    this.uploadImage({ path, formData })
                        .then(res => {
                            $.material.init()
                            if (path == 'header')
                                this.showAllImage = true
                            else
                                this.showAllAvatar = true
                        })
                        .catch(err => {})
                })
            },
            handleUpdate(type) {
                if (type == 'header')
                    this.updateHeaderPhoto(this.selectImage)
                else
                    this.changeAvatar(this.selectImage)
                this.showAvatar = false
                this.showAllAvatar = false
                this.showHeader = false
                this.showAllImage = false
            }
        },
        components: {
            ImageModal
        }
    }
</script>

<style lang="scss" scoped>
    .author-thumb {
        >img {
            max-width: 100%;
        }
    }
    .clickable:hover {
        cursor: pointer;
    }
</style>
