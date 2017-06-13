import messages from '../../locale'

export function message(nameRule, ...locale) {
    let localizations = {}

    for (let lang of locale) {
        localizations[lang] =
            (field, args, data) =>
                (data && data.message) || messages[lang]['validations'][nameRule].replace(':attribute', field).replace(':value', args)
    }

    return localizations
}
