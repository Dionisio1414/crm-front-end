<template>
  <modal-container
      v-if="isModalOpen" 
      :dialog="isModalOpen" 
      :modalWidth="1234" 
      @clickOutside="onCloseModal"
      :customDialogClass="['modal-container-documents']"
  >
    <template v-slot:header>
      <div class="dialog-header">
        <div class="header-text">
          <span>{{ $t('directories.documentEmployeeText') }}</span>
        </div>
        <div class="dialog-header-actions">
          <v-btn icon color="#5893D4" @click="addTab('employees-documents', 'directories.documentEmployeeText')" :disabled="checkTabs" v-if="mode === 'create'">
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
          <form class="form">
            <div class="form-content">
              <v-row>
                  <v-col cols="6">
                    <div class="label-title label-title--required">Сотрудник</div>
                    <div class="select-wrap">
                        <v-select class="select-switcher"
                            v-model="localFormData.employee_id"
                            :items="employeesList"
                            label="Выберите сотрудника"
                            item-text="full_name"
                            item-value="id"
                            solo
                            dense
                            hide-details
                            :menu-props="{ contentClass: 'base-select'}"
                            @change="onChangeMainSelect($event, 'employee_id')"
                            :disabled="mode === 'view' ? true : false"
                            return-object
                        />
                    </div>
                  </v-col>
                  <v-col cols="6">
                    <div class="label-title label-title--required">Вид документа</div>
                    <div class="select-wrap">
                        <v-select class="select-switcher"
                            v-model="localFormData.document_type_id"
                            :items="documentsList"
                            label="Выберите тип документа"
                            item-text="title"
                            item-value="directory_id"
                            solo
                            dense
                            hide-details
                            :menu-props="{ contentClass: 'base-select'}"
                            @change="onChangeMainSelect($event, 'document_type_id')"
                            :disabled="mode === 'view' ? true : false"
                            return-object
                        />
                    </div>
                  </v-col>
              </v-row>
              <v-row>
                  <v-col cols="6">
                    <div class="label-title label-title--required">Серия / Номер</div>
                    <div class="form-field">
                      <input 
                        type="text" 
                        class="field" 
                        placeholder="Серия" 
                        v-model="localFormData.document_serial"
                        :disabled="mode === 'view' ? true : false"
                      >
                    </div>
                  </v-col>

                  <v-col cols="3">
                    <div class="label-title">{{ $t('directories.dateOfIssue') }}</div>
                    <div class="form-field">
                      <masked-input 
                        class="field"
                        v-model="localFormData.document_issued_date"
                        :class="{'field-error': mode === 'copy' || !isValidIssuedDate}"
                        @input="changeDate($event, 'document_issued_date')"
                        mask="11.11.1111" 
                        placeholder="ДД/ММ/ГГГГ"
                        :disabled="mode === 'view'" 
                      />
                    </div>
                  </v-col>
                  <v-col cols="3">
                    <div class="label-title">Срок действия</div>
                    <div class="form-field">
                      <masked-input 
                        class="field"
                        :class="{'field-error': mode === 'copy' || !isValidValidityDate}"
                        v-model="localFormData.document_validity_date"
                        mask="11.11.1111" 
                        placeholder="ДД/ММ/ГГГГ"
                        :disabled="mode === 'view'" 
                      />
                    </div>
                  </v-col>
              </v-row>
              <v-row>
                  <v-col cols="6">
                    <div class="label-title">Кем выдан</div>
                    <div class="form-field">
                      <input 
                        type="text" 
                        class="field" 
                        placeholder="Кем выдан" 
                        v-model="localFormData.document_issued"
                        :disabled="mode === 'view' ? true : false" 
                      >
                    </div>
                  </v-col>
              </v-row>
              <v-row>
                  <v-col cols="6">
                      <div class="checkbox">
                          <label class="checkbox-label">
                              <input 
                                type="checkbox" 
                                class="field"
                                :disabled="mode === 'view' ? true : false"
                                v-model="localFormData.is_identity_document"
                              >
                              <div class="checkbox-text">
                                  <div class="text">
                                      Является документом, удостоверяющим личность
                                  </div>
                              </div>
                          </label>
                      </div>
                  </v-col>
              </v-row>
              <v-row>
                <v-col cols="3">
                  <div class="label-title label-title--singular">
                    <div class="label-title-text">{{ $t('directories.dateTitle') }}</div>
                    <v-tooltip 
                      right
                      max-width="400px"
                      text-color="#7E7E7E"
                      content-class="gray-tooltip"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <div class="icon" v-bind="attrs" v-on="on">
                          <SvgSprite
                            :width="16"
                            :height="16"
                            iconId="circle_question"
                          >
                          </SvgSprite>
                        </div>
                      </template>
                      <div>- {{ $t('directories.dateRegDB') }}</div>
                    </v-tooltip>
                  </div>
                  <div class="form-field">
                    <div class="calendar">
                      <calendar
                        @updateDate="updateDate"
                        :placeholder="$t('purchases.modal.enterTheDate')"
                        :date-value="localFormData.document_creation_date"
                        custom-class="default-calendar"
                        date-key="date_create_document"
                        icon-color="#9DCCFF"
                        isCloseOnContentClick
                        is-icon
                      />
                    </div>
                  </div>
                </v-col>
              </v-row>
              <v-row>
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
            v-if="mode !== 'view'"
            type="button" 
            class="base-button base-button--transparent" 
            :disabled="loader"
            @click="cancel"
          >
            Назад
          </button>
          <button 
            v-if="mode === 'view'"
            type="button" 
            class="base-button base-button--transparent" 
            :disabled="loader"
            @click="cancel"
          >
            {{ $t('page.close') }}
          </button>
          <button 
            v-if="mode !== 'view'"
            type="button" 
            class="base-button base-button--blue" 
            :disabled="$v.$invalid || loader || !isValidValidityDate || !isValidIssuedDate"
            @click="onSave"
          >
            {{ $t('page.saveButton') }}
          </button>
        </div>
      </div>
    </template>
  </modal-container>
