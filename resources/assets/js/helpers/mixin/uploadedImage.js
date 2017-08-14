import UploadQuill from '../../components/libs/UploadQuill.vue'
import { editorOption } from '../../config'

export default {
    data: () => ({
        uploadVisible: false,
        imageInsert: '',
        toggleFullscreen: false,
        editorOption
    }),
    mounted() {
        const vm = this
        this.$refs.description.quill.getModule('toolbar').addHandler('image', function () {
            vm.uploadVisible = true
        });

        var fullscreen = document.querySelector('.ql-fullscreen');
        fullscreen.addEventListener('click', function() {
            vm.toggleFullscreen = ! vm.toggleFullscreen

            if (vm.toggleFullscreen) {
                document.body.style.overflow = 'hidden'
            } else {
                document.body.removeAttribute('style')
            }
        });
    },
    methods: {
        insertImageToContent(image) {
            const quill = this.$refs.description.quill
            quill.focus()
            const { index } = quill.getSelection()
            quill.insertEmbed(index, 'image', image);

            this.uploadVisible = false
        }
    },
    components: {
        UploadQuill
    }
}
