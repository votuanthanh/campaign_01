<template>
    <div class='expense'>
        <div class='row direct'>
            <div
                :class="{
                    'col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3': !event.deleted_at,
                    'col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6': event.deleted_at
                }">
                <router-link
                    tag="button"
                    class="btn btn-primary btn-md-2 bg-breez"
                    :to="{ name: 'expense.list' }"
                    active-class="active"
                    data-toggle="modal"
                    data-target="#update-header-photo">
                    {{ $t('events.expenses.list_expense') }}
                </router-link>
            </div>
            <div
                class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3"
                v-if="!event.deleted_at">
                <router-link
                    tag="button"
                    class="btn btn-primary btn-md-2 bg-breez"
                    :to="{ name: 'expense.create' }"
                    active-class="active"
                    data-toggle="modal"
                    data-target="#update-header-photo">
                    {{ $t('events.expenses.create_expense') }}
                </router-link>
            </div>
            <div
                class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3"
                v-if="!event.deleted_at">
                <router-link
                    tag="button"
                    class="btn btn-primary btn-md-2 bg-breez"
                    :to="{ name: 'expense.create.buy' }"
                    active-class="active"
                    data-toggle="modal"
                    data-target="#update-header-photo">
                    {{ $t('events.expenses.create_expense_buy') }}
                </router-link>
            </div>
            <div
                :class="{
                    'col-xl-3 col-lg-3 col-md-3 col-sm-3 col-xs-3': !event.deleted_at,
                    'col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6': event.deleted_at
                }">
                <router-link
                    tag="button"
                    class="btn btn-primary btn-md-2 bg-breez"
                    :to="{ name: 'expense.statictis' }"
                    active-class="active"
                    data-toggle="modal"
                    data-target="#update-header-photo">
                    {{ $t('events.expenses_statistic.statistic') }}
                </router-link>
            </div>
        </div>
        <div>
            <router-view>
            </router-view>
        </div>
    </div>
</template>

<script type="text/javascript">
    import { mapState } from 'vuex'

    export default {
        computed: {
            ...mapState('event', ['event'])
        },
    }
</script>

<style lang="scss">
    .expense {
        .direct {
            margin-right: 0px;
            margin-left: 0px;
            div {
                padding: 0px;
                .btn {
                    width: 100%;
                    border-radius: 0px;
                    margin-bottom: 0px;
                }
            }
        }
    }
</style>
