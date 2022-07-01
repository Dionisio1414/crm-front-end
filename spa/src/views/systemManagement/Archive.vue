<template>
    <div class="page with-tabs">
        <div class="page-header">
            <div class="page-header-caption">
                <h2 class="page-title">{{ $t('programSettings.archive') }}</h2>
            </div>
            <div class="page-header-actions">
                <div class="search-block">
                    <div class="filtered-block" @click="onFilterHundler">
                        <span class="filtered-icon">
                            <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="14" height="2" fill="#F4F9FF"/>
                                <rect x="2" y="4" width="10" height="2" fill="#F4F9FF"/>
                                <rect x="4" y="8" width="6" height="2" fill="#F4F9FF"/>
                            </svg>
                            <span class="filtered-cirlce-icon">
                            </span>
                        </span>
                        <button type="button" class="filtered-btn">
                            Сбросить все фильтры
                        </button>
                    </div>
                    <input type="text" :placeholder="$t('page.search')">
                    <div class="search-icon">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.685 14.3335C11.377 14.3335 14.37 11.3487 14.37 7.66674C14.37 3.9848 11.377 1 7.685 1C3.99298 1 1 3.9848 1 7.66674C1 11.3487 3.99298 14.3335 7.685 14.3335Z"
                            stroke="#5893D4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.0002 16L12.3652 12.375" stroke="#5893D4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="actions-buttons">
                    <v-btn class="orange-btn">
                        <span>
                            {{ $t('programSettings.restore') }}
                        </span>
                    </v-btn>
                    <v-btn class="base-btn">
                        <span>
                            {{ $t('programSettings.clear') }}
                        </span>
                    </v-btn>
                    <v-btn class="base-btn">
                        <span>
                            {{ $t('programSettings.сhooseAll') }}
                        </span>
                    </v-btn>
                </div>
            </div>
        </div>
        
    <div class="card-grid tabs-grid">
      <div class="card list-item">
        <ul class="tabs-list">
          <li class="tabs-item " :class="{ 'active': index === currentTab.index }" v-for="(tab, index) in tabs"
              @click="changeTab(index)" :key="index">
            {{ $t(tab.title) }}
            <svg width="18" height="23" viewBox="0 0 18 23" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M9 0L0 6.70833L1.63 7.92542L9 13.4167L16.36 7.92542L18 6.70833L9 0ZM16.37 10.2829L9 15.7837L1.62 10.2925L0 11.5L9 18.2083L18 11.5L16.37 10.2829ZM16.37 15.0746L9 20.5754L1.62 15.0842L0 16.2917L9 23L18 16.2917L16.37 15.0746Z"
                  fill="#9DCCFF"/>
            </svg>
          </li>
        </ul>
      </div>
      <div class="card card-table">
        <keep-alive>
          <component
              :is="currentComponent"
              @changeItem="onChangeItem"
              @viewItem="onViewItem"
          ></component>
        </keep-alive>
        <!-- <filter-employees ref="FilterEmployees">
        </filter-employees>
        <filter-documents ref="FilterDocuments">
        </filter-documents> -->
      </div>
    </div>
    <Alert></Alert>
    </div>
</template>
<script>
// import FilterDocuments from "@/components/dashboard/directoties/employees/filters/FilterDocuments"
// import FilterEmployees from "@/components/dashboard/directoties/employees/filters/FilterEmployees"
import Alert from "@/components/ui/Alert"

export default {
    name: "Archive",
    components: {
        // FilterDocuments,
        // FilterEmployees,
        Alert
    },
    data: () => ({
        itemData: {},
        mode: '',
        navIsSmall: false,
        tabs: [],
        tabsComponents: [],
        modalsComponents: [],
        filtersComponents: [],
        currentTab: {
            index: 0,
            name: '',
        },
    }),
    computed: {
        currentComponent() {
            return this.tabsComponents[this.currentTab.index]
        },
        computedTab() {
            return this.tabs[this.currentTab.index]
        }
    },
    methods: {
        onFilterHundler() {
            // this.mode = 'create'
            console.log(this.$refs[this.currentFilter])
            // this.$refs[this.currentFilter].open()
        },
        changeTab(index) {
            this.currentTab.index = index
            console.log(this.isFiltered)
        },
        onSaveData(params) {
            this[params.action]({...params})
            this.$refs[this.currentModal].cancel()
            console.log('params', params);
            if (params.action === 'create') this.showAlert(params.alertItem)
            if (params.data.is_default) this.changeDefault({id: params.data.id, resources: params.resources } )
        },
        showAlert (item) {
            const dataAlert = {startText: 'Создан новый элемент справочника', item, text: true}
            this.$store.dispatch('alertShow', dataAlert)
        },
        onChangeItem(itemData) {
            this.mode = 'change'
            this.$refs[this.currentModal].open(this.mode, itemData)
        },
        onViewItem (itemData) {
            this.mode = 'view',
            this.$refs[this.currentModal].open(this.mode, itemData)
        },
    },
    created() {

    },
    mounted() {

    }
}
</script>
<style lang="sass" scoped>

</style>