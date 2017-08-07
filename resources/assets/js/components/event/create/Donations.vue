<template lang="html">
<div>
    <div class="row donation">
        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
            <div class="form-group label-floating" :class="{ 'has-danger': this.errors.has('goal') }">
                <label class="control-label">{{ $t('form.label.goal') }}</label>
                <input class="form-control"
                    name="goal"
                    type="text"
                    :value="donation.goal"
                    @input="goal = $event.target.value"
                    v-validate="'required|numeric'">
                <span v-show="errors.has('goal')" class="material-input text-danger">
                    {{ errors.first('goal') }}
                </span>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <multiselect
                @input="(value, id) => { type = value }"
                :value="donation.type"
                v-validate="'required'"
                data-vv-name="type"
                :class="{ 'has-danger': this.errors.has('type') }"
                :placeholder="$i18n.t('form.placeholder.choose_type')"
                :searchable="true"
                :options="types"
                @search-change="newType">
            </multiselect>
            <span v-show="errors.has('type')" class="material-input text-danger">
                {{ errors.first('type') }}
            </span>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <multiselect
                @input="(value, id) => { quality = value }"
                :value="donation.quality"
                v-validate="'required'"
                data-vv-name="quality"
                :class="{ 'has-danger': this.errors.has('quality') }"
                :placeholder="$i18n.t('form.placeholder.choose_quality')"
                :searchable="true"
                :options="qualitys"
                @search-change="newQuality">
            </multiselect>
            <span v-show="errors.has('quality')" class="material-input text-danger">
                {{ errors.first('quality') }}
            </span>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 store-icon">
            <i class="fa fa-trash icon-donation"
                aria-hidden="true"
                id='delete-donation'
                :class="{ visible: visible }"
                @click="deleteDonation">
            </i>
        </div>
    </div>
</div>
</template>

<script type="text/javascript">
    import Multiselect from 'vue-multiselect'
    import { get } from '../../../helpers/api'
    export default {
        data: () => ({
            types: [],
            qualitys: [],
            goal: '',
            type: '',
            quality: '',
        }),
        props: {
            donation: {
                type: Object,
                required: true
            },
            index: {
                type: Number,
                required: true
            },
            visible: {
                type: Boolean,
                required: true
            }
        },
        watch: {
            goal: {
                handler: function(newGoal) {
                    this.$emit('update-row-donation', { index: this.index, goal: newGoal, instanseValidate: this.$validator })
                },
                deep: true
            },
            type: {
                handler: function(newType) {
                    this.$emit('update-row-donation', { index: this.index, type: newType, instanseValidate: this.$validator})
                },
                deep: true
            },
            quality: {
                handler: function(newQuality) {
                    this.$emit('update-row-donation', { index: this.index, quality: newQuality, instanseValidate: this.$validator})
                },
                deep: true
            }
        },
        methods: {
            deleteDonation() {
                this.$emit('delete-donation')
            },
            newType(newValue) {
                if (newValue != '' && this.types.indexOf(newValue) < 0) {
                    this.types.push(newValue)
                }
            },
            newQuality(newValue) {
                if (newValue != '' && this.qualitys.indexOf(newValue) < 0) {
                    this.qualitys.push(newValue)
                }
            }
        },
        created() {
            this.$emit('add-instance-validate', this.index, this.$validator)

            get('event/donation').then(res => {
                this.types = res.data.types.map(type => type.name)
                this.qualitys = res.data.qualitys.map(quality => quality.name)
            })
        },
        components: {
            Multiselect
        }
    }
</script>

<style type="text/css">

</style>
