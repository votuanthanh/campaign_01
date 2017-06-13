<template lang="html">
    <input :value="birthday"/>
</template>

<script>
export default {
    props: {
        birthday: {
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

        $(this.$el).on('hide.daterangepicker', (ev, picker) => {
            const { format } = picker.locale
            const { date } = picker.startDate._d

            this.$emit('update:birthday', picker.startDate.format(format))
        });
    },
    beforeDestroy() {
        $(this.$el).data('daterangepicker').remove()
    }
}
</script>
