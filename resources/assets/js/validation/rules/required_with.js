import { config } from '../config'
import { message } from './function'
export default {
    messages: message('required_with', ...window.Laravel.languages),
    validate(value, args) {
        let fields = []
        let elements = []

        for (let n of args ) {
            const [field, arg] = n.split('-')
            fields.push(field)
            elements.push(arg)
        }

        return new Promise(resolve => {
            resolve({
                valid: !elements.some(element => element === '' && value),
                data: fields
            });
        });
    }
}
