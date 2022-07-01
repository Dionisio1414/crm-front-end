<template>
  <modal-container
    v-if="isModalOpen" 
    @clickOutside="onCloseModal"
    :dialog="isModalOpen" 
    :modalWidth="1230"
    :customDialogClass="['modal-container-documents']"
  >
    <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t('directories.contactsDocumentText') }}</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" @click="addTab('individual-documents', 'directories.contactsDocumentText')" :disabled="checkTabs" v-if="mode === 'create'">
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
                  <v-col cols="3">
                    <div class="label-title label-title--required">{{ $t('directories.contractorTitle') }}</div>
                    <div class="select-wrap" :class="{'select-wrap--error': mode === 'copy'}">
                        <v-select 
                            v-model="localFormData.counterparty_id"
                            class="select-switcher"
                            :items="counterpartiesList"
                            :label="$t('directories.contractorTitle')"
                            item-text="title"
                            item-value="id"
                            solo
                            dense
                            hide-details
                            :menu-props="{ contentClass: 'base-select'}"
                            @change="onChangeMainSelect($event, 'counterparty_id')"
                            return-object
                        />
                    </div>
                  </v-col>
                  <v-col cols="3" :style="{ opacity: !individualList.length ? .5 : 1, pointerEvents: !individualList.length ? 'none' : '' }">
                    <div class="label-title label-title--required">{{ $t('directories.individualTitle') }}</div>
                    <div class="select-wrap" :class="{'select-wrap--error': mode === 'copy'}">
                      <v-select class="select-switcher"
                          v-model="localFormData.supplier_contact_id"
                          :items="individualList"
                          :label="$t('directories.individualTitle')"
                          :disabled="!individualList.length"
                          item-text="title"
                          item-value="id"
                          solo
                          dense
                          hide-details
                          :menu-props="{ contentClass: 'base-select'}"
                          @change="onChangeMainSelect($event, 'supplier_contact_id')"
                          return-object
                      />
                    </div>
                  </v-col>
                  <v-col cols="6">
                    <div class="label-title label-title--required">{{ $t('filters.labels.typeDocument') }}</div>
                    <div class="select-wrap" :class="{'select-wrap--error': mode === 'copy'}">
                        <v-select class="select-switcher"
                            v-model="localFormData.document_type_id"
                            :items="documentsList"
                            :label="$t('filters.labels.typeDocument')"
                            item-text="title"
                            item-value="directory_id"
                            solo
                            dense
                            hide-details
                            :menu-props="{ contentClass: 'base-select'}"
                            @change="onChangeMainSelect($event, 'document_type_id')"
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
                        :class="{'field-error': mode === 'copy'}"
                        :placeholder="$t('directories.seriesTitle')"
                        v-model="localFormData.document_number"
                        :disabled="mode === 'view'"
                      >
                      <div class="form-field-helper" v-if="mode === 'copy'">{{ $t('directories.changeTitle') }}</div>
                    </div>
                  </v-col>
                  <v-col cols="3">
                    <div class="label-title">{{ $t('directories.dateOfIssue') }}</div>
                    <div class="form-field">
                      <masked-input 
                        class="field"
                        :class="{'field-error': mode === 'copy' || !isValidIssuedDate}"
                        v-model="localFormData.document_issued_date"
                        mask="11.11.1111" 
                        placeholder="ДД/ММ/ГГГГ"
                        :disabled="mode === 'view'" 
                      />
                      <div class="form-field-helper" v-if="mode === 'copy'">{{ $t('directories.changeTitle') }}</div>
                    </div>
                  </v-col>
                  <v-col cols="3">
                    <div class="label-title">{{ $t('directories.validityTitle') }}</div>
                    <div class="form-field">
                      <masked-input 
                        class="field"
                        :class="{'field-error': mode === 'copy' || !isValidValidityDate}"
                        v-model="localFormData.document_validity_date"
                        placeholder="ДД/ММ/ГГГГ"
                        mask="11.11.1111" 
                        :disabled="mode === 'view'" 
                      />
                      <div class="form-field-helper" v-if="mode === 'copy'">{{ $t('directories.changeTitle') }}</div>
                    </div>
                  </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="label-title">{{ $t('directories.issuedBy') }}</div>
                  <div class="form-field">
                      <input 
                        type="text" 
                        class="field" 
                        :class="{'field-error': mode === 'copy'}"
                        :placeholder="$t('directories.issuedBy')" 
                        v-model="localFormData.document_issued"
                        :disabled="mode === 'view' ? true : false" 
                      >
                      <div class="form-field-helper" v-if="mode === 'copy'">{{ $t('directories.changeTitle') }}</div>
                    </div>
                </v-col>
              </v-row>
              <v-row>
                  <v-col cols="6">
                      <div class="checkbox">
                          <label class="checkbox-label">
                              <input type="checkbox" v-model="localFormData.is_personal_identity">
                              <div class="checkbox-text">
                                  <div class="text">
                                    {{ $t('directories.isYourselfDocument') }}
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
                            :date-value="localFormData.date_create_document"
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
            :disabled="$v.$invalid || !isValidValidityDate || !isValidIssuedDate || loader"
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
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import { mapActions, mapGetters } from 'vuex'
import MaskedInput from "vue-masked-input"
import mixin from '@/mixins/mixinTabs'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"
import moment from 'moment'
import Calendar from "@/components/ui/Calendar/Calendar"
// import getCurrentDate from '@/helper/getCurrentDate'
import SvgSprite from '@/components/ui/SvgSprite/SvgSprite'

