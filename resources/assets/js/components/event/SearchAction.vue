<template>
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="ui-block">
                    <div class="ui-block-title search_action">
                        <div class="row">
                            <div>
                                <input
                                    v-model="key_search"
                                    @input="search"
                                    class="form-control input-search-action"
                                    :placeholder="$t('events.search_action')"
                                    type="text">
                            </div>
                        </div>
                        <div class="align-right">
                            <button
                                href="#"
                                class="btn btn-primary btn-md-2"
                                data-toggle="modal"
                                data-target="#update-header-photo">
                                {{ $t('events.create_action') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    export default {
        data: () => {
            return {
                key_search: ''
            }
        },

        methods: {
            ...mapActions('event', [
                'search_action'
            ]),

            search: _.debounce(function () {
                if (this.key_search) {
                    this.search_action({ event_id: this.$route.params.event_id, key_search: this.key_search.trim()})
                } else {
                    this.search_action({ event_id: this.$route.params.event_id, key_search: this.key_search })
                }
            }, 1000)
        },
    }
</script>

<style type="scss">
    .search_action {
        padding: 7px !important;
        .search_action {
            div {
                input {
                    padding: .8rem 2.1rem !important;
                    margin-top: 2px !important;
                }
            }
            .input-search-action {
                height: 42px !important;
                width: 60%;
                display: inline-block !important;
                float: left;
            }
        }
    }

</style>
