<template lang="html">
    <div id="create-campagin">
        <div id="container-campaign" class="modal-dialog ui-block window-popup create-event">
            <div class="ui-block-title">
                <h6 class="title">{{ $t('campaigns.create_campaign') }}</h6>
            </div>
            <form class="content" @submit.prevent="handleCreateCampagin">
                <div class="ui-block-content">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">{{ $t('campaigns.title') }}</label>
                                    <input id="title"
                                        name="title"
                                        class="form-control"
                                        type="text"
                                        v-model="campaign.title"
                                        v-validate="'required|max:255'">
                                <span v-show="errors.has('title')" class="material-input text-danger">
                                    {{ errors.first('title') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">{{ $t('campaigns.hashtag') }}</label>
                                    <input id="hashtag"
                                        name="hashtag"
                                        class="form-control"
                                        type="text"
                                        v-model="campaign.hashtag"
                                        v-validate="'required|max:255|unique:campaigns,hashtag'">
                                <span v-show="errors.has('hashtag')" class="material-input text-danger">
                                    {{ errors.first('hashtag') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group label-floating">
                        <label class="control-label">{{ $t('campaigns.description') }}</label>
                            <textarea name="description"
                                id="description"
                                class="form-control"
                                v-model="campaign.description"
                                v-validate="'required'">
                            </textarea>
                        <span v-show="errors.has('description')" class="material-input text-danger">
                            {{ errors.first('description') }}
                        </span>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">{{ $t('campaigns.location') }}</label>
                                    <input id="location"
                                        name="location"
                                        class="form-control"
                                        type="text"
                                        v-model="campaign.location"
                                        v-validate="'max:255'">
                                <span v-show="errors.has('location')" class="material-input text-danger">
                                    {{ errors.first('location') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <multiselect
                                v-model="campaign.tags"
                                :tag-placeholder="$t('campaigns.add_tags')"
                                :placeholder="$t('campaigns.tags')"
                                label="name"
                                track-by="id"
                                :options="tags"
                                :multiple="true"
                                :taggable="true"
                                @tag="addTags">
                            </multiselect>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div id="map"></div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <input type="file" name="file"
                                @change="uploadImage($event)"
                                v-validate="'image|size:1024'">
                            <img :src="campaign.media" alt="image" id="img" />
                            <span v-show="errors.has('file')" class="material-input text-danger">
                                    {{ errors.first('file') }}
                            </span>
                        </div>
                    </div>

                    <div class="ui-block-title">
                        <h6 class="title">{{ $t('campaigns.settings') }}</h6>
                    </div>

                    <div class="row setting">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 form-group label-floating is-select is-empty">
                            <label class="control-label">{{ $t('campaigns.status') }}</label>
                            <div class="btn-group bootstrap-select form-control">
                                <div class="dropdown-menu open" role="combobox">
                                    <ul class="dropdown-menu inner" role="listbox" aria-expanded="false">
                                        <li data-original-index="0" class="selected"
                                            v-for="name in status">
                                            <a tabindex="0"
                                                class=" dropdown-item"
                                                data-tokens="null"
                                                role="option"
                                                aria-disabled="false"
                                                aria-selected="true">
                                                <span class="text">{{ name.name }}</span>
                                                <span class="glyphicon glyphicon-ok check-mark"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <select ref="selectpicker"
                                    class="selectpicker form-control"
                                    tabindex="-98"
                                    v-model="feature">
                                    <option v-for="name in status" :value="{ value: name.value }">
                                        {{ name.name }}
                                    </option>
                                </select>
                            </div>
                            <span class="material-input"></span>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">{{ $t('campaigns.limit') }}</label>
                                    <input id="limit"
                                        name="limit"
                                        class="form-control"
                                        type="text"
                                        v-model="limit"
                                        v-validate="'numeric'">
                                <span v-show="errors.has('limit')" class="material-input text-danger">
                                    {{ errors.first('limit') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <setting-date
                        :startDay.sync="startDay"
                        :endDay.sync="endDay"
                        :flag.sync="flag">
                    </setting-date>

                    <button type="submit" class="btn btn-breez btn-lg full-width">
                        {{ $t('campaigns.create_campaign') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import DatePicker from '../libs/DatePicker.vue'
import { post, get } from '../../helpers/api'
import { storeCampaign, tags } from '../../router/router'
import Multiselect from 'vue-multiselect'
import noty from '../../helpers/noty'
import SettingDate from '../libs/SettingDate.vue'

export default {
    data: () => ({
        campaign: {
            title: '',
            hashtag: '',
            description: '',
            longitude: '',
            latitude: '',
            tags: [],
            settings: [],
            media: '',
            location: ''
        },
        tags: [],
        feature: '',
        limit: '',
        startDay: '',
        endDay: '',
        flag: ''
    }),
    created () {
        this.getTags()
    },
    mounted() {
        $(this.$refs.selectpicker).selectpicker()
    },
    beforeDestroy() {
        $(this.$refs.selectpicker).selectpicker('destroy')
    },
    computed: {
        status() {
            return [
                { name: this.$i18n.t('campaigns.public'), value: window.Laravel.settings.value.status.public },
                { name: this.$i18n.t('campaigns.private'), value: window.Laravel.settings.value.status.private }
            ]
        }
    },
    methods: {
        handleCreateCampagin() {
            this.$validator.validateAll().then((result) => {
                // add settings for campaign
                this.addSettings(window.Laravel.settings.campaigns.status, this.feature.value)
                this.addSettings(window.Laravel.settings.campaigns.limit, this.limit)
                this.addSettings(window.Laravel.settings.campaigns.start_day, this.startDay)
                this.addSettings(window.Laravel.settings.campaigns.end_day, this.endDay)

                if (!this.flag) {
                    return
                }

                post(storeCampaign, this.campaign)
                    .then(res => {
                        const message = this.$i18n.t('messages.create_success')
                        noty({ text: message, force: true})
                    })
                    .catch(err => {
                        const message = this.$i18n.t('messages.create_fail')
                        noty({ text: message, force: true})
                    })
            })
        },
        addTags(newTag) {
            let tag = {
                name: newTag,
                id: null
            }

            this.tags.push(tag)
            this.campaign.tags.push(tag)
        },
        getTags() {
            get(tags)
                .then(res => {
                    this.tags = res.data.tags.map(function(value, key) {
                        return {
                            name: value.name,
                            id: value.id
                        }
                    })
                })
                .catch(err => {
                    this.tags.push(this.$i18n.t('messages.empty'))
                })
        },
        addSettings(key, value) {
            if (key == window.Laravel.settings.campaigns.status && value == null) {
                value = window.Laravel.settings.value.status.public
            }

            let flag = false

            this.campaign.settings.map(function (setting) {
                if (setting.key == key) {
                    setting.value = value
                    flag = true
                }
            })

            if (!flag) {
                let setting = {
                    key: key,
                    value: value
                }

                this.campaign.settings.push(setting)
            }
        },
        uploadImage(event) {
            var file = event.target.files[0]

            var fileReader = new FileReader()

            fileReader.onload = (event) => {
                this.campaign.media = event.target.result
            }

            fileReader.readAsDataURL(file)

        }
    },
    components: {
        DatePicker,
        Multiselect,
        SettingDate
    }
}
</script>

<style lang="scss">
#create-campaign {
    width: 100% !important;
    display: block;
}
#container-campaign {
    width: 100% !important;
}

.setting {
    margin-top: 20px;
}

.dropdown-menu .open {
    max-height: 189.438px;
    overflow: hidden;
    min-height: 0px;
}

.dropdown-menu .inner {
     max-height: 171.438px;
     overflow-y: auto;
     min-height: 0px;
}

#map {
    background: yellow;
    width: 100%;
    height: 400px;
}

.multiselect__tags {
    min-height: 54px !important;
}

#img {
    border: 5px solid #dedede;
    max-width: 100%;
    height: 330px;
    margin: 7px 0px;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
