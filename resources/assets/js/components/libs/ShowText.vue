<template>
    <span class="ui-block-title">
        <span v-if="flag">
            <span v-html="convertToHTML(shot_text)"></span>
            <a href="javascript:void(0)" @click="show_hide" v-if="show_link">{{ show }}</a>
        </span>
        <span v-else="!flag">
            <span v-html="convertToHTML(text)"></span>
            <a href="javascript:void(0)" @click="show_hide">{{ hide }}</a>
        </span>
    </span>
</template>

<script>
    import { mapState } from 'vuex'
    export default {
        data: () => {
            return {
                flag : true,
                show_link: true
            }
        },
        props : {
            text: {
                type: String,
            },
            show_char: {
                default: 100,
                type: Number,
            },
            show: {
                type: String,
            },
            hide: {
                type: String,
            }
        },
        computed: {
            shot_text() {
                if (this.text.length > this.show_char) {
                    return this.text.substr(0, this.show_char) + '...'
                } else {
                    this.show_link = false

                    return this.text
                }
            }
        },
        methods: {
            show_hide() {
                this.flag = !this.flag
            },
            convertToHTML(text) {
                return text.replace(/(?:\r\n|\r|\n)/g, '<br />');
            }
        },
        components: {

        }
    }
</script>

<style type="scss">

</style>
