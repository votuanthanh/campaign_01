import { authGuard, guestGuard } from './middleware'
// Register Components to router
import Campaign from '../components/campaign/Campaign.vue'
import App from '../components/layout/App.vue'
import Auth from '../components/auth/Auth.vue'
import AddCampaign from '../components/campaign/AddCampaign.vue'
import CreateEvent from '../components/event/CreateEvent.vue'
import User from '../components/user/User.vue'
import Password from '../components/user/Password.vue'
import Profile from '../components/user/Profile.vue'
import Setting from '../components/user/Setting.vue'

const router = [
    ...authGuard([
        { path: '/', component: App,
            children: [
                { path: '/campaign', component: Campaign },
                { path: '/campaign/create', component: AddCampaign },
                { path: '/event/create/:campaign_id', component: CreateEvent },
                { path: '/settings', component: User,
                    children: [
                        { path: '', redirect: '/settings/settings' },
                        { path: 'password', component: Password },
                        { path: 'profile', component: Profile },
                        { path: 'settings', component: Setting },
                    ]
                }
            ]
        }
    ]),
    ...guestGuard([
        { path: '/register', component: Auth },
        { path: '/login', component: Auth},
    ])
]

export default router
