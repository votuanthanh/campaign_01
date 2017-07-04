import { authGuard, guestGuard } from './middleware'
// Register Components to router
import Campaign from '../components/campaign/Campaign.vue'
import App from '../components/layout/App.vue'
import Auth from '../components/auth/Auth.vue'
import AddCampaign from '../components/campaign/AddCampaign.vue'
import CreateEvent from '../components/event/CreateEvent.vue'

const router = [
    ...authGuard([
        { path: '/', component: App,
            children: [
                { path: '/campaign', component: Campaign },
                { path: '/campaign/create', component: AddCampaign },
                { path: '/event/create/:campaign_id', component: CreateEvent }
            ]
        }
    ]),
    ...guestGuard([
        { path: '/register', component: Auth },
        { path: '/login', component: Auth},
    ])
]

export default router
