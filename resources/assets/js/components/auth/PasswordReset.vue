<template lang="html">
    <div class="landing-page">

        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div id="site-header-landing" class="header-landing">
                        <router-link class="logo" to="/">
                            <img src="/images/logo.png" alt="Olympus">
                            <h5 class="logo-title">Campaign</h5>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Password Form  -->
        <div class="container">
            <div class="row display-flex container-forgot-password" id="hello">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="landing-content">
                        <h1>Welcome to the Biggest Social Network in the World</h1>
                        <p>We are the best and biggest social network with 5 billion active users all around the world. Share you
                            thoughts, write blog posts, show your favourite music via Stopify, earn badges and much more!
                        </p>
                        <router-link :to="{ name: 'register' }" class="btn btn-md btn-border c-white">
                            {{ $t('form.button.register') }}
                        </router-link>
                    </div>
                </div>

                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <router-view></router-view>
                </div>
            </div>
        </div>
        <!-- Reset Password Form  -->

        <vue-progress-bar></vue-progress-bar>
    </div>
</template>

<script>
import { post } from '../../helpers/api'
import { passwordEmail } from '../../router/router'
import noty from '../../helpers/noty'

export default {
    metaInfo() {
        return {
            title: this.$t('form.title.reset_password')
        }
    }
}
</script>

<style lang="scss" scoped>
    .container-forgot-password {
        height: 65vh;
        .registration-login-form {
            padding-left: 0px;
        }
    }
</style>
