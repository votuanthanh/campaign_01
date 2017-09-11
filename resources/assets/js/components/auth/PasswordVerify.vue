<template lang="html">
    <div class="registration-login-form">
        <div class="tab-pane" role="tabpanel" data-mh="log-tab">
            <div class="title h6">{{ $t('form.title.reset_password') }}</div>
            <form class="content" @submit.prevent="updatePassword">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="form-group label-floating" :class="{ 'has-danger': errors.has('email')}">
                            <label class="control-label">{{ $t('form.label.email') }}</label>
                            <input data-vv-delay="300"
                                v-validate="'required|email|max:255'"
                                name="email"
                                v-model="user.email"
                                class="form-control">
                            <span v-show="errors.has('email')" class="material-input text-danger">
                                {{ errors.first('email') }}
                            </span>
                        </div>

                        <div class="form-group label-floating" :class="{ 'has-danger': errors.has('password')}">
                            <label class="control-label">{{ $t('form.label.new_password') }}</label>
                            <input data-vv-delay="300"
                                v-validate="'required|confirmed'"
                                name="password"
                                type="password"
                                v-model="user.password"
                                class="form-control">
                            <span v-show="errors.has('password')" class="material-input text-danger">
                                {{ errors.first('password') }}
                            </span>
                        </div>

                        <div class="form-group label-floating" :class="{ 'has-danger': errors.has('password_confirmation')}">
                            <label class="control-label">{{ $t('form.label.confirm_password') }}</label>
                            <input data-vv-delay="300"
                                v-validate="'required'"
                                name="password_confirmation"
                                v-model="user.password_confirmation"
                                type="password"
                                class="form-control">
                            <span v-show="errors.has('password_confirmation')" class="material-input text-danger">
                                {{ errors.first('password_confirmation') }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary full-width">
                            <i class="fa fa-spinner fa-spin" v-show="show"></i> {{ $t('form.button.reset_password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { post } from '../../helpers/api'
import noty from '../../helpers/noty'
import { passwordReset } from '../../router/router'

export default {
    layout: 'default',
    data: function () {
        return {
            user: {
                email: '',
                password: '',
                password_confirmation: '',
                token: this.$route.params.token
            },
            show: false
        }
    },
    methods: {
        updatePassword() {
            this.$validator.validateAll()
                .then(() => {
                    this.$Progress.start()
                    this.show = true
                    post(passwordReset, this.user)
                        .then((res) => {
                            this.$Progress.finish()
                            this.show = false
                            this.notification(res.data.message)

                            this.$nextTick(function () {
                                this.errors.clear()
                            })

                            this.$router.push({ name: 'login' })
                        })
                        .catch((err) => {
                            this.$Progress.fail()
                            this.show = false
                            this.notification(err.response.data.http_status.message, 'error')
                        })
                })
                .catch(() => {})
        },
        notification(message, type = 'success') {
            noty({
                text: message,
                force: true,
                container: '.content',
                type: type,
                killer: true,
                animation: {
                    open: ''
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
