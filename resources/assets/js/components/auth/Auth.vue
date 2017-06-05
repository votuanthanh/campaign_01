<template lang="html">
    <div class="landing-page">

        <div class="container">
            <div class="row">
        		<div class="col-xl-12 col-lg-12 col-md-12">
        			<div id="site-header-landing" class="header-landing">
        				<a href="02-ProfilePage.html" class="logo">
        					<img src="/images/logo.png" alt="Olympus">
        					<h5 class="logo-title">olympus</h5>
        				</a>

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
        				<a href="#" class="btn btn-md btn-border c-white">Register Now!</a>
        			</div>
        		</div>

        		<div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12">
        			<div class="registration-login-form" data-mh="log-tab">
        				<!-- Nav tabs -->
        				<ul class="nav nav-tabs" role="tablist">
        					<li class="nav-item">
        						<a class="nav-link" :class="{ active: activeLogin }"
                                    data-toggle="tab" href="#home" role="tab" @click="activeLogin = !activeLogin">
        							<svg class="olymp-login-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-login-icon"></use></svg>
        						</a>
        					</li>
        					<li class="nav-item">
        						<a class="nav-link" :class="{ active: !activeLogin }"
                                    data-toggle="tab" href="#profile" role="tab" @click="activeLogin = !activeLogin">
        							<svg class="olymp-register-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-register-icon"></use></svg>
        						</a>
        					</li>
        				</ul>

        				<!-- Tab panes -->
        				<div class="tab-content">
                            <register></register>
                            <login></login>
                            <router-link class="hello" :to="homepage">Homepage</router-link>
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

export default {
    data: () => ({
        user: {
            email: '',
            password: ''
        },
        activeLogin: false,
        homepage: '/'
    }),
    components: {
        Login,
        Register
    },
    beforeRouteEnter (to, from, next) {
        next((vm) => {
            const tab = to.path === '/register' ? 'home' : 'profile'

            vm.activeLogin = tab == 'profile' ? true : false

            $(`.registration-login-form a[href="#${tab}"]`).tab('show')
        })
    }
}
</script>

<style lang="scss">
</style>
