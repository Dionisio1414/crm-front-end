<template>
  <modal-container 
      v-if="isModalOpen" 
      @clickOutside="onCloseModal"
      :dialog="isModalOpen" 
      :modalWidth="1200"
      :customDialogClass="['modal-container-departments']"
  >
    <template v-slot:header> 
      <div class="dialog-header">
        <div class="header-text">
          <span>{{ $t('directories.addDepartment') }}</span>
        </div>
        <div class="dialog-header-actions">
          <v-btn icon color="#5893D4" @click="addTab('companies_departments', 'directories.nameDepartment')" :disabled="checkTabs">
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
          <form class="form" @submit.prevent="onSave">
            <div class="form-content">
              <v-row>
                <v-col cols="6">
                    <div class="label-title label-title--required">
                        {{ $t('directories.nameDepartment') }}
                    </div>
                    <div class="form-field">
                      <input 
                        class="field" 
                        type="text" 
                        @input="onValid"
                        v-model="localFormData.title" 
                        :placeholder="$t('page.typeName')">
                      <div class="form-field-helper" v-show="!isValid">
                        {{ $t('page.suppliers.modal.createSupplier.firstForm.error.nameExist') }}
                      </div>
                    </div>
                </v-col>
                <v-col cols="6">
                    <div class="label-title">{{ $t('directories.mentorDepartment') }}</div>
                    <div class="select-wrap">
                      <v-select class="select-switcher"
                          label="Выберите руководителя отдела"
                          :items="employeesLists"
                          item-text="full_name"
                          item-value="id"
                          solo
                          dense
                          hide-details
                          :menu-props="{ contentClass: 'base-select'}"
                          no-data-text="Создайте сотрудника"
                          @change="changeLeader"
                      />
                    </div>
                </v-col>
                <v-col cols="12">
                  <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
                </v-col>
              </v-row>
            </div>
          </form>
        </div>
      </div>
    </template>
    <template v-slot:footer>
      <div class="dialog-footer">
        <div class="form-actions">
          <button 
            type="button" 
            class="base-button base-button--transparent" 
            :disabled="loader"
            @click="cancel"
          >
            {{ $t('page.cancelButton') }}
          </button>
          <button 
            type="submit" 
            class="base-button base-button--blue"
            @click="onSave"
            :disabled="!$v.localFormData.title.required || !isValid || loader"
          >
            {{ $t('page.saveButton') }}
          </button>
        </div>
      </div>
    </template>
  </modal-container>

</template>

<script>
import { mapGetters, mapActions } from "vuex"
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import mixin from '@/mixins/mixinTabs'
import debounce from 'lodash.debounce'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"

export default {
  name: "ModalDepartments",
  props: {
    isModalOpen: Boolean
  },
  components: { ModalContainer },
  mixins: [validationMixin, mixin],
  validations: {
    localFormData: {
      title: { 
        required
      }
    }
  },
  data: () => ({
    dialog: false,
    isValid: true,
    loader: false,
    localFormData: {
      is_default: false,
      title: "",
      archive: false,
      employees: [],
    },
  }),
  computed: {
    ...mapGetters([
        'getLists',
        'list',
        'isAsyncValidate',
        'tabsLength'
    ]),
    employeesList() {
        return this.getLists('lists')['employees']
    },
    defaultEmployee() {
      if(this.getLists('lists')['employees'])
        return this.getLists('lists')['employees'][0]
      else
        return {}
    },
    employeesLists() { return this.list('employees_list') },
  },
  methods: {
    ...mapActions(['fetchEmployees', 'createAsyncValidation', 'updateTabs']),
    onCloseModal() { if(!this.loader) this.$emit('close') },
    onSave() {
      const { params } = this.$route.params
      if(params) {
        this.$emit('save', {
          action: 'create',
          resources: 'companies_departments',
          data: { ...this.localFormData },
          alertItem: 'directories.departments'
        })
        this.loader = true
        this.$router.go(-1)
      } else {
        this.$emit('save', {
          action: 'create',
          resources: 'companies_departments',
          data: { ...this.localFormData },
          alertItem: 'directories.departments'
        })
        this.loader = true
      }
    },
    changeLeader(value) {
      this.localFormData.employees = []
      this.localFormData.employees.push({
        employee_id: value,
        is_leader: true
      })
    },
    cancel() { 
      if(!this.loader) {
        const { params } = this.$route.params
        if(params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    open() {
      this.localFormData.title = ''
      this.dialog = true
    },
    onValid: debounce(async function() {
      const response = await this.createAsyncValidation({ type: 'companies_departments', obj: { title: this.localFormData.title }})
      if(!response.success) this.isValid = false
      else this.isValid = true
    }, 100),
  },
  async mounted() {
    await this.fetchEmployees(1)
  }
}
</script>

<style lang="sass">
.modal-container-departments
  .dialog-footer
    padding-top: 0!important
    padding-bottom: 50px!important
    .form-actions
      padding-top: 0!important
</style>