<template>
    <div>
        <section class="list-expense">
            <h1>
                <a href="javascript:void(0)" class="back-page" @click="prevPage" v-if="expenses.prev_page_url">
                    <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                </a>
                {{ $t('events.expenses.list_expense') }}
                <a href="javascript:void(0)" class="next-page" @click="nextPage" v-if="expenses.next_page_url">
                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                </a>
            </h1>
            <div class="tbl-header">
                <table cellpadding="0" cellspacing="0" border="0">
                  <thead>
                    <tr>
                        <th>{{ $t('events.expenses.donate_type') }}</th>
                        <th>{{ $t('events.expenses.cost') }}</th>
                        <th>{{ $t('events.expenses.time') }}</th>
                        <th>{{ $t('events.expenses.product') }}</th>
                        <th>{{ $t('events.expenses.quantity') }}</th>
                        <th>{{ $t('events.expenses.quality') }}</th>
                        <th >{{ $t('events.expenses.reason') }}</th>
                        <th v-if="isManager && !event.deleted_at">
                            {{ $t('events.expenses.manage') }}
                        </th>
                    </tr>
                  </thead>
                </table>
            </div>
            <div class="tbl-content">
                <table cellpadding="0" cellspacing="0" border="0">
                  <tbody>
                    <tr v-for="expense in expenses.data">
                      <td>
                            {{ expense.goal.donation_type.name }}
                        </td>
                        <td>
                            {{ expense.cost }} ({{ expense.goal.donation_type.quality.name }})
                        </td>
                        <td>
                            {{ formatTime(expense.time) }}
                        </td>
                        <td>
                            {{ getNameProduct(expense) }}
                        </td>
                        <td>
                            <div>{{ getQuantity(expense) }}</div>
                        </td>
                        <td>
                            <div>
                                {{ getNameQuality(expense) }}
                            </div>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="remove-icon" @click="showReason(expense.reason)">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td v-if="isManager && !event.deleted_at">
                            <a
                                href="javascript:void(0)"
                                class="remove-icon"
                                @click="updateExpenseMy(expense)"
                                v-if="expense.type">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>
                            <a
                                href="javascript:void(0)"
                                class="remove-icon"
                                @click="updateExpense(expense)"
                                v-else>
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>

                            <a href="javascript:void(0)" class="remove-icon" @click="comfirmDelete(expense.id)">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </section>
        <message :show.sync="isShowReason">
            <h2 class="exclamation-header" slot="header">
                <span class="fa fa-check-circle"></span>&nbsp;{{ $t('events.expenses.reason') }}
            </h2>
            <div class="body-modal" slot="main">
                <div v-html="reason"></div>
            </div>
        </message>
        <message :show.sync="isShowDelete">
            <h5 class="exclamation-header" slot="header">
                {{ $t('messages.comfirm_delete_expense') }}
            </h5>
            <div class="body-modal confirm-delete" slot="main">
                <a href="javascript:void(0)"
                    class="btn btn-breez col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    @click="deleteExpense">
                    {{ $t('form.button.agree') }}
                </a>
                <a href="javascript:void(0)"
                    class="btn btn-secondary col-lg-3 col-md-6 col-sm-12 col-xs-12"
                    @click="cancelDeleteExpense">
                    {{ $t('form.button.no') }}
                </a>
            </div>
        </message>
        <update-expense
            :show.sync="isShowUpdate"
            :expense="expenseIsUpdate"
            @loadAgainApi="callApi"
            v-if="isShowUpdate">
        </update-expense>
        <update-expense-buy
            :show.sync="isShowUpdateBuy"
            :expense="expenseIsUpdateBuy"
            @loadAgainApi="callApi"
            v-if="isShowUpdateBuy">
        </update-expense-buy>
    </div>
</template>

