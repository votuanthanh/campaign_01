import Vue from 'vue'
import VueRouter from 'vue-router'
// Homepage
import Campaigns from '../components/homepage/Campaigns.vue'
import Events from '../components/homepage/Events.vue'
import Actions from '../components/homepage/Actions.vue'
import Contacts from '../components/homepage/Campaigns.vue'

Vue.use(VueRouter)

export const router = new VueRouter({
	mode: 'history',
	routes: [
		{ path: '/homepage/campaigns', component: Campaigns },
		{ path: '/homepage/events', component: Events },
		{ path: '/homepage/actions', component: Actions },
		{ path: '/homepage/contacts', component: Contacts }
	]
})
