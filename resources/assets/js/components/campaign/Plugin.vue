<template>
    <div class="btn-group bootstrap-select show-tick form-control style-2">
        <div class="dropdown-menu open" role="combobox">
            <div class="searchbox">
                <input type="text" v-model="search" class="form-control" role="textbox" :placeholder="$t('messages.search')">
            </div>
            <ul class="dropdown-menu inner" role="listbox" aria-expanded="false">
                <li class="member" v-for="member in filteredListMember">
                    <router-link :to="{ name: 'user.timeline', params: { id: member.id }}">
                        <span class="text">
                            <div class="inline-items">
                                <div class="author-thumb">
                                    <img :src="member.image_thumbnail" :alt="member.name">
                                </div>
                                <div class="h6 author-title">{{ member.name }}</div>
                            </div>
                        </span>
                        <span class="glyphicon glyphicon-ok check-mark"></span>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    data: () => ({
        search : ''
    }),
    computed: {
        ...mapState('campaign', ['campaign']),
        filteredListMember() {
            return this.campaign.members.filter(member => {
                return member.name.toLowerCase().match(this.search.toLowerCase())
            })
        }
    },
    mounted() {
        $(this.$el).selectpicker()
    }
}
</script>

<style lang="scss">
.bootstrap-select {
    &.btn-group {
        .dropdown-menu {
            &.inner {
                max-height: 490px !important;
                .member {
                    height: 34px;
                    margin: 12px 0;
                    .author-thumb {
                        img {
                            height: 34px;
                        }
                    }
                }
            }
        }
    }
}
</style>
