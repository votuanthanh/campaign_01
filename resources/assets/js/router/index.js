import { authGuard, guestGuard } from './middleware'
// Register Components to router
import Campaign from '../components/campaign/Campaign.vue'
import App from '../components/layout/App.vue'
import Auth from '../components/auth/Auth.vue'

const router = [
    ...authGuard([
        // auth routes here
    ]),
    ...guestGuard([
        { path: '/register', component: Auth },
        { path: '/login', component: Auth},
    ]),
    { path: '/', component: App,
        children: []
    }

]

export default router
