require('./bootstrap');
import Vue from 'vue'
import store from './store'
import VueI18n from 'vue-i18n'
import messages from './locale'
import { router } from './router'
import VeeValidate, { Validator } from 'vee-validate'
import rules from './validation'

console.log(rules)

Vue.use(VueI18n)
Vue.use(VeeValidate)

// Register rules vee-validation
for (let rule in rules) {
    Validator.extend(rule, rules[rule])
}

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
