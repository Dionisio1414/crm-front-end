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
            <span>{{ $t('directories.editUserText') }}</span>
          </div>
          <div class="dialog-header-actions">
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
                <v-row>
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
                            v-model="mainData.sex_id"
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
                                  v-model="mainData.company_department_id"
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
                                    v-model="mainData.position_id"
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
                  class="base-button base-button--transparent" 
                  :disabled="!isValidDates || loader"
                  @click="cancel"
                >
                  {{ $t('page.close') }}
                </button>
                <button 
                  type="button" 
                  class="base-button base-button--lighten" 
                  :disabled="$v.mainData.$invalid || !isValidDates || loader"  
                  @click="saveUsers"
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
import FilesManager from '@/components/ui/FilesManager/FilesManager'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"

export default {
  name: "ModalEditUsers",
  mixins: [validationMixin],
  props: {
    isModalOpen: Boolean,
    itemData: Object
  },
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
    currentId: null,
    isOpen: false,
    isValidDates: true,
    loader: false,
    mainData: {
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
    }
  }),
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
  computed: {
    ...mapGetters([
      'getLists',
      'list'
    ]),
    positionsList() { return this.getLists('lists')['positions'] },
    sexList() { return this.getLists('core_lists')['sex'] },
    departmentsList() { return this.list('companies_departments') },
    transformBirthDate() { return this.inverseDate(this.mainData.date_of_birth) },
  },
  methods: {
    ...mapActions([
      'updateTabs',
      'deleteCurrentTab',
      'changeClassifiersItem'
    ]),
    onCloseModal() { (!this.loader) && this.$emit('close') },
    filesManagerHandler() { this.isOpen = true },
    closeFilesManager() { this.isOpen = false },
    isLetter(e) {
      let char = String.fromCharCode(e.keyCode)
      if(/^[А-Яа-яёA-Za-z\s]+$/.test(char)) return true
      else e.preventDefault()
    },
    onSave() {
      console.log('on save')
      this.$emit('save', {
        action: this.mode,
        resources_employees: 'employees',
        resources_users: 'users',
        data: {
          ...this.mainData,
          date_of_birth: this.inverseDate(this.mainData.date_of_birth)
        },
        alertItem: `${this.mainData.last_name} ${this.mainData.first_name} ${this.mainData.middle_name}` 
      })
      this.$emit('close')
    },
    checkStep() { this.isSteps = false },
    dataEmployee(value, state) {
      console.log('dataEmployee value', value, state)
      this.$emit('dataEmployee', value, state)
      this.cancel()
    },
    cancel() {
      this.isSteps = false
      this.onCloseModal()
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
    formatDate(date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },
    saveUsers() {
      this.$emit('saveEditEmployees', { 
        data: {
          ...this.mainData,
          date_of_birth: this.inverseDate(this.mainData.date_of_birth)
        }, 
        resources: 'users', 
        id: this.currentId 
      })
      this.loader = true
    },
    getImage({ data, successUrl }) {
      this.mainData.thumbnail = successUrl
      this.mainData.thumbnail_id = data
    },
  },
  mounted() {
    console.log('mounted', this.itemData)
    this.currentId = this.itemData.id
    this.mainData = { 
      thumbnail_id: this.itemData.employee.thumbnail_id !== null ? this.itemData.employee.thumbnail_id : null,
      thumbnail: this.itemData.employee.thumbnail !== null ? this.itemData.employee.thumbnail.url : null,
      first_name: this.itemData.employee.first_name,
      last_name: this.itemData.employee.last_name,
      middle_name: this.itemData.employee.middle_name,
      date_of_birth: this.itemData.employee.date_of_birth !== null ? this.formatDate(this.itemData.employee.date_of_birth.slice(0, 10)) : null,
      sex_id: this.itemData.employee.sex_id !== null ? this.itemData.employee.sex_id : 1,
      company_department_id: this.itemData.employee.company_department_id,
      position_id: this.itemData.employee.position_id 
    }
  }
}
</script>

<style scoped lang="sass">
  .upload-picture
    margin-bottom: 25px
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