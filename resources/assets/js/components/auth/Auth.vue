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
                        <ul class="profile-menu">
                            <li>
                                <a href="#">About Us</a>
                            </li>
                            <li>
                                <a href="#">Careers</a>
                            </li>
                            <li>
                                <a href="#">FAQS</a>
                            </li>
                            <li>
                                <a href="#">Help & Support</a>
                            </li>
                            <li>
                                <a href="#" class="js-expanded-menu">
                                    <svg class="olymp-menu-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-menu-icon"></use></svg>
                                    <svg class="olymp-close-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-close-icon"></use></svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login-Registration Form  -->
        <div class="container">
            <div class="row display-flex" id="hello">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="landing-content">
                        <h1>Welcome to the Biggest Social Network in the World</h1>
                        <p>We are the best and biggest social network with 5 billion active users all around the world. Share you
                            thoughts, write blog posts, show your favourite music via Stopify, earn badges and much more!
                        </p>
                        <a href="javascript:void(0)" class="btn btn-md btn-border c-white" @click.prevent="handleTab('register')">
                            {{ $t('form.button.register') }}
                        </a>
                    </div>
                </div>

                <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="registration-login-form">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" :class="{ active: registerTab }"
                                    data-toggle="tab" href="#home" role="tab" @click="handleTab('register')">
                                    <svg class="olymp-login-icon">
                                        <use xlink:href="/frontend/icons/icons.svg#olymp-login-icon"></use>
                                    </svg>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="{ active: loginTab }"
                                    data-toggle="tab" href="#profile" role="tab" @click="handleTab('login')">
                                    <svg class="olymp-register-icon">
                                        <use xlink:href="/frontend/icons/icons.svg#olymp-register-icon"></use>
                                    </svg>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <register :registerTab="registerTab"></register>
                            <login :loginTab="loginTab" @handleTab="handleTab"></login>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ... end Login-Registration Form  -->
    </div>
</template>

<script>
import Login from './Login.vue'
import Register from './Register.vue'
import { mapState } from 'vuex'

export default {
    data: () => ({
        user: {
            email: '',
            password: ''
        },
        loginTab: false,
        registerTab: false,
    }),
    computed: {
        ...mapState('auth', {
            authenticated: state => state.authenticated
        })
    },
    components: {
        Login,
        Register
    },
    methods: {
        handleTab(tab) {
            if (tab === 'login') {
                this.loginTab = true
                this.registerTab = !this.loginTab
            } else {
                this.registerTab = true
                this.loginTab = !this.registerTab
            }
        }
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            const tab = to.path == '/register' ? 'home' : 'profile'
            if (tab == 'profile') {
                vm.loginTab = true
            } else {
                vm.registerTab = true
            }
        })
    }
}
</script>

<style lang="scss">
</style>
