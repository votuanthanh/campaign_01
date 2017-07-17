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
                                data-target="#update-header-photo"
                                @click="createAction">
                                {{ $t('events.create_action') }}
                            </button>
                            <div class="more">
                                <svg class="olymp-three-dots-icon"><use xlink:href="/frontend/icons/icons.svg#olymp-three-dots-icon"></use></svg>
                                <ul class="more-dropdown">
                                    <li>
                                        <a href="#">Edit Post</a>
                                    </li>
                                    <li>
                                        <a href="#">Delete Post</a>
                                    </li>
                                    <li>
                                        <a href="#">Turn Off Notifications</a>
                                    </li>
                                    <li>
                                        <a href="#">Select as Featured</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <create-action :show.sync="show">
        </create-action>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    import CreateAction from './CreateAction.vue'
    export default {
        data: () => {
            return {
                key_search: '',
                show: false,
            }
        },

        methods: {
            ...mapActions('event', [
                'search_action'
            ]),

            search: _.debounce(function () {
                if (this.key_search) {
                    this.search_action({ event_id: this.$route.params.event_id, key_search: this.key_search.replace(/ +/g, ' ') })
                } else {
                    this.search_action({ event_id: this.$route.params.event_id, key_search: this.key_search })
                }
            }, 1000),

            createAction() {
                this.show = true
            }
        },

        components: {
            CreateAction
        }
    }
</script>

<style lang="scss">
    .search_action {
        padding: 7px !important;
        .input-search-action {
            height: 42px !important;
            width: 50%;
            display: inline-block !important;
            float: left;
            padding: .8rem 2.1rem !important;
            margin-top: 2px !important;
        }
        .more {
            display: inline;
        }
    }
</style>
