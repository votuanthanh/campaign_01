<template>
    <div class="follow">
        <div class="load-follower loader" v-if="loading">
        </div>
        <!--end Top Header -->
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="ui-block">
                        <div class="ui-block-title">
                            <div class="h6 title">{{ this.info }}</div>
                            <form class="w-search">
                                <div class="form-group with-button">
                                    <input class="form-control" @input="search" v-model="searchKey" type="text" placeholder="Search Friends...">
                                    <button>
                                        <svg class="olymp-magnifying-glass-icon">
                                            <use xlink:href="/frontend/icons/icons.svg#olymp-magnifying-glass-icon"></use>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                            <div class="more">
                                <svg class="olymp-three-dots-icon">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                </svg>
                                <ul class="more-dropdown">
                                    <li><router-link :to="'/user/' + currentPageUser.id + '/followings'" class="h5 author-name">List following</router-link></li>
                                    <li><a>{{ $t('user.follow.block') }}</a></li>
                                    <li><a>{{ $t('user.follow.turn_off_notifications') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Friends -->
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-6" v-for="(user, index) in followers">
                    <div class="ui-block animated zoomIn">
                        <div class="friend-item">
                            <div class="friend-header-thumb">
                                <img :src="user.head_photo" alt="friend">
                            </div>
                            <div class="friend-item-content">
                                <div class="more">
                                    <svg class="olymp-three-dots-icon">
                                        <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                                    </svg>
                                    <ul class="more-dropdown">
                                        <li><a @click="follow($event, user.id)">{{ parseTextRelation(user) }}</a></li>
                                        <li><a>{{ $t('user.follow.block') }}</a></li>
                                        <li><a>{{ $t('user.follow.turn_off_notifications') }}</a></li>
                                    </ul>
                                </div>
                                <div class="friend-avatar">
                                    <div class="author-thumb">
                                        <img :src="user.url_file" alt="author">
                                    </div>
                                    <div class="author-content">
                                        <router-link :to="{ name: 'user', params: { id: user.id } }" class="h5 author-name">{{ user.name }}</router-link>
                                        <div class="country">{{ user.address }}</div>
                                    </div>
                                </div>
                                <div class="swiper-container" ref="swiper" data-slide="fade">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <div class="friend-count" data-swiper-parallax="-500">
                                                <a class="friend-count-item">
                                                    <div class="h6">{{ user.followers.length }}</div>
                                                    <div class="title">{{ $t('user.follow.followers') }}</div>
                                                </a>
                                                <a class="friend-count-item">
                                                    <div class="h6">240</div>
                                                    <div class="title">{{ $t('user.follow.photo') }}</div>
                                                </a>
                                                <a class="friend-count-item">
                                                    <div class="h6">16</div>
                                                    <div class="title">{{ $t('user.follow.video') }}</div>
                                                </a>
                                            </div>
                                            <div class="control-block-button" data-swiper-parallax="-100">
                                                <a class="btn btn-control bg-blue">
                                                    <svg class="olymp-happy-face-icon">
                                                        <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
                                                    </svg>
                                                </a>
                                                <a :class="((!user.followed.length) ? 'red ' : '') + 'btn btn-control bg-purple link-svg' + user.id">
                                                    <svg :class="'olymp-' + ((!user.followed.length) ? 'close-icon' : 'check-icon')">
                                                        <use :xlink:href="'/frontend/icons/icons.svg#olymp-' + ((!user.followed.length) ? 'close-icon' : 'check-icon')"></use>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <p class="friend-about" data-swiper-parallax="-500">
                                                {{ $t('user.follow.birthday') }}: {{ user.birthday }}<br>
                                                {{ $t('user.follow.gender') }}: {{ (user.gender) ? $t('user.follow.male') : $t('user.follow.female') }}<br>
                                                {{ $t('user.follow.email') }}: {{ slipEmail(user.email) }}<br>
                                                {{ $t('user.follow.phone') }}: {{ user.phone }}<br>
                                            </p>
                                            <div class="friend-since" data-swiper-parallax="-100">
                                                <h6>{{ $t('user.follow.follow_since') }}:</h6>
                                                <div class="h6">{{ user.pivot.created_at }}</div>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="spinner-load-more" v-if="loadingMore">
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
            <span>{{ $t('user.follow.loading') }}</span>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import DatePicker from '../libs/DatePicker.vue'
    import Modal from '../libs/Modal.vue'
    import noty from '../../helpers/noty'
    import { get, patch } from '../../helpers/api'
    export default {
        data: () => ({
            followers: [],
            currentPageUser: {},
            info: '',
            dataNotEmpty: true,
            loadingMore: false,
            loading: false,
            pageNumber: 0,
            page: 0,
            checkPageIsFollowers: true,
            searchKey: ''
        }),
        created() {
            this.listUser();
        },
        mounted() {
            $(window).scroll(() => {
                if (this.dataNotEmpty && !this.searchKey) {
                    if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
                        this.loadMore()
                    }
                }
            })
        },
        updated() {
            this.swiper()
        },
        watch: {
            // call again the method if the route changes
            '$route': 'listUser',
        },
        computed: {
            ...mapState('auth', {
                authenticated: state => state.authenticated,
                user: state => state.user
            })
        },
        methods: {
            slipEmail(email) {
                if (email.length > 25) {
                    return email.substring(0, 22) + '...'
                }

                return email
            },
            parseTextRelation(user) {
                return (!user.followed.length) ? this.$i18n.t('user.follow.follow') : this.$i18n.t('user.follow.unfollow')
            },
            listUser() {
                this.page = 1
                this.dataNotEmpty = true
                let link = 'user/' + this.$route.params.id
                this.checkPageIsFollowers = ((this.$route.path).indexOf('followers') >= 0)

                if (this.checkPageIsFollowers) {
                    link += '/followers'
                } else {
                    link += '/followings'
                }

                get(link)
                    .then(response => {
                        let listFollower = response.data.data
                        this.pageNumber = listFollower.total / window.Laravel.pagination.follow
                            + (!(listFollower.total % window.Laravel.pagination.follow) ? 0 : 1 )
                        this.followers = listFollower.data
                        this.currentPageUser = response.data.currentPageUser
                        this.info = (this.checkPageIsFollowers)
                        ? this.$i18n.t('user.follow.info.there_are') + listFollower.total
                            + this.$i18n.t('user.follow.info.people_following') + this.currentPageUser.name + '.'
                        : this.currentPageUser.name + this.$i18n.t('user.follow.info.is_following')
                            + listFollower.total + this.$i18n.t('user.follow.people')
                    })
                    .catch(function(error) {
                        noty({
                            text: this.$i18n.t('messages.load_fail'),
                            type: 'error',
                            force: false,
                            container: false
                        })
                    });
            },
            loadMore() {
                let link = 'user/' + this.$route.params.id

                if (this.checkPageIsFollowers) {
                    link += '/followers/?page=' + (++this.page)
                } else {
                    link += '/followings/?page=' + (++this.page)
                }

                if (this.page > this.pageNumber) {
                    this.dataNotEmpty = false
                } else {
                    this.loadingMore = true
                    get(link)
                        .then(response => {
                            this.followers = $.merge( this.followers, response.data.data.data)
                            this.currentPageUser = response.data.currentPageUser
                            this.loadingMore = false
                        })
                        .catch(function(error) {
                            noty({
                                text: this.$i18n.t('messages.load_fail'),
                                type: 'error',
                                force: false,
                                container: false
                            })
                        });
                }

            },
            search: _.debounce(function () {
                if (!this.searchKey.trim()) {
                    this.listUser()
                } else {
                    let link = 'user/' + this.$route.params.id
                    this.loading = true

                    if (this.checkPageIsFollowers) {
                        link += '/search-followers/' + this.searchKey
                    } else {
                        link += '/search-followings/' + this.searchKey
                    }

                    get(link)
                        .then(response => {
                            this.followers = response.data.data

                            if (!this.followers.length) {
                                this.info = this.$i18n.t('user.follow.info.there_are_no')
                            } else if (this.followers.length == 1) {
                                this.info = this.$i18n.t('user.follow.info.there_is')
                            } else {
                                this.info = this.$i18n.t('user.follow.info.there_are')
                                    + this.followers.length + this.$i18n.t('user.follow.info.result_for')
                            }

                            this.info += '"' + this.searchKey + '".'
                            this.loading = false
                        })
                        .catch(function(error) {
                            noty({
                                text: this.$i18n.t('messages.load_fail'),
                                type: 'error',
                                force: false,
                                container: false
                            })
                        });
                }
            }, 1000),
            follow(event, id) {
                patch('follow/' + id)
                    .then(response => {
                        if (event.target.textContent == this.$i18n.t('user.follow.follow')) {
                            event.target.innerHTML = this.$i18n.t('user.follow.unfollow')
                            $('.link-svg' + id).removeClass('red')
                            $('.link-svg' + id + '> svg').attr('class', 'olymp-check-icon');
                            $('.link-svg' + id + '> svg > use').attr('xlink:href', '/frontend/icons/icons.svg#olymp-check-icon')
                        } else {
                            event.target.innerHTML = this.$i18n.t('user.follow.follow')
                            $('.link-svg' + id).addClass('red')
                            $('.link-svg' + id + '> svg').attr('class', 'olymp-close-icon')
                            $('.link-svg' + id + '> svg > use').attr('xlink:href', '/frontend/icons/icons.svg#olymp-close-icon')
                        }
                    })
                    .catch(function(error) {
                        noty({
                            text: this.$i18n.t('messages.load_fail'),
                            type: 'error',
                            force: false,
                            container: false
                        })
                    });
            },
            swiper() {
                var initIterator = 0;
                var $breakPoints = false;
                var swipers = {}
                $('.swiper-container').each(function () {
                    var $t = $(this);
                    var index = 'swiper-unique-id-' + initIterator;

                    $t.addClass('swiper-' + index + ' initialized').attr('id', index);
                    $t.find('.swiper-pagination').addClass('pagination-' + index);

                    var $effect = ($t.data('effect')) ? $t.data('effect') : 'slide',
                        $crossfade = ($t.data('crossfade')) ? $t.data('crossfade') : true,
                        $loop = ($t.data('loop') == false) ? $t.data('loop') : true,
                        $showItems = ($t.data('show-items')) ? $t.data('show-items') : 1,
                        $scrollItems = ($t.data('scroll-items')) ? $t.data('scroll-items') : 1,
                        $scrollDirection = ($t.data('direction')) ? $t.data('direction') : 'horizontal',
                        $mouseScroll = ($t.data('mouse-scroll')) ? $t.data('mouse-scroll') : false,
                        $autoplay = ($t.data('autoplay')) ? parseInt($t.data('autoplay'), 10) : 0,
                        $autoheight = ($t.hasClass('auto-height')) ? true: false,
                        $slidesSpace = ($showItems > 1) ? 20 : 0;

                    if ($showItems > 1) {
                        $breakPoints = {
                            480: {
                                slidesPerView: 1,
                                slidesPerGroup: 1
                            },
                            768: {
                                slidesPerView: 2,
                                slidesPerGroup: 2
                            }
                        }
                    }

                    swipers['swiper-' + index] = new Swiper('.swiper-' + index, {
                        pagination: '.pagination-' + index,
                        paginationClickable: true,
                        direction: $scrollDirection,
                        mousewheelControl: $mouseScroll,
                        mousewheelReleaseOnEdges: $mouseScroll,
                        slidesPerView: $showItems,
                        slidesPerGroup: $scrollItems,
                        spaceBetween: $slidesSpace,
                        keyboardControl: true,
                        setWrapperSize: true,
                        preloadImages: true,
                        updateOnImagesReady: true,
                        autoplay: $autoplay,
                        autoHeight: $autoheight,
                        loop: $loop,
                        breakpoints: $breakPoints,
                        effect: $effect,
                        fade: {
                            crossFade: $crossfade
                        },
                        parallax: true,
                        onSlideChangeStart: function (swiper) {
                            var sliderThumbs = $t.siblings('.slider-slides');
                            if (sliderThumbs.length) {
                                sliderThumbs.find('.slide-active').removeClass('slide-active');
                                var realIndex = swiper.slides.eq(swiper.activeIndex).attr('data-swiper-slide-index');
                                sliderThumbs.find('.slides-item').eq(realIndex).addClass('slide-active');
                            }
                        }
                    });
                    initIterator++;
                });
            }
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

    .load-follower {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        z-index: 3;
        background: url(/images/loading.gif) 50% 10% no-repeat rgba(235, 242, 242, 0.66);
        opacity: .6;
        background-size: 120px 120px;
    }

    .spinner-load-more {
        text-align: center;
    }

    .follow {
        margin-bottom: 25px;
        position: relative;
    }

    .friend-avatar {
        margin-bottom: 20px;
    }

    .swiper-container {
        padding-bottom: 30px;
    }

    .spinner-search {
        float: right;
        margin-right: 10px;
    }

    .bg-purple {
        background-color: #429175;
    }

    .bg-purple {
        background-color: #fe9c52;
    }

    .btn-control {
        border-radius: 100%;
        width: 50px;
        height: 50px;
        line-height: 54px;
        padding: 0;
        font-size: 26px;
        fill: #fff;
    }

    [class^="olymp-"], [class*=" -icon"] {
        height: 22px;
        width: 22px;
    }

    .red {
        background: #f97070 !important;
    }

    .more-dropdown > li > a {
        color: #515365 !important;
        &:hover {
            color: #fc5c3a !important;
        }
    }
</style>
