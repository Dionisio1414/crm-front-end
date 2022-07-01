<template>
<!-- :tabList="['filters.tablist.mainTabName', 'filters.tablist.optionsTabName', 'filters.tablist.characterTabName']" -->
    <modal-container 
        v-if="isOpen" 
        :dialog="isOpen" 
        :modalWidth="920" 
        :customDialogClass="['modal-container-filter']"
        @clickOutside="onCloseModal"
    >
        <template v-slot:header>
            <div class="dialog-header">
                <div class="header-text">
                    <SvgSprite 
                        :width="14"
                        :height="10"
                        iconId="filter"
                    />
                    <span>{{ $t('filters.filterTitle') }}</span>
                </div>
                <div class="header-tablist" v-if="isTabs">
                    <div class="tablist">
                        <div 
                            v-for="(val, index) in tabList" 
                            class="tablist-item" 
                            :class="{'tablist-item--active': currentTabIndex === index}" 
                            :key="index" @click="changeTab(index)"
                        >
                            {{ $t(val) }}  
                        </div>
                    </div>
                </div>
                <div class="dialog-header-actions">
                    <button 
                        type="button" 
                        class="base-button base-button--white" 
                        @click="resetFilter"
                    >
                        {{ $t('filters.resetFilters') }}
                    </button>
                    <button 
                        type="button" 
                        class="base-button base-button--accent" 
                        @click="applyFilter"
                    >
                        {{ $t('filters.applyFilter') }}
                    </button>
                    <button 
                        type="button" 
                        class="base-button close" 
                        @click="onCloseModal"
                    >
                        <SvgSprite
                            style="color: rgb(157,204,255)"
                            :width="12"
                            :height="12"
                            iconId="closeWhite"
                        />
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main>
            <div class="dialog-body">
                <div class="dialog-body-content">
                    <form class="form" @submit="e => e.preventDefault()">
                        <div class="form-content">
                            <template v-if="isTabs.length > 0">
                                <transition name="fade">
                                    <slot :name="tabSlotName"></slot>
                                </transition>
                            </template>
                            <template v-else>
                                <slot 
                                    name="content" 
                                    :data="computedFilterData"
                                    :resource="resource"
                                >
                                </slot>
                            </template>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </modal-container>
</template>
<script>
import ModalContainer from "../ModalContainer/ModalContainer"
import SvgSprite from "../SvgSprite/SvgSprite"
import { mapGetters, mapActions } from "vuex"

export default {
    name: "FilterWrapper",
    components: { 
        ModalContainer,
        SvgSprite
    },
    props: {
        isOpen: Boolean,
        tabList: Array,
        resource: String
    },
    data: () => ({
        currentTabIndex: 0,
    }),
    computed: {
        ...mapGetters([
            'getFilterData'
        ]),
        isTabs() {
            if(this.tabList) return this.tabList.length
            return []
        },
        tabSlotName() {
            return `tab-${this.currentTabIndex}`
        },  
        computedFilterData() {
            return this.getFilterData(this.resource)
        },
        computedState() {
            return this.$store.state.filters[`${this.resource}_filter`]
        }
    },
    methods: {
        ...mapActions([
            'changeDataFilter',
            'applyFilters',
            'resetFilters'
        ]),
        onCloseModal() { this.$emit('closeFilter') },
        changeTab(index) { this.currentTabIndex = index },
        applyFilter() { 
            this.applyFilters({ data: this.computedState, resource: this.resource })
            this.onCloseModal() 
        },
        resetFilter() { 
            this.resetFilters(this.resource)
            this.onCloseModal() 
        }
    },
}
</script>
<style lang="sass">
    @import 'sass/filter'

    /* Animation styles */

    .fade-enter-active, 
    .fade-leave-active 
        transition: opacity .5s
    .fade-enter, 
    .fade-leave-to
        opacity: 0

    /* End animation styles */
    
</style>