<template lang="html">
    <div>
        <input type="text" class="form-control" name="birthday" :value="value"/>
    </div>
</template>

<script>
export default {
    props: {
        date: {
            type: String
        },
        data: {
            type: Object
        }
    },
    data() {
        return {
            value: this.date
        }
    },
    mounted() {
        $(this.$el).daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'L'
            },
            ...this.data,
        });

        $(this.$el).on('apply.daterangepicker', (ev, picker) => {
            const { format } = picker.locale
            const { date } = picker.startDate._d

            this.$emit('update:date', picker.startDate.format(format))
            this.$emit('input', picker.startDate.format(format))
            this.value = picker.startDate.format(format)
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
