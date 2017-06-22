<template lang="html">
    <input :value="date"/>
</template>

<script>
export default {
    props: {
        date: {
            type: String
        }
    },
    mounted() {
        $(this.$el).daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'L'
            }
        });

        $(this.$el).on('apply.daterangepicker', (ev, picker) => {
            const { format } = picker.locale
            const { date } = picker.startDate._d

            this.$emit('update:date', picker.startDate.format(format))
        });

        $(this.$el).on('hide.daterangepicker', (ev, picker) => {
            this.$emit('update:date', '')
        })
    },
    beforeDestroy() {
        $(this.$el).data('daterangepicker').remove()
    }
}
</script>
