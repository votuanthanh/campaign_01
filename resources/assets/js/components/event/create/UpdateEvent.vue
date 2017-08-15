<template lang="html">
    <div class="fade show" id="update-event" style="display: block">
        <div class="modal-dialog ui-block window-popup create-event">
            <div class="ui-block-title">
                <h6 class="title">{{ $t('events.update_event') }}</h6>
            </div>
            <div class="ui-block-content">
                <div class="form-group label-floating is-focused" :class="{ 'has-danger': errors.has('name')}">
                    <label class="control-label">{{ $t('form.label.event_name') }}</label>
                    <input
                        name="name"
                        class="form-control"
                        type="text"
                        minlength="20"
                        maxlength="255"
                        v-model="dataUpdate.title"
                        v-validate="'required|max:255'">
                    <span v-show="errors.has('name')" class="material-input text-danger">
                        {{ errors.first('name') }}
                    </span>
                </div>

                <div class="form-group label-floating">
                    <label class="control-label">{{ $t('form.label.event_location') }}</label>
                    <gmap-autocomplete :value="dataUpdate.address" class="form-control" @place_changed="setPlace"></gmap-autocomplete>
                    <span class="material-input"></span>
                </div>

                <div class="form-group label-floating" v-if="showMap">
                    <gmap-map :center="center" :zoom="zoom" style="width: 100%; height: 500px" ref="elMap">
                        <gmap-marker
                            :position="center"
                            :clickable="true"
                            :draggable="true"
                            @click="center"
                            @dragend="updatePosition($event)">
                        </gmap-marker>
                    </gmap-map>
                </div>

                <setting-date v-if="showSettings"
                    :startDay.sync="startDate"
                    :endDay.sync="endDate"
                    :flag.sync="flag"
                    :isUpdate="true">
                </setting-date>

                <quill-editor
                    data-vv-name="description"
                    id="description"
                    :class="{ fullscreen: toggleFullscreen }"
                    :options="editorOption"
                    ref="description"
                    v-model="dataUpdate.description"
                    v-validate="'required'">
                </quill-editor>
                <span v-show="errors.has('description')" class="material-input text-danger">
                    {{ errors.first('description') }}
                </span>

                <div class="wrap-donation">
                    <p v-if='goals.length'>{{ $t('events.donation.old_donation') }}</p>
                    <table class="table list-goal-update" v-if="goals.length">
                        <thead>
                            <tr>
                                <th>{{ $t('form.label.type') }}</th>
                                <th>{{ $t('form.label.goal') }}</th>
                                <th>{{ $t('form.label.quality') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(goal, index) in goals">
                                <td>{{ goal.donation_type.name }}</td>
                                <td class="update-goal" v-if="dataUpdate.goalUpdates[index]">
                                    <input
                                        :name="'goal-' + index"
                                        type="text"
                                        v-model="dataUpdate.goalUpdates[index].goal"
                                        v-validate="'required|numeric|min_value:0'">
                                        <span v-show="errors.has('goal-' + index)" class="material-input text-danger">
                                            {{ errors.first('goal-' + index) }}
                                        </span>
                                </td>
                                <td>
                                    {{ goal.donation_type.quality.name }}
                                    <a
                                        href="javascript:void(0)"
                                        class="remove-icon delete-old-goal"
                                        @click="comfirmDelete(goal.goals_id)"
                                        v-if="!goal.donations.length">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                        {{ $t('form.title.add_donation') }}
                        <i class="fa fa-plus-square icon-donation" aria-hidden="true" id="add-donation" @click="addDonation">
                        </i>
                    </p>
                    <donations
                        v-for="(donation, index) in donations"
                        :donation="donation"
                        :index="index"
                        :key="index"
                        :visible="visible"
                        @add-instance-validate="addErrors"
                        @update-row-donation="updateGoal"
                        @delete-donation="deleteDonation(index)">
                    </donations>
                </div>

                <div class="form-group label-floating">
                    <p>{{ $t('form.title.old_image_event') }}</p>
                    <div class="old_images">
                        <div class="list-image">
                            <div v-for="(image, index) in this.event.media">
                                <img :src="image.image_medium">
                                <a href="javascript:void(0)" @click="appendIdsMedia($event, image.id)" class="remove-img close icon-close">
                                    <svg class="olymp-close-icon">
                                        <use xlink:href="/frontend/icons/icons.svg#olymp-close-icon"></use>
                                    </svg>
                                </a>
                                <i class="recovery-img fa fa-reply-all" aria-hidden="true" @click="recoverIdsMedia($event, image.id)"></i>
                            </div>
                        </div>
                    </div>
                    <p>{{ $t('form.title.add_new_images') }}</p>
                    <div :class="{ 'upload-file': true, 'has-error-upload': hasErrorFiles }">
                        <dropzone
                            id="myVueDropzoneId"
                            ref="myVueDropzone"
                            url="/api/file/upload"
                            acceptedFileTypes='image/*'
                            :autoProcessQueue="false"
                            :maxNumberOfFiles="maxFile"
                            :maxFileSizeInMB='maxSizeFile'
                            :headers = "{
                                Authorization: accessToken
                            }"
                            @vdropzone-success="showSuccess"
                            @vdropzone-removed-file="deleteFile"
                            @vdropzone-queue-complete="queueComplete"
                            @vdropzone-files-added="fileAdded">
                        </dropzone>
                    </div>
                </div>
                <button class="btn btn-breez btn-lg full-width" @click="createEvent">{{ $t('form.button.save') }}</button>
            </div>
            <upload-quill
                :uploadVisible.sync="uploadVisible"
                :imageInsert="imageInsert"
                @insertImage="insertImageToContent">
            </upload-quill>
        </div>
        <message :show.sync="showDeleteGoal">
            <h5 class="exclamation-header" slot="header">
                {{ $t('messages.comfirm_delete') }}
            </h5>
            <div class="body-modal confirm-delete" slot="main">
                <a href="javascript:void(0)"
                    class="btn btn-breez col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    @click="deleteGoalOld">
                    {{ $t('form.button.agree') }}
                </a>
                <a href="javascript:void(0)"
                    class="btn btn-secondary col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    @click="cancelDeleteGoal">
                    {{ $t('form.button.no') }}
                </a>
            </div>
        </message>
    </div>
</template>

<script type="text/javascript">
    import * as VueGoogleMaps from 'vue2-google-maps'
    import Dropzone from 'vue2-dropzone'
    import Vue from 'vue'
    import Donations from './Donations.vue'
    import SettingDate from '../../libs/SettingDate.vue'
    import { config, editorOption } from '../../../config'
    import { patch, get, del } from '../../../helpers/api'
    import noty from '../../../helpers/noty'
    import uploadedImage from '../../../helpers/mixin/uploadedImage'
    import Message from '../../libs/Modal.vue'
    import searchMap from '../../../helpers/mixin/searchMap'

    Vue.use(Dropzone)
    Vue.use(VueGoogleMaps, {
        load: {
            key: config.keyMap,
            libraries: 'places',
        }
    })

    export default {
        data: () => ({
            showMap: false,
            zoom: config.zoom,
            maxFile: config.maxFileUpload,
            maxSizeFile: config.maxSizeFile,
            visible: true,
            startDate: '',
            endDate: '',
            flag: true,
            donations: [
                { type : '', goal: '', quality: ''}
            ],
            errorBags: {},
            dataUpdate : {
                title: '',
                description: '',
                latitude: null,
                longitude: null,
                address: ' ',
                settings: [],
                files: [],
                mediaDels: [],
                goalUpdates: [],
                goalAdds: [],
            },
            hasErrorFiles: false,
            uploadVisible: false,
            imageInsert: '',
            accessToken: `Bearer ${localStorage.getItem('access_token')}`,
            //add when update event
            event: {},
            goals: [],
            showSettings: false,
            goalDelete: null,
            showDeleteGoal: false
        }),
        mixins: [uploadedImage, searchMap],
        methods: {
            addErrors(index, value) {
                this.errorBags[index] = value
            },

            setPlace(place) {
                this.setLocation(this.dataUpdate, place)
            },

            addDonation() {
                let donation = { type : '', goal: '', quality: ''}
                this.donations.push(donation)
                this.visible = false
            },

            // filter danation before request to sever
            getDonation() {
                for (let donation of this.donations) {
                    let flag = true

                    for (let key in donation) {
                        if (!donation[key]) {
                            flag = false
                            break
                        }
                    }

                    if (flag) {
                        this.dataUpdate.goalAdds.push(donation)
                    }
                }
            },

            deleteDonation(index) {
                this.donations.length > 1 && this.donations.splice(index, 1)

                this.errorBags.length > 1 && this.errorBags.splice(index, 1)

                if (this.donations.length === 1) {
                    this.visible = true
                }
            },

            updateGoal(newValue) {
                // mix <=> key: type, goal, quality
                // key <=> key of newValue that children emitted
                const [key, mix, instanse] = Object.keys(newValue)
                const index = newValue[key]

                this.errorBags[index] = newValue[instanse]
                this.donations[index][mix] = newValue[mix]
            },

            updatePosition(event) {
                const latLng = event.latLng.toJSON()
                this.setGeocoder(this.dataUpdate, latLng)
            },

            addSettings() {
                if (!this.startDate) {
                    this.startDate = moment().format('L');
                }

                this.dataUpdate.settings = [
                    { key: window.Laravel.settings.events.start_day, value: this.startDate },
                    { key: window.Laravel.settings.events.end_day, value: this.endDate }
                ]
            },

            showSuccess(file, response) {
                this.dataUpdate.files.push(response)
            },

            createEvent() {
                this.hasErrorFiles = this.$refs.myVueDropzone.getRejectedFiles().length
                this.$validator.validateAll().then((result) => {
                    if (!this.flag || (Object.keys(this.errorBags).length > 1 && this.hasErrorDonation())) {
                        return
                    }

                    if (!this.hasErrorFiles) {
                        this.$refs.myVueDropzone.processQueue()

                        if (!this.$refs.myVueDropzone.getUploadingFiles().length) {
                            this.queueComplete()
                        }
                    }
                })
            },

            hasErrorDonation() {
                let errorDonations = []

                for(let index in this.errorBags) {
                    // Triger validation children donation
                    this.errorBags[index].validateAll().catch(() => {})
                    errorDonations.push(this.errorBags[index].getErrors())
                }

                return !errorDonations.every(item => !item.count())
            },

            deleteFile(file, error, xhr) {
                this.hasErrorFiles = this.$refs.myVueDropzone.getRejectedFiles().length
            },

            queueComplete(file, xhr, formData) {
                this.hasErrorFiles = this.$refs.myVueDropzone.getRejectedFiles().length

                if (!this.hasErrorFiles) {
                    this.getDonation()
                    this.addSettings()
                    patch(`event/update/${this.$route.params.id}`, this.dataUpdate)
                        .then(res => {
                            noty({
                                text: this.$i18n.t('messages.update_success'),
                                force: false,
                                container: false,
                                type: 'success'
                            })
                            this.$router.push({ name: 'event.index', params: { event_id: this.$route.params.id }})
                        })
                        .catch(err => {
                            noty({
                                text: this.$i18n.t('messages.update_fail'),
                                type: 'error',
                                force: false,
                                container: false
                            })
                        })
                }
            },

            fileAdded() {
                this.hasErrorFiles = this.$refs.myVueDropzone.getRejectedFiles().length
            },


            //add when update
            getStartDateOfSetting(date) {
                return this.startDate = date.settings.filter(setting => {
                    return setting.key == window.Laravel.settings.events.start_day
                })[0].value
            },

            getEndDateOfSetting(date) {
                return this.endDate = date.settings.filter(setting => {
                    return setting.key == window.Laravel.settings.events.end_day
                })[0].value
            },

            appendIdsMedia(event, id) {
                this.dataUpdate.mediaDels.push(id)
                $(event.currentTarget).css('display', 'none')
                $(event.currentTarget).siblings('.recovery-img').css('display', 'block')
            },

            recoverIdsMedia(event, id) {
                this.dataUpdate.mediaDels = this.dataUpdate.mediaDels.filter(function(item) {
                    return item !== id
                })
                $(event.currentTarget).css('display', 'none')
                $(event.currentTarget).siblings('.remove-img').css('display', 'block')
            },

            setDataUpdate() {
                this.dataUpdate.title = this.event.title
                this.dataUpdate.description = this.event.description
                this.dataUpdate.latitude = this.center.lat = Number(this.event.latitude)
                this.dataUpdate.longitude = this.center.lng = Number(this.event.longitude)
                this.dataUpdate.address = this.event.address
                this.dataUpdate.settings = [
                    {
                        key: window.Laravel.settings.events.start_day,
                        value: this.getStartDateOfSetting(this.event)
                    },
                    {
                        key: window.Laravel.settings.events.end_day,
                        value: this.getEndDateOfSetting(this.event)
                    }
                ]
            },

            callApiGetDataGoal() {
                this.dataUpdate.goalUpdates = []
                get(`goal?event_id=${this.$route.params.id}`)
                    .then(res => {
                        this.goals = res.data.goals
                        this.goals.forEach(goal => {
                            let donation = {
                                id: goal.goals_id,
                                goal: goal.goal
                            }
                            this.dataUpdate.goalUpdates.push(donation)
                        })
                    })
            },

            comfirmDelete(id) {
                this.goalDelete = id
                this.showDeleteGoal = true
            },

            deleteGoalOld() {
                del(`goal/${this.goalDelete}?event_id=${this.$route.params.id}`)
                    .then(res => {
                        this.$Progress.finish()
                        noty({
                            text: this.$i18n.t('messages.delete_success'),
                            force: false,
                            container: false,
                            type: 'success'
                        })
                        this.callApiGetDataGoal()
                        this.cancelDeleteGoal()
                    })
                    .catch(err => {
                        this.$Progress.fail()
                        noty({
                            text: this.$i18n.t('messages.delete_fail'),
                            type: 'error',
                            force: false,
                            container: false
                        })
                        this.cancelDeleteGoal()
                    })
            },

            cancelDeleteGoal() {
                this.goalDelete = null
                this.showDeleteGoal = false
            }
        },

        components: {
            Donations,
            Dropzone,
            SettingDate,
            Message
        },

        created() {
            get(`event/show/${this.$route.params.id}`)
                .then(res => {
                    this.event = res.data.event[0]
                    this.setDataUpdate()
                    this.showSettings = true
                    this.showMap = true
                })
            this.callApiGetDataGoal()

            get(`event/check-permission/${this.$route.params.id}`)
                .then(res => {
                    if (!res.data) {
                        this.$router.push({ name: 'event.index', params: { event_id: this.$route.params.id }})
                    }
                })
        }
    }
</script>

<style lang="scss">
    .update-event {
        width: 80% !important;
        .wrap-donation{
            #add-donation {
                &:hover {
                    color: #08ddc1;
                    cursor: pointer;
                }
            }
            .store-icon {
                padding-left: 5px;
                margin-bottom: 25px;
            }
            .icon-donation {
                font-size: 1.5em !important;
                padding-left: 5px;
            }
            #delete-donation {
                margin-top: 1em;
                &:hover {
                    color: #ff5e3a;
                    cursor: pointer;
                }
            }
            .visible:hover {
                cursor: not-allowed !important;
            }
        }
        .upload-file {
            min-height: 300px;
            form {
                min-height: 300px;
            }
        }
        .has-error-upload {
            border: 1px solid red;
            border-radius: 2px;
        }
        .vue-dropzone {
            .dz-preview {
                .dz-error-mark {
                    i {
                        color: red !important;
                    }
                }
            }
        }
        .dz-error-message {
            top: 5px !important;
            left: 59px !important;
        }
    }
    .list-goal-update {
        border-left: 1px solid #eceeef;
        border-right: 1px solid #eceeef;
        tr {
            border-bottom: 1px solid #eceeef;
            border-top: 0px;
            .update-goal {
                width: 33.33%;
                padding: 0px 12px;
                input {
                    padding: 12px;
                }
            }
            .delete-old-goal {
                float: right;
                font-size: 18px;
            }
        }
    }
    .list-image {
        margin-top: 20px;
        > div {
            display: inline-block;
            position: relative;
            >img {
                border: 5px solid #fff;
                margin: 2px;
                max-height: 120px;
                max-width: 200px;
                box-shadow: 0 1px 1px 0 rgba(0,0,0,.14), 0 2px 1px -1px rgba(0,0,0,.12), 0 1px 3px 0 rgba(0,0,0,.2);
            }
            .remove-img {
                position: absolute;
                right: 12px;
                top: 12px;
            }
            &:hover {
                >img {
                    opacity: 0.8;
                }
            }

            .recovery-img {
                position: absolute;
                display: none;
                right: 12px;
                top: 12px;
                font-size: 45px;
                color: #5c9fe9;
                &:hover {
                    color: #0085ff;
                }
            }
        }
    }
</style>
