<template>
    <div class="page with-tabs page-company" :class="{'with-small-nav': navIsSmall}">
        <div class="page-header">
            <div class="page-header-caption">
                <h2 class="page-title">{{ $t('directories.company') }}</h2>
            </div>
            <div class="page-header-actions">
                <v-btn class="orange-btn" @click="createNewItem">
                    <span>
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                                fill="#F4F9FF"/>
                        </svg>
                        <span>{{ $t('page.createBtn') }}</span>
                    </span>
                </v-btn>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" color="#9DCCFF" fab small class="action-btn circle-btn" :disabled="selectedLength.data.length === 0" @click="deleteModal(selectedLength.data)">
                            <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.33203 5.35742H5.41536V11.786H4.33203V5.35742Z" fill="#F4F9FF"/>
                            <path d="M7.58398 5.35742H8.66732V11.786H7.58398V5.35742Z" fill="#F4F9FF"/>
                            <path d="M0 2.14258V3.214H1.08333V13.9282C1.08333 14.2124 1.19747 14.4849 1.40063 14.6858C1.6038 14.8868 1.87935 14.9996 2.16667 14.9996H10.8333C11.1207 14.9996 11.3962 14.8868 11.5994 14.6858C11.8025 14.4849 11.9167 14.2124 11.9167 13.9282V3.214H13V2.14258H0ZM2.16667 13.9282V3.214H10.8333V13.9282H2.16667Z" fill="#F4F9FF"/>
                            <path d="M4.33203 0H8.66536V1.07142H4.33203V0Z" fill="#F4F9FF"/>
                            </svg>
                        </v-btn>
                    </template>
                    <span>{{ $t('page.contextMenu.remove') }}</span>
                </v-tooltip>
            </div>
        </div>
            
        <div class="card-grid">
            <div class="card list-item">
                <span class="resize-btn" @click="navIsSmall = !navIsSmall">
                    <svg v-if="!navIsSmall" width="12" height="30" viewBox="0 0 12 30" fill="none" xmlns="http://www.w3.org/2000/svg">
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
            <CompanyList
                :selectedItems="selectedItem"
                :resource="currentTab.name"
                :list="list" 
                :key="componentKey"
                @changeDefault="changeDefault"
                @initDetails="handler"
                :loader="companyLoading"
                @checkboxLength="checkboxLength"
            />
            <div class="card detail-item" force-visible>
                <CreateOrganizations
                    v-if="currentComponent === 'CreateOrganizations'"
                    :key="organizationsKey" 
                    :mode="mode"
                    :data="componentData" 
                    :stateDetails="stateDetails"
                    @backList="backList"
                />
                <CreateDepartments
                    v-if="currentComponent === 'CreateDepartments'"
                    :key="departmentsKey"
                    :mode="mode"
                    :data="componentData" 
                    :stateDetails="stateDetails"
                    @backList="backList"
                />
                <modal-departments
                    v-if="modalToggles.ModalDepartments"
                    :isModalOpen="modalToggles.ModalDepartments"
                    ref="ModalDepartments"
                    @close="closeModal"
                    @onCloseModal="closeModal"
                    @save="onSaveData"
                >
                </modal-departments>
            </div>
            
        </div>
        <modal-delete
            @successDelete="successDelete"
            ref="modalDelete"
        >
        </modal-delete>
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
import { mapActions, mapGetters } from "vuex"
import CreateOrganizations from "@/components/dashboard/directoties/company/CreateOrganizations"
import CreateDepartments from "@/components/dashboard/directoties/company/CreateDepartments"
import CompanyList from "@/components/dashboard/directoties/company/CompanyList"
import ModalDepartments from "@/components/dashboard/directoties/company/modals/ModalDepartments"
// import DeleteModal from "@/components/dashboard/directoties/deleteModal"
import ModalDelete from "@/components/ui/ModalDelete"
// import Alert from "@/components/ui/Alert"
import Snackbar from "@/components/dashboard/directoties/snackbar/Snackbar"

