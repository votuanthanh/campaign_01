<template>
<transition name="modal">
    <div class="modal v-modal-mask wrap-action" id="private-event" style="display: block" v-show="show">
        <div class="modal-dialog ui-block window-popup event-private-public private-event">
            <a href="#" class="close icon-close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                <svg class="olymp-close-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-close-icon"></use></svg>
            </a>
            <article class="hentry post has-post-thumbnail thumb-full-width private-event">
                <div class="private-event-head inline-items">
                    <img src="/images/avatar77-sm.jpg" alt="author">
                    <div class="author-date">
                        <a class="h3 event-title" href="#">{{ shorten(data.title, false, 55) }}</a>
                        <div class="event__date">
                            <time class="published" datetime="2017-03-24T18:18">
                                #{{ data.hashtag }}
                            </time>
                        </div>
                    </div>
                </div>
                <div class="post-thumb">
                    <img :src="data.media" alt="photo">
                </div>
                <div class="row">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                        <div class="post__author author vcard inline-items">
                            <img :src="data.owner.image_thumbnail" alt="author">
                            <div class="author-date">
                                <a class="h6 post__author-name fn" href="#">{{ data.owner.name }}</a>
                                {{ $t('campaigns.statistic.created_campaign') }}
                                <div class="post__date">
                                    <time class="published" datetime="2017-03-24T18:18">
                                        {{ $t('campaigns.statistic.at') }}
                                        {{ data.created_at }}
                                    </time>
                                </div>
                            </div>
                        </div>
                        <div>
                            {{ shorten(data.description, true, 300) }}
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <div class="event-description">
                            <h6 class="event-description-title">
                                {{ getDay(data.settings, settings.campaigns.status) == 0
                                    ? $t('campaigns.statistic.private_campaign')
                                    : $t('campaigns.statistic.public_campaign') }}
                            </h6>
                            <div class="place inline-items">
                                <span><svg class="olymp-add-a-place-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-add-a-place-icon"></use></svg>
                                {{ data.address }}</span>
                            </div>
                            <div class="place inline-items">
                                <span>
                                    <svg class="olymp-small-calendar-icon">
                                        <use xlink:href="/frontend/icons/icons.svg#olymp-small-calendar-icon"></use>
                                    </svg>
                                    {{ getDay(data.settings, settings.campaigns.start_day) | localeDate }}
                                    <template v-if="getDay(data.settings, settings.campaigns.end_day)">
                                        - {{ getDay(data.settings, settings.campaigns.end_day) | localeDate }}
                                    </template>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post-additional-info inline-items">
                    <a class="post-add-icon inline-items place">
                        <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use></svg>
                        <span>{{ data.number_of_likes }}</span>
                    </a>
                    <a class="post-add-icon inline-items place">
                        <svg class="olymp-speech-balloon-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-month-calendar-icon"></use></svg>
                        <span>{{ data.event_count }} events</span>
                    </a>
                    <div class="float-right">
                        <ul class="friends-harmonic">
                            <li v-for="(user, index) in data.active_users" v-if="index < 5">
                                <a><img :src="user.image_thumbnail" alt="friend"></a>
                            </li>
                            <li class="with-text">
                                {{ data.active_users.length }}
                                {{ $t('campaigns.statistic.user_join_campaign') }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="control-block-button post-control-button">
                    <a href="#" class="btn btn-control" @click.prevent="reload">
                        <svg class="olymp-like-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-weather-refresh-icon"></use></svg>
                    </a>
                    <a href="#" class="btn btn-control" @click.prevent="print">
                        <svg class="olymp-comments-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-blog-icon"></use></svg>
                    </a>
                </div>
            </article>
            <div v-if="data.event_count">
                <ul class="comments-list" v-for="(events, day) in data.events">
                    <li><h3 class="date-title">{{ day | localeDate}}</h3></li>
                    <li v-for="event in events">
                        <div class="post__author author vcard inline-items">
                            <img :src="event.media[0].image_thumbnail" alt="author">
                            <div class="author-date">
                                <a class="h6 post__author-name fn" href="#">{{ event.title }}</a>
                                <div class="post__date">
                                    <time class="published" datetime="2017-03-24T18:18">
                                        {{ $t('campaigns.statistic.created_at') }}
                                        {{ event.created_at | localeDate }}
                                    </time>
                                </div>
                            </div>
                            <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg></a>
                        </div>
                        <div>
                            <a class="place inline-items post-add-icon">
                                <svg class="olymp-add-a-place-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-add-a-place-icon"></use></svg>
                                <span>{{ event.address }}</span>
                            </a>
                            <a class="place inline-items post-add-icon">
                                <svg class="olymp-small-calendar-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-small-calendar-icon"></use></svg>
                                <span>{{ getDay(event.settings, settings.events.start_day) | localeDate }}</span>
                                <span v-if="getDay(event.settings, settings.events.end_day)">
                                    - {{ getDay(event.settings, settings.events.end_day) | localeDate }}
                                </span>
                            </a>
                            <a class="post-add-icon inline-items place">
                                <svg class="olymp-heart-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-heart-icon"></use></svg>
                                <span>{{ event.number_of_likes }}</span>
                            </a>
                        </div>
                        <div>
                            <table class="mt-3 table-bordered table table-sm" v-if="event.goals.length">
                                <thead class="thead-default">
                                    <tr>
                                        <th><a class="place inline-items post-add-icon">
                                            <svg class="olymp-add-a-place-icon">
                                                <use xlink:href="/frontend/icons/icons.svg#olymp-star-icon"></use>
                                            </svg>
                                        </a></th>
                                        <th>{{ $t('events.donation.donate') }}</th>
                                        <th>{{ $t('events.donation.goal') }}</th>
                                        <th>{{ $t('events.donation.received') }}</th>
                                        <th>{{ $t('campaigns.statistic.donation_times') }}</th>
                                        <th>{{ $t('events.expenses_statistic.spent') }}</th>
                                        <th>{{ $t('campaigns.statistic.spent_times') }}</th>
                                        <th class="text-capitalize">{{ $t('events.expenses_statistic.remain') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(goal, i) in event.goals">
                                        <th scope="row">{{ i + 1 }}</th>
                                        <td>{{ goal.donation_type.name + ' (' + goal.donation_type.quality.name + ')' }}</td>
                                        <td>{{ goal.goal }}</td>
                                        <td>
                                            {{ sumValue(goal.donations, 'value') }}
                                            ({{ getPercent(sumValue(goal.donations, 'value'), goal.goal) }} %)
                                        </td>
                                        <td>{{ goal.donations.length }}</td>
                                        <td>
                                            {{ sumValue(goal.expenses, 'cost') }}
                                            ({{ getPercent(sumValue(goal.expenses, 'cost'), sumValue(goal.donations, 'value')) }}%)
                                        </td>
                                        <td>{{ goal.expenses.length }}</td>
                                        <td>
                                            {{ sumValue(goal.donations, 'value') - sumValue(goal.expenses, 'cost') }}
                                            ({{ 100 - getPercent(sumValue(goal.expenses, 'cost'), sumValue(goal.donations, 'value')) }}%)
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p class="h6 post__author-name fn text-center align-middle mt-1">
                    {{ $t('campaigns.statistic.no_event') }}
                </p>
            </div>
            <div class="comment-form inline-items">
                <div class="with-icon-right is-empty float-right">
                    {{ $t('campaigns.statistic.reported_at') }}
                    <strong>{{ data.reported_at }}</strong>
                    (<a href="#" @click.prevent="reload">{{ $t('campaigns.statistic.recreate') }}</a>)
                </div>
            </div>
        </div>
    </div>
</transition>
</template>
<script>
import string from '../../helpers/mixin/string'
export default {
    mixins: [string],
    props: ['show', 'data'],
    data() {
        return {
            settings: window.Laravel.settings
        }
    },
    methods: {
        closeModal() {
            this.$emit('update:show', false)
        },
        reload() {
            this.$emit('reload')
        },
        getDay(settings, key) {
            let filter = settings.filter(setting => {
                return setting.key == key
            })[0]

            return filter ? filter.value : null
        },
        sumValue(object, key) {
            return object.reduce((sum, value) => sum + value[key], 0)
        },
        getPercent(divisor, devide) {
            if (devide == 0)
                return null
            return Math.round(divisor/devide*100)
        },
        print() {
            window.print()
        }
    },
    filters: {
        localeDate(date) {
            return date ? new Date(date).toLocaleDateString(window.Laravel.locale) : null
        }
    },
    mounted() {
        document.addEventListener("keydown", (e) => {
            if (this.show && e.keyCode == 27) {
                this.closeModal()
            }
        })
    }
}
</script>

<style lang="scss" scoped>
    .v-modal-mask {
      position: fixed;
      z-index: 9998;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, .5);
      display: table;
      transition: opacity .3s ease;
    }
    .modal-dialog {
        overflow-y: initial !important;
    }
    .tab-content {
        max-height: 50vh;
        overflow-y: auto;
    }
    .ui-block-content, .modal-footer {
        padding-bottom: 0;
    }
    .wrap-action {
        overflow-y: scroll;
        &::-webkit-scrollbar {
            display: none;
        }
    }
    body.modal-dialog {
        overflow: hidden;
        position: fixed;
    }
    .comments-list {
        li {
            padding: 20px;
        }
        .post__author {
            img {
                width: 36px;
                height: 36px;
            }
        }
    }
    .date-title {
        font-weight: 300;
        line-height: 1;
    }
    .friends-harmonic {
        li {
            a {
                img {
                    max-width: 30px;
                }
            }
        }
    }
    .place {
        font-size: 13px;
        svg {
            fill: #c10d4a;
        }
    }
</style>
