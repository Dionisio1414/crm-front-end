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
            <span>{{ $t('directories.editEmployeeText') }}</span>
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
            <div class="wrapper">
              <div class="step-tabs">
                <div 
                  class="step-tabs-item" 
                  :class="{'step-tabs-item--active': index === currentStep}" 
                  v-for="(val, index) in stepTabs" 
                  :key="index"
                >
                  <button type="button" class="base-btn" @click="goTo(index)">
                    {{ $t(val.title) }}
                  </button>
                </div>
              </div>
              <div class="forms-wrapper">
                <transition name="fade" mode="in-out">
                  <div class="form form-first" v-if="currentStep === 0">
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
                              <input 
                                  type="text" 
                                  class="field" 
                                  placeholder="Введите фамилию" 
                                  v-model="mainData.last_name"
                                  @keypress="isLetter"
                              >
                          </div>
                      </v-col>
                      <v-col cols="2">
                          <div class="label-title label-title--required">{{ $t('page.suppliers.modal.createSupplier.thirdForm.name') }}</div>
                          <div class="form-field">
                              <input 
                                  type="text" 
                                  class="field" 
                                  placeholder="Введите имя" 
                                  v-model="mainData.first_name"
                                  @keypress="isLetter"
                              >
                          </div>
                      </v-col>
                      <v-col cols="2"> 
                          <div class="label-title label-title--required">{{ $t('page.suppliers.modal.createSupplier.thirdForm.patronymic') }}</div>
                          <div class="form-field">
                              <input 
                                  type="text" 
                                  class="field" 
                                  placeholder="Введите отчество"
                                  v-model="mainData.middle_name"
                                  @keypress="isLetter"
                              >
                          </div>
                      </v-col>
                      <v-col cols="2">
                          <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.firstForm.dateOfBirthday') }}</div>
                          <div class="form-field">
                            <!-- 'field-error': !isValidDate && mainData.date_of_birth.length > 1 -->
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
                                  @change="onChangeMainSelect($event, 'mainData', 'sex_id')"
                                  :menu-props="{ contentClass: 'base-select'}"
                                  return-object
                              />
                          </div>
                      </v-col>
                      </v-row>
                      <v-row>
                          <v-col cols="6">
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
                                      @change="onChangeMainSelect($event, 'mainData', 'company_department_id', )"
                                      :menu-props="{ contentClass: 'base-select'}"
                                      return-object
                                  />
                              </div>
                          </v-col>
                          <v-col cols="6">
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
                                      @change="onChangeMainSelect($event, 'mainData', 'position_id')"
                                      :menu-props="{ contentClass: 'base-select'}"
                                      return-object
                                  />
                              </div>
                          </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="3">
                            <div class="label-title">{{ $t('user.dateReceipt') }}</div>
                            <div class="form-field">
                              <div class="calendar">
                                <calendar
                                  @updateDate="updateDate"
                                  :placeholder="$t('purchases.modal.enterTheDate')"
                                  :date-value="main.date_receipt"
                                  custom-class="default-calendar"
                                  date-key="date_receipt"
                                  icon-color="#9DCCFF"
                                  isCloseOnContentClick
                                  is-icon
                                />
                              </div>
                            </div>
                        </v-col>
                        <v-col cols="3">
                            <div class="label-title">{{ $t('user.dateDismissal') }}</div>
                            <div class="form-field">
                              <div class="calendar">
                                <calendar
                                  @updateDate="updateDate"
                                  :placeholder="$t('purchases.modal.enterTheDate')"
                                  :date-value="main.date_dismissal"
                                  custom-class="default-calendar"
                                  date-key="date_dismissal"
                                  icon-color="#9DCCFF"
                                  isCloseOnContentClick
                                  is-icon
                                />
                              </div>
                          </div>
                          <div class="checkbox">
                            <label class="checkbox-label">
                                <input type="checkbox" v-model="main.is_not_active">
                                <div class="checkbox-text">
                                    <div class="text">{{ $t('user.emloyeeNotActive') }}</div>
                                </div>
                            </label>
                          </div>
                        </v-col>
                        <v-col cols="3">
                          <div class="label-title">{{ $t('user.salary') }}</div>
                          <div class="form-field">
                            <input class="field" type="text" v-model="main.salary" placeholder="Оклад">
                          </div>
                        </v-col>
                        <v-col cols="3">
                          <div class="label-title">{{ $t('user.codeInn') }}</div>
                          <div class="form-field">
                            <input 
                              class="field" 
                              :class="{'field-error': validate.inn}"
                              type="text" 
                              v-model="main.inn" 
                              v-mask="'##########'"
                              placeholder="Код инн" 
                              @keypress="onValid($event, 'main', 'inn')"
                            >
                            <div class="form-field-helper" v-if="validate.inn">{{ $t('page.suppliers.modal.createSupplier.secondForm.error.isExist') }}</div>
                          </div>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12">
                          <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
                        </v-col>
                      </v-row>
                    </div>
                  </div>
                </transition>
                <transition name="fade" mode="in-out">
                  <div class="form form-second" v-if="currentStep === 1">
                    <div class="form-content">
                      <v-row>
                        <v-col cols="6">
                          <v-row>
                            <v-col cols="4">
                              <div class="label-title">{{ $t('user.mobilePhone') }}</div>
                              <vue-phone
                                  :defaultCountry="defaultPhoneSettings.defaultCountry"    
                                  :preferredCountries="defaultPhoneSettings.preferredCountries"
                                  :isRepeater="defaultPhoneSettings.isRepeater"
                                  :isFlags="true"
                                  :customPhoneClass="['phone-default']"
                                  :disabled="defaultPhoneSettings.disabledField"
                                  :repeaterValuesArray="defaultPhoneSettings.contactsValues"
                                  :valueField="contact.mobile_phone"
                                  phoneLabel="Введите номер"
                                  @updateInput="updateDefaultInput"
                              >
                              </vue-phone>
                            </v-col>
                            <v-col cols="8">
                              <div class="label-title">Email</div>
                              <div class="form-field">
                                <input 
                                  :class="{'field': true, 'field-error': $v.contact.email.$invalid}" 
                                  type="email" 
                                  placeholder="Введите почту" 
                                  v-model="contact.email"
                                >
                              </div>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="4">
                              <div class="label-title">{{ $t('user.workPhone') }}</div>
                              <vue-phone
                                  :defaultCountry="workPhoneSettings.defaultCountry"    
                                  :preferredCountries="workPhoneSettings.preferredCountries"
                                  :isRepeater="workPhoneSettings.isRepeater"
                                  :isFlags="true"
                                  :customPhoneClass="['phone-default']"
                                  :disabled="workPhoneSettings.disabledField"
                                  :repeaterValuesArray="workPhoneSettings.contactsValues"
                                  :valueField="contact.work_phone"
                                  phoneLabel="Введите номер"
                                  @updateInput="updateWorkInput"
                              >
                              </vue-phone>
                            </v-col>
                            <v-col cols="8">
                              <div class="label-title">Skype</div>
                              <div class="form-field">
                                <input class="field" type="text" placeholder="Введите свой скайп" v-model="contact.skype">
                              </div>
                            </v-col>
                          </v-row>
                          <v-row>
                            <v-col cols="4">
                              <div class="label-title">{{ $t('user.innerPhone') }}</div>
                              <vue-phone
                                  :defaultCountry="internalPhoneSettings.defaultCountry"    
                                  :preferredCountries="internalPhoneSettings.preferredCountries"
                                  :isRepeater="internalPhoneSettings.isRepeater"
                                  :isFlags="true"
                                  :customPhoneClass="['phone-default']"
                                  :disabled="internalPhoneSettings.disabledField"
                                  :repeaterValuesArray="internalPhoneSettings.contactsValues"
                                  :valueField="contact.internal_phone"
                                  phoneLabel="Введите номер"
                                  @updateInput="updateInternalInput"
                              >
                              </vue-phone>
                            </v-col>
                            <v-col cols="8">
                              <div class="label-title">Zoom</div>
                              <div class="form-field">
                                <input class="field" type="text" placeholder="Введите свой zoom" v-model="contact.zoom">
                              </div>
                            </v-col>
                          </v-row>
                        </v-col>
                        <v-col cols="6">
                          <residence-address
                            :addressData="residenceAddressData"
                            modeType="change"
                            @updateData="updateResidenceAddress"
                          >
                          </residence-address>
                          <div class="form-row">
                            <v-row>
                              <v-col cols="12">
                                <div class="checkbox">
                                    <label class="checkbox-label">
                                        <input 
                                          type="checkbox" 
                                          @change="doubleData" 
                                          v-model="contact.is_equal_residence_registration"
                                        >
                                        <div class="checkbox-text">
                                            <div class="text">{{ $t('user.registrationMatchLives') }}</div>
                                        </div>
                                    </label>
                                </div>
                              </v-col>
                            </v-row>
                          </div>
                          <place-residence
                            modeType="change"
                            :addressData="addressData"
                            @updateData="updatePlaceAddress"
                          >
                          </place-residence>
                        </v-col>
                      </v-row>
                    </div>
                  </div>
                </transition>
                <transition name="fade" mode="in-out">
                <div class="form form-third" v-if="currentStep === 2">
                  <div class="form-content">
                    <v-row>
                      <v-col cols="6">
                        <v-row>
                          <v-col cols="12">
                              <div class="label-title label-title--required">Документ</div>
                              <div class="repeater">
                                <div class="select-wrap">
                                    <v-select class="select-switcher"
                                        v-model="docs.document_type_id"
                                        label="Выберите документ"
                                        :items="transformArray"
                                        item-text="title"
                                        item-value="directory_id"
                                        solo
                                        dense
                                        hide-details
                                        :menu-props="{ contentClass: 'base-select'}"
                                        @change="onChangeMainSelect($event, 'docs', 'document_type_id')"
                                        return-object
                                    />
                                </div>
                                <button 
                                  v-if="mode == 'add'" 
                                  type="button" 
                                  class="add-value" 
                                  @click="addValue" 
                                  :disabled="$v.docs.$invalid || !isValidDateIssuedDate || !isValidDateValidity"
                                >
                                  <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z" stroke="#9DCCFF"></path><path data-v-24021d1c="" d="M16.1027 13.8732L20 13.8732L20 16.0765L16.1027 16.0765L16.1027 20L13.8973 20L13.8973 16.0765L10 16.0765L10 13.8732L13.8973 13.8732L13.8973 10L16.1027 10L16.1027 13.8732Z" fill="#9DCCFF"></path></svg>
                                </button>
                                <button 
                                  type="button" 
                                  v-else 
                                  @click="saveValue"
                                >
                                  <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M30 15C30 23.2843 23.2843 30 15 30C6.71573 30 2.49446e-06 23.2843 3.21869e-06 15C3.94292e-06 6.71573 6.71573 -2.03558e-06 15 -1.31134e-06C23.2843 -5.87108e-07 30 6.71573 30 15Z" fill="#9DCCFF"/>
                                    <path d="M19.0424 10L12.928 16.1041L10.9449 14.1244L9 16.066L10.983 18.0457L12.9407 20L14.8856 18.0584L21 11.9543L19.0424 10Z" fill="white"/>
                                  </svg>
                                </button>
                              </div>
                          </v-col>
                          <v-col cols="12">
                            <div class="form-field">
                              <div class="label-title label-title--required">Серия / Номер</div>
                              <input 
                                class="field" 
                                type="text" 
                                placeholder="Введите серию/номер документа" 
                                v-model="docs.document_number"
                              >
                            </div>
                          </v-col>
                          <v-col cols="6">
                              <div class="form-field">
                                <div class="label-title">Дата выдачи</div>
                                <masked-input 
                                  class="field"
                                  :class="{'field-error': !isValidDateIssuedDate}"
                                  mask="11.11.1111" 
                                  placeholder="ДД/ММ/ГГГГ"
                                  v-model="docs.document_issued_date"
                                />
                              </div>
                          </v-col>
                          <v-col cols="6">
                            <div class="form-field">
                              <div class="label-title">Срок действия</div>
                              <masked-input 
                                class="field"
                                mask="11.11.1111" 
                                placeholder="ДД/ММ/ГГГГ"
                                :class="{'field-error': !isValidDateValidity}"
                                v-model="docs.document_validity_date"
                              />
                            </div>
                          </v-col>
                          <v-col cols="12">
                            <div class="form-field">
                              <div class="label-title">Кем выдан</div>
                              <input 
                                class="field" 
                                type="text" 
                                placeholder="Кем выдан" 
                                v-model="docs.document_issued"
                              >
                            </div>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12">
                            <div class="checkbox">
                              <label class="checkbox-label">
                                  <input 
                                    type="checkbox" 
                                    class="field"
                                    v-model="docs.is_personal_identity"
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
                          <v-col cols="6">
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
                                  @updateDate="updateDocsDate"
                                  :placeholder="$t('purchases.modal.enterTheDate')"
                                  :date-value="docs.date_create_document"
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
                      </v-col>
                      <v-col cols="6">
                        <div class="values-list">
                          <div class="value-item" v-for="(val, index) in documents" :key="index" :class="{'value-item--editable': editableElemIndex === index}">
                            <span>
                              <template v-for="(item, key) in val">
                                <template v-if="key === 'document_type_id'">
                                  <template v-for="doc in transformArray">
                                    <template v-if="doc.directory_id === val.document_type_id">
                                      {{ doc.title }}
                                    </template>
                                  </template>
                                </template>
                                <template v-else-if="key === 'is_personal_identity'">
                                  {{ '' }}
                                </template>
                                <template v-else-if="key === 'date_create_document'">
                                  {{ formatDates( String(item) ) }}
                                </template>
                                <template v-else>
                                  {{ item }}
                                </template>
                              </template>
                            </span>
                            <svg @click="editValue(index)" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg" class="edit"><path d="M16 3.77778L13.2222 1L2.11111 12.1111L1 16L4.88889 14.8889L16 3.77778ZM11 3.22222L13.7778 6L11 3.22222ZM2.11111 12.1111L4.88889 14.8889L2.11111 12.1111Z" stroke="#BBDBFE" stroke-width="1.39565" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            <svg @click="deleteValue(index)" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg" class="close"><path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"></path></svg>
                          </div>
                        </div>
                      </v-col>
                    </v-row>
                  </div>
                </div>
              </transition>
              </div>
              <div class="form-actions">
                <button 
                  type="button" 
                  class="base-button base-button--transparent" 
                  v-if="currentStep === 0" 
                  :disabled="$v.mainData.$invalid || !isValidDates || loader"
                  @click="cancel"
                >
                  {{ $t('page.close') }}
                </button>
                <button 
                  type="button" 
                  class="base-button base-button--transparent" 
                  v-if="currentStep > 0" 
                  :disabled="$v.mainData.$invalid || !isValidDates || loader || !isValidDateIssuedDate || !isValidDateValidity"
                  @click="prev"
                >
                    Назад
                  </button>
                <button 
                  type="button" 
                  class="base-button base-button--blue"
                  @click="next" 
                  v-if="currentStep !== stepTabs.length - 1"
                  :disabled="$v.mainData.$invalid || !isValidDates || loader"
                >
                  {{ $t('page.suppliers.modal.createSupplier.next') }}
                </button>
                <button 
                  type="button" 
                  class="base-button base-button--blue" 
                  v-if="currentStep === stepTabs.length - 1" 
                  :disabled="$v.mainData.$invalid || !isValidDates || loader || !isValidDateIssuedDate || !isValidDateValidity"
                  @click="saveEmployees"
                >
                  {{ $t('page.saveButton') }}
                </button>
              </div>
            </div>
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
import { required, email } from 'vuelidate/lib/validators'
import MaskedInput from 'vue-masked-input'
import moment from 'moment'
import debounce from 'lodash.debounce'
import FilesManager from '@/components/ui/FilesManager/FilesManager'
import VuePhone from '@/components/ui/VuePhone/VuePhone'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"
import Calendar from "@/components/ui/Calendar/Calendar"
import ResidenceAddress from '../create/components/ResidenceAddress'
import PlaceResidence from '../create/components/PlaceResidence'
import inverseDate from "@/helper/inverseDate"
import formatDate from "@/helper/formatDate"
import SvgSprite from '@/components/ui/SvgSprite/SvgSprite'

