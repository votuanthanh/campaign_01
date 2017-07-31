<template>
    <div class="col-md-12">
        <div class="ui-block">
            <div class="ui-block-title">
                <h6 class="title">{{ $t('events.donation.list_support') }}</h6>
                <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg></a>
            </div>
            <ul class="notification-list">
                <li v-for="donation in donations">
                    <div class="author-thumb">
                        <img :src="donation.user.image_thumbnail" alt="author" style="max-height: 100%">
                    </div>
                    <div class="notification-event">
                        <a href="#" class="h6 notification-friend">{{ donation.user.name }}</a> donate {{ donation.value }} {{ type.quantity }} {{ type.donate }}.
                        <span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">{{ donation.donated_at }}</time></span>
                    </div>
                        <span class="notification-icon">
                            <svg class="olymp-comments-post-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-comments-post-icon"></use></svg>
                        </span>
                </li>
                <li v-show="!donations.length">{{ $t('events.donation.dont_have_donation') }}</li>
            </ul>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'vuex'
    export default {
        computed: {
            ...mapState('event', ['event']),
            donations() {
                let goals = this.event.complete_percent.filter(goal => goal.id == this.$route.params.id)[0]
                return goals.donations.filter(donation => donation.status == 1)
            },
            received() {
                return this.donations.reduce((sum, value) => sum + value.value, 0)
            },
            type() {
                const goal = this.event.complete_percent.filter(goal => goal.id == this.$route.params.id)[0]
                return {
                    donate: goal.donation_type.name,
                    quantity: goal.donation_type.quality.name
                }
            }
        }
    }
</script>
