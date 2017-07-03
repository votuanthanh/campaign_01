import messages from '../../locale'
import { config } from '../config'

export function message(nameRule, ...locale) {
    let localizations = {}

    for (let lang of locale) {
        localizations[lang] =
            (field, args, data) => {
                if (data && nameRule == 'required_with') {
                    const configAttributes = config['dictionary'][window.Laravel.locale]['attributes']
                    args = data.map(v => configAttributes[v]).join(' / ')
                }

                return messages[lang]['validations'][nameRule]
                    .replace(':attribute', field)
                    .replace(':value', args)
            }
    }

    return localizations
}