export default {
  name: "ModalEditEmployee",
  mixins: [validationMixin],
  props: { isModalOpen: Boolean, itemData: Object },
  components: {
    MaskedInput,
    FilesManager,
    VuePhone,
    ModalContainer,
    Calendar,
    ResidenceAddress,
    PlaceResidence,
    SvgSprite
  },
  validations: {
    contact: {
      email: { email }
    },
    docs: {
      document_type_id: { required },
      document_number: { required },
    },
    mainData: {
      first_name: { required },
      last_name: { required },
      middle_name: { required },
    }
  },
  data: () => ({
    residenceAddressData: {
      residence_country_id: null,
      residence_region_id: null,
      residence_city_id: null,
      residence_street: null,
      residence_number_house: null,
    },
    addressData: {
      registration_country_id: null,
      registration_region_id: null,
      registration_city_id: null,
      registration_street: null,
      registration_number_house: null
    },
    validate: {
      inn: false,
      email: false,
    },
    currentId: null,
    dialog: false,
    currentStep: 0,
    isOpenCalendarReceipt: false,
    isOpenCalendarDismissal: false,
    isOpenCalendarIssued: false,
    isOpenCalendarValidity: false,
    mode: 'add',
    mode_type: 'create',
    editableElemIndex: null,
    isValid: true,
    phoneStatus: false,
    defaultCountry: 'UA',
    isOpen: false,
    loader: false,
    isValidDates: true,
    isValidDateIssuedDate: true,
    isValidDateValidity: true,
    stepTabs: [
      { title: 'user.stepsModal.mainData' },
      { title: 'user.stepsModal.contactData' },
      { title: 'user.stepsModal.documentsData' },
    ],
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
      is_user: false,
      is_employee: true,
    },
    defaultDocObject: {
      document_type_id: null,
      document_number: null,
      document_issued_date: null,
      document_validity_date: null,
      document_issued: null,
      is_personal_identity: false,
      date_create_document: null
    },
    docs: {
      document_type_id: null,
      document_number: null,
      document_issued_date: null,
      document_validity_date: null,
      document_issued: null,
      is_personal_identity: false,
      date_create_document: null
    },
    main: {
      inn: "",
      salary: "",
      date_receipt: "",
      date_dismissal: "",
      is_not_active: false,
    },
    contact: {
      mobile_phone: null,
      work_phone: null,
      internal_phone: null,
      email: null,
      skype: null,
      zoom: null,
      residence_country_id: null,
      residence_region_id: null,
      residence_city_id: null,
      residence_street: null,
      residence_number_house: null,
      is_equal_residence_registration: false,
      registration_country_id: null,
      registration_region_id: null,
      registration_city_id: null,
      registration_street: null,
      registration_number_house: null
    },
    documents: [],
    currentDocID: null,
    localFormData: {
      codes: [
        { name: "+380" },
        { name: "+780" },
        { name: "+280" }
      ],
      countries: [
          { name: "Украина", id: 1 },
          { name: "Польша", id: 2 },
          { name: "Германия", id: 3 }
      ],
      regions: [
          { name: "Харьковская область", id: 1 },
          { name: "Запорожская область", id: 2 },
          { name: "Киевская область", id: 3 }
      ],
      cities: [
          { name: "Харьков", id: 1 },
          { name: "Киев", id: 2 },
          { name: "Запорожье", id: 3 }
      ],
      docs: [
        { name: 'Паспорт гражданина Украины', id: 1 },
        { name: 'Военный билет', id: 2 },
        { name: 'Свидетельство о рождении', id: 3 },
        { name: 'Водительское удостоверение', id: 4 },
      ]
    },
    defaultPhoneSettings: {
      defaultCountry: 'UA',
      preferredCountries: ['UA', 'RU'],
      isRepeater: false,
      disabledField: false,
    },
    workPhoneSettings: {
      defaultCountry: 'UA',
      preferredCountries: ['UA', 'RU'],
      isRepeater: false,
      disabledField: false,
    },
    internalPhoneSettings: {
      defaultCountry: 'UA',
      preferredCountries: ['UA', 'RU'],
      isRepeater: false,
      disabledField: false,
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
    },
    'computedDateIssuedDate': function(val) {
      if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
        this.isValidDateIssuedDate = false
      } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
        this.isValidDateIssuedDate = true
      } else {
        this.isValidDateIssuedDate = true
      }
    },
    'computedDateValidity': function(val) {
      if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
        this.isValidDateValidity = false
      } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
        this.isValidDateValidity = true
      } else {
        this.isValidDateValidity = true
      }
    },
  },
  computed: {
    ...mapGetters(['getLists', 'list']),
    computedDateIssuedDate() { return inverseDate(this.docs.document_issued_date) },
    computedDateValidity() { return inverseDate(this.docs.document_validity_date) },
    documentsList() { return this.getLists('core_lists')['type_documents'] },
    transformBirthDate() { return inverseDate(this.mainData.date_of_birth) },
    positionsList() { return this.getLists('lists')['positions'] },
    sexList() { return this.getLists('core_lists')['sex'] },
    departmentsList() { return this.list('companies_departments') },
    isValidDate() { return moment(this.transformBirthDate, 'YYYY-MM-DD', true).isValid() },
    transformArray() {
      const cloneDocumentsList = this.documentsList.map(item => ({...item}))
      cloneDocumentsList.forEach(item => item.disabled = false)
      cloneDocumentsList.forEach(item => {
        this.documents.forEach(child => {
            if(child.document_type_id === item.directory_id) {
                item.disabled = true
            } 
        })
      })
      return cloneDocumentsList
    },
  },
  methods: {
    ...mapActions([
      'employeesAsyncValidation',
      'changeClassifiersItem'
    ]),
    updateResidenceAddress(val) { this.contact = { ...this.contact, ...val } },
    updatePlaceAddress(val) { this.contact = { ...this.contact, ...val }},
    updateDate({ date, key }) { this.main[key] = date },
    updateDefaultInput(val) { this.contact.mobile_phone = val },
    updateDocsDate({ date, key }) { this.docs[key] = date },
    updateWorkInput(val) { this.contact.work_phone = val },
    updateInternalInput(val) { this.contact.internal_phone = val },
    filesManagerHandler() { this.isOpen = true },
    closeFilesManager() { this.isOpen = false },
    isLetter(e) {
        let char = String.fromCharCode(e.keyCode)
        if(/^[А-Яа-яёA-Za-z\s]+$/.test(char)) return true
        else e.preventDefault()
    },
    formatDates(val) { 
      const date = val ? val.slice(0, 10) : null
      const result = formatDate(date) 
      console.log(result)
      return result 
    },
    onCloseModal() { (!this.loader) && this.$emit('close') },
    cancel() { this.onCloseModal() },
    onValid: debounce(function(e, part, field) {
      console.log('WORK!')
      const value = this[part][field]
      const self = this
      this.employeesAsyncValidation({ 
        type: 'employees',
        obj: {
          [field]: value 
        }
      }).then(response => {
        if(response.message) self.validate[field] = true
        else self.validate[field] = false
      })
    }, 500),
    doubleData(e) {
      if(e.target.checked) {
        this.contact = {
          ...this.contact,
          registration_city_id: this.contact.residence_city_id,
          registration_country_id: this.contact.residence_country_id,
          registration_region_id: this.contact.residence_region_id,
          registration_street: this.contact.residence_street,
          registration_number_house: this.contact.residence_number_house,
        }
        const newObj = {
          registration_city_id: this.contact.residence_city_id,
          registration_country_id: this.contact.residence_country_id,
          registration_region_id: this.contact.residence_region_id,
          registration_street: this.contact.residence_street,
          registration_number_house: this.contact.residence_number_house,
        }
        this.addressData = newObj 
      } else {
        this.contact = {
          ...this.contact,
          registration_city_id: null,
          registration_country_id: null,
          registration_region_id: null,
          registration_street: null,
          registration_number_house: null,
        }
      }
    },
    addValue() {
      const { 
        document_type_id, 
        document_number, 
        document_issued_date, 
        document_validity_date, 
        document_issued, 
        date_create_document,
        is_personal_identity 
      } = this.docs
      // const currentDoc = this.localFormData.docs.find(item => item.id === document_type_id)
      // this.currentDocID = currentDoc
      this.documents.push({
        document_type_id,
        document_number,
        document_issued_date,
        document_validity_date,
        document_issued,
        is_personal_identity,
        date_create_document
      })
      this.docs = { ...this.defaultDocObject }
    },
    editValue(index) {
      this.editableElemIndex = index
      this.mode = 'edit'
      const editableElementIndex = this.documents.findIndex((item, i) => i === index)
      const currentDocument = this.documents[editableElementIndex]
      
      console.log('currentDocument', currentDocument)

      this.docs = { ...currentDocument }

      this.docs = {
        document_type_id: this.documents[editableElementIndex].document_type_id,
        document_number: this.documents[editableElementIndex].document_number,
        document_issued_date: this.documents[editableElementIndex].document_issued_date,
        document_validity_date: this.documents[editableElementIndex].document_validity_date,
        document_issued: this.documents[editableElementIndex].document_issued,
        is_personal_identity: this.documents[editableElementIndex].is_personal_identity,
        date_create_document: this.documents[editableElementIndex].date_create_document
      }

      // this.docs = {
      //   directory_document_id: this.documents[editableElementIndex].directory_document_id,
      //   serial: this.documents[editableElementIndex].serial,
      //   serial_number: this.documents[editableElementIndex].serial_number,
      //   passport_issued: this.documents[editableElementIndex].passport_issued,
      //   passport_issued_date: this.documents[editableElementIndex].passport_issued_date,
      //   validity_date: this.documents[editableElementIndex].validity_date,
      // }
    },
    deleteValue(index) {
      this.documents.splice(index, 1)
      this.editableElemIndex = null
      this.mode = 'add'
      this.docs = { ...this.defaultDocObject }
    },
    saveValue() {
      console.log('save', this.documents[this.editableElemIndex])
      this.documents[this.editableElemIndex] = {
        document_type_id: this.docs.document_type_id,
        document_number: this.docs.document_number,
        document_issued_date: this.docs.document_issued_date,
        document_validity_date: this.docs.document_validity_date,
        document_issued: this.docs.document_issued,
        is_personal_identity: this.docs.is_personal_identity,
        date_create_document: this.docs.date_create_document
      }
      this.documents = [ ...this.documents ]
      this.docs = { ...this.defaultDocObject }
      this.mode = 'add'
      this.editableElemIndex = null
    },
    updateDataAddress(index) {
      if(this.currentStep === index) {
        console.log('updateDataAddress')
        const addressDataObj = {
          registration_country_id: this.contact.registration_country_id,
          registration_region_id: this.contact.registration_region_id,
          registration_city_id: this.contact.registration_city_id,
          registration_street: this.contact.registration_street,
          registration_number_house: this.contact.registration_number_house
        }
        const residenceAddressDataObj = {
          residence_country_id: this.contact.residence_country_id,
          residence_region_id: this.contact.residence_region_id,
          residence_city_id: this.contact.residence_city_id,
          residence_street: this.contact.residence_street,
          residence_number_house: this.contact.residence_number_house,
        }
        this.addressData = addressDataObj
        this.residenceAddressData = residenceAddressDataObj
      }
    },
    goTo(index) { this.currentStep = index },
    next() { this.currentStep++ },
    prev() {
      if(this.currentStep === 0) this.$emit('checkStep')
      if(this.currentStep > 0) this.currentStep--
    },
    onChangeMainSelect(obj, part, property) {
      const idx = obj.id ? obj.id : obj.directory_id
      console.log(idx, part, property)
      this[part][property] = idx
    },
    saveEmployees() {
      const { is_user, is_employee } = this.mainData
      console.log(is_employee, is_user)

      this.documents.slice().forEach(item => {
        if(item.document_issued_date && item.document_issued_date !== null) item.document_issued_date = inverseDate(item.document_issued_date)
        if(item.document_validity_date && item.document_validity_date !== null) item.document_validity_date = inverseDate(item.document_validity_date)
      })

      const completeData = {
        ...this.mainData,
        date_of_birth: inverseDate(this.mainData.date_of_birth),
        main: { 
          ...this.main,
          salary: /\s/g.test(this.main.salary) ? this.main.salary.replace(/\s/g, '') : this.main.salary 
        },
        contact: this.contact,
        documents: [ ...this.documents ]
      }
      this.loader = true
      this.$emit('saveEditEmployees', { data: completeData, resources: 'employees', id: this.currentId  })
    },
    createEmployee() {
      const { is_user } = this.mainData
      const completeData = {
        ...this.mainData,
        date_of_birth: inverseDate(this.mainData.date_of_birth),
        main: this.main,
        contact: this.contact,
        documents: this.documents
      }
      console.log(completeData)
      this.$emit('createEmployee', completeData, is_user)
    },
    open(mode, itemData) {
      console.log('itemData', itemData)
      this.currentStep = 0
      this.currentId = itemData.id
      itemData.documents.forEach(item => {
        Object.assign(item, { document_issued_date: item.document_issued_date !== null ? formatDate(item.document_issued_date.slice(0, 10)) : null, document_validity_date: item.document_validity_date !== null ? formatDate(item.document_validity_date.slice(0, 10)) : null })
      })
      this.mode_type = mode
      console.log('itemData', itemData)
      this.mainData = { 
        thumbnail_id: itemData.thumbnail_id !== null ? itemData.thumbnail_id : null,
        thumbnail: itemData.thumbnail !== null ? itemData.thumbnail.url : null,
        first_name: itemData.first_name,
        last_name: itemData.last_name,
        middle_name: itemData.middle_name,
        date_of_birth: itemData.date_of_birth !== null ? formatDate(itemData.date_of_birth.slice(0, 10)) : null,
        sex_id: itemData.sex_id ? itemData.sex_id : 1,
        position_id: itemData.position_id,
        company_department_id: itemData.company_department_id
      }
      this.main = { 
        ...itemData.main,
        date_dismissal: itemData.main !== null && itemData.main.date_dismissal !== null ? itemData.main.date_dismissal.slice(0, 10) : null,
        date_receipt: itemData.main !== null && itemData.main.date_receipt !== null ? itemData.main.date_receipt.slice(0, 10) : null
      }
      this.contact = { ...itemData.contact }
      this.documents = [ ...itemData.documents ]
      this.addressData = {
        registration_country_id: this.contact.registration_country_id,
        registration_region_id: this.contact.registration_region_id,
        registration_city_id: this.contact.registration_city_id,
        registration_street: this.contact.registration_street,
        registration_number_house: this.contact.registration_number_house
      }
      this.residenceAddressData = {
        residence_country_id: this.contact.residence_country_id,
        residence_region_id: this.contact.residence_region_id,
        residence_city_id: this.contact.residence_city_id,
        residence_street: this.contact.residence_street,
        residence_number_house: this.contact.residence_number_house,
      }
    },
    getImage({ data, successUrl }) {
      this.mainData.thumbnail = successUrl
      this.mainData.thumbnail_id = data
    },
  },
  mounted() {
    this.currentStep = 0
    this.currentId = this.itemData.id
    this.itemData.documents.forEach(item => {
      Object.assign(item, { document_issued_date: item.document_issued_date !== null ? item.document_issued_date.slice(0, 10) : null, document_validity_date: item.document_validity_date !== null ? item.document_validity_date.slice(0, 10) : null })
    })
    
    this.mainData = { 
      thumbnail_id: this.itemData.thumbnail_id !== null ? this.itemData.thumbnail_id : null,
      thumbnail: this.itemData.thumbnail !== null ? this.itemData.thumbnail.url : null,
      first_name: this.itemData.first_name,
      last_name: this.itemData.last_name,
      middle_name: this.itemData.middle_name,
      date_of_birth: this.itemData.date_of_birth !== null ? formatDate(this.itemData.date_of_birth.slice(0, 10)) : null,
      sex_id: this.itemData.sex_id ? this.itemData.sex_id : 1,
      position_id: this.itemData.position_id,
      company_department_id: this.itemData.company_department_id
    }
    this.main = { 
      ...this.itemData.main,
      date_dismissal: this.itemData.main !== null && this.itemData.main.date_dismissal !== null ? this.itemData.main.date_dismissal.slice(0, 10) : null,
      date_receipt: this.itemData.main !== null && this.itemData.main.date_receipt !== null ? this.itemData.main.date_receipt.slice(0, 10) : null
    }
    this.contact = { ...this.itemData.contact }
    this.documents = [ ...this.itemData.documents ]
    this.addressData = {
      registration_country_id: this.contact.registration_country_id,
      registration_region_id: this.contact.registration_region_id,
      registration_city_id: this.contact.registration_city_id,
      registration_street: this.contact.registration_street,
      registration_number_house: this.contact.registration_number_house
    }
    this.residenceAddressData = {
      residence_country_id: this.contact.residence_country_id,
      residence_region_id: this.contact.residence_region_id,
      residence_city_id: this.contact.residence_city_id,
      residence_street: this.contact.residence_street,
      residence_number_house: this.contact.residence_number_house,
    }
  }
}
</script>

