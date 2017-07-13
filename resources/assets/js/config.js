export const config = {
    keyMap : 'AIzaSyBOvX0GSM1XmrsnM_-0kInTFu2n2g3OBM8',
    zoom: 15,
    maxFileUpload: 10,
    maxSizeFile: 0.49,
}

// Config Editor Quill
export const editorOption = {
    modules: {
        history: {
          delay: 1000,
          maxStack: 50,
          userOnly: false
        },
        imageImport: true,
        imageResize: {
          displaySize: true
        }
    }
}

// Config VueTimeago
export const timeago = {
    name: 'timeago', // component name, `timeago` by default
    locale: 'en-US',
    locales: {
        // you will need json-loader in webpack 1
        'en-US': require('vue-timeago/locales/en-US.json')
    }
}

// Config VueTimeago
export const topProgressBar = {
    color: '#ffc923',
    failedColor: '#f92552',
    height: '4px'
}

export default {
    config,
    editorOption,
    timeago,
    topProgressBar
}
