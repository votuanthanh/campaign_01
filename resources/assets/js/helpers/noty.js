import Noty from 'noty'

const config = {
    type: 'error',
    layout: 'topRight',
    theme: 'metroui',
    timeout: 3000,
    progressBar: false,
    closeWith: ['click', 'button'],
    id: false,
    force: false,
    queue: 'global',
    container: '.tab-content'
}

export default function noty(customConfig) {
    if (typeof customConfig === 'object') {
        Object.assign(config, customConfig)
    }

    return new Noty(config).show()
}
