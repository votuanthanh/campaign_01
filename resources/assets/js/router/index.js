import Vue from 'vue'
import VueRouter from 'vue-router'
import Campaign from '../components/campaign/Campaign.vue'
import App from '../components/layout/App.vue'
import Auth from '../components/auth/Auth.vue'

Vue.use(VueRouter)

export const router = new VueRouter({
	mode: 'history',
	routes: [
		{ path: '/register', component: Auth },
		{ path: '/login', component: Auth },
		{ path: '/', component: App }
	]
})
