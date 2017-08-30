export default {
    created() {
        const pageType = this.pageType || ''
        axios.get(window.Laravel.url + '/api/get-slug/' + pageType + '/' + this.pageId)
            .then(res => {
                if (res.data.slug) {
                    if (pageType == 'event')
                        this.$router.push({ name: this.$route.name, params: { slug: this.$route.params.slug, slugEvent: res.data.slug } })
                    else
                        this.$router.push({ name: this.$route.name, params: { slug: res.data.slug }})
                }
            })
    }
}
