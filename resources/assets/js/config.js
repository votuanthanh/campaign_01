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
