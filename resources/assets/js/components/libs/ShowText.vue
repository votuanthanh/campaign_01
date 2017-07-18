<template>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <i v-if="flag">
                        <span v-html="convertToHTML(shot_text)"></span>
                        <a href="javascript:void(0)" @click="show_hide" v-if="show_link">{{ show }}</a>
                    </i>
                    <i v-else="!flag">
                        <span v-html="convertToHTML(text)"></span>
                        <a href="javascript:void(0)" @click="show_hide">{{ hide }}</a>
                    </i>
                </div>
            </div>
        </div>
    </div>
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
