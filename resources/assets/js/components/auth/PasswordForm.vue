<template lang="html">
    <div class="registration-login-form">
        <div class="tab-pane" role="tabpanel" data-mh="log-tab">
            <header class="header-reset-password">
                <div class="title h6">{{ $t('form.title.reset_password') }}</div>
                <router-link :to="{ name: 'login' }" class="login-link">
                    {{ $t('form.button.login') }}
                </router-link>
            </header>
            <form class="content" @submit.prevent="resetPassword">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="form-group label-floating" :class="{ 'has-danger': errors.has('email')}">
                            <label class="control-label">{{ $t('form.label.email') }}</label>
                            <input data-vv-delay="300"
                                v-validate="'required|email'"
                                name="email"
                                v-model="email"
                                class="form-control">
                            <span v-show="errors.has('email')" class="material-input text-danger">
                                {{ errors.first('email') }}
                            </span>
                            <span v-show="messageValidate" class="material-input text-danger">
                                {{ messageValidate }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary full-width">
                            <i class="fa fa-spinner fa-spin" v-show="show"></i> {{ $t('form.button.password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { post } from '../../helpers/api'
import { passwordEmail } from '../../router/router'
import noty from '../../helpers/noty'

export default {
    data: () => ({
        email: '',
        messageValidate: '',
        show: false
    }),
    methods: {
        resetPassword() {
            this.$validator.validateAll()
                .then(() => {
                    this.$Progress.start()
                    this.show = true
                    this.messageValidate = ''

                    post(passwordEmail, { email: this.email })
                        .then(res => {
                            this.show = false
                            this.email = ''

                            this.$nextTick(function () {
                                this.errors.clear()
                            })

                            noty({
                                text: res.data.message,
                                force: true,
                                container: '.content',
                                type: 'success',
                                killer: true,
                                animation: {
                                    open: ''
                                }
                            })

                            this.$Progress.finish()
                        })
                        .catch(err => {
                            this.$Progress.fail()
                            this.show = false
                            const { message, status } = err.response.data.http_status

                            if (!status) {
                                this.messageValidate = message
                            }
                        })
                })
                .catch(() => {})
        }
    },
    created() {
        console.log(123)
    }
}
</script>

<style lang="scss" scoped>
    .header-reset-password {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e6ecf5;
        .login-link {
            padding: 10px 25px;
            &:hover {
                color: #ff763a;
            }
        }
        .title {
            border-bottom: none;
        }
    }
</style>
