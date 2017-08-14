<template lang="html">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group date-time-picker label-floating is-focused">
                <label class="control-label">{{ $t('campaigns.startday') }}</label>
                <date-picker :date.sync="start">
                </date-picker>
                <span class="input-group-addon">
                    <svg class="olymp-calendar-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-calendar-icon"></use>
                    </svg>
                </span>
                <span class="material-input text-danger">
                    {{ messageStartDay }}
                </span>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group date-time-picker label-floating is-focused">
                <label class="control-label">{{ $t('campaigns.endday') }}</label>
                <date-picker :date.sync="end">
                </date-picker>
                <span class="input-group-addon">
                    <svg class="olymp-calendar-icon">
                        <use xlink:href="/frontend/icons/icons.svg#olymp-calendar-icon"></use>
                    </svg>
                </span>
                <span class="material-input text-danger">
                    {{ messageEndDay }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import DatePicker from '../libs/DatePicker.vue'

export default {
    data: () => ({
        messageEndDay: '',
        messageStartDay: '',
        start: '',
        end: '',
        status: ''
    }),
    props: {
        flag: Boolean,
        startDay: String,
        endDay: String
    },
    watch: {
        start() {
            this.status = this.validateDate(this.start, this.end)
            this.$emit('update:startDay', this.start)
        },
        end() {
            this.status = this.validateDate(this.start, this.end)
            this.$emit('update:endDay', this.end)
        },
        status() {
            this.$emit('update:flag', this.status)
        }
    },
    methods: {
        validateDate(start, end) {
            let flag = true
            let now = window.moment().format(this.$i18n.t('campaigns.format_date'))

            if (!start) {
                this.start = window.moment().format(this.$i18n.t('campaigns.format_date'))
                this.messageStartDay = ''
                this.messageEndDay = ''
            }

            if (window.moment(start).isBefore(now)) {
                this.messageStartDay = this.$i18n.t('messages.start_day')
                flag = false
            } else {
                this.messageStartDay = ''
            }

            if (end && (window.moment(end).isSameOrBefore(now) || window.moment(end).isSameOrBefore(start))) {
                this.messageEndDay = this.$i18n.t('messages.end_day')
                flag = false
            } else {
                this.messageEndDay = ''
            }

            return flag
        }
    },
    components: {
        DatePicker
    }
}
</script>
