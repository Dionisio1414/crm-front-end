<template>
    <simplebar class="card list-items" force-visible>
        <div class="check-all">
            <input type="checkbox" id="all" @click="selectAll">
            <label for="all">{{ $t('page.chooseAll') }}</label>
        </div>
        <v-skeleton-loader
            class="companies-skeleton-loader"
            :loading="loader"
            type="sentences@15"
        >
            <draggable
                class="companies-list"
                tag="ul"
                v-model="computedList"
                v-bind="dragOptions"
                @update="checkMove"
                @start="drag = true"
                @end="drag = false"
            >
                <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                    <li
                        class="companies-list-item"
                        v-for="(element, index) in computedList"
                        :key="index"
                        :class="{'companies-list-item--active': selectedItem === index}"
                    >
                    <!-- @contextmenu.prevent="$refs.menu.open($event, { element, index })" -->
                        <div class="checkbox">
                            <label class="checkbox-label">
                                <input @change="selectItem(element)" :checked="allSelected" type="checkbox">
                                <div class="checkbox-text"></div>
                            </label>
                        </div>
                        <div :class="{'favorite': true, 'favorite-active': element.is_default}" @click="setFavorite(element.id)">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.61065 15.9392C3.22465 16.1504 2.78665 15.7803 2.86465 15.3078L3.69465 10.2626L0.171653 6.68298C-0.157347 6.34806 0.0136534 5.73581 0.454653 5.66968L5.35265 4.9273L7.53665 0.31199C7.73365 -0.103997 8.26665 -0.103997 8.46365 0.31199L10.6477 4.9273L15.5457 5.66968C15.9867 5.73581 16.1577 6.34806 15.8287 6.68298L12.3057 10.2626L13.1357 15.3078C13.2137 15.7803 12.7757 16.1504 12.3897 15.9392L7.99865 13.5329L3.60965 15.9392H3.61065Z"/>
                            </svg>
                        </div>
                        <div class="list-title" @click="addDetails($event, index, element)">
                            <span class="title">{{ element.title }}</span>
                            <span class="code" v-if="element.code">{{ element.code }}</span>
                        </div>
                        <div class="sort-buttons">
                            <v-btn  color="#9DCCFF" fab small
                                    class="action-btn circle-btn swap-btn"
                                    @click="move(index,index - 1)"
                                    :disabled="index == 0"
                            >
                                <svg width="6" height="12" viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="white"/>
                                </svg>
                            </v-btn>
                            <v-btn color="#9DCCFF" fab small
                                    class="action-btn circle-btn swap-btn"
                                    @click="move(index,index + 1)"
                                    :disabled="index==(computedList.length-1)"
                            >
                                <svg width="6" height="12" viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 12L6 9L5.3 8.3L3.5 10.1L3.5 2.18557e-07L2.5 3.0598e-07L2.5 10.1L0.699999 8.3L-2.62268e-07 9L3 12Z" fill="white"/>
                                </svg>
                            </v-btn>
                        </div>
                    </li>
                </transition-group>
            </draggable>

        </v-skeleton-loader>
    </simplebar>
</template>
<script>

Array.prototype.move = function(from, to) {
  this.splice(to, 0, this.splice(from, 1)[0])
  return this
}

import { mapActions, mapGetters } from 'vuex'

export default {
    name: "CompanyList",
    props: ['list', 'resource', 'selectedItems', 'loader'],
    data: () => ({
        allSelected: false,
        selected: [],
        drag: false,
        loading: true,
        selectedItem: null,
    }),
    computed: {
        ...mapGetters(['getCompaniesPaginateCounter']),
        computedList: {
            get() {
                return this.list
            },
            set(value) {
                console.log('value', value)
                return value
            }
        },
        dragOptions() {
            return {
                animation: 200,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            }
        },
    },
    methods: {
        ...mapActions(['fetchCompaniesWithPaginate', 'updatePaginateCounter', 'changeCompanyItem']),
        async checkMove(e) {
            const currentItem = this.computedList[e.oldIndex]
            const nextItem = this.computedList[e.newIndex]
            await this.changeCompanyItem({ data: { ...currentItem, order: currentItem.id + 1 }, type: this.resource, id: currentItem.id })
            await this.changeCompanyItem({ data: { ...nextItem, order: nextItem.id - 1 }, type: this.resource, id: nextItem.id })
        },
        selectItem(val) {
            const index = this.selected.findIndex(item => item.id === val.id)
            const dataObject = {
                data: []
            }
            if (index === -1) {
                dataObject.data = []
                this.selected.push({id: val.id, title: val.title})
                this.selected.forEach(item => {
                    dataObject.data.push({ id: item.id, title: item.title })
                })
                console.log('if', dataObject)
                this.$emit('checkboxLength', dataObject)
            } else {
                dataObject.data = []
                this.selected.splice(index, 1)
                this.selected.forEach(item => {
                    dataObject.data.push({ id: item.id, title: item.title })
                })
                console.log('else', dataObject)
                this.$emit('checkboxLength', dataObject)
            }
        },
        selectAll() {
            this.allSelected = !this.allSelected
            const dataObject = {
                data: []
            }
            if (this.allSelected) {
                this.selected = []
                dataObject.data = []
                this.computedList.forEach(({ id, title }) => {
                    this.selected.push({id, title})
                    dataObject.data.push({id, title})
                })
                console.log('dataObject', dataObject)
                this.$emit('checkboxLength', dataObject)
            } else {
                this.selected = []
                this.$emit('checkboxLength', dataObject)
            }
        },
        async move(from, to) {
            const currentItem = this.computedList[from]
            const nextItem = this.computedList[to]
            this.selectedItem = to
            await this.changeCompanyItem({ data: { ...currentItem, order: currentItem.id + 1 }, type: this.resource, id: currentItem.id })
            await this.changeCompanyItem({ data: { ...nextItem, order: nextItem.id - 1 }, type: this.resource, id: nextItem.id })
        },
        setFavorite(id) {
            const defaultItem = this.computedList.find((item) => item.id === id)
            if(!defaultItem.is_default) this.$emit('changeDefault', id, { ...defaultItem, is_default: true })
        },
        addDetails(e, index, element) {
            console.log('element', element)
            this.selectedItem = index
            this.$emit('initDetails', element, true, this.resource, 'change')
        },
        paginationList() {
            const listElem = document.querySelector('.companies-skeleton-loader')
            listElem.addEventListener('scroll', async () => {
                if(listElem.scrollTop + listElem.clientHeight >= listElem.scrollHeight) {
                    console.log('scrolledToEnd')
                    await this.updatePaginateCounter()
                    await this.fetchCompaniesWithPaginate({ type: this.resource, page: this.getCompaniesPaginateCounter })
                }
            })
            console.log(listElem)
        }
    },
    watch: {
        loader: function(newVal) {
            console.log('val', newVal)
            // if(newVal) this.loading = true
            // else this.loading = false
        }
    },
    created() {
        this.loading = true
    },
    async mounted() {
        this.selectedItem = this.selectedItems
        await this.paginationList()
        await this.updatePaginateCounter('clear')
        console.log('mounted')
    }
}
</script>
<style lang="sass" scoped>

</style>
