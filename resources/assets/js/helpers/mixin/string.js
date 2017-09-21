export default {
    methods: {
        shorten(string, strip = false, length = 0) {
            string = String(string)

            if (strip) {
               let tmp = document.createElement("DIV")
               tmp.innerHTML = string
               string = tmp.textContent || tmp.innerText || ""
            }

            if (length > 0 && string.length > length)
                return string.substr(0, length - 3) + '...'
            return string
        }
    }
}
