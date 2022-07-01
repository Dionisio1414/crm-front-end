<template>
    <div class="page with-tabs" :class="{'with-small-nav': navIsSmall}">
        <div class="page-header">
            <div class="page-header-caption">
                <h2 class="page-title">{{ $t("directories.contractors") }}</h2>
            </div>
            <div class="page-header-actions">
                <AsyncSimpleInput
                    :isFiltered="true"
                    resource="counterparties_contracts"
                    @onFilter="onFilterHundler"
                >
                </AsyncSimpleInput>

                <div class="actions-buttons">
                    <v-btn class="orange-btn" @click="createNewItem" v-if="computedTab.name !== 'contractors'">
                        <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                            fill="#F4F9FF"/>
                        </svg>
                        <span>{{ $t('page.createBtn') }}</span>
                        </span>
                    </v-btn>
                    <!-- <div class="settings-select" v-if="computedTab.name === 'individualDocuments' || computedTab.name === 'contractsСontractors'">
                        <v-select class="select-switcher"
                            item-text="name"
                            item-value="name"
                            solo
                            dense
                            hide-details
                            menu-props="documentTypes"
                        />
                    </div> -->
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
            @viewItem="onViewItem"
            @clickRow="clickRow"
            @copy="copy"
        >
        </router-view>
        <modal-canceled-purchace
            v-if="modalToggles.ModalCanceledPurchace"
            :isModalOpen="modalToggles.ModalCanceledPurchace"
            :itemData="itemData"
            :modeType="mode"
            ref="ModalCanceledPurchace"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveData"
        >
        </modal-canceled-purchace>
        <modal-returing-goods
            v-if="modalToggles.ModalReturingGoods"
            :isModalOpen="modalToggles.ModalReturingGoods"
            :modeType="mode"
            :itemData="itemData"
            ref="ModalReturingGoods"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveData"
        >
        </modal-returing-goods>
        <modal-documents-contractors
            v-if="modalToggles.ModalDocumentsContractors"
            :modeType="mode"
            :isModalOpen="modalToggles.ModalDocumentsContractors"
            :itemData="itemData"
            ref="ModalDocumentsContractors"
            @save="onSaveData"
            @close="closeModal"
            @onCloseModal="closeModal"
        >
        </modal-documents-contractors>
        <modal-contracts-contractors
            v-if="modalToggles.ModalContractsContractors"
            :isModalOpen="modalToggles.ModalContractsContractors"
            :itemData="itemData"
            :modeType="mode"
            @close="closeModal"
            @onCloseModal="closeModal"
            ref="ModalContractsContractors"
            @save="onSaveData"
        >
        </modal-contracts-contractors>
      </div>
    </div>
    <Snackbar 
        v-if="alert.alertFlag"
        :modeType="alert.modeType"
        :typeTitle="alert.title"
        @closeSnackbar="closeSnackbar"
    >
    </Snackbar>
    <FilterWrapper 
        @closeFilter="closeFilter"
        :isOpen="isOpen"
        @copy="copy"
        resource="counterparties_contracts"
    >
        <template #content="{ data, resource }">
            <v-row>
                <v-col cols="4">
                    <FilterSelect
                        label="filters.labels.typeDocument"
                        :list="{ 
                            lists: data.selects.type_contracts,
                            resource: resource,
                            type: 'type_contracts'
                        }"
                    >
                    </FilterSelect>
                </v-col>
                <v-col cols="4">
                    <FilterPeriods
                        label="filters.labels.period"
                        :list="{
                           lists: data.periods.period_date,
                           resource: resource,
                           type: 'period_date' 
                        }"
                    >
                    </FilterPeriods>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    <FilterList
                        label="filters.labels.organization"
                        :list="{
                            lists: data.lists.organizations,
                            resource: resource,
                            type: 'organizations'
                        }"
                    >
                    </FilterList>
                </v-col>
                <v-col cols="4">
                    <FilterList
                        label="directories.typesOfPrice"
                        :list="{ 
                            lists: data.lists.price_types,
                            resource: resource,
                            type: 'price_types'
                        }"
                    >
                    </FilterList>
                </v-col>
                <v-col cols="4">
                    <FilterList
                        label="page.wareHouses.stockModal.currency"
                        :list="{ 
                            lists: data.lists.currencies,
                            resource: resource,
                            type: 'currencies' 
                        }"
                    >
                    </FilterList>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4"> 
                    <FilterBoolean
                        label="filters.labels.subscribe"
                        :list="{
                            type: { is_contract_signed: data.booleans.is_contract_signed },
                            lists: ['filters.labels.yes', 'filters.labels.no'],
                            resource: resource
                        }"
                    >
                    </FilterBoolean>
                </v-col>
                <v-col cols="4">
                    <FilterBoolean
                        label="filters.labels.status"
                        :list="{
                            type: { is_status_contract: data.booleans.is_status_contract },
                            lists: ['filters.labels.close', 'filters.labels.open'],
                            resource: resource
                        }"
                    >
                    </FilterBoolean>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    <FilterRange
                        label="filters.labels.due_date"
                        :list="{
                            data: data.ranges.due_date,
                            minValue: 1,
                            maxValue: 31,
                            resource: resource,
                            type: 'due_date'
                        }"
                    >
                    </FilterRange>
                </v-col>
                <v-col cols="4">
                    <FilterRange
                        label="filters.labels.discounts_markups"
                        :list="{
                            data: data.ranges.percent,
                            minValue: -100,
                            maxValue: 100,
                            resource: resource,
                            type: 'percent'
                        }"
                    >
                    </FilterRange>
                </v-col>
            </v-row>
        </template>
    </FilterWrapper>
    </div>
