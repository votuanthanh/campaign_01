<template>
    <div class="list-expense">
        <div class="window-popup playlist-popup open">

            <a href="" class="icon-close js-close-popup">
                <svg class="olymp-close-icon"><use xlink:href="icons/icons.svg#olymp-close-icon"></use></svg>
            </a>

            <table class="playlist-popup-table">

                <thead>

                <tr>

                    <th class="play">
                        {{ $t('events.expenses.donate_type') }}
                    </th>

                    <th class="cover">
                        {{ $t('events.expenses.cost') }}
                    </th>

                    <th class="song-artist">
                        {{ $t('events.expenses.time') }}
                    </th>

                    <th class="album">
                        {{ $t('events.expenses.product') }}
                    </th>

                    <th class="released">
                        {{ $t('events.expenses.quantity') }}
                    </th>

                    <th class="spotify">
                        {{ $t('events.expenses.quality') }}
                    </th>

                    <th class="duration">
                        {{ $t('events.expenses.reason') }}
                    </th>

                    <th class="remove">
                        {{ $t('events.expenses.manage') }}
                    </th>
                </tr>

                </thead>

                <tbody>
                <tr v-for="expense in expenses">
                    <td class="play">
                        {{ expense.goal.donation_type.name }}
                    </td>
                    <td class="cover">
                        {{ expense.cost }}
                    </td>
                    <td class="song-artist">
                        {{ formatTime(expense.time) }}
                    </td>
                    <td class="album">
                        {{ getNameProduct(expense) }}
                    </td>
                    <td class="released">
                        <div class="release-year">{{ getQuantity(expense) }}</div>
                    </td>
                    <td class="spotify">
                        <div class="composition-time">
                            {{ getNameQuality(expense) }}
                        </div>
                    </td>
                    <td class="duration">
                        <i class="fa fa-duration composition-icon" aria-hidden="true"></i>
                    </td>
                    <td class="remove">
                        <a href="#" class="remove-icon">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/javascript">
    import { get } from '../../../helpers/api'
    export default {
        data: () => ({
            expenses: []
        }),

        created() {
            get(`expense?event_id=${this.$route.params.event_id}`).then(res => {
                this.expenses = res.data.expenses
            })
        },

        methods: {
            getNameProduct(expense) {
                return expense.products.length ? expense.products[0].name : ''
            },

            getQuantity(expense) {
                return expense.products.length ? expense.products[0].pivot.quantity : ''
            },

            getNameQuality(expense) {
                return expense.qualitys.length ? expense.qualitys[0].name : ''
            },

            formatTime(time) {
                return moment(time).format('YYYY-MM-DD')
            }
        }
    }
</script>

<style lang="scss">
    .list-expense {
        .playlist-popup {
            position: inherit !important;
        }
    }
</style>