export default {
  name: "ModalDocumentsContractors",
  props: {
    itemData: Object,
    isModalOpen: Boolean,
    modeType: String
  },
  mixins: [validationMixin, mixin],
  validations: {
    localFormData: {
      document_number: { required },
      document_type_id: { required },
      supplier_contact_id: { required },
      counterparty_id: { required }
    }
  },
  components: {
    MaskedInput,
    ModalContainer,
    Calendar,
    SvgSprite
  },
  data: () => ({
    mode: 'create',
    dialog: false,
    currentId: null,
    isOpenDocumentCalendar: false,
    isOpenValidityCalendar: false,
    isOpenIssueCalendar: false,
    isValidIssuedDate: true,
    isValidValidityDate: true,
    loader: false,
    localFormData: {
      is_personal_identity: false,
      date_create_document: null,
      supplier_contact_id: null,
      counterparty_id: null,
      document_type_id: null,
      document_number: null,
      document_issued_date: null,
      document_validity_date: null,
      document_issued: null,
    },
    throughTimeObj: ["Завтра", "Через неделю", "Послезавтра", "Через месяц"],
  }),
  computed: {
    ...mapGetters(['getLists']),
    counterpartiesList() { return this.getLists('lists')['counterparties'] },
    documentsList() { return this.getLists('core_lists')['type_documents'] },
    computedDateIssue() { return this.inverseDate(this.localFormData.document_issued_date) },
    computedDateValidity() { return this.inverseDate(this.localFormData.document_validity_date) },
    individualList() { return this.getLists('lists')['individuals'] },
    computedCreateDocument: {
      get() {
        if(this.mode === 'create') {
          return this.formatDate(this.localFormData.date_create_document)
        }
        return this.localFormData.date_create_document
      },
      set(value) {
        return value
      }
    },
    defaultIndividualList: {
      get() {
        if(this.individualList.length > 0) return this.individualList.find(item => item.id)
        return []
      },
      set({ id }) {
        console.log(id)
        this.localFormData.supplier_contact_id = id
      }
    },
  },
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
    'computedDateValidity': function(val) {
      if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
        this.isValidValidityDate = false
      } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
        this.isValidValidityDate = true
      } else {
        this.isValidValidityDate = true
      }
    },
    'localFormData.counterparty_id': async function(val) {
        await this.fetchList({ type: '', resources: 'individuals', directory: 'directories', query: `?counterparty_id=${val}` })
        if(!this.individualList.length) this.localFormData.supplier_contact_id = null
        else this.localFormData.supplier_contact_id = val
    }
  },
  methods: {
    ...mapActions(['fetchList', 'fetchLists', 'resetList']),
    updateDate(params) {
      console.log(params)
      this.localFormData.date_create_document = params.date
    },
    onSave() {
      const { params } = this.$route.params
      if(this.mode == 'copy') this.mode = 'create'
  
      if(params) {
        this.$emit('save', {
          action: this.mode,
          id: this.currentId,
          resources: 'individuals_documents',
          data: { 
            ...this.localFormData, 
            date_create_document: this.localFormData.date_create_document !== null ? this.localFormData.date_create_document.slice(0, 10) : null, 
            document_issued_date: this.computedDateIssue !== null ? this.computedDateIssue : null,
            document_validity_date: this.computedDateValidity !== null ? this.computedDateValidity : null 
          },
          alertItem: 'directories.contactsDocumentText'
        })
        this.$router.go(-1)
        this.$router.push(this.$route.path.slice(0, -params.length))
        this.loader = true
        this.resetList({ type: 'lists', resources: 'individuals' })
      } else {
        this.$emit('save', {
          action: this.mode,
          id: this.currentId,
          resources: 'individuals_documents',
          data: { 
            ...this.localFormData, 
            date_create_document: this.localFormData.date_create_document !== null ? this.localFormData.date_create_document.slice(0, 10) : null, 
            document_issued_date: this.computedDateIssue !== null ? this.computedDateIssue : null,
            document_validity_date: this.computedDateValidity !== null ? this.computedDateValidity : null 
          },
          alertItem: 'directories.contactsDocumentText'
        })
        this.loader = true
        this.resetList({ type: 'lists', resources: 'individuals' })
      }
    },
    async cancel() {
      await this.resetList({ type: 'lists', resources: 'individuals' })
      if(!this.loader) {
        const { params } = this.$route.params
        if(params) {
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
    async onChangeMainSelect(obj, property) {
      const idx = obj.id ? obj.id : obj.directory_id
      this.localFormData[property] = idx
    },
    formatDate(date) {
      console.log(date)
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}.${month}.${year}`
    },
    inverseDate(date) {
      if(!date) return null
      const [day, month, year] = date.split('.') 
      return `${year}-${month}-${day}`
    },
    async onCloseModal() { 
      await this.resetList({ type: 'lists', resources: 'individuals' })
      if(!this.loader) {
        const { params } = this.$route.params
        if(params) {
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
  },
  async created() {
    await this.fetchLists({ type: '', resources: 'counterparties' })
    await this.fetchLists({ type: 'core', resources: 'type_documents' })
    if(this.itemData.counterparty_id && this.modeType === 'change') {
      await this.fetchList({ type: '', resources: 'individuals', directory: 'directories', query: `?counterparty_id=${this.localFormData.counterparty_id}` })
    }
  },
  mounted() {
    this.mode = this.modeType
    if(this.mode !== 'create') {
      const { 
          id, 
          counterparty_id, 
          date_create_document, 
          document_issued, 
          document_issued_date,
          document_number,
          document_serial, 
          document_type_id,
          document_validity_date,
          is_personal_identity,
          supplier_contact_id
      } = this.itemData

      this.localFormData = {
        is_editable: true,
        is_personal_identity,
        date_create_document,
        supplier_contact_id,
        counterparty_id,
        document_type_id,
        document_serial,
        document_number,
        document_issued_date: document_issued_date !== null ? this.formatDate(document_issued_date.slice(0, 10)) : null,
        document_validity_date: document_validity_date !== null ? this.formatDate(document_validity_date.slice(0, 10)) : null,
        document_issued
      }
      this.currentId = id
    }
  }
}
</script>

<style lang="sass">
  .gray-tooltip
    padding-top: 10px!important
    padding-bottom: 10px!important

  .modal-container-documents
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