<template>
    <div class="companies-detail">
        <div 
            class="close-details" 
            v-if="stateDetails">
            <button type="button" class="button button-prev" @click="backList">
                <svg class="icon" width="6" height="12" viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.162909 6.42922L4.70779 11.3243C4.8129 11.4376 4.95323 11.5 5.10285 11.5C5.25247 11.5 5.39279 11.4376 5.49791 11.3243L5.83261 10.9639C6.0504 10.729 6.0504 10.3473 5.83261 10.1128L2.01616 6.00228L5.83684 1.88715C5.94196 1.77384 6 1.6228 6 1.46173C6 1.30049 5.94196 1.14944 5.83684 1.03604L5.50215 0.67573C5.39695 0.562422 5.25671 0.5 5.10708 0.5C4.95746 0.5 4.81714 0.562422 4.71202 0.67573L0.162909 5.57525C0.0575425 5.68892 -0.000329774 5.84068 2.08512e-06 6.00201C-0.000329813 6.16397 0.0575425 6.31564 0.162909 6.42922Z"/>
                </svg>
                <span class="btn-text">Назад</span>
            </button>
        </div>
        <div class="select-item" v-if="stateDetails === false">
            <img src="@/assets/icons/area-bg.svg" alt="">
            <div>
                {{ $t('directories.chooseDepartmentText') }}
            </div>
        </div>
        <div class="companies-content departments-section" v-if="stateDetails">
            <div class="item-form">
                <v-row>
                    <v-col cols="12">
                        <div class="item-name">
                            <div class="label-title">{{ $t('directories.nameDepartmentText') }} *</div>
                            <input 
                                type="text" 
                                name="departments_name" 
                                placeholder="Введите название отдела" 
                                :class="{'error-on-input': $v.copyData.title.required === false ? true : false}"
                                v-model="copyData.title"
                            >
                        </div>
                        
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <div class="label-title">{{ $t('directories.employees') }}</div>
                        <div class="employes-list">
                            <!-- Employee add -->
                                <div class="employe-add">
                                    <div class="text" @click="togglePopup" v-click-outside="hide" :class="{'text-disabled': employeesLists.list && employeesLists.list.length === 0 ? true : false}">{{ $t('directories.addEmployeeText') }}</div>
                                    <div 
                                        :class="{
                                            'employes-popup': true, 
                                            'employes-popup--active': isPopup 
                                        }" 
                                        v-if="employeesLists.list"
                                    >
                                        <div 
                                            :style="{opacity: val.main && val.main.is_not_active ? '.5' : '1' }"
                                            class="employes-popup-item" 
                                            v-for="(val, index) in employeesLists.list" 
                                            :key="index"
                                        >
                                            <div class="employe">
                                                <div class="employe-status">
                                                    <div class="checkbox">
                                                        <label class="checkbox-label">
                                                            <input type="checkbox" @change="addEmployee($event, val, index)">
                                                            <div class="checkbox-text"></div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="employe-picture">
                                                    <template v-if="val.thumbnail !== null">
                                                        <img 
                                                            class="picture" 
                                                            :src="val.thumbnail.url" 
                                                            alt=""
                                                        >
                                                    </template>
                                                    <template v-else>
                                                        <SvgSprite
                                                            class="icon"
                                                            :width="40"
                                                            :height="40"
                                                            iconId="default_user_icon"
                                                        />
                                                    </template>
                                     
                                                </div>
                                                <div class="employe-information">
                                                    <div class="fullname">{{ val.last_name }} {{ val.first_name }} {{ val.middle_name }}</div>
                                                    <div class="position">{{ val.position }}</div>
                                                </div>
                                                <!-- <div class="employe-actions">
                                                    <button type="button" class="remove" @click="deleteEmployee(val.id)">
                                                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
                                                        </svg>
                                                    </button>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- End employee add -->
                            <!-- <template v-if="data.employees.length"> -->
                                <div 
                                    class="employe" 
                                    v-for="(val, index) in employeesArray" 
                                    :style="{opacity: val.is_not_active ? '.5' : '1'}"
                                    :key="index"
                                >

                                    <div class="employe-status" :class="{'employe-status--main': val.is_leader}" @click="changeLeader(val)">
                                        <svg class="icon" width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.5 1.88V2.62891L2.19169 2.3418C3.21955 1.91514 4.59096 1.5 6 1.5C6.69167 1.5 7.18831 1.61473 7.57445 1.77451C7.96289 1.93524 8.26337 2.15101 8.56265 2.39043C8.62241 2.43824 8.68273 2.4878 8.74413 2.53824C9.28687 2.98416 9.91471 3.5 11 3.5C12.6215 3.5 13.7301 2.95625 14.4373 2.39043C14.4586 2.37342 14.4795 2.35641 14.5 2.3394V8.86452C14.4749 8.90375 14.4407 8.95405 14.3969 9.0125C14.2773 9.17188 14.0872 9.38993 13.8127 9.60957C13.2699 10.0438 12.3785 10.5 11 10.5C10.3083 10.5 9.81169 10.3853 9.42555 10.2255C9.03711 10.0648 8.73663 9.84899 8.43735 9.60957C8.37759 9.56176 8.31727 9.5122 8.25587 9.46176C7.71313 9.01584 7.08529 8.5 6 8.5C4.37854 8.5 3.26992 9.04375 2.56265 9.60957C2.21219 9.88993 1.96484 10.1719 1.80313 10.3875C1.72218 10.4954 1.66234 10.5872 1.62143 10.6546C1.60097 10.6883 1.5852 10.716 1.57385 10.7366C1.56818 10.7469 1.56361 10.7555 1.56011 10.7621L1.55566 10.7707L1.55403 10.7739L1.55336 10.7753L1.55306 10.7758C1.55292 10.7761 1.55279 10.7764 2 11L1.55279 10.7764L1.5 10.882V11V17.5H0.5V1C0.5 0.867392 0.552678 0.740215 0.646447 0.646447C0.740215 0.552678 0.867392 0.5 1 0.5C1.13261 0.5 1.25979 0.552678 1.35355 0.646447C1.44732 0.740215 1.5 0.867392 1.5 1V1.88Z"/>
                                        </svg>
                                    </div>
                                    <div class="employe-picture">
                                        <template v-if="val.thumbnail !== null">
                                            <img 
                                                class="picture" 
                                                :src="val.thumbnail.url" 
                                                alt=""
                                            >
                                        </template>
                                        <template v-else>
                                            <SvgSprite
                                                class="icon"
                                                :width="40"
                                                :height="40"
                                                iconId="default_user_icon"
                                            />
                                        </template>
                                    </div>
                                    <div class="employe-information">
                                        <div class="fullname">{{ val.last_name }} {{ val.first_name }} {{ val.middle_name }}</div>
                                        <div class="position">{{ val.position }}</div>
                                    </div>
                                    <div class="employe-actions">
                                        <button type="button" class="remove" @click="removeEmployee(index)">
                                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.98676 4.2921L10.2789 0L11.9711 1.69222L7.67898 5.98432L12 10.3053L10.3053 12L5.98432 7.67898L1.69222 11.9711L0 10.2789L4.2921 5.98676L0.0264792 1.72114L1.72114 0.0264774L5.98676 4.2921Z" fill="#9DCCFF"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            <!-- </template> -->
                        </div>
                    </v-col>
                </v-row>
            </div>
        </div> 
        <div class="organizations-buttons" v-if="stateDetails">
            <div class="validation-rules">(*) - {{ $t('page.suppliers.modal.createSupplier.firstForm.error.requiredFields') }}</div>
            <v-btn
                :disabled="$v.copyData.title.required === false ? true : false"
                @click="saveDepartments"
                class="base-btn shadow-btn">{{ $t('page.saveButton') }}
            </v-btn>
        </div>
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex"
import ClickOutside from 'vue-click-outside'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
// import { pick } from "@/helper/pick"

