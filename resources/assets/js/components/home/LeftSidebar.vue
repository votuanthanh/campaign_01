<template>
    <aside class="col-xl-3 pull-xl-6 col-lg-6 pull-lg-0 col-md-6 col-sm-12 col-xs-12">
        <div class="ui-block">
            <div class="campaign-popup widget w-create-fav-page">
                <div class="icons-block">
                    <svg class="olymp-star-icon left-menu-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-star-icon"></use>
                    </svg>
                </div>
                <div class="content">
                    <span>{{ $t('homepage.be_like') }}</span>
                    <h3 class="title">{{ $t('homepage.create_campaign') }}!</h3>
                    <router-link class="btn btn-bg-secondary btn-sm" :to="{ name: 'campaign.create'}">
                        {{ $t('homepage.start_now') }}!
                    </router-link>
                </div>
            </div>
        </div>
        <div class="ui-block" style="display: none;">
            <div class="widget w-wethear">
                <a href="#" class="more">
                    <svg class="olymp-three-dots-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                </a>
                <div class="wethear-now inline-items">
                    <div class="temperature-sensor">64°</div>
                    <div class="max-min-temperature">
                        <span>58°</span>
                        <span>76°</span>
                    </div>
                    <svg class="olymp-weather-partly-sunny-icon">
                        <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-partly-sunny-icon"></use>
                    </svg>
                </div>
                <div class="wethear-now-description">
                    <div class="climate">Partly Sunny</div>
                    <span>Real Feel: <span>67°</span></span>
                    <span>Chance of Rain: <span>49%</span></span>
                </div>
                <ul class="weekly-forecast">
                    <li>
                        <div class="day">sun</div>
                        <svg class="olymp-weather-sunny-icon">
                            <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-sunny-icon"></use>
                        </svg>
                        <div class="temperature-sensor-day">60°</div>
                    </li>
                    <li>
                        <div class="day">mon</div>
                        <svg class="olymp-weather-partly-sunny-icon">
                            <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-partly-sunny-icon"></use>
                        </svg>
                        <div class="temperature-sensor-day">58°</div>
                    </li>
                    <li>
                        <div class="day">tue</div>
                        <svg class="olymp-weather-cloudy-icon">
                            <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-cloudy-icon"></use>
                        </svg>
                        <div class="temperature-sensor-day">67°</div>
                    </li>
                    <li>
                        <div class="day">wed</div>
                        <svg class="olymp-weather-rain-icon">
                            <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-rain-icon"></use>
                        </svg>
                        <div class="temperature-sensor-day">70°</div>
                    </li>

                    <li>
                        <div class="day">thu</div>
                        <svg class="olymp-weather-storm-icon">
                            <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-storm-icon"></use>
                        </svg>
                        <div class="temperature-sensor-day">58°</div>
                    </li>
                    <li>
                        <div class="day">fri</div>
                        <svg class="olymp-weather-snow-icon">
                            <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-snow-icon"></use>
                        </svg>
                        <div class="temperature-sensor-day">68°</div>
                    </li>
                    <li>
                        <div class="day">sat</div>
                        <svg class="olymp-weather-wind-icon-header">
                            <use xlink:href="/frontend/icons/icons-weather.svg#olymp-weather-wind-icon-header"></use>
                        </svg>
                        <div class="temperature-sensor-day">65°</div>
                    </li>
                </ul>
                <div class="date-and-place">
                    <h5 class="date">Saturday, March 26th</h5>
                    <div class="place">San Francisco, CA</div>
                </div>
            </div>
        </div>
        <div class="ui-block">
            <div class="ui-block-title">
                <h6 class="title">{{ $t('homepage.campaigns_may_join') }}</h6>
                <a href="#" class="more">
                    <svg class="olymp-three-dots-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use>
                    </svg>
                </a>
            </div>
            <ul class="widget w-friend-pages-added notification-list friend-requests">
                <li class="inline-items" v-for="campaign in listCampaign">
                    <div class="author-thumb">
                        <img :src="campaign.media[0].image_medium" alt="author">
                    </div>
                    <div class="notification-event">
                        <router-link class="h6 notification-friend" :to="{ name: 'campaign.timeline', params: { slug: campaign.slug }}">
                            <span v-if="campaign.title.length < 51">{{ campaign.title }}</span>
                            <span v-else>{{ campaign.title.substr(0, 50) }}...</span>
                        </router-link>
                        <span class="chat-message-item">
                            <span class="tag-info" v-for="tag in campaign.tags">{{ tag.name }}</span>
                        </span>
                    </div>
                    <span class="notification-icon">
                        <router-link :to="{ name: 'campaign.timeline', params: { slug: campaign.slug }}">
                            <svg class="olymp-star-icon">
                                <use xlink:href="/frontend/icons/icons.svg#olymp-star-icon"></use>
                            </svg>
                        </router-link>
                    </span>
                </li>
            </ul>
        </div>
    </aside>
</template>


<script>
    import noty from '../../helpers/noty'
    import { get } from '../../helpers/api'

    export default {
        data: () => ({
            listCampaign: []
        }),
        created() {
            this.getCampaignInvolve()
        },
        methods: {
            getCampaignInvolve() {
                get('campaign/involve')
                    .then(res => {
                        this.listCampaign = res.data.campaignInvolve
                    })
                    .catch(err => {
                        noty({
                            text: this.$i18n.t('messages.connection_error'),
                            container: false,
                            force: true
                        })
                    })
            },
        }
    }
</script>

<style lang="scss" scoped>
    .w-create-fav-page {
        padding: 35px 25px 50px 25px;
        background-color: #ff5e3a;
        .olymp-star-icon {
            width: 35px;
            height: 35px;
        }
    }

    .author-thumb img {
        width: 36px;
        height: 36px;
    }

    .tag-info {
        padding: 2px 7px;
        color: white;
        display: inline-block;
        margin: auto 1px 3px 1px;
        border-radius: 4px;
        background: #57b6ff;
        font-weight: bold;
    }

    .notification-list {
        li {
            padding: 15px 15px;
        }
    }
</style>