</template>
<script>
import { mapActions } from "vuex"
import ModalCanceledPurchace from "@/components/dashboard/directoties/contractors/modals/ModalCanceledPurchace"
import ModalReturingGoods from "@/components/dashboard/directoties/contractors/modals/ModalReturingGoods"
import ModalDocumentsContractors from "@/components/dashboard/directoties/contractors/modals/ModalDocumentsContractors"
import ModalContractsContractors from "@/components/dashboard/directoties/contractors/modals/ModalContractsContractors"
import FilterWrapper from "@/components/ui/FilterWrapper/FilterWrapper"
import FilterSelect from "@/components/ui/FilterWrapper/ui/selects/FilterSelect"
import FilterList from "@/components/ui/FilterWrapper/ui/lists/FilterList"
import FilterBoolean from "@/components/ui/FilterWrapper/ui/booleans/FilterBoolean"
import FilterRange from "@/components/ui/FilterWrapper/ui/ranges/FilterRange"
import FilterPeriods from "@/components/ui/FilterWrapper/ui/periods/FilterPeriods"
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput"
import Snackbar from "@/components/dashboard/directoties/snackbar/Snackbar"

export default {
    name: "Contractors",
    components: {
        ModalCanceledPurchace,
        ModalReturingGoods,
        ModalDocumentsContractors,
        ModalContractsContractors,
        FilterWrapper,
        FilterSelect,
        AsyncSimpleInput,
        FilterList,
        FilterBoolean,
        FilterRange,
        FilterPeriods,
        Snackbar
    },
    data: () => ({
        itemData: {},
        mode: '',
        navIsSmall: false,
        activeModal: null,
        isOpen: false,
        tabs: [
            {title: "directories.contractors", filtered: true, name: "contractors", url: "/directories/contractors/contractor"},
            {title: "directories.contractsСontractors", filtered: true, name: "contractsСontractors", url: "/directories/contractors/contracts-contractors"},
            {title: "directories.individualDocuments", filtered: true, name: "individualDocuments", url: "/directories/contractors/individual-documents"},
            {title: "directories.reasonReturningGoods", filtered: false, name: "reasonReturningGoods", url: "/directories/contractors/reason-returning-goods"},
            {title: "directories.reasonCanceledPurchase", filtered: false, name: "reasonCanceledPurchase", url: "/directories/contractors/reason-canceled-purchase"},
        ],
        tabsComponents: ['Contractor', 'ContractsСontractors', 'IndividualDocuments', 'ReasonReturningGoods', 'ReasonCanceledPurchase'],
        modalsComponents: ['', 'ModalContractsContractors', 'ModalDocumentsContractors', 'ModalReturingGoods', 'ModalCanceledPurchace'],
        currentTab: {
            index: 0,
            name: '',
        },
        modalToggles: {
            ModalContractsContractors: false,
            ModalDocumentsContractors: false,
            ModalReturingGoods: false,
            ModalCanceledPurchace: false
        },
        alert: {
            alertFlag: false,
            title: null,
            modeType: null
        }
    }),
    computed: {
        currentComponent() {
            return this.tabsComponents[this.currentTab.index]
        },
        currentModal() {
            return this.modalsComponents[this.currentTab.index]
        },
        computedTab() {
            return this.tabs[this.currentTab.index]
        }
    },
    watch: {
        $route(to) {
            console.log('watch', to.params.params)
            if(to.params.params) {
                switch(to.params.params) {
                    case "contractor":
                        this.currentTab.index = 0
                        break
                    case "contracts-contractors":
                        this.currentTab.index = 1
                        break
                    case "individual-documents":
                        this.currentTab.index = 2
                        break
                    case "reason-returning-goods":
                        this.currentTab.index = 3
                        break
                    case "reason-canceled-purchase":
                        this.currentTab.index = 4
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
            changeDefault: 'changeDefaultItem',
            fetchCompanies: 'fetchCompanies',
            fetchLists: 'fetchLists',
            getFilters: 'getFilters'
        }),
        closeSnackbar() {
            this.alert.alertFlag = false
            this.alert.modeType = null
            this.alert.title = null
        },
        closeModal() {
            this.modalToggles[`${this.currentModal}`] = false
        },
        closeFilter() {
            console.log('close filter')
            this.isOpen = false
        },
        copy(val) {
            console.log(val)
            this.mode = 'copy'
            this.itemData = val
            this.modalToggles[`${this.currentModal}`] = true
        },
        clickRow(val) {
            console.log('val', val)
            // this.$refs[modal].open('edit', val)
        },
        onFilterHundler() {
            this.isOpen = true
            this.getFilters('counterparties_contracts')
        },
        createNewItem() {
            this.mode = 'create'
            // this.$refs[this.currentModal].open(this.mode)
            this.modalToggles[`${this.currentModal}`] = true
        },
        changeTab(index) {
            this.currentTab.index = index
        },
        async onSaveData(params) {
            console.log('params', params)
            await this[params.action]({...params})
            this.modalToggles[`${this.currentModal}`] = false
            this.alert.alertFlag = true
            this.alert.modeType = params.action
            this.alert.title = params.alertItem
        },
        onChangeItem(itemData) {
            this.mode = 'change'
            this.itemData = itemData
            this.modalToggles[`${this.currentModal}`] = true
        },
        onViewItem(itemData) {
            this.mode = 'view'
            this.itemData = itemData
            this.modalToggles[`${this.currentModal}`] = true
        },
    },
    created() {
        let componentName = this.$route.path.split('/')[3] !== undefined ? this.$route.path.split('/')[3] : this.$route.path.split('/')[2]
        switch(componentName) {
            case "contractor":
                this.currentTab.index = 0
                break
            case "contracts-contractors":
                this.currentTab.index = 1
                break
            case "individual-documents":
                this.currentTab.index = 2
                break
            case "reason-returning-goods":
                this.currentTab.index = 3
                break
            case "reason-canceled-purchase":
                this.currentTab.index = 4
                break
            default:
                this.currentTab.index = 0
        }
    },
    async mounted() {
        const { params } = this.$route.params
        await Promise.all([
            this.fetchCompanies('companies_departments'),
            this.fetchCompanies('organizations'),
            this.fetchLists({ type: 'core', resources: 'types_contract' }),
            this.fetchLists({ type: '', resources: 'currencies' }),
            this.fetchLists({ type: 'core', resources: 'status_contracts' }),
            // this.fetchLists({ type: 'core', resources: 'type_documents' }),
            // this.fetchLists({ type: '', resources: 'prices_types' }),
            // this.fetchLists({ type: '', resources: 'counterparties' }),
        ])
        params && this.createNewItem()
    }
}
</script>
<style scoped>
</style>