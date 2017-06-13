import Vue from 'vue'
import store from './store'
import VueI18n from 'vue-i18n'
import messages from './locale'
import router from './router'
import VeeValidate, { Validator } from 'vee-validate'
import rules from './validation'
import { config, dictionary } from './validation/config'
import { get } from './helpers/api'

Vue.use(VueI18n)

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

const app = new Vue({
    el: '#app',
    store,
    router,
    i18n
})