export default {
    name: "CreateDepartments",
    props: {
        data: Object,
        stateDetails: Boolean,
        mode: String
    },
    mixins: [validationMixin],
    components: {
        SvgSprite
    },
    validations: {
        copyData: {
            title: { required }
        }
    },
    data: () => ({
        isPopup: false,
        employeesArray: [],
        copyData: null,
    }),
    computed: {
        ...mapGetters([
            'getLists',
            'list'
        ]),
        employeesList() {
            return this.getLists('lists')['employees']
        },
        employeesLists() {
            return this.$store.getters.employeesList
        },
        transformEmployeeList() {
            const employees = [], self = this
            if(this.employeesArray.length) {
                    this.employeesArray.forEach((element, index) => {
                    const transformItems = Object.keys(element)
                            .filter(key => ['employee_id', 'is_leader'].includes(key))
                            .reduce((obj, key) => {
                            return {
                                ...obj,
                                [key]: self.employeesArray[index][key]
                            }
                            }, {})
                    employees.push(transformItems) 
                })
                return employees
            } else {
                return []
            }
        },
        checkEmployees() {
            return this.copyData.employees.length && this.employeesArray.length ? true : false
        }
    },
    methods: {
        ...mapActions([
            'changeCompanyItem',
            'fetchEmployees'
        ]),
        togglePopup() { 
            if(this.employeesLists.list.length > 0) {
                this.isPopup = !this.isPopup
            }
            // this.fetchEmployees(this.copyData.id)
        },
        backList() { 
            this.$emit('backList') 
        },
        removeEmployee(index) {
            console.log(index)
            this.isPopup = false
            this.employeesArray.splice(index, 1)
        },
        hide() {
            this.isPopup = false
        },
        addEmployee({ target }, value, index) {
            let currentEmployee = null
            if(target.checked) {
                const { id, first_name, last_name, middle_name, position, thumbnail } = value
                console.log('value', value)
                currentEmployee = {
                    employee_id: id,
                    first_name,
                    is_leader: false,
                    last_name,
                    middle_name,
                    position,
                    thumbnail
                }
                this.employeesArray.push(currentEmployee)
                console.log(currentEmployee)
            } else {
                const { id } = value
                console.log('else', id, index)
                const employeeId = this.employeesArray.findIndex(item => item.employee_id === id)
                this.employeesArray.splice(employeeId, 1)
                console.log(employeeId)
            }
        },
        changeItem() {
            this.backList()
        },
        changeLeader({ employee_id }) {
            this.employeesArray.forEach(item => {
                if(item.employee_id === employee_id && item.is_leader) {
                    item.is_leader = false
                } else if(item.employee_id === employee_id && !item.is_leader) {
                    item.is_leader = true
                } else {
                    item.is_leader = false
                }
            })
        },
        saveDepartments() {
            if(this.mode === 'change') {
                const { id } = this.copyData
                const data = {
                    is_default: this.copyData.is_default, 
                    title: this.copyData.title, 
                    archive: false,
                    employees: this.transformEmployeeList
                }
                console.log('data', data)
                this.changeCompanyItem({ data, type: 'companies_departments', id })
                this.$emit('backList', { modeType: 'change', title: 'directories.departments' }) 
            }
        }
    }, 
    created() {
        this.copyData = { ...this.data }
        if(Object.keys(this.data).length && this.data.employees.length) { 
            let copyEmployees = this.data.employees.slice()
            this.employeesArray = copyEmployees
        }
    },
    mounted() {
        if(this.copyData.id) this.fetchEmployees(this.copyData.id)
    },
    directives: {
      ClickOutside
    }
}
</script>
<style lang="sass" scoped>
    .page
        .companies-detail
            .close-details
                margin-bottom: 15px
</style>