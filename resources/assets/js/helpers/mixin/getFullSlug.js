export default {
    created() {
        const pageType = this.pageType || ''
        axios.get(window.Laravel.url + '/api/get-slug/' + pageType + '/' + this.pageId)
            .then(res => {
                if (res.data.slug) {
                    this.$router.push({ name: this.$route.name, params: { slug: res.data.slug }})
                }
            })
    }
}
