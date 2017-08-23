<template lang="html">
    <div class="fade show" id="create-event" style="display: block">
        <div class="modal-dialog ui-block window-popup create-event">
            <div class="ui-block-title">
                <h6 class="title">{{ $t('events.create_event') }}</h6>
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
                        v-model="newEvent.title"
                        v-validate="'required|max:255'">
                    <span v-show="errors.has('name')" class="material-input text-danger">
                        {{ errors.first('name') }}
                    </span>
                </div>

                <div class="form-group label-floating">
                    <label class="control-label">{{ $t('form.label.event_location') }}</label>
                    <gmap-autocomplete :value="newEvent.address" class="form-control" @place_changed="setPlace" ref="elSearch"></gmap-autocomplete>
                    <span class="material-input"></span>
                </div>

                <div class="form-group label-floating">
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

                <setting-date
                    :startDay.sync="startDate"
                    :endDay.sync="endDate"
                    :flag.sync="flag">
                </setting-date>

                <quill-editor
                    data-vv-name="description"
                    id="description"
                    :class="{ fullscreen: toggleFullscreen }"
                    :options="editorOption"
                    ref="description"
                    v-model="newEvent.description"
                    v-validate="'required'">
                </quill-editor>
                <span v-show="errors.has('description')" class="material-input text-danger">
                    {{ errors.first('description') }}
                </span>

                <div class="wrap-donation">
                    <p>{{ $t('form.title.add_donation') }}
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
                    <p>{{ $t('form.title.upload_images') }}</p>
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
                <button class="btn btn-breez btn-lg full-width" @click="createEvent">{{ $t('form.button.create_event') }}</button>
            </div>
            <upload-quill
                :uploadVisible.sync="uploadVisible"
                :imageInsert="imageInsert"
                @insertImage="insertImageToContent">
            </upload-quill>
        </div>
    </div>
</template>

<script type="text/javascript">
    import * as VueGoogleMaps from 'vue2-google-maps'
    import Dropzone from 'vue2-dropzone'
    import Vue from 'vue'
    import Donations from './Donations.vue'
    import SettingDate from '../../libs/SettingDate.vue'
    import axios from 'axios'
    import { config } from '../../../config'
    import { post, del } from '../../../helpers/api'
    import noty from '../../../helpers/noty'
    import uploadedImage from '../../../helpers/mixin/uploadedImage'
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
            newEvent : {
                title: '',
                campaign_id: '',
                description: '',
                latitude: null,
                longitude: null,
                settings: [],
                files: [],
                address: ' ',
                donations: []
            },
            hasErrorFiles: false,
            accessToken: `Bearer ${localStorage.getItem('access_token')}`
        }),
        mixins: [uploadedImage, searchMap],
        mounted() {
            this.loadedMaps(this.newEvent)
        },
        methods: {
            addErrors(index, value) {
                this.errorBags[index] = value
            },

            setPlace(place) {
                this.setLocation(this.newEvent, place)
            },

            updatePosition(event) {
                const latLng = event.latLng.toJSON()
                this.setGeocoder(this.newEvent, latLng)
            },

            addDonation() {
                let donation = { type : '', goal: '', quality: ''}
                this.donations.push(donation)
                this.visible = false
            },

            // filter danation before request to sever
            getDonation() {
                this.newEvent.donations = []

                for (let donation of this.donations) {
                    let flag = true

                    for (let key in donation) {
                        if (!donation[key]) {
                            flag = false
                            break
                        }
                    }

                    if (flag) {
                        this.newEvent.donations.push(donation)
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

            addSettings() {
                if (!this.startDate) {
                    this.startDate = moment().format('L');
                }

                this.newEvent.settings = [
                    { key: window.Laravel.settings.events.start_day, value: this.startDate },
                    { key: window.Laravel.settings.events.end_day, value: this.endDate }
                ]
            },

            showSuccess(file, response) {
                this.newEvent.files.push(response)
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
                .catch(() => {})
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
                    this.newEvent.campaign_id = this.pageId
                    this.getDonation()
                    this.addSettings()
                    post('event/create', this.newEvent)
                        .then(res => {
                            noty({
                                text: this.$i18n.t('messages.create_success'),
                                force: false,
                                container: false,
                                type: 'success'
                            })
                            this.$router.push({ name: 'event.index', params: { slug: res.data.event.slug }})
                        })
                        .catch(err => {
                            noty({
                                text: this.$i18n.t('messages.create_fail'),
                                type: 'error',
                                force: false,
                                container: false
                            })
                        })
                }
            },

            fileAdded() {
                this.hasErrorFiles = this.$refs.myVueDropzone.getRejectedFiles().length
            }
        },

        components: {
            Donations,
            Dropzone,
            SettingDate
        }
    }
</script>

<style lang="scss">
    .create-event {
        width: 80% !important;
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
</style>
