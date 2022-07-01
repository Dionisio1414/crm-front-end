<template>
  <div v-frag>
    <modal-container
      v-if="isModalOpen" 
      @clickOutside="onCloseModal"
      :dialog="isModalOpen" 
      :modalWidth="1230"
      :customDialogClass="['modal-container-default']"
    >
      <template v-slot:header>
       <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t('directories.addUserText') }}</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" @click="addTab('users', 'directories.addUserText')" :disabled="checkTabs" v-if="mode === 'create'">
              <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                      fill="#BBDBFE"/>
              </svg>
            </v-btn>
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="cancel">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                    fill="#BBDBFE"/>
              </svg>
            </v-btn>
          </div>
        </div>
      </template>
      <template v-slot:main>
        <div class="dialog-body">
          <div class="dialog-body-content">
            <steps-create-employee 
              v-if="isSteps" 
              @checkStep="checkStep" 
              :mainData="mainData"
              @createEmployee="dataEmployee"
            >
            </steps-create-employee>
            <form class="form" v-else>
              <div class="form-content">
                <v-row align="center">
                  <v-col cols="1">
                    <div class="upload-picture" @click="filesManagerHandler">
                        <span class="upload-picture-text">{{ $t('user.addPhoto') }}</span>
                        <span class="picture">
                            <img :src="mainData.thumbnail" alt="">
                        </span>
                    </div>
                  </v-col>
                  <v-col cols="2">
                    <div class="label-title label-title--required">{{ $t('page.suppliers.modal.createSupplier.thirdForm.surname') }}</div>
                    <div class="form-field">
                      <input type="text" class="field" placeholder="Введите фамилию" v-model="mainData.last_name" @keypress="isLetter">
                    </div>
                  </v-col>
                  <v-col cols="2">
                    <div class="label-title label-title--required">{{ $t('page.suppliers.modal.createSupplier.thirdForm.name') }}</div>
                    <div class="form-field"> 
                      <input type="text" class="field" placeholder="Введите имя" v-model="mainData.first_name" @keypress="isLetter">
                    </div>
                  </v-col>
                  <v-col cols="2"> 
                    <div class="item-name">
                      <div class="label-title label-title--required">{{ $t('page.suppliers.modal.createSupplier.thirdForm.patronymic') }}</div>
                      <div class="form-field">
                        <input type="text" class="field" placeholder="Введите отчество" v-model="mainData.middle_name" @keypress="isLetter">
                      </div>
                    </div>
                  </v-col>
                  <v-col cols="2">
                    <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.firstForm.dateOfBirthday') }}</div>
                    <!-- <input type="text" placeholder="Введите дату рождения"> -->
                    <div class="form-field">
                      <masked-input 
                        :class="{'field': true, 'field-error': !isValidDates}"
                        mask="11.11.1111" 
                        placeholder="ДД/ММ/ГГГГ"
                        v-model="mainData.date_of_birth" 
                      />
                    </div>
                  </v-col>
                  <v-col cols="3">
                      <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.thirdForm.sex') }}</div>
                      <div class="select-wrap">
                        <v-select class="select-switcher"
                            v-model="defaultSexItem"
                            :items="sexList"
                            item-text="title"
                            item-value="directory_id"
                            solo
                            dense
                            hide-details
                            @change="onChangeMainSelect($event, 'sex_id')"
                            :menu-props="{ contentClass: 'base-select'}"
                            return-object
                        />
                      </div>
                  </v-col>
                </v-row>

                <v-row>
                    <v-col cols="6">
                        <div class="item-name">
                            <div class="label-title label-title--required">{{ $t('user.groupDepartment') }}</div>
                            <div class="select-wrap">
                              <v-select class="select-switcher"
                                  v-model="defaultDepartmentItem"
                                  label="Выберите отдел"
                                  :items="departmentsList"
                                  item-text="title"
                                  item-value="id"
                                  solo
                                  dense
                                  hide-details
                                  @change="onChangeMainSelect($event, 'company_department_id')"
                                  :menu-props="{ contentClass: 'base-select'}"
                                  return-object
                              />
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <div class="item-name">
                            <div class="label-title label-title--required">{{ $t('page.suppliers.modal.createSupplier.thirdForm.position') }}</div>
                            <div class="select-wrap">
                                <v-select class="select-switcher"
                                    v-model="defaultPositionItem"
                                    label="Выберите должность"
                                    :items="positionsList"
                                    item-text="title"
                                    item-value="id"
                                    solo
                                    dense
                                    hide-details
                                    :menu-props="{ contentClass: 'base-select'}"
                                    @change="onChangeMainSelect($event, 'position_id')"
                                    return-object
                                />
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <div class="checkbox checkbox--disabled">
                            <label class="checkbox-label">
                                <input type="checkbox">
                                <div class="checkbox-text"> 
                                    <div class="text">{{ $t('user.userIsEmployee') }}</div>
                                </div>
                            </label>
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                  <v-col cols="12">
                    <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
                  </v-col>
                </v-row>
              </div>
              <div class="form-actions" v-if="!isSteps">
                <button 
                  type="button" 
                  class="base-button base-button--lighten" 
                  :disabled="$v.mainData.$invalid || !isValidDates || loader" 
                  @click="createEmployee"
                >
                  {{ $t('user.continueCreateEmployee') }}
                </button>
                <button 
                  type="button" 
                  class="base-button base-button--blue" 
                  :disabled="$v.mainData.$invalid || !isValidDates || loader" 
                  @click="onSave"
                >
                  {{ $t('user.saveUser') }}
                </button>
              </div>
            </form>

          </div>
        </div>
      </template>
    </modal-container>
    <files-manager
      v-if="isOpen"
      :isOpen="isOpen"
      typeField="radio"
      @closeFilesManager="closeFilesManager"
      @image="getImage"
    >
    </files-manager>
  </div>

