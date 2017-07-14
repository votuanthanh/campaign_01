import Vue from 'vue'
import store from './store'
import VueI18n from 'vue-i18n'
import messages from './locale'
import routes from './router'
import makeRouter from './router/middleware'
import VeeValidate, { Validator } from 'vee-validate'
import rules from './validation'
import { config, dictionary } from './validation/config'
import VueProgressBar from 'vue-progressbar'
import * as configPlugin from './config'
import * as VueGoogleMaps from 'vue2-google-maps'
import VueTimeago from 'vue-timeago'

// import editor quill
import VueQuillEditor from 'vue-quill-editor'
import { ImageImport } from './helpers/quill-editor/ImageImport'
import { ImageResize } from './helpers/quill-editor/ImageResize'

Quill.register('modules/imageImport', ImageImport)
Quill.register('modules/imageResize', ImageResize)

import VueSocketio from 'vue-socket.io'
import socketio from 'socket.io-client'

Vue.use(VueSocketio, socketio(':8890'))
Vue.use(VueQuillEditor)
Vue.use(VueI18n)
Vue.use(VueTimeago, configPlugin.timeago)
Vue.use(VueProgressBar, configPlugin.topProgressBar)

// Register rules vee-validation
Vue.use(VeeValidate, config)
for (let rule in rules) {
    Validator.extend(rule, rules[rule])
}

Validator.updateDictionary(dictionary);

const i18n = new VueI18n({
    locale: window.Laravel.locale,
    fallbackLocale: window.Laravel.fallbackLocale,
    messages
})

const router = makeRouter(routes)

const app = new Vue({
    el: '#app',
    store,
    router,
    i18n
})
