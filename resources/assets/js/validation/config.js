import english from 'vee-validate/dist/locale/en'
import japan from 'vee-validate/dist/locale/ja'
import vietnam from 'vee-validate/dist/locale/vi'
import { message } from './rules/function'
import attribute from './attributes'

// Field-specific Custom Messages
const customMessages = {}
const rules = ['required'];

for (let lang of window.Laravel.languages) {
    customMessages[lang] = { messages : {} }
    for (let rule of rules) {
        let messageRule = message(rule, ...window.Laravel.languages)
        customMessages[lang]['messages'][rule] = messageRule[lang]
    }
}

export const dictionary = customMessages

// Set attributes and messages
export const config = {
    locale: window.Laravel.locale,
    dictionary: {
        en: {
            messages: english.messages,
            attributes: attribute.en
        },
        vi: {
            messages: vietnam.messages,
            attributes: attribute.vi
        },
        ja: {
            messages: japan.messages,
            attributes: attribute.ja
        }
    }
}
