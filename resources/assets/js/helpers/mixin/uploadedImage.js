import UploadQuill from '../../components/libs/UploadQuill.vue'

export default {
    mounted() {
        const vm = this
        this.$refs.description.quill.getModule('toolbar').addHandler('image', function () {
            vm.uploadVisible = true
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
