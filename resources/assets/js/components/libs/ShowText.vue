<template>
    <span v-if="flag">
        <span class="show-text" v-html="convertToHTML(shot_text)"></span>
        <a href="javascript:void(0)" @click="show_hide" v-if="show_link">{{ show }}</a>
    </span>
    <span v-else>
        <span class="show-text" v-html="convertToHTML(text)"></span>
        <a href="javascript:void(0)" @click="show_hide">{{ hide }}</a>
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
                default: 1000,
                type: Number,
            },
            number_char_show: {
                default: 700,
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
                    return this.text.substr(0, this.number_char_show) + '...'
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
    }
</script>

<style type="scss" scoped>
    .show-text {
        word-wrap: break-word;
    }
</style>
