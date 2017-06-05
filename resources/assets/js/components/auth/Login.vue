<template lang="html">
    <div class="tab-pane" id="profile" role="tabpanel" data-mh="log-tab">
        <div class="title h6">Login to your Account</div>
        <form class="content" @submit.prevent="login">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="form-group label-floating" :class="{ 'has-danger': errors.has('email')}">
                        <label class="control-label">Your Email</label>
                        <input data-vv-delay="300" v-validate="'unique:types,name,1'"
                            name="email"
                            class="form-control"
                            placeholder=""
                            v-model="user.email">
                        <span v-show="errors.has('email')" class="material-input text-danger">{{ errors.first('email') }}</span>
                    </div>

                    <div class="form-group label-floating">
                        <label class="control-label">Your Password</label>
                        <input class="form-control"
                            v-validate="'required|min:3|max:20'"
                            name="password"
                            type="password"
                            v-model="user.password">
                        <span v-show="errors.has('password')" class="material-input text-danger">{{ errors.first('password') }}</span>
                    </div>

                    <div class="remember">

                        <div class="checkbox">
                            <label>
                                <input name="optionsCheckboxes" type="checkbox">
                                Remember Me
                            </label>
                        </div>
                        <a href="#" class="forgot">Forgot my Password</a>
                    </div>

                    <button type="submit" class="btn btn-lg btn-primary full-width">Login</button>

                    <div class="or"></div>

                    <a href="#" class="btn btn-lg bg-facebook full-width btn-icon-left"><i class="fa fa-facebook" aria-hidden="true"></i>Login with Facebook</a>

                    <a href="#" class="btn btn-lg bg-twitter full-width btn-icon-left"><i class="fa fa-twitter" aria-hidden="true"></i>Login with Twitter</a>


                    <p>Don’t you have an account? <a href="#">Register Now!</a> it’s really simple and you can start enjoing all the benefits!</p>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { get, post } from '../../helpers/api'

export default {
    data: () => ({
        user: {
            email: '',
            password: ''
        }
    }),
    methods: {
        login() {
            this.$validator.validateAll().then(() => {

              }).catch(() => {
                  // eslint-disable-next-line
                  alert('Correct them errors!');
              });
        }
    }
}
</script>

<style lang="scss">
    .label-floating.has-danger {
        &:after {
            content: none;
        }
    }
</style>
