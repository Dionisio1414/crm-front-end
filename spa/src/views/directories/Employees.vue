<template>
    <div class="page with-tabs" :class="{'with-small-nav': navIsSmall}">
        <div class="page-header">
            <div class="page-header-caption">
                <h2 class="page-title">{{ $t('directories.employeesAndUsers') }}</h2>
            </div>
            <div class="page-header-actions">
                <div class="filtered-select" v-if="computedTab.name === 'employees'">
                    <v-select class="select-switcher"
                        v-model="defaultId"
                        :items="settingsSelectEmployees"
                        item-text="title"
                        item-value="id"
                        solo
                        dense
                        hide-details
                        @change="filterEmployees"
                        :menu-props="{ contentClass: 'filtered-select-list'}"
                        return-object
                    />
                </div>
                <div class="filtered-select" v-if="computedTab.name === 'users'">
                    <v-select class="select-switcher"
                        v-model="defaultId"
                        :items="settingsSelectUsers"
                        item-text="title"
                        item-value="id"
                        solo
                        dense
                        hide-details
                        @change="filterEmployees"
                        :menu-props="{ contentClass: 'filtered-select-list'}"
                        return-object
                    />
                </div>
                <AsyncSimpleInput
                    @updateInput="updateInput"
                >
                </AsyncSimpleInput>
                <div class="actions-buttons">
                    <v-btn class="orange-btn" @click="createModal" v-if="computedTab.name === 'positions'">
                        <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                            fill="#F4F9FF"/>
                        </svg>
                        <span>{{ $t('page.createBtn') }}</span>
                        </span>
                    </v-btn>
                    <v-btn class="orange-btn" @click="createModal" v-if="computedTab.name === 'documents'">
                        <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                            fill="#F4F9FF"/>
                        </svg>
                        <span>{{ $t('page.createBtn') }}</span>
                        </span>
                    </v-btn>
                    <v-btn class="orange-btn" @click="createModal" v-if="computedTab.name === 'users'">
                        <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                            fill="#F4F9FF"/>
                        </svg>
                        <span>{{ $t('page.createUser') }}</span>
                        </span>
                    </v-btn>
                    <v-btn class="orange-btn" @click="createModal" v-if="computedTab.name === 'employees'">
                        <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                            fill="#F4F9FF"/>
                        </svg>
                        <span>{{ $t('page.createEmployee') }}</span>
                        </span>
                    </v-btn>
                    <!-- <v-btn class="base-btn" @click="openDepartments" v-if="computedTab.name === 'employees' || computedTab.name === 'users'">
                        <span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.15408 5.80986L14 5.80986L14 8.11469L8.15408 8.11469L8.15408 14L5.84592 14L5.84592 8.11469L-1.39284e-06 8.11469L-1.19134e-06 5.80986L5.84592 5.80986L5.84592 2.40822e-07L8.15408 4.42608e-07L8.15408 5.80986Z"
                            fill="#F4F9FF"/>
                        </svg>
                        <span>{{ $t('page.createDepartment') }}</span>
                        </span>
                    </v-btn> -->
                    <button type="button" class="control-btn other-btn" @click="openOtherDropDown = !openOtherDropDown" v-if="computedTab.name === 'documents'">
                        <span>
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.43975 2.17531L4.83146 6.37332L0.0157811 6.39179L0 10.6765L4.81463 10.6562L2.38995 14.8715L6.05954 17L8.48316 12.7829L10.8749 16.9809L14.5602 14.8247L12.1685 10.6267L16.9842 10.6082L17 6.32345L12.1854 6.34377L14.6101 2.12853L10.9405 0L8.51684 4.21709L6.12513 0.0190841L2.43975 2.17531ZM4.37114 2.69037L5.5996 1.97163L8.5062 7.07339L11.4508 1.94941L12.673 2.65727L9.72846 7.78126L15.5796 7.75904L15.5734 9.18565L9.72226 9.20787L12.6289 14.3096L11.4004 15.0284L8.4938 9.92661L5.54922 15.0506L4.32696 14.3427L7.27154 9.21874L1.42036 9.24096L1.42656 7.81436L7.27774 7.79214L4.37114 2.69037Z"
                                fill="white"/>
                            </svg>
                            <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55708e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z"
                                fill="#E3F0FF"/>
                            </svg>
                        </span>
                    </button>
                    <ul class="other-list" v-show="openOtherDropDown">
                        <li class="other-item" v-for="item in otherDropDownInner" @click="goToItemAction(item)" :key="item.title">
                        {{ item.title }}
                        </li>
                    </ul>
                    <!-- <div class="settings-select" v-if="computedTab.name === 'documents'">
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
            @delete="deleteItems"
        >
        </router-view>
        <modal-positions
            v-if="modalToggles.ModalPositions"
            :modeType="mode"
            :isModalOpen="modalToggles.ModalPositions"
            :itemData="itemData"
            @close="closeModal"
            @onCloseModal="closeModal"
            ref="ModalPositions"
            @save="onSaveData"
        >
        </modal-positions>
        <modal-documents-employees
            v-if="modalToggles.ModalDocuments"
            :modeType="mode"
            :isModalOpen="modalToggles.ModalDocuments"
            :itemData="itemData"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveData"
        >
        </modal-documents-employees>
        <modal-departments
            :itemData="itemData"
            ref="ModalDepartments"
            @save="saveDepartment">
        </modal-departments>
        <modal-employee
            v-if="modalToggles.ModalEmployee"
            :isModalOpen="modalToggles.ModalEmployee"
            :itemData="itemData"
            ref="ModalEmployee"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveEmployees"
            @dataEmployee="dataEmployee"
        >
        </modal-employee>
        <modal-users
            v-if="modalToggles.ModalUsers"
            :isModalOpen="modalToggles.ModalUsers"
            :itemData="itemData"
            ref="ModalUsers"
            @close="closeModal"
            @onCloseModal="closeModal"
            @save="onSaveEmployees"
            @dataEmployee="dataEmployee"
        >
        </modal-users>
        <modal-edit-employee
            v-if="modalEditToggles.ModalEmployee"
            :isModalOpen="modalEditToggles.ModalEmployee"
            :itemData="itemData"
            ref="ModalEditEmployee"
            @saveEditEmployees="saveEditEmployees"
            @close="closeEditModal"
        >
        </modal-edit-employee>
        <modal-edit-users
            v-if="modalEditToggles.ModalUsers"
            :isModalOpen="modalEditToggles.ModalUsers"
            :itemData="itemData"
            ref="ModalEditUsers"
            @saveEditEmployees="saveEditEmployees"
            @close="closeEditModal"
        >
        </modal-edit-users>
        <modal-add-user
            @onCloseModal="onCloseModal"
            :isOpen="isOpen"
            v-if="isOpen"
        >
        </modal-add-user>
      </div>
    <Snackbar 
        v-if="alert.alertFlag"
        :modeType="alert.modeType"
        :typeTitle="alert.title"
        @closeSnackbar="closeSnackbar"
    >
    </Snackbar>
    </div>
    </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex"
