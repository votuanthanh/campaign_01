<template>
    <div id="statistic">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <div class="ui-block" data-mh="pie-chart">
                    <div class="ui-block-title">
                        <div class="h6 title">{{ $t('events.expenses_statistic.overview') }}</div>
                        <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="icons/icons.svg#olymp-three-dots-icon"></use></svg></a>
                    </div>
                     <div class="ui-block-content">
                        <div class="skills-item" v-for="(goal, index) in goals">
                            <div class="skills-item-info">
                                <span class="skills-item-title">
                                    {{ goal.donation_type.name }} {{ $t('events.expenses_statistic.remain') }} {{ goal.calculate.donation_sum - goal.calculate.expense_sum }}/{{ goal.calculate.donation_sum }} {{ goal.donation_type.quality.name }}
                                </span>
                                <span class="skills-item-count">
                                    <span class="count-animate" data-speed="1000" data-refresh-interval="50" data-to="62" data-from="0"></span>
                                    <span class="units">
                                        {{ goal.calculate.donation_sum > 0 ? Math.round((goal.calculate.donation_sum - goal.calculate.expense_sum)/goal.calculate.donation_sum*100) : 0}}%
                                    </span>
                                </span>
                            </div>
                            <div class="skills-item-meter">
                                <span class="skills-item-meter-active skills-animate"
                                    :class="randomColor()"
                                    :style="{ width: (goal.calculate.donation_sum > 0 ? Math.round((goal.calculate.donation_sum - goal.calculate.expense_sum)/goal.calculate.donation_sum*100) : 0) + '%', opacity: 1 }">
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 col-xs-12">
                <div class="ui-block block-chart" data-mh="pie-chart">
                    <div class="ui-block-title">
                        <div class="w-select">
                            <h6 class="title">{{ $t('events.expenses_statistic.select_goal') }}</h6>
                            <fieldset class="form-group">
                                <select class="selectpicker form-control without-border" v-model="selectedGoal">
                                    <option v-for="goal in goals" :value="goal">{{ goal.donation_type.name }}</option>
                                </select>
                            </fieldset>
                        </div>
                        <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="icons/icons.svg#olymp-three-dots-icon"></use></svg></a>
                    </div>
                    <div class="ui-block-content">
                        <div class="chart-with-statistic">
                            <ul class="statistics-list-count">
                                <li>
                                    <div class="points">
                                        <span>
                                            <span class="statistics-point bg-purple"></span>
                                            {{ $t('events.expenses_statistic.spent') }}
                                        </span>
                                    </div>
                                       <div class="count-stat">{{ selectedGoal.calculate.expense_sum }}</div>
                                </li>
                                <li>
                                    <div class="points">
                                        <span>
                                            <span class="statistics-point bg-breez"></span>
                                            {{ $t('events.expenses_statistic.remain') }}
                                        </span>
                                    </div>
                                       <div class="count-stat">{{  selectedGoal.calculate.donation_sum - selectedGoal.calculate.expense_sum }}</div>
                                </li>
                            </ul>
                            <div class="chart-js chart-js-pie-color">
                                <pie-chart :chart-data="datacollection" :options="pieOptions"></pie-chart>
                                <div class="general-statistics">
                                       {{ selectedGoal.calculate.donation_sum }}
                                    <span>{{ $t('events.expenses_statistic.total') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-5 col-sm-12 col-xs-12">
                <div class="ui-block block-chart" data-mh="pie-chart">
                    <div class="ui-block-title">
                        <div class="w-select">
                        <h6 class="title">{{ $t('events.expenses_statistic.select_goal') }}</h6>
                            <fieldset class="form-group">
                                <select class="selectpicker form-control without-border" v-model="selectedList" @change="fetchExpense">
                                    <option value="">{{ $t('events.expenses_statistic.all') }}</option>
                                    <option v-for="goal in goals" :value="goal.id">{{ goal.donation_type.name }}</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="w-select">
                            <fieldset class="form-group">
                                <select class="selectpicker form-control without-border" v-model="orderBy" @change="fetchExpense">
                                    <option value="time">{{ $t('events.expenses_statistic.most_recent') }}</option>
                                    <option value="cost">{{ $t('events.expenses_statistic.most_spent') }}</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <table class="table ui-block-content">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ $t('events.expenses.donate_type') }}</th>
                                <th>{{ $t('events.expenses.cost') }}</th>
                                <th>{{ $t('events.expenses.time') }}</th>
                                <th class="text-right">{{ $t('events.expenses.reason') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(expense, index) in expenses">
                                <td class="text-center">{{index + 1 }}</td>
                                <td>{{ expense.goal.donation_type.name }}</td>
                                <td>{{ expense.cost }}</td>
                                <td>{{ formatTime(expense.time) }}</td>
                                <td class="text-right">
                                    <a href="#" class="remove-icon" @click.prevent="showReason(expense.reason)">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-7 col-sm-12 col-xs-12">
                <div class="ui-block responsive-flex" data-mh="pie-chart">
                    <div class="ui-block-title">
                        <div class="h6 title">{{ $t('events.expenses_statistic.select_goal') }}</div>
                        <select class="selectpicker form-control without-border" v-model="goalWithTime" @change="fetchExpenseByTime">
                            <option value="">{{ $t('events.expenses_statistic.all') }}</option>
                            <option :value="goal.id" v-for="goal in goals">{{ goal.donation_type.name }}</option>
                        </select>
                        <div class="points align-right">
                            <p>{{ $t('events.expenses_statistic.line_chart_msg') }}</p>
                        </div>
                        <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="icons/icons.svg#olymp-three-dots-icon"></use></svg></a>
                    </div>
                    <div class="ui-block-content">
                        <div class="chart-js chart-js-line-graphic">
                            <line-chart id="line-graphic-chart" :chart-data="lineGraphicChart" :options="lineOptions" :width="730" :height="300"></line-chart>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <message :show.sync="isShowReason">
            <h2 class="exclamation-header" slot="header">
                <span class="fa fa-check-circle"></span>&nbsp;{{ $t('events.expenses.reason') }}
            </h2>
            <div class="body-modal" slot="main">
                <div v-html="reason"></div>
            </div>
        </message>
    </div>
</template>

<script>
import PieChart from '../../libs/chart/PieChart.js'
import LineChart from '../../libs/chart/LineChart.js'
import { get } from '../../../helpers/api'
import Message from '../../libs/Modal.vue'
export default {
    components: {
        PieChart,
        LineChart,
        Message
    },
    data () {
        return {
            isShowReason: false,
            reason: '',
            pieOptions: {
                deferred: {
                    delay: 300
                },
                cutoutPercentage:93,
                legend: {
                    display: false
                },
                animation: {
                    animateScale: false
                }
            },
            lineOptions: {
                deferred: {
                    delay: 300
                },
                legend: {
                    display: true
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        gridLines: {
                            color: "#f0f4f9"
                        },
                        ticks: {
                            fontColor: '#888da8'
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            beginAtZero:true,
                            fontColor: '#888da8'
                        }
                    }]
                }
            },
            goals: [],
            colors: [
                'bg-primary',
                'bg-purple',
                'bg-blue',
                'bg-breez',
                'bg-yellow'
            ],
            selectedGoal: {
                calculate: {
                    donation_sum: 0,
                    expense_sum: 0
                }
            },
            selectedList: '',
            orderBy: 'time',
            expenses: [],


            goalWithTime: '',
            lineGraphicChart: {},
            pageType: 'event'
        }
    },
    computed: {
        datacollection() {
            return {
                labels: [this.$t('events.expenses_statistic.spent'), this.$t('events.expenses_statistic.remain')],
                datasets: [
                    {
                        data: [
                            this.selectedGoal.calculate.expense_sum,
                            this.selectedGoal.calculate.donation_sum - this.selectedGoal.calculate.expense_sum
                        ],
                        borderWidth: 0,
                        backgroundColor: [
                            "#7c5ac2",
                            "#08ddc1",
                            "#ff5e3a",
                            "#ffd71b"
                        ]
                    }]
            }
        },
    },
    methods: {
        showReason(reason) {
            this.reason = reason
            this.isShowReason = true
        },
        randomColor() {
            let key = Math.floor(Math.random() * this.colors.length)
            return this.colors[key]
        },
        formatTime(time) {
            return moment(time).format('YYYY-MM-DD')
        },
        fetchExpense() {
            get(`get-list-expense?event_id=${this.pageId}&goal_id=${this.selectedList}&order_by=${this.orderBy}`)
                .then(res => {
                    this.expenses = res.data.expenses
                })
        },
        fetchExpenseByTime() {
            get(`get-statistic-data?event_id=${this.pageId}&goal_id=${this.goalWithTime}`)
                .then(res => {
                    this.lineGraphicChart = Object.assign({})
                    this.$set(this.lineGraphicChart, 'labels', res.data.statistic.time)
                    this.$set(this.lineGraphicChart, 'datasets', [])
                    this.lineGraphicChart.datasets.push({
                        label: this.$t('events.expenses.cost'),
                        backgroundColor: "rgba(255,215,27,0.6)",
                        borderColor: "#ffd71b",
                        borderWidth: 4,
                        pointBorderColor: "#ffd71b",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 4,
                        pointRadius: 0,
                        pointHoverRadius: 8,
                        data: res.data.statistic.cost
                        })
                    this.lineGraphicChart.datasets.push({
                        label: this.$t('events.expenses_statistic.times'),
                        backgroundColor: "rgba(255,94,58,0.6)",
                        borderColor: "#ff5e3a",
                        borderWidth: 4,
                        pointBorderColor: "#ff5e3a",
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 4,
                        pointRadius: 0,
                        pointHoverRadius: 8,
                        data: res.data.statistic.count
                        })
                })
        }
    },
    created() {
        get(`event/${this.pageId}/statistic`)
            .then(res => {
                this.goals = res.data.goal
                this.selectedGoal = res.data.goal[0]
            })
        this.fetchExpense()
        this.fetchExpenseByTime()
    },
    updated() {
        $('.selectpicker').selectpicker('refresh')
    }
}
</script>

<style lang="scss" scoped>
    #statistic {
        margin-top: 15px;
    }
    .block-chart {
        max-height: 433px;
    }
</style>