<script type="text/javascript">
    import { get, del } from '../../../helpers/api'
    import noty from '../../../helpers/noty'
    import Message from '../../libs/Modal.vue'
    import UpdateExpense from './UpdateExpense.vue'
    import UpdateExpenseBuy from './UpdateExpenseBuy.vue'
    import { mapState } from 'vuex'
    import axios from 'axios'

    export default {
        data: () => ({
            expenses: [],
            isManager: false,
            reason: '',
            isShowReason: false,
            isShowDelete: false,
            isShowUpdate: false,
            isShowUpdateBuy: false,
            deleteExpenseId: null,
            expenseIsUpdate: null,
            expenseIsUpdateBuy: null,
            pageType: 'event'
        }),

        created() {
            this.callApi()
        },

        computed : {
            ...mapState('event', [
                'event',
            ])
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
            },

            showReason(reason) {
                this.reason = reason
                this.isShowReason = true
            },

            comfirmDelete(id) {
                this.deleteExpenseId = id
                this.isShowDelete = true
            },

            cancelDeleteExpense() {
                this.deleteExpenseId = null
                this.isShowDelete = false
            },

            deleteExpense() {
                del(`expense/${this.deleteExpenseId}`)
                    .then(res => {
                        this.$Progress.finish()
                        noty({
                            text: this.$i18n.t('messages.delete_success'),
                            force: false,
                            container: false,
                            type: 'success'
                        })
                        this.cancelDeleteExpense()
                        this.callApiAfterDelete()
                    })
                    .catch(err => {
                        this.$Progress.fail()
                        noty({
                            text: this.$i18n.t('messages.delete_fail'),
                            type: 'error',
                            force: false,
                            container: false
                        })
                        this.cancelDeleteExpense()
                    })
            },

            callApi() {
                get(`expense?event_id=${this.pageId}`).then(res => {
                    this.expenses = res.data.expenses
                    this.isManager = res.data.isManager
                })
            },

            updateExpense(expense) {
                this.expenseIsUpdate = expense
                this.isShowUpdate = true
            },

            updateExpenseMy(expense) {
                this.expenseIsUpdateBuy = expense
                this.isShowUpdateBuy = true
            },

            prevPage() {
                this.$Progress.start()
                axios({
                    method: 'GET',
                    url: this.expenses.prev_page_url + `&event_id=${this.pageId}`
                }).then(res => {
                    this.$Progress.finish()
                    this.expenses = res.data.expenses
                    this.isManager = res.data.isManager
                })
            },

            nextPage() {
                this.$Progress.start()
                axios({
                    method: 'GET',
                    url: this.expenses.next_page_url + `&event_id=${this.pageId}`
                }).then(res => {
                    this.$Progress.finish()
                    this.expenses = res.data.expenses
                    this.isManager = res.data.isManager
                })
            },

            callApiAfterDelete() {
                let url = this.expenses.path + `?event_id=${this.pageId}`

                if (this.expenses.data.length - 1 || !this.expenses.prev_page_url){
                    url = url + `&page=${this.expenses.current_page}`
                } else {
                    url = this.expenses.prev_page_url + `&event_id=${this.pageId}`
                }

                axios({
                    method: 'GET',
                    url
                }).then(res => {
                    this.$Progress.finish()
                    this.expenses = res.data.expenses
                    this.isManager = res.data.isManager
                })
            }
        },

        components: {
            Message,
            UpdateExpense,
            UpdateExpenseBuy
        }
    }
</script>

<style lang="scss" scoped>
    .body-modal {
        div {
            max-height: 600px;
            overflow: auto;
        }
    }
    .confirm-delete {
        text-align: center;
        a {
            margin: 0 20px;
        }
    }
    .list-expense {
        margin-bottom: 50px;
        background: white;
        font-family: 'Roboto', sans-serif;
        h1 {
            font-size: 30px;
            color: black;
            text-transform: uppercase;
            font-weight: 300;
            text-align: center;
            margin-bottom: 10px;
            border-bottom: solid 1px rgb(193, 193, 193);
            .back-page {
                float: left;
                padding-left: 10px;
            }
            .next-page {
                float: right;
                padding-right: 10px;
            }
        }
        table {
          width:100%;
          table-layout: fixed;
        }
        .tbl-header {
          background-color: white;
          border-bottom: solid 1px rgb(193, 193, 193);
         }
        .tbl-content {
          max-height:500px;
          overflow-x:auto;
          margin-top: 0px;
          border: 1px solid rgba(255,255,255,0.3);
        }
        th {
          padding: 20px 15px;
          text-align: left;
          font-weight: 500;
          font-size: 12px;
          color: black;
          text-transform: uppercase;
        }
        td {
          padding: 15px;
          text-align: left;
          vertical-align:middle;
          font-weight: 300;
          font-size: 12px;
          color: black;
          border-bottom: solid 1px rgb(193, 193, 193);
        }
        tr {
            &:hover {
                background-color: #ececec;
            }
        }
        .made-with-love a:hover {
          text-decoration: underline;
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        }
        ::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        }
        .remove-icon {
            margin-left: 10px;
            i {
                font-size: 1.5em;
                color: #ff5e3a;
                &:hover {
                    color: #85e897;
                }
            }
        }
    }
</style>
