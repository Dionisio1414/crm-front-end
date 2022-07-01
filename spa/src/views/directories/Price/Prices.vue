<template>
  <div class="page with-tabs">
    <div class="page-header">
      <div class="page-header-caption">
        <h2 class="page-title">{{ $t('directories.typesOfPrices') }}</h2>
      </div>
      <div class="page-header-actions">
        <AsyncSimpleInput
            @updateInput="updateInput"
        >
        </AsyncSimpleInput>
        <div class="actions-buttons">
          <button type="button" class="orange-btn" @click="createNewItem">
            <span>
              <svg-sprite width="14" height="14" icon-id="plus"></svg-sprite>
            {{ $t('page.createBtn') }}
            </span>
          </button>
          <button class="base-button base-button--link">
            <router-link class="base-button base-button--lighten" to="/crm/price&discounts">
              {{ $t('page.toListPrices') }}
            </router-link>
          </button>
        </div>
      </div>
    </div>
    <div class="card-grid tabs-grid">
      <div class="card card-table full-width"> 
        <router-view 
          @changeItem="onChangeItem"
          @viewItem="onViewItem"
          @delete="deleteItems"
        >
        </router-view>
      </div>
    </div>
    <modal-types 
      :modeType="mode"
      :itemData="itemData"
      :isModalOpen="isModalOpen"
      @close="closeModal"
      @onCloseModal="closeModal"
      @save="onSaveData" 
      v-if="isModalOpen"
    >
    </modal-types>
    <!-- <Alert></Alert> -->
    <Snackbar 
      v-if="alert.alertFlag"
      :modeType="alert.modeType"
      :typeTitle="alert.title"
      @closeSnackbar="closeSnackbar"
    >
    </Snackbar>
  </div>
</template>
<script>
// import Alert from "@/components/ui/Alert"
import { mapActions } from "vuex"
// import TabPrices from "@/components/dashboard/directoties/prices/tabs/TabPrices"
// import TabTypes from "@/components/dashboard/directoties/prices/tabs/TabTypes"
import ModalTypes from "@/components/dashboard/directoties/prices/modals/ModalTypes"
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput"
import Snackbar from "@/components/dashboard/directoties/snackbar/Snackbar"

export default {
  name: "Prices",
  components: {
    ModalTypes,
    // Alert,
    AsyncSimpleInput,
    Snackbar
  },
  data() {
    return {
      tabs: [
        {title: "directories.typesOfPrices",name:'types', modal:'ModalTypes', resources:'prices', filtered: false, url: "/directories/prices/type-price"},
        {title: "directories.prices", resources:'', name: 'prices', filtered: true, url: "/directories/prices/price"},
      ],
      tabsComponents: ['TabTypes', 'TabPrices'],
      modalsComponents: ['', 'ModalTypes'],
      currentTab: {
        index: 0,
        name: '',
      },
      navIsSmall: false,
      search: '',
      mode: 'create',
      isModalOpen: false,
      itemData: {},
      alert: {
        alertFlag: false,
        title: null,
        modeType: null
      }
    }
  },
  computed: {
    currentComponent() {
      return this.tabsComponents[this.currentTab.index]
    },
    currentModal() {
      return this.tabs[this.currentTab.index].modal
    },
    computedTab() {
        return this.tabs[this.currentTab.index]
    }
  },
  watch: {
    $route(to) { if(to.params.params) this.createNewItem() }
  },
  methods: {
    ...mapActions({
      create: 'createClassifiersItem',
      change: 'changeClassifiersItem',
      changeDefault: 'changeDefaultItem',
      fetchLists: 'fetchLists',
    }),
    createNewItem() {
      this.mode = 'create'
      this.isModalOpen = true
    },
    changeTab(index) {
      this.currentTab.index = index
      this.search = ''
    },
    onChangeDefault(params) {
      this.changeDefault({id: params.id, resources: params.resources})
    },
    async onSaveData(params) {
      console.log(params)
      await this[params.action]({...params})
      this.isModalOpen = false
      this.alert.alertFlag = true
      this.alert.modeType = params.action
      this.alert.title = params.alertItem
    },
    closeSnackbar() {
      this.alert.alertFlag = false
      this.alert.modeType = null
      this.alert.title = null
    },
    showAlert(item) {
      const dataAlert = {startText: 'Создан новый элемент справочника', item, text: true}
      this.$store.dispatch('alertShow', dataAlert)
    },
    onChangeItem(itemData) {
      this.mode = 'change'
      this.isModalOpen = true
      this.itemData = itemData
    },
    onViewItem(itemData) {
      console.log(itemData);
      this.mode = 'view'
      this.isModalOpen = true
      this.itemData = itemData
    },
    onSearch() {
      if (this.search.length >= 2) {
        console.log(this.search)
      }
    },
    deleteItems(params) {
      console.log('params', params)
      this.showAlert(params)
    },
    onFilterHundler() {
      console.log(this.$refs[this.currentFilter])
    },
    updateInput(val) {
      console.log(val)
    },
    closeModal() {
      this.isModalOpen = false
    }
  },
  async mounted() {
    const { params } = this.$route.params
    await Promise.all([
      this.fetchLists({ type: 'core', resources: 'type_prices' }),
      this.fetchLists({ type: '', resources: 'currencies' }),
      this.fetchLists({ type: '', resources: 'prices_types' })
    ])

    params && this.createNewItem()

  }
}
</script>
<style lang="sass">
  .base-button
    &--link
      padding: 0
    > a
        display: block
        min-width: 150px
</style>