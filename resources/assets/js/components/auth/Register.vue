<template lang="html">
    <div class="tab-pane" :class="{ active: registerTab }" id="home" role="tabpanel" data-mh="log-tab">
        <div class="title h6">{{ $t('form.title.register_campaign') }}</div>
        <form class="content" @submit.prevent="handleRegister">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="form-group label-floating" :class="{ 'has-danger': errors.has('name')}">
                        <label class="control-label">{{ $t('form.label.name') }}</label>
                        <input class="form-control"
                            placeholder=""
                            name="name"
                            type="text"
                            autocomplete="off"
                            v-model="user.name"
                            v-validate="'required|max:255'">
                        <span v-show="errors.has('name')" class="material-input text-danger">
                            {{ errors.first('name') }}
                        </span>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="form-group label-floating" :class="{ 'has-danger': errors.has('email')}">
                        <label class="control-label">{{ $t('form.label.email') }}</label>
                        <input class="form-control"
                            placeholder=""
                            type="email"
                            name="email"
                            autocomplete="off"
                            v-model="user.email"
                            v-validate="'required|email|unique:users,email|max:255'">
                        <span v-show="errors.has('email')" class="material-input text-danger">
                            {{ errors.first('email') }}
                        </span>
                    </div>

                    <div class="form-group label-floating" :class="{ 'has-danger': errors.has('password')}">
                        <label class="control-label">{{ $t('form.label.password') }}</label>
                        <input class="form-control"
                            placeholder=""
                            type="password"
                            name="password"
                            v-model="user.password"
                            v-validate="'required|min:6|max:255'">
                        <span v-show="errors.has('password')" class="material-input text-danger">
                            {{ errors.first('password') }}
                        </span>
                    </div>

                    <div class="form-group label-floating is-select">
                        <label class="control-label">{{ $t('form.label.gender') }}</label>
                        <select ref="selectpicker" v-model="user.gender" class="selectpicker form-control" name="gender">
                            <option v-for="gender in genders" :value="gender.value">
                                {{ gender.name }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group date-time-picker label-floating">
                        <label class="control-label">{{ $t('form.label.birthday') }}</label>
                        <date-picker :date.sync="user.birthday"></date-picker>
                        <span class="input-group-addon">
                            <svg class="olymp-calendar-icon">
                                <use xlink:href="/frontend/icons/icons.svg#olymp-calendar-icon"></use>
                            </svg>
                        </span>
                    </div>

                    <button type="submit" class="btn btn-purple btn-lg full-width">
                        <i class="fa fa-spinner fa-spin" v-if="spinner"></i> {{ $t('form.button.complete_register') }}
                    </button>
                </div>
            </div>
        </form>
        <modal :show.sync="loading">
            <h2 class="exclamation-header" slot="header">
                <span class="fa fa-exclamation-triangle"></span>&nbsp;Account verification required
            </h2>
            <div class="body-modal" slot="main">
                <ul>
                    <li>{{ $t('messages.warning_verify_register') }}</li>
                    <li>
                        {{ $t('messages.you_may') }}
                        <a href="/account/resend-email-verification">{{ $t('messages.want_to_resend') }}</a>
                        {{ $t('messages.not_receive_mail') }}
                    </li>
                </ul>
            </div>
        </modal>
    </div>
</template>

<script>
import DatePicker from '../libs/DatePicker.vue'
import Modal from '../libs/Modal.vue'
import noty from '../../helpers/noty'
import { post } from '../../helpers/api'
import { register } from '../../router/router'

export default {
    data: () => ({
        user: {
            name: '',
            email: '',
            password: '',
            birthday: '',
            gender: '' // not undefined gender
        },
        genders: [
            { name: 'Other', value: null},
            { name: 'Female', value: 0 },
            { name: 'Male', value: 1 }
        ],
        loading: false,
        spinner: false
    }),
    props: ['registerTab'],
    methods: {
        handleRegister() {
            this.$validator.validateAll().then((result) => {
                this.spinner = true
                post(register, this.user)
                    .then(res => {
                        this.spinner = false
                        this.loading = true
                        for (let key in this.user) {
                            this.user[key] = ''
                        }

                        this.$nextTick(function () {
                            this.errors.clear()
                        })
                    })
                    .catch(err => {
                        this.spinner = false
                        const message = this.$i18n.t('messages.regiser_fail')
                        noty({ text: message, force: true})
                    })
            })
        }
    },
    mounted() {
        $(this.$refs.selectpicker).selectpicker()
    },
    beforeDestroy() {
        $(this.$refs.selectpicker).selectpicker('destroy')
    },
    components: {
        DatePicker,
        Modal
    }
}
</script>

<style lang="scss">
    .exclamation-header {
        color: rgb(0, 0, 0);
    }

    .body-modal {
        ul {
            list-style-type: disc;
            list-style-position: outside;
            padding-bottom: 18px;
            li {
                margin-left: 15px;
            }
        }
    }
</style>
