<template>
  <div class="page with-tabs" :class="{'with-small-nav': navIsSmall}">
    <div class="page-header">
      <div class="page-header-caption">
        <h2 class="page-title">{{ $t('directories.classifiers') }}</h2>
      </div>
      <div class="page-header-actions">

        <div class="search-block">
          <input type="text" :placeholder="$t('page.search')" @keyup="onSearch" v-model="search">
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
          <v-btn class="orange-btn" @click="createNewItem">
            <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                  fill="#F4F9FF"/>
            </svg>
            {{ $t('page.createBtn') }}
            </span>
          </v-btn>
        </div>

      </div>

    </div>
    <div class="card-grid tabs-grid">
      <div class="card list-item">
        <span class="resize-btn" @click="navIsSmall = !navIsSmall">
         <svg v-if="!navIsSmall" width="12" height="30" viewBox="0 0 12 30" fill="none"
              xmlns="http://www.w3.org/2000/svg">
        <path d="M0 5C0 2.23858 2.23858 0 5 0H12V30H5C2.23858 30 0 27.7614 0 25V5Z" fill="#E3F0FF"/>
        <rect x="4" y="11" width="1" height="8" fill="#9DCCFF"/>
        <rect x="7" y="11" width="1" height="8" fill="#9DCCFF"/>
        </svg>
          <svg v-else width="42" height="44" viewBox="0 0 42 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g filter="url(#filter0_d)">
            <path d="M35 32C35 34.7614 32.7614 37 30 37L7 37L7 7L30 7C32.7614 7 35 9.23858 35 12L35 32Z" fill="white"/>
            </g>
            <path d="M21 26L20 26L20 18L21 18L27 22L21 26Z" fill="#9DCCFF"/>
            <rect x="18" y="26" width="1" height="8" transform="rotate(-180 18 26)" fill="#9DCCFF"/>
            <defs>
            <filter id="filter0_d" x="0" y="0" width="42" height="44" filterUnits="userSpaceOnUse"
                    color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix"/>
            <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
            <feOffset/>
            <feGaussianBlur stdDeviation="3.5"/>
            <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0"/>
            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
            </filter>
            </defs>
          </svg>

        </span>
        <ul class="tabs-list">
            <li 
                class="tabs-item" 
                v-for="(item, index) in tabs" 
                :key="index" 
                @click="changeTab(index)"
            >
                <router-link 
                    tag="span"
                    :to="item.url"
                >
                    {{ $t(item.title) }}
                    <svg width="18" height="23" viewBox="0 0 18 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9 0L0 6.70833L1.63 7.92542L9 13.4167L16.36 7.92542L18 6.70833L9 0ZM16.37 10.2829L9 15.7837L1.62 10.2925L0 11.5L9 18.2083L18 11.5L16.37 10.2829ZM16.37 15.0746L9 20.5754L1.62 15.0842L0 16.2917L9 23L18 16.2917L16.37 15.0746Z"
                            fill="#9DCCFF"/>
                    </svg>
                </router-link>
            </li>
        </ul>
      </div>
      <div class="card card-table">
        <router-view 
          @changeItem="onChangeItem"
          @viewItem="onViewItem">
        </router-view>
        <create-currencies
            v-if="currentModal === 'CreateCurrencies'"
            ref="CreateCurrencies"
            @save="onSaveData">
        </create-currencies>
        <create-measurement-point
            v-if="currentModal === 'CreateMeasurementPoint'"
            ref="CreateMeasurementPoint"
            @save="onSaveData"
            @changeDefault = onChangeDefault>
        </create-measurement-point>
      </div>
    </div>
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
import { mapActions } from "vuex"
import CreateCurrencies from "@/components/dashboard/directoties/classifiers/CreateCurrencies"
import CreateMeasurementPoint from "@/components/dashboard/directoties/classifiers/CreateMeasurementPoint"
// import Alert from "@/components/ui/Alert"
import Snackbar from "@/components/dashboard/directoties/snackbar/Snackbar"

export default {
  name: "Classifiers",
  components: {
    // Alert,
    CreateMeasurementPoint,
    // DialogSave,
    // Countries,
    // Currencies,
    // MeasurementPoints,
    CreateCurrencies,
    Snackbar
  },
  data() {
    return {
      tabs: [
        {title: "directories.currencies", url: "/directories/classifiers/currencies"},
        {title: "directories.units", url: "/directories/classifiers/measurement-points"},
      ],
      tabsComponents: ['Currencies', 'MeasurementPoints', 'Countries'],
      modalsComponents: ['CreateCurrencies', 'CreateMeasurementPoint', 'CreateCountries'],
      currentTab: {
        index: 0,
        name: '',
      },
      navIsSmall: false,
      search: '',
      mode: 'create',
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
      return this.modalsComponents[this.currentTab.index]
    },
  },
  watch: {
    $route(to) {
        if(to.params.params) {
         switch(to.params.params) {
            case "currencies":
                this.currentTab.index = 0
                break
            case "measurement-points":
                this.currentTab.index = 1
                break
            default:
                this.currentTab.index = 0
          }
          this.createNewItem()
        }
    }
  },
  methods: {
    ...mapActions({
      create: 'createClassifiersItem',
      change: 'changeClassifiersItem',
      changeDefault: 'changeDefaultItem'
    }),
    closeSnackbar() {
      this.alert.alertFlag = false
      this.alert.modeType = null
      this.alert.title = null
    },
    createNewItem() {
      this.mode = 'create'
      this.$refs[this.currentModal].open(this.mode)
    },
    changeTab(index) {
      this.currentTab.index = index
      this.search = ''
    },
    onChangeDefault (params) {
      this.changeDefault({id: params.id, resources: params.resources } )
    },
    async onSaveData(params) {
      this[params.action]({...params})
      this.$refs[this.currentModal].close()
      this.alert.alertFlag = true
      this.alert.modeType = params.action
      this.alert.title = params.alertItem
      // if (params.action === 'create') {
      //   this.showAlert(params.alertItem)
      // }

      if (params.data.is_default) {
        this.changeDefault({id: params.data.id, resources: params.resources } )
      }
    },
    showAlert (item) {
      const dataAlert = {startText: 'Создан новый элемент справочника', item, text: true}
      this.$store.dispatch('alertShow', dataAlert)
    },
    onChangeItem (itemData) {
      console.log(itemData)
      this.mode = 'change'
      this.$refs[this.currentModal].open(this.mode, itemData)
    },
    onViewItem (itemData) {
      this.mode = 'view',
      this.$refs[this.currentModal].open(this.mode, itemData)
    },
    onSearch () {
      if (this.search.length >= 2) {
        console.log(this.search)
      }
    }
  },
  created() {
    let componentName = this.$route.path.split('/')[3] !== undefined ? this.$route.path.split('/')[3] : this.$route.path.split('/')[2]
    switch(componentName) {
      case "currencies":
          this.currentTab.index = 0
          break
      case "measurement-points":
          this.currentTab.index = 1
          break
      default:
          this.currentTab.index = 0
    }
  },
  mounted() {
    const { params } = this.$route.params
    params && this.createNewItem()
  }
}

</script>

<style scoped>

</style>