</template>

<script>
import { validationMixin } from "vuelidate"
import { required } from "vuelidate/lib/validators"
import { mapActions, mapGetters } from "vuex"
import MaskedInput from "vue-masked-input"
import moment from "moment"
import mixin from '@/mixins/mixinTabs'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"
import SvgSprite from '@/components/ui/SvgSprite/SvgSprite'
import Calendar from "@/components/ui/Calendar/Calendar"

export default {
  name: "ModalDocumentsEmployees",
  props: {
    itemData: Object,
    modeType: String,
    isModalOpen: Boolean
  },
  mixins: [validationMixin, mixin],
  validations: {
    localFormData: {
      document_serial: { required },
      // document_number: { required },
    }
  },
  components: {
    MaskedInput,
    ModalContainer,
    SvgSprite,
    Calendar
  },
  data: () => ({
    mode: 'create',
    currentId: null,
    isOpenDocumentCalendar: false,
    isOpenValidityCalendar: false,
    isOpenIssueCalendar: false,
    loader: false,
    isValidIssuedDate: true,
    isValidValidityDate: true,
    localFormData: {
      is_editable: true,
      archive: false,
      employee_id: null,
      counterparty_id: null,
      document_type_id: null,
      document_serial: null,
      document_number: null,
      document_issued_date: null,
      document_validity_date: null,
      document_issued: null,
      is_identity_document: false,
      document_creation_date: null,
      is_personal_identity: false,
    },
  }),
  watch: {
    'computedDateIssue': function(val) {
      if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
        this.isValidIssuedDate = false
      } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
        this.isValidIssuedDate = true
      } else {
        this.isValidIssuedDate = true
      }
    },
    'computedValidity': function(val) {
      if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
        this.isValidValidityDate = false
      } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
        this.isValidValidityDate = true
      } else {
        this.isValidValidityDate = true
      }
    },
  },
  computed: {
    ...mapGetters([
      'getLists',
      'tabsLength'
    ]),
    computedDateIssue() { return this.inverseDate(this.localFormData.document_issued_date) },
    computedValidity() { return this.inverseDate(this.localFormData.document_validity_date) },
    employeesList() { return this.getLists('lists')['employees']},
    documentsList() { return this.getLists('core_lists')['type_documents'] },
    defaultDocument() { return this.documentsList.find(item => item) },
    defaultEmployee() { return this.employeesList.find(item => item) },
  },
  methods: {
    ...mapActions(['updateTabs', 'deleteCurrentTab']),
    updateDate(params) { this.localFormData.document_creation_date = params.date },
    onCloseModal() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(this.$route.params.params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    changeDate(val, type) {
      console.log(this.inverseDate(val), type)
      // this.localFormData[type] = val
    },
    onSave() {
      const { params } = this.$route.params

      if(this.mode === 'change') {
        this.$emit('save', {
          action: this.mode,
          id: this.currentId,
          resources: 'employee_documents',
          data: { 
            ...this.localFormData,
            document_creation_date: this.localFormData.document_creation_date !== null ? this.localFormData.document_creation_date.slice(0, 10) : null, 
            document_issued_date: this.computedDateIssue !== null ? this.computedDateIssue : null,
            document_validity_date: this.computedValidity !== null ? this.computedValidity : null 
          },
          alertItem: 'directories.documentEmployeeText'
        })
        this.loader = true
      } else {
        if(params) {
          this.$emit('save', {
            action: this.mode,
            resources: 'employee_documents',
            data: {...this.localFormData},
            alertItem: 'directories.documentEmployeeText'
          })
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
          this.loader = true
        } else {
          this.$emit('save', {
            action: this.mode,
            resources: 'employee_documents',
            data: {...this.localFormData},
            alertItem: 'directories.documentEmployeeText'
          })
          this.loader = true
        }
      }
    },
    changeDefaultState() {
      this.localFormData.is_default = !this.localFormData.is_default
      if (this.mode !== 'create') {
        this.$emit('changeDefault', {id: this.localFormData.id, resources: 'employee_documents'})
      }
    },
    cancel() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(this.$route.params.params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    onChangeDate(value) { this[value] = false },
    onChangeMainSelect(obj, property) {
      const idx = obj.id ? obj.id : obj.directory_id
      this.localFormData[property] = idx
    },
    formatDate(date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}.${month}.${year}`
    },
    inverseDate(date) {
      if(!date) return null
      const [day, month, year] = date.split('.') 
      return `${year}-${month}-${day}`
    },
  },
  mounted() {
    console.log('mounted', this.defaultEmployee, this.defaultDocument)
    this.mode = this.modeType
    this.localFormData.employee_id = this.defaultEmployee?.id
    this.localFormData.document_type_id = this.defaultDocument?.directory_id
    if(this.mode !== 'create') {
      this.localFormData = {
        ...this.itemData,
        is_editable: true,
        document_issued_date: this.itemData.document_issued_date !== null ? this.formatDate(this.itemData.document_issued_date.slice(0, 10)) : null,
        document_validity_date: this.itemData.document_validity_date !== null ? this.formatDate(this.itemData.document_validity_date.slice(0, 10)) : null,
        document_creation_date: this.itemData.document_creation_date !== null ? this.itemData.document_creation_date.slice(0, 10) : null,
      }
      this.currentId = this.itemData.id
    }
}
}
</script>

<style lang="sass">
  .gray-tooltip
    padding-top: 10px!important
    padding-bottom: 10px!important

  .modal-container-documents
    .form 
      .validation-rules
        margin-top: 5px
    .dialog-footer
      padding-bottom: 50px
      .form-actions
        padding-top: 0
    .form-content
      .row
        margin-bottom: 20px
        &:last-child
          margin-bottom: 20px
      .label-title--singular
        .icon
          line-height: 1
      .label-title-text
        margin-right: 10px
        &::before
          display: none
    .form
      &-field
        .calendar
          .v-input
            &__slot
              &::before,
              &::after
                display: none
              input
                font-size: 14px
                font-weight: 400
                line-height: 1
                width: 100%
                min-height: 26px
                padding: 0 0 5px 0
                color: #7e7e7e
</style>