import ModalPositions from '@/components/dashboard/directoties/employees/modals/ModalPositions'
import ModalDocumentsEmployees from '@/components/dashboard/directoties/employees/modals/ModalDocumentsEmployees'
import ModalEmployee from '@/components/dashboard/directoties/employees/modals/ModalEmployee'
import ModalUsers from '@/components/dashboard/directoties/employees/modals/ModalUsers'
import ModalDepartments from "@/components/dashboard/directoties/company/modals/ModalDepartments"
import ModalEditEmployee from '@/components/dashboard/directoties/employees/modals/edit/ModalEditEmployee'
import ModalEditUsers from '@/components/dashboard/directoties/employees/modals/edit/ModalEditUsers'
import ModalAddUser from '@/components/dashboard/directoties/employees/modals/ModalAddUser'
import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput"
import Snackbar from "@/components/dashboard/directoties/snackbar/Snackbar"

export default {
    name: "Employees",
    components: {
        ModalPositions,
        ModalDocumentsEmployees,
        ModalDepartments,
        ModalEmployee,
        ModalUsers,
        ModalEditEmployee,
        ModalEditUsers,
        ModalAddUser,
        AsyncSimpleInput,
        Snackbar
    },
    data() {
        return {
            openOtherDropDown: false,
            itemData: {},
            mode: '',
            navIsSmall: false,
            defaultId: 1,
            isOpen: false,
            currentTypeUser: 'employees',
            tabs: [
                {title: "directories.employees", filtered: true, name: "employees", modal: "ModalEmployee", url: "/directories/employees/employee"},
                {title: "directories.users", filtered: true, name: "users", modal: "ModalUsers", url: "/directories/employees/users"},
                {title: "directories.positions", filtered: false, name: "positions", modal: 'ModalPositions', url: "/directories/employees/positions"},
                {title: "directories.employees_documents", filtered: true, name: "documents", modal: "ModalDocuments", url: "/directories/employees/employees-documents"},
            ],
            tabsComponents: ['Employee', 'Users', 'Positions', 'EmployeesDocuments'],
            modalsComponents: ['ModalEmployee', 'ModalUsers', 'ModalPositions', 'ModalDocuments'],
            filtersComponents: ['FilterEmployees', '', 'FilterDocuments'],
            currentTab: {
                index: 0,
                name: '',
            },
            otherDropDownInner: [
                {
                    title: 'События',
                },
                {
                    title: 'Задачи',
                }
            ],
            modalToggles: {
                ModalEmployee: false,
                ModalUsers: false,
                ModalPositions: false,
                ModalDocuments: false
            },
            modalEditToggles: {
                ModalEmployee: false,
                ModalUsers: false,
                ModalPositions: false,
                ModalDocuments: false
            },
            alert: {
                alertFlag: false,
                title: null,
                modeType: null
            }
        }
    },
    computed: {
        ...mapGetters(['getEmployees', 'getUsers']),
        currentComponent() { return this.tabsComponents[this.currentTab.index] },
        currentFilter() { return this.filtersComponents[this.currentTab.index] },
        currentModal() { return this.modalsComponents[this.currentTab.index] },
        computedTab() { return this.tabs[this.currentTab.index] },
        settingsSelectEmployees() {
            return [
                { title: this.$t('filters.allUsers'), id: 1 },
                { title: this.$t('filters.activeEmployees'), id: 2 },
                { title: this.$t('filters.notActiveEmployees'), id: 3 },
                { title: this.$t('filters.beignUser'), id: 4 },
                { title: this.$t('filters.notBeignUser'), id: 5 }
            ]
        },
        settingsSelectUsers() {
            return [
                { title: this.$t('filters.allUsers'), id: 1 },
                { title: this.$t('filters.activeEmployees'), id: 2 },
                { title: this.$t('filters.notActiveEmployees'), id: 3 },
            ]
        },
        getLastIdUser() {
            if(this.currentTypeUser === 'employees') {
                return this.getEmployees.body.slice().shift()
            } else if(this.currentTypeUser === 'users') {
                return this.getUsers.body.slice().shift()
            } else {
                return false
            }
        },
    },
    watch: {
        $route(to) {
            if(to.params.params) {
                console.log('to.params.params',  to.params.params) 
                switch(to.params.params) {
                    case "employee":
                        this.currentTab.index = 0
                        break
                    case "users":
                        this.currentTab.index = 1
                        break
                    case "positions":
                        this.currentTab.index = 2
                        break
                    case "employees-documents":
                        this.currentTab.index = 3
                        break
                    default:
                        this.currentTab.index = 0
                }
                this.createModal()
            }
        }
    },
    methods: {
        ...mapActions({
            create: 'createClassifiersItem',
            change: 'changeClassifiersItem',
            changeDefault: 'changeDefaultItem',
            updateCompaniyList: 'updateCompanies',
            fetchLists: 'fetchLists',
            fetchCompanies: 'fetchCompanies',
            filteredEmployees: 'filteredEmployees'
        }),
        async saveEditEmployees({ data, resources, id }) {
            await this.change({ data, resources, id })
            this.modalEditToggles[this.computedTab.modal] = false
            this.alert.alertFlag = true
            this.alert.modeType = 'change'
            this.alert.title = ""
        },
        closeSnackbar() {
            this.alert.alertFlag = false
            this.alert.modeType = null
            this.alert.title = null
        },
        closeModal() {
            this.modalToggles[this.computedTab.modal] = false
        },
        closeEditModal() {
            this.modalEditToggles[this.computedTab.modal] = false
        },
        copy(val) {
            console.log('copy', val)
            this.mode = 'copy'
            this.itemData = val
            this.modalToggles[this.computedTab.modal] = true
        },
        clickRow(val) {
            this.itemData = val
            this.modalEditToggles[this.computedTab.modal] = true
        },
        filterEmployees({ id }) {
            console.log(id)
            const computedName = this.computedTab.name
            const data = { resource: computedName, id }
            this.defaultId = id
            this.filteredEmployees(data)
        },
        updateInput(value) {
            console.log('updateInput value', value)
        },
        openDepartments() {
            this.$refs.ModalDepartments.open()
        },
        createModal() {
            console.log('this.computedTab.modal', this.computedTab.modal)
            this.mode = 'create'
            this.modalToggles[this.computedTab.modal] = true
        },
        createNewItem() {
            this.mode = 'create'
            if(this.computedTab.name === 'positions' || this.computedTab.name === 'documents') this.$refs[this.currentModal].open(this.mode)
            else this.$refs[this.computedTab.modal].open(this.mode)
        },
        changeTab(index) {
            this.defaultId = 1
            this.currentTab.index = index
            console.log(this.isFiltered)
        },
        onChangeDefault(params) {
            console.log('change params', params)
            this.changeDefault({id: params.id, resources: params.resources } )
        },
        saveDepartment(params) {
            console.log('department', params)
            this.updateCompaniyList({ data: params.data, type: params.resources })
        },
        deleteItems(params) {
            console.log('params', params)
            this.showAlert(params)
        },
        async onSaveData(params) {
            console.log('params', params)
            await this[params.action]({...params})
            this.modalToggles[`${this.currentModal}`] = false
            this.alert.alertFlag = true
            this.alert.modeType = params.action
            this.alert.title = params.alertItem
        },
        async onSaveEmployees(params, type) {
            await this.create({ data: params.data, resources: params.resources_employees, type })
            this.currentTypeUser = type
            localStorage.setItem('invite_url', this.getLastIdUser.invite_url)
            this.isOpen = true
        },
        showAlert(item) {
            const dataAlert = {startText: 'Удалена(ы) должность(и)', item, text: true}
            this.$store.dispatch('alertShow', dataAlert)
        },
        onChangeItem(itemData) {
            console.log('Change itemData', itemData)
            this.mode = 'change'
            this.itemData = itemData
            this.modalToggles[this.computedTab.modal] = true
        },
        onViewItem(itemData) {
            console.log('View itemData', itemData)
            this.mode = 'view'
            this.itemData = itemData
            this.modalToggles[this.computedTab.modal] = true
        },
        onCloseModal() {
            const { params } = this.$route.params
            const currentModal = this.computedTab.modal
            console.log('onCloseModal', currentModal)
            if(params) { 
                this.$router.go(-1)
                this.$router.push(this.$route.path.slice(0, -params.length))
                this.modalToggles[this.computedTab.modal] = false
                this.isOpen = false
            } else {
                this.modalToggles[this.computedTab.modal] = false
                this.isOpen = false
                this.alert.alertFlag = true
                this.alert.modeType = 'create'
                this.alert.title = currentModal === 'ModalEmployee' ? 'directories.employees' : 'directories.users'
            }
        },
        async dataEmployee(value, is_user, is_employee, type) {
            await this.create({ data: value, resources: 'employees', type })
            this.currentTypeUser = type
            localStorage.setItem('invite_url', this.getLastIdUser.invite_url)
            this.isOpen = true
        }
    },
    created() {
        let componentName = this.$route.path.split('/')[3] !== undefined ? this.$route.path.split('/')[3] : this.$route.path.split('/')[2]
        switch(componentName) {
            case "employee":
                this.currentTab.index = 0
                break
            case "users":
                this.currentTab.index = 1
                break
            case "positions":
                this.currentTab.index = 2
                break
            case "employees-documents":
                this.currentTab.index = 3
                break
            default:
                this.currentTab.index = 0
        }
    },
    async mounted() {
        const { params } = this.$route.params
        await Promise.all([
            this.fetchCompanies('companies_departments'),
            this.fetchLists({ type: '', resources: 'positions' }),
            this.fetchLists({ type: '', resources: 'countries' }),
            this.fetchLists({ type: '', resources: 'cities' }),
            this.fetchLists({ type: 'core', resources: 'type_documents' }),
            this.fetchLists({ type: 'core', resources: 'sex' })
        ])
        if(params && params !== 'add-user') this.createModal()
        if(params && params === 'add-user') this.isOpen = true
    }
}
</script>
<style lang="sass">

.other-btn 
  background-color: #9DCCFF !important
  border-radius: 100px
  margin-left: 20px
  width: 73px
  height: 40px
  padding: 0 15px
  span 
    display: flex
    align-items: center
    justify-content: space-between
    width: 100%

.other-list 
  list-style: none
  background: #FFFFFF
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05)
  border-radius: 10px
  position: absolute
  right: 20px
  top: 59px
  z-index: 3
  padding-left: 0
  .other-item 
    padding: 12px 15px
    font-size: 14px
    line-height: 1
    color: #7E7E7E
    min-width: 250px
    cursor: pointer
    &:hover 
      background: #E3F0FF
    
.filtered-select
    .select-switcher
        .v-input
            &__control
                min-height: 40px!important

</style>