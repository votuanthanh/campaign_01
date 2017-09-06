<template>
    <aside class="col-xl-3 pull-xl-6 col-lg-6 pull-lg-0 col-md-6 col-sm-12 col-xs-12">
        <div class="left-home-box">
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
            <campaign-involve></campaign-involve>
        </div>
    </aside>
</template>

<script>
    import CampaignInvolve from './CampaignInvolve.vue'
    export default {
        components: {
            CampaignInvolve,
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
</style>
