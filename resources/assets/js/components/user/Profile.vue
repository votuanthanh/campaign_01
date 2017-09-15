<template lang="html">
    <div class="col-xl-9 push-xl-3 col-lg-9 push-lg-3 col-md-12 col-sm-12 col-xs-12">
        <div class="ui-block">
            <div class="ui-block-title">
                <h6 class="title">{{ $t('form.title.profile') }}</h6>
            </div>
            <div class="ui-block-content">
                <form>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label" >{{ $t('form.label.full_name') }}</label>
                                <input class="form-control" placeholder="" type="text" disabled="" :value="fullName">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('first-name') }">
                                <label class="control-label" >{{ $t('form.label.first_name') }}</label>
                                <input
                                    class="form-control"
                                    placeholder=""
                                    type="text"
                                    name="first-name"
                                    v-model="firstName"
                                    v-validate="'required|max:255'">
                                <span v-show="errors.has('first-name')" class="material-input text-danger">
                                    {{ errors.first('first-name') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('last-name') }">
                                <label class="control-label" >{{ $t('form.label.last_name') }}</label>
                                <input
                                    class="form-control"
                                    placeholder=""
                                    type="text"
                                    name="last-name"
                                    v-model="lastName"
                                    v-validate="'max:255'">
                            </div>
                            <span v-show="errors.has('last-name')" class="material-input text-danger">
                                {{ errors.first('last-name') }}
                            </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating">
                                <label class="control-label">{{ $t('form.label.email') }}</label>
                                <input class="form-control" placeholder="" type="email" v-model= "user.email" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group date-time-picker label-floating">
                                <label class="control-label">{{ $t('form.label.birthday') }}</label>
                                <date-picker :date.sync="birthday" :data="{ startDate: birthday, maxDate: maxDate }"></date-picker>
                                <span class="input-group-addon">
                                    <svg class="olymp-calendar-icon">
                                        <use xlink:href="/frontend/icons/icons.svg#olymp-calendar-icon"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('phone') }">
                                <label class="control-label">{{ $t('form.label.phone') }}</label>
                                <input
                                    class="form-control"
                                    placeholder=""
                                    type="text"
                                    name="phone"
                                    v-model="user.phone"
                                    v-validate="'numeric'">
                                <span v-show="errors.has('phone')" class="material-input text-danger">
                                    {{ errors.first('phone') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating is-select">
                                <label class="control-label">{{ $t('form.label.gender') }}</label>
                                <select ref="selectpicker" v-model="user.gender" class="selectpicker form-control" name="gender">
                                    <option v-for="gender in genders" :value="gender.value">
                                        {{ gender.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('about') }">
                                <label class="control-label">{{ $t('form.label.about') }}</label>
                                <textarea
                                    class="form-control"
                                    placeholder=""
                                    name="about"
                                    v-model="user.about"
                                    v-validate="'min:6|max:255'"></textarea>
                                <span v-show="errors.has('about')" class="material-input text-danger">
                                    {{ errors.first('about') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('address') }">
                                <label class="control-label">{{ $t('form.label.address') }}</label>
                                <textarea
                                    class="form-control"
                                    placeholder=""
                                    name="address"
                                    v-model="user.address"
                                    v-validate="'min:6|max:255'"></textarea>
                                <span v-show="errors.has('address')" class="material-input text-danger">
                                    {{ errors.first('address') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" @click.prevent="restoreAll">
                            <button class="btn btn-secondary btn-lg full-width">{{ $t('form.button.restore') }}</button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn-lg full-width" @click.prevent="updateProfile">
                                <i class="fa fa-spinner fa-spin" v-if="spinner"></i> {{ $t('form.button.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions } from 'vuex'
import DatePicker from '../libs/DatePicker.vue'
import { updateProfile } from '../../router/router'
import { patch } from '../../helpers/api'
import noty from '../../helpers/noty'

export default {
    data() {
        return {
            user: _.cloneDeep(this.$store.state.auth.user),
            firstName: '',
            lastName: '',
            spinner: false,
            genders: [
                { name: 'Other', value: null},
                { name: 'Female', value: 0 },
                { name: 'Male', value: 1 }
            ],
        }
    },
    computed: {
        fullName() {
            return `${this.firstName} ${this.lastName}`.trim()
        },
        birthday: {
            get() {
                let birthday = new Date(this.user.birthday).toLocaleDateString(window.Laravel.locale)
                return birthday
            },
            set(value) {
                this.user.birthday = value
            }
        },
        maxDate() {
            return new Date().toLocaleString(window.Laravel.locale)
        }
    },
    methods: {
        ...mapActions('auth', {
            update: 'setUser'
        }),
        updateProfile() {
            this.$validator.validateAll().then((result) => {
                this.spinner = true,
                this.user.name = this.fullName
                patch(updateProfile, this.user)
                    .then(res => {
                        this.spinner = false
                        this.$nextTick(function () {
                            this.errors.clear()
                        })
                        this.update(this.user)
                        this.user = _.cloneDeep(this.$store.state.auth.user)
                        noty({ text: this.$i18n.t('messages.profile.change_success'), force: false, container: false, type: 'success' })
                    })
                    .catch(err => {
                        this.spinner = false
                        noty({ text: err.response.data.messages[0], force: false, container: false, type: 'error'})
                    })
            })
        },
        getName() {
            this.firstName = this.user.name.split(' ').slice(0, -1).join(' ')
            this.lastName = this.user.name.split(' ').slice(-1).join(' ')
        },
        restoreAll() {
            this.user = _.cloneDeep(this.$store.state.auth.user)
            this.getName()
        }
    },
    created() {
        this.getName()
    },
    mounted() {
        $('.selectpicker').selectpicker({});
    },
    components: {
        DatePicker
    }
}
</script>

<style lang="scss" scoped>
</style>
