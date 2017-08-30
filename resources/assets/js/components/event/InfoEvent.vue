<template>
    <div>
        <div class="ui-block">
            <div class="ui-block-content">
                <form>
                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group with-icon label-floating">
                                <label class="control-label">{{ $t('events.address') }}</label>
                                <input class="form-control" type="text" :value="event.address" disabled>
                                <i class="fa fa-address-card-o" aria-hidden="true"></i>
                                <span class="material-input"></span>
                            </div>

                            <div class="form-group with-icon label-floating">
                                <label class="control-label">{{ $t('events.start_day') }}</label>
                                <input class="form-control" type="text" :value="event.startDay || 'N/A'" disabled>
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <span class="material-input"></span>
                            </div>

                            <div class="form-group with-icon label-floating">
                                <label class="control-label">{{ $t('events.end_day') }}</label>
                                <input class="form-control" type="text" :value="event.endDay || 'N/A'" disabled>
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <span class="material-input"></span>
                            </div>

                            <div class="form-group with-icon label-floating" v-show="actions">
                                <label class="control-label">{{ $t('events.quantity_actions') }}</label>
                                <input class="form-control" type="text" :value="actions" disabled>
                                <i class="fa fa-strikethrough" aria-hidden="true"></i>
                                <span class="material-input"></span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <gmap-map id="map" :center="center" :zoom="zoom" ref="elMap">
                                <gmap-marker
                                    :position="center"
                                    :clickable="true"
                                    :draggable="true"
                                    @click="center">
                                </gmap-marker>
                            </gmap-map>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { get } from '../../helpers/api'
import * as VueGoogleMaps from 'vue2-google-maps'
import DatePicker from '../libs/DatePicker.vue'
import { config } from '../../config'

export default {
    data: () => {
        return {
            event: {
                address: '',
                startDay: '',
                endDay: '',
            },
            actions: 0,
            center: { lat: 0, lng: 0 },
            zoom: config.zoom,
            time: '',
            pageType: 'event'
        }
    },
    created() {
        get(`event/get-info-event/${this.pageId}`)
            .then(res => {
                if (res.data.http_status.code == 200) {
                    let data = res.data.event
                    this.event.address = data.address
                    this.center.lng = parseFloat(data.longitude)
                    this.center.lat = parseFloat(data.latitude)
                    let startDayIndex = data.settings
                        .findIndex(setting => parseInt(setting.key) == parseInt(window.Laravel.settings.events.start_day))
                    let endDayIndex = data.settings
                        .findIndex(setting => parseInt(setting.key) == parseInt(window.Laravel.settings.events.end_day))
                    this.event.startDay = startDayIndex == -1 ? this.event.startDay : data.settings[startDayIndex].value
                    this.event.endDay = endDayIndex == -1 ? this.event.endDay : data.settings[endDayIndex].value
                    this.actions = res.data.countActions
                }
            })
            .catch(err => {

            })
    }
}
</script>

<style lang="scss" scoped>
.ui-block {
    background-color: #fff;
    border-radius: 5px;
    border: 1px solid #e6ecf5;
    margin-bottom: 15px;
}
</style>
