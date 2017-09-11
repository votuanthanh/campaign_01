<template>
    <div id="app">
        <vue-progress-bar></vue-progress-bar>
        <component v-if="layout" :is="layout"></component>
    </div>
</template>

<script>
const layouts = {
    app: require('../components/layout/App.vue'),
    default: require('../components/layout/Default.vue')
}

export default {
    metaInfo() {
        const { appName } = window.Laravel

        return {
            title: appName,
            titleTemplate: `%s | ${appName}`
        }
    },
    data() {
        return {
            layout: null,
            defaultLayout: 'app'
        }
    },
    mounted() {
        this.$Progress.finish()
    },
    created() {
        this.$Progress.start()
        this.$router.beforeEach((to, from, next) => {
            if (to.meta.progress !== undefined) {
                let meta = to.meta.progress
                this.$Progress.parseMeta(meta)
            }
            this.$Progress.start()
            next()
        })
        this.$router.afterEach((to, from) => {
            this.$Progress.finish()
        })
    },
    methods: {
        setLayout (layout) {
            if (!layout || !layouts[layout]) {
                layout = this.defaultLayout
            }

            this.layout = layouts[layout]
        }
    }
}
</script>