</template>

<script>
import { mapGetters, mapActions } from "vuex"
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import MaskedInput from 'vue-masked-input'
import StepsCreateEmployee from '@/components/dashboard/directoties/employees/modals/create/StepsCreateEmployee'
import moment from 'moment'
import mixin from '@/mixins/mixinTabs'
import FilesManager from '@/components/ui/FilesManager/FilesManager'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"

export default {
  name: "ModalUsers",
  props: {
    itemData: Object,
    isModalOpen: Boolean
  },
  mixins: [validationMixin, mixin],
  components: {
    StepsCreateEmployee,
    MaskedInput,
    FilesManager,
    ModalContainer
  },
  validations: {
    mainData: {
      first_name: { required },
      last_name: { required },
      middle_name: { required },
    }
  },
  data: () => ({
    mode: 'create',
    dialog: false,
    isSteps: false,
    isValidDates: true,
    isOpen: false,
    loader: false,
    mainData: {
      thumbnail_id: null,
      thumbnail: null,
      first_name: '',
      last_name: '',
      middle_name: '',
      date_of_birth: '',
      sex_id: 1,
      company_department_id: 1,
      position_id: 1,
      archive: false,
      is_user: true,
      is_employee: 1,
      directory_employee_id: null
    },
    departments: [
      { name: 'Отдел продаж', id: 1},
      { name: 'Отдел маркетинга', id: 2},
      { name: 'Бухгалтерия', id: 3},
    ],
    positions: [
      { name: 'Менеджер по продажам', id: 1},
      { name: 'Менеджер по закупкам', id: 2},
      { name: 'Бухгалтер', id: 3},
    ],
    sex: [
      { name: 'Мужской', id: 1},
      { name: 'Женский', id: 2},
    ],
    localFormData: {
      id: Math.floor( Math.random() * ( 1 - 50000 + 1 ) ) + 1,
      is_editable: true,
      is_default: false,
      cells: {
          title: ""
      }
    },
  }),
  computed: {
    ...mapGetters([
      'getLists',
      'list',
      'tabsLength'
    ]),
    transformBirthDate() {
      return this.inverseDate(this.mainData.date_of_birth)
    },
    tableCells() {
      return this.localFormData.cells
    },
    positionsList() {
      return this.getLists('lists')['positions']
    },
    sexList() {
      return this.getLists('core_lists')['sex']
    },
    departmentsList() {
      return this.list('companies_departments')
    },
    defaultSexItem: {
      get() {
        if(this.sexList[0].directory_id !== undefined) {
          return this.sexList[0].directory_id
        } else {
          return []
        }
      },
      set(value) {
        return value
      }
    },
    defaultDepartmentItem: {
      get() {
        return this.departmentsList[0].id
      },
      set(value) {
        return value
      }
    }, 
    defaultPositionItem: {
      get() {
        return this.positionsList[0].id
      },
      set(value) {
        return value
      }
    }
  },
  watch: {
    'transformBirthDate': function(val) {
      if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
        this.isValidDates = false
      } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
        this.isValidDates = true
      } else {
        this.isValidDates = true
      }
    }
  },
  created() {
    this.defaultData = JSON.parse(JSON.stringify(this.localFormData))
    console.log(this.defaultData)
  },
  methods: {
    ...mapActions([
      'updateTabs'
    ]),
    onCloseModal() {
      if(!this.loader) {
        const { params } = this.$route.params
        console.log('params', params)
        if(this.$route.params.params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        } else {
          this.$emit('close')
        }
      }
    },
    filesManagerHandler() {
      this.isOpen = true
    },
    closeFilesManager() {
      this.isOpen = false
    },
    isLetter(e) {
        let char = String.fromCharCode(e.keyCode)
        if(/^[А-Яа-яёA-Za-z\s]+$/.test(char)) return true
        else e.preventDefault()
    },
    onSave() {
      const { params } = this.$route.params
      let self = this
      if(params) {
        this.$emit('save', {
          action: this.mode,
          resources_employees: 'employees',
          resources_users: 'users',
          data: {
            ...this.mainData,
            date_of_birth: this.mainData.date_of_birth !== null ? self.inverseDate(self.mainData.date_of_birth) : ""
          },
          alertItem: `${this.mainData.last_name} ${this.mainData.first_name} ${this.mainData.middle_name}` 
        }, 'users')
        this.$router.go(-1)
        this.$router.push(this.$route.path.slice(0, -params.length))
        this.loader = true
      } else {
        this.$emit('save', {
          action: this.mode,
          resources_employees: 'employees',
          resources_users: 'users',
          data: {
            ...this.mainData,
            date_of_birth: this.mainData.date_of_birth !== null ? self.inverseDate(self.mainData.date_of_birth) : ""
          },
          alertItem: `${this.mainData.last_name} ${this.mainData.first_name} ${this.mainData.middle_name}` 
        }, 'users')
        this.loader = true
      }
    },
    changeDefaultState() {
      this.localFormData.is_default = !this.localFormData.is_default
      if (this.mode !== 'create') {
        this.$emit('changeDefault', {id: this.localFormData.id, resources: 'positions'})
      }
    },
    checkStep() {
      this.isSteps = false
    },
    dataEmployee(value, is_user, is_employee) {
      console.log('dataEmployee value', value, is_user, is_employee)
      this.$emit('dataEmployee', value, is_user, is_employee, 'users')
    },
    cancel() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(this.$route.params.params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.isSteps = false
      }
    },
    checkSteps() {
      console.log('Check step')
    },
    createEmployee() {
      if(this.$v.mainData.first_name.required || this.$v.mainData.last_name.required || this.$v.mainData.middle_name.required) {
        this.isSteps = true
      }
    },
    onChangeMainSelect(obj, value) {
      const correctKeyId = obj.id ? obj.id : obj.directory_id
      console.log(correctKeyId, value)
      this.mainData[value] = correctKeyId
    },
    inverseDate(date) {
      if(!date) return null
      const [day, month, year] = date.split('.')
      return `${year}-${month}-${day}`
    },
    getImage({ data, successUrl }) {
      console.log('get image', data, successUrl)
      this.mainData.thumbnail = successUrl
      this.mainData.thumbnail_id = data
    },
    open(mode, itemData) {
      this.mode = mode

      console.log(this.mode, this.defaultData)
      const item = mode === 'create' ? JSON.parse(JSON.stringify(this.defaultData)) : JSON.parse(JSON.stringify(itemData))
      this.localFormData = item
      console.log(this.defaultData);
      this.dialog = true
    }
  }
}
</script>

<style scoped lang="sass">
  // .upload-picture
  //   margin-bottom: 25px
  .form-content
    .row
      &.align-center
        margin-bottom: 20px
  .checkbox
    margin-top: 20px
    &--disabled
      .checkbox-text
        &::before
          border-color: transparent
        &::after
          background: tranpsparent
        .text
          padding-left: 0         
  .base-button
    min-width: 270px
</style>