export default {
    name: "Company",
    components: {
        CreateOrganizations,
        CreateDepartments,
        CompanyList,
        ModalDepartments,
        ModalDelete,
        // DeleteModal,
        // Alert,
        Snackbar
    },
    data: () => ({
        mode: '',
        navIsSmall: false,
        stateDetails: false,
        componentKey: 0,
        organizationsKey: 0,
        departmentsKey: 0,
        componentData: {},
        selectedItem: null,
        loader: false,
        selectedLength: {
            data: []
        },
        tabs: [
            {title: "directories.organizations", url: "/directories/company/organizations"},
            {title: "directories.departments", url: "/directories/company/departments"},
        ],
        tabsComponents: ['CreateOrganizations', 'CreateDepartments'],
        modalsComponents: ['', 'ModalDepartments'],
        currentTab: {
            index: 0, 
            name: '',
        },
        modalToggles: {
            ModalDepartments: false
        },
        alert: {
            alertFlag: false,
            title: null,
            modeType: null
        }
    }),
    computed: {
        ...mapGetters([
            'deleteConfirm',
            'getLists',
            'changeItem',
            'getEmployees',
            'companyLoading'
        ]),
        list() {
            return this.$store.getters.list(this.currentTab.name) 
        },
        currentComponent() {
            return this.tabsComponents[this.currentTab.index]
        },
        currentModal() {
            return this.modalsComponents[this.currentTab.index]
        },
    },
    watch: {
        $route(to) {
            if(to.params.params) this.createNewItem()
        }
    },
    methods: {
        ...mapActions({
            change: 'changeClassifiersItem',
            changeDefaultList: 'changeDefaultList',
            fetchCompanies: 'fetchCompanies',
            updateCompaniyList: 'updateCompanies',
            deleteCompaniesList: 'deleteCompanies',
            fetchItems: 'fetchClassifiersItems',
            fetchLists: 'fetchLists',
            changeCompanyItem: 'changeCompanyItem',
        }),
        closeSnackbar() {
            this.alert.alertFlag = false
            this.alert.modeType = null
            this.alert.title = null
        },
        forceRerender(property) { this[property] += 1 },
        closeModal() { this.modalToggles[this.currentModal] = false },
        createNewItem() {
            if(this.currentModal !== "") {
                this.mode = 'create'
                this.modalToggles[this.currentModal] = true
                // this.$refs[this.currentModal].open()
            } else {
                this.mode = 'create_organizations'
                this.stateDetails = true
                this.forceRerender('organizationsKey')
                this.forceRerender('componentKey')
                this.componentData = {}
            }
        },
        changeTab(index) {
            console.log('changeTab', index)
            this.currentTab.index = index
            if(this.currentComponent.replace('Create', '').toLowerCase() === "organizations") {
                this.currentTab.name = this.currentComponent.replace('Create', '').toLowerCase()  
                this.forceRerender('componentKey')   
                this.fetchCompanies(this.currentTab.name)
                this.stateDetails = false
            } 
            
            if(this.currentComponent.replace('Create', '').toLowerCase() === "departments") {
                this.currentTab.name = `companies_${this.currentComponent.replace('Create', '').toLowerCase()}` 
                this.forceRerender('componentKey')   
                this.fetchCompanies(this.currentTab.name)
                this.stateDetails = false
            }  

        },
        async onSaveData(params) {
            console.log('params', params)
            await this.updateCompaniyList({ data: params.data, type: params.resources })
            this.modalToggles.ModalDepartments = false
            this.alert.alertFlag = true
            this.alert.modeType = params.action
            this.alert.title = params.alertItem
        },
        async changeDefault(id, data) {
            this.stateDetails = false
            await this.changeCompanyItem({ data, type: this.currentTab.name, id })
            this.componentData = {}
        },
        handler(element, stateDetails, resource, mode) {
            this.stateDetails = stateDetails
            console.log('element', element)
            this.componentData = element
            this.mode = mode
            if(resource === "organizations") {
                this.forceRerender('organizationsKey')
            } 
            if(resource === "companies_departments") {
                this.forceRerender('departmentsKey')
            }
            console.log('handler', element, stateDetails, resource)
        },
        backList(params) {
            this.stateDetails = false
            this.mode = ''
            this.selectedItem = null
            this.componentData = {}
            this.forceRerender('componentKey')
            if(params && params.modeType) {
                this.alert.alertFlag = true
                this.alert.modeType = params.modeType
                this.alert.title = params.title
            } else {
                return
            }
        },
        checkboxLength(value) {
            console.log(value)
            this.selectedLength = value
        },
        deleteModal(itemsToDelete) {
            console.log('itemsToDelete', itemsToDelete)
            const directory = this.currentTab.name === 'organizations' ? 'directories.organizationsText' : 'directories.departmentsText'
            const items = itemsToDelete.length > 1 ? itemsToDelete.length : itemsToDelete[0].title 
            this.$refs.modalDelete.data = itemsToDelete
            this.$refs.modalDelete.open = true
            
            this.$refs.modalDelete.options = {
                directory,
                items
            }
        },
        async successDelete(data) {
            console.log('successDelete')
            this.selectedLength.data = []
            console.log('successDeletes', this.selectedLength)
            const currentTabName = this.currentTab.name
            this.selectedItem = null
            this.stateDetails = false
            await this.deleteCompaniesList({ type: currentTabName === 'organizations' ? 'organizations' : 'companies_departments',  data})
        },
    },
    created() {
        let componentName = this.$route.path.split('/')[3] !== undefined ? this.$route.path.split('/')[3] : this.$route.path.split('/')[2]
        if(componentName === "organizations") {
            this.currentTab.name = this.currentComponent.replace('Create', '').toLowerCase() 
            this.currentTab.index = 0
            return   
        } else if(componentName === "departments") {
            this.currentTab.name = `companies_${componentName}`
            this.currentTab.index = 1
            return   
        } else if(componentName === "company") {
            this.currentTab.name = "organizations" 
            this.$router.push('/directories/company/organizations')
            return
        } else {
            return
        }
    },
    async mounted() {
        const { params } = this.$route.params

        if(this.currentTab.name !== '') await this.fetchCompanies(this.currentTab.name)

        await Promise.all([
            this.fetchLists({ type: '', resources: 'employees' }),
            this.fetchLists({ type: 'core', resources: 'counterparty_type' }),
        ])

        params && this.createNewItem()

    }
}
</script>
<style lang="sass" scoped>
</style>