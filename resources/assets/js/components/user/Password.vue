<template lang="html">
    <div class="col-xl-9 push-xl-3 col-lg-9 push-lg-3 col-md-12 col-sm-12 col-xs-12">
        <div class="ui-block">
            <div class="ui-block-title">
                <h6 class="title">{{ $t('form.title.change_password') }}</h6>
            </div>
            <div class="ui-block-content tab-content">
                <form @submit.prevent="updatePassword">
                    <div class="row">

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('current_password')}">
                                <label class="control-label">{{ $t('form.label.current_password') }}</label>
                                <input
                                    class="form-control"
                                    placeholder=""
                                    type="password"
                                    value="Olympus-2017"
                                    name="current_password"
                                    v-model="user.current_password"
                                    v-validate="'required|min:6|max:255'">
                                <span v-show="errors.has('current_password')" class="material-input text-danger">
                                    {{ errors.first('current_password') }}
                                </span>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('password')}">
                                <label class="control-label">{{ $t('form.label.new_password') }}</label>
                                <input
                                    class="form-control"
                                    placeholder=""
                                    type="password"
                                    name="password"
                                    v-model="user.password"
                                    v-validate="'required|min:6|max:255|confirmed:password_confirmation'">
                            </div>
                            <span v-show="errors.has('password')" class="material-input text-danger">
                                {{ errors.first('password') }}
                            </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group label-floating" :class="{ 'has-danger': errors.has('password_confirmation') }">
                                <label class="control-label">{{ $t('form.label.confirm_password') }}</label>
                                <input
                                    class="form-control"
                                    placeholder=""
                                    type="password"
                                    name="password_confirmation"
                                    v-model="user.password_confirmation"
                                    v-validate="'required|min:6|max:255'">
                            </div>
                            <span v-show="errors.has('password_confirmation')" class="material-input text-danger">
                                {{ errors.first('password_confirmation') }}
                            </span>
                        </div>

                        <div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
                            <div class="remember">

                                <div class="checkbox">
                                    <label>
                                        <input name="optionsCheckboxes" type="checkbox">
                                        {{ $t('form.label.remember_me') }}
                                    </label>
                                </div>

                                <a href="#" class="forgot">{{ $t('form.label.forgot_password') }}</a>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary btn-lg full-width">
                                <i class="fa fa-spinner fa-spin" v-if="spinner"></i> {{ $t('form.button.change_password') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import { changePassword } from '../../router/router'
import { patch } from '../../helpers/api'
import noty from '../../helpers/noty'

export default {
    data: () => ({
        user: {
            'current_password': '',
            'password': '',
            'password_confirmation': ''
        },
        'spinner': false,
    }),
    methods: {
        updatePassword() {
            this.$validator.validateAll().then((result) => {
                this.spinner = true,
                patch(changePassword, this.user)
                    .then(res => {
                        this.spinner = false
                        for (let key in this.user) {
                            this.user[key] = ''
                        }
                        this.$nextTick(function () {
                            this.errors.clear()
                        })
                        noty({ text: 'Change your password successfully!', force: false, container: false, type: 'success' })
                    })
                    .catch(err => {
                        this.spinner = false
                        noty({ text: err.response.data.http_status.message, force: false, container: false})
                    })
            })
        }
    }
}
</script>

<style lang="scss">
</style>
