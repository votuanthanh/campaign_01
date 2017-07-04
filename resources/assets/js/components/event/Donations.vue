<template lang="html">
<div>
    <div class="row donation" v-for="(donation, index) in donations">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="form-group label-floating">
                <label class="control-label">Goal</label>
                <input class="form-control"
                    name="goal"
                    type="text"
                    :value="donation.goal"
                    @input="handleDonation($event.target.value, index, 'goal')">
                <span class="material-input"></span>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div class="form-group label-floating is-select">
                <label class="control-label">Measure</label>
                <input class="selectpicker form-control"
                    list="list-measure"
                    name="quality"
                    :value="donation.quality"
                    @input="handleDonation($event.target.value, index, 'quality')">
                <datalist id="list-measure">
                    <option :value="quality.name" v-for="quality in qualitys"></option>
                </datalist>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
            <div class="form-group label-floating is-select">
                <label class="control-label">Type</label>
                <input class="selectpicker
                    form-control"
                    list="list-type"
                    name="type"
                    :value="donation.type"
                    @input="handleDonation($event.target.value, index, 'type')">
                <datalist id="list-type">
                    <option :value="type.name" v-for="type in types"></option>
                </datalist>
            </div>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 store-icon">
            <i class="fa fa-trash icon-donation"
                aria-hidden="true"
                id='delete-donation'
                v-bind:class="{ 'visible': visible }"
                @click="$emit('delete-donation', index)">
            </i>
        </div>
    </div>

</div>
</template>

<script type="text/javascript">
    import { get } from '../../helpers/api'
    export default {
        data: () => ({
            types:'',
            qualitys: ''
        }),
        props: ['donations', 'visible'],
        methods: {
            handleDonation(value, index, type) {
                this.$emit('update-donations', value, index, type)
            }
        },
        created() {
            get('event/donation').then(res => {
                this.types = res.data.types
                this.qualitys = res.data.qualitys
            })
        }
    }
</script>

<style type="text/css">

</style>