<style scoped lang="sass">

  .forms-wrapper
    position: relative
    margin-bottom: 40px
    .form-field
      + .checkbox
        margin-top: 15px
    .form
      &.form-first
        .form-content
          > .row
              &:not(:last-child)
                margin-bottom: 20px
      &.form-second
        .form-content
          > .row
              .col
                &:first-child
                  > .row
                      &:not(:last-child)
                        margin-bottom: 20px
                      .col-4
                        .label-title
                          margin-bottom: 6px
                &:last-child
                  > .form-row
                      .checkbox
                        margin-top: 10px
                      &:not(:last-child)
                        margin-bottom: 20px
      &.form-third
        .form-content
          > .row
              > .col
                  > .row
                      .col
                        margin-bottom: 20px
      
  .fade-enter-active, 
  .fade-leave-active
    transition: opacity .5s
  .fade-enter, 
  .fade-leave-to
    position: absolute
    opacity: 0 

  .wrapper
    width: 100%
    .step-tabs
      display: flex
      margin-bottom: 23px
      &-item
        &:not(:last-child)
          margin-right: 20px
        .base-btn
          background: transparent
          border: 1px solid #9DCCFF
          border-radius: 25px
          font-size: 14px
          line-height: 1
          min-height: 36px
          min-width: 220px
          color: #9DCCFF
          transition: .25s ease-in-out
        &--active, &:hover
          .base-btn
            background: #5893D4
            border-color: #5893D4
            color: #fff
    .item-form
      min-height: 0
    .values-list
      .value-item
        text-align: left
        max-width: 100%
        span
          max-width: 100%
          white-space: normal

.form-actions
  display: flex
  justify-content: center
  padding: 0 30px
  > .btn
      &:not(:last-child)
        margin-right: 30px
.btn
  background: #5893D4
  font-size: 13px
  line-height: 1
  font-weight: bold
  border: 1px solid #5893D4
  border-radius: 32px
  min-height: 40px
  min-width: 196px
  color: #fff
  &--transparent
    background: transparent
    color: #5893D4

.repeater
  display: flex
  justify-content: space-between
  align-items: center
  .select-wrap
    flex: 0 1 calc(100% - 45px)

</style>