<template>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="ui-block">
                    <div class="ui-block-content">
                        <div class="monthly-indicator">
                            <a href="#" class="btn btn-control bg-breez">
                                <svg class="olymp-stats-arrow"><use xlink:href="/frontend/icons/icons.svg#olymp-stats-arrow"></use></svg>
                            </a>
                            <div class="monthly-count">
                                {{ $t('events.donation.goal') }}: {{ goals.goal }} {{ goals.donation_type.quality.name }} {{ goals.donation_type.name }}
                                <span class="period">{{ $t('events.donation.goal_des') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                <div class="ui-block">
                    <div class="ui-block-content">
                        <div class="monthly-indicator">
                            <a href="#" class="btn btn-control bg-primary negative">
                                <svg class="olymp-stats-arrow"><use xlink:href="/frontend/icons/icons.svg#olymp-stats-arrow"></use></svg>
                            </a>
                            <div class="monthly-count">
                                {{ $t('events.donation.received') }}: {{ received }} {{ goals.donation_type.quality.name }} {{ goals.donation_type.name }}
                                <span class="indicator negative">{{ Math.round(received/goals.goal*100) }}%</span>
                                <span class="period">{{ $t('events.donation.with') }} {{ goals.donations.length }} {{ $t('events.donation.donations') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="progress">
                    <div
                        class="progress-bar progress-bar-striped active bg-primary"
                        role="progressbar"
                        :style="{ width: (received/goals.goal > 1 ? goals.goal/received : received/goals.goal) * 100 + '%'}"
                        :aria-valuenow="received/goals.goal*100"
                        aria-valuemin="0"
                        :aria-valuemax="received/goals.goal > 1 ? Math.round(received/goals.goal*100) : 100">
                        {{  received/goals.goal > 1 ? 100 : Math.round(received/goals.goal*100) }}%
                    </div>
                    <div
                        class="progress-bar progress-bar-striped bg-success active"
                        role="progressbar"
                        style="width: 100%"
                        v-show="received/goals.goal > 1">
                        {{ Math.round(received/goals.goal*100)- 100 }}% {{ $t('events.donation.over') }}
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="ui-block">
                    <div class="ui-block-title">
                        <h6 class="title">{{ $t('events.donation.list_support') }}</h6>
                        <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg></a>
                    </div>
                    <ul class="notification-list">
                        <li v-for="donation in goals.donations">
                            <div class="author-thumb">
                                <img :src="donation.user.image_thumbnail" alt="author" style="max-height: 100%">
                            </div>
                            <div class="notification-event">
                                <a href="#" class="h6 notification-friend">{{ donation.user.name }}</a> donate {{ donation.value }} {{ goals.donation_type.quality.name }} {{ goals.donation_type.name }}.
                                <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">{{ donation.donated_at }}</time></span>
                            </div>
                                <span class="notification-icon">
                                    <svg class="olymp-comments-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-comments-post-icon"></use></svg>
                                </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    export default {
        computed: {
            ...mapState('event', ['event']),
            goals() {
                return this.event.complete_percent.filter(goal => goal.id == this.$route.params.id)[0]
            },
            received() {
                return this.goals.donations.reduce((sum, value) => sum + value.value, 0)
            }
        },
        mounted() {
            console.log(this.goals)
        }
    }
</script>
<style lang="scss">
    .notification-list {
        min-height: 0;
    }
</style>
