<template>
  <simplebar class="item-form supplierThirdForm create-new-item">
    <v-row>
      <v-col cols="6">
        <v-row>
          <v-col cols="12">
            <div class="item-name">
              <v-row>
                <field
                    cols="4"
                >
                  <input
                      type="text"
                      :class="{'error-on-input': false }"
                      v-model="form.surname"
                      :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.surname')"
                  >
                </field>
                <field
                    cols="4"
                >
                  <input
                      type="text"
                      :class="{'error-on-input': false }"
                      v-model="form.name"
                      :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.name')"
                  >
                </field>
                <field
                    cols="4"
                >
                  <input
                      type="text"
                      :class="{'error-on-input': false }"
                      v-model="form.patronymic"
                      :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.patronymic')"
                  >
                </field>
              </v-row>
            </div>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12">
            <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.thirdForm.position') }}</div>
            <v-autocomplete
                class="select-switcher"
                :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.selectPosition')"
                item-text="title"
                item-value="id"
                v-model="form.positionValue"
                :items="getPosition"
                :menu-props="{contentClass: 'currencyDropDown face'}"
                hide-selected
                return-object
                dense
            ></v-autocomplete>
          </v-col>
        </v-row>
        <v-row>
          <field
              cols="4"
              :title="$t('page.suppliers.modal.createSupplier.thirdForm.phoneNumber')"
              :isDataSafe="!!isDataSafe"
              :isExistTheSamePhone="isExistTheSamePhone"
          >
            <vue-single-phone
              :key="phoneComponentKey"
              :phone="form.mobile_phone"
              :defaultCountry="phoneSettings.defaultCountry"
              :placeholderInput="$t('page.suppliers.modal.createSupplier.thirdForm.placeholderPhoneNumber')"
              inputClass="wrapper"
              errorClass="wrapper-error-field"
              @input="inputHandler"
            >
            </vue-single-phone>
          </field>
          <field
              cols="8"
              title="Email"
          >
            <input
                :type="$t('page.suppliers.modal.createSupplier.thirdForm.email')"
                :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.emailPlaceholder')"
                :class="{'error-on-select':  $v.form.email.$error }"
                @change="setName($event.target.value)"
                v-model.trim="form.email"
            >
          </field>
        </v-row>
        <v-row>
          <field
              cols="4"
              :title="$t('page.suppliers.modal.createSupplier.thirdForm.dateTitle')"
          >
            <masked-input
                :class="{'field': true, 'error-on-select': !isValidDate && form.birthdayDay && form.birthdayDay.length}"
                v-model="form.birthdayDay"
                mask="11.11.1111"
                :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.formatDate')"
            />
          </field>
          <field
              cols="4"
              customClass="birthday"
              :title="$t('page.suppliers.modal.createSupplier.thirdForm.sex')"
          >
            <v-select
                class="select-switcher"
                item-text="title"
                :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.placeholderSex')"
                item-value="id"
                :items="getTypeOfSex"
                v-model="form.sexValue"
                :menu-props="{contentClass: 'currencyDropDown'}"
                return-object
                dense
            ></v-select>
          </field>
        </v-row>
        <v-row>
          <field
              cols="4"
              :title="$t('page.suppliers.modal.createSupplier.thirdForm.viewOfDocument')"
          >
            <v-select
                class="input_doc select-switcher"
                item-text="title"
                item-value="directory_id"
                :items="getDocument"
                v-model="form.documentValue"
                :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.selectDocument')"
                return-object
                dense
            ></v-select>
          </field>
          <field
              cols="8"
              :title="$t('page.suppliers.modal.createSupplier.thirdForm.number')"
          >
            <input
                type="text"
                :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.fillTheNumber')"
                v-model="form.number"
            >
          </field>
        </v-row>
        <v-row align="end">
          <field
              cols="6"
              :title="$t('page.suppliers.modal.createSupplier.thirdForm.sourceOfAttraction')"
          >
            <v-select
                class="select-switcher"
                item-text="title"
                item-value="directory_id"
                :items="getSourceOfAttraction"
                v-model="form.sourceOfAttractionValue"
                :placeholder="$t('page.suppliers.modal.createSupplier.thirdForm.placeholderSourceOfAttraction')"
                return-object
                dense
            ></v-select>
          </field>
          <form-btn
              v-if="isDataSafe"
              cols="4"
              @updateValue="onAddContactFace"
              :disabled="!isExistValue || isExistTheSamePhone"
              :title="$t('page.suppliers.modal.createSupplier.add')"
          ></form-btn>
          <form-btn
              v-else
              cols="4"
              @updateValue="onDataSafe"
              :title="$t('page.suppliers.modal.createSupplier.safe')"
          ></form-btn>
        </v-row>
      </v-col>

      <v-col class="contactFace" :class="{'itemExist': getContactFacesLength}" cols="6">
        <span class="notExitItems" v-if="!getContactFacesLength">
          {{ $t('page.suppliers.modal.createSupplier.thirdForm.error.text') }}
          <br>
          {{ $t('page.suppliers.modal.createSupplier.thirdForm.error.secondText') }}
        </span>
        <template v-else>
          <contact-face
              v-for="(item, idx) in contactFaces"
              :key="item.id"
              :idx="idx"
              :id="item.id"
              :name="item.name"
              :surname="item.surname"
              :patronymic="item.patronymic"
              :position="item.positionValue && item.positionValue.title"
              :mobilePhone="item.mobile_phone"
              :email="item.email"
              :sex="item.sexValue && item.sexValue.title"
              :document="item.documentValue && item.documentValue.title"
              :number="item.number"
              :sourceOfAttraction="item.sourceOfAttractionValue && item.sourceOfAttractionValue.title"
              :birthdayDay="item.birthdayDay"
              :isDataSafe="isDataSafe"
              @updateRemoveItem="removeItem"
              @updateEdit="editItem"
          ></contact-face>
        </template>
      </v-col>
    </v-row>
  </simplebar>
</template>

<script>
// vuex
import { mapGetters } from "vuex";
// moment
import moment from 'moment';
// components
import MaskedInput from "vue-masked-input";
import Field from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/Field";
import ContactFace from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/ContactFace";
import FormBtn from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/FormBtn";
// helper
import getUniqueId from "@/helper/getUniqueId";
import getAddressItem from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/helper/getAddressItem";
import {email} from 'vuelidate/lib/validators';
import inverseDate from "@/helper/inverseDate";
// Vue Phone
import VueSinglePhone from '@/components/ui/VueSinglePhone/VueSinglePhone';


export default {
  name: "SupplierFormThird",
  components: {
    MaskedInput,
    'field': Field,
    'contact-face': ContactFace,
    'form-btn': FormBtn,
    // 'vue-phone': VuePhone,
    VueSinglePhone
  },
  props: {
    currentStep: Number,
    copyThirdTab: Array
  },
  validations: {
    form:{
      email:{
        email
      }
    }
  },
  computed: {
    ...mapGetters(['getLists']),
    getTypeOfSex() {
      return this.getLists('core_lists')['sex']
    },
    getDocument() {
      return this.getLists('core_lists')['type_documents']
    },
    getPosition() {
      return this.getLists('lists')['positions']
    },
    getSourceOfAttraction() {
      return this.getLists('core_lists')['source_attractions']
    },
    getCopyData() {
      return this.copyThirdTab.map((item) => {
        const {
          document_id,
          sex_id,
          position_id,
          source_attractions_id,
          surname,
          name,
          patronymic,
          mobile_phone,
          email,
          number,
          birthdayDay,
        } = item;

        const documentValue = document_id ? getAddressItem(this.getDocument, document_id): {};
        const positionValue = position_id ? getAddressItem(this.getPosition, position_id): {};
        const sourceOfAttractionValue = source_attractions_id ? getAddressItem(this.getSourceOfAttraction, source_attractions_id): {};
        const sexValue = sex_id ? getAddressItem(this.getTypeOfSex, sex_id): {};
        const id = getUniqueId();

        return {
          documentValue,
          positionValue,
          sourceOfAttractionValue,
          sexValue,
          id,
          surname: surname ?? '',
          name: name ?? '',
          patronymic: patronymic ?? '',
          mobile_phone,
          email,
          number,
          birthdayDay,
        }
      })
    },
    getContactFacesLength() {
      return this.contactFaces.length;
    },
    isValidDate() {
      const formatFate = inverseDate(this.form.birthdayDay);
      return moment(formatFate, 'YYYY-MM-DD', true).isValid()
    }
  },
  mounted() {
    if (this.copyThirdTab) {
      this.contactFaces = this.getCopyData;
    }
  },
  beforeUpdate() {
    this.getExistValue();
    this.getExistTheSamePhone();
  },
  deactivated() {
    const contacts = this.contactFaces.map((item) => {
      const {
        birthdayDay,
        documentValue: { directory_id: document_id },
        email,
        mobile_phone: phone,
        name: first_name,
        surname: middle_name,
        patronymic: last_name,
        number: number_document,
        positionValue: { id: position_id },
        sexValue: { directory_id: sex_id },
        sourceOfAttractionValue: { directory_id: source_attractions_id }
      } = item;

      let date_of_birth = this.inverseDate(birthdayDay);

      return {
        date_of_birth, document_id, email, phone, first_name, middle_name, last_name, number_document: +number_document, position_id, sex_id, source_attractions_id
      }
    });

    this.$emit('stepUpdated', { contacts });
  },
  data() {
   return {
     phoneComponentKey: 0,
     contactFaces: [],
     isExistValue: false,
     isExistTheSamePhone: false,
     isDataSafe: true,
     form: {
       surname: '',
       name: '',
       patronymic: '',
       positionValue: '',
       mobile_phone: '',
       email: '',
       sexValue: null,
       documentValue: null,
       number: null,
       sourceOfAttractionValue: null,
       birthdayDay: ''
     },
     phoneSettings: {
      defaultCountry: 'ua',
      preferredCountries: ['UA', 'RU'],
      customPhoneClass: ['phone-default', 'without-select'],
      isFlags: true
     }
   }
  },
  methods: {
    inputHandler(val) {
      this.form.mobile_phone = val.phone
    },
    inverseDate(date) {
      if(!date) return null
      const [day, month, year] = date.split('.')
      return `${year}-${month}-${day}`
    },
    setName(value) {
      this.form.email = value
      this.$v.form.email.$touch()
    },
    resetForm() {
      this.form = {
        surname: '',
        name: '',
        patronymic: '',
        positionValue: '',
        mobile_phone: '',
        email: '',
        sexValue: '',
        documentValue: null,
        number: null,
        sourceOfAttractionValue: '',
        birthdayDay: ''
      }
    },
    getExistValue() {
      this.isExistValue = Object.values(this.form).some((item) => !!item);
    },
    getExistTheSamePhone() {
      this.isExistTheSamePhone = this.contactFaces.some((item) => item.mobile_phone === this.form.mobile_phone && !!item.mobile_phone);
    },
    onAddContactFace() {
      if (this.isExistValue && !this.isExistTheSamePhone) {
        const id = getUniqueId();

        this.contactFaces.push({ ...this.form, id });
      }

      this.resetForm();
      this.phoneComponentKey += 1;
    },
    getFilteredContactFaces(idx) {
      return this.contactFaces.filter((item, index) => index !== idx);
    },
    getEditData(id) {
      return this.contactFaces.find((item) => item.id === id);
    },
    removeItem(idx) {
      this.contactFaces = this.getFilteredContactFaces(idx);
      this.phoneComponentKey += 1;
    },
    onDataSafe() {
      this.isDataSafe = true;

      this.resetForm();

      this.phoneComponentKey += 1;
    },
    editItem(id) {
      this.isDataSafe = false;
      this.form = this.getEditData(id);
    }
  }
}
</script>

<style scoped lang="scss">
  .contactFace {
    border: 1px solid #F4F9FF;
    box-sizing: border-box;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #BBDBFE;

    .notExitItems {
      font-size: 20px;
      line-height: 20px;
      text-align: center;
    }

    &.itemExist {
      display: block;
      padding: 20px;
    }
  }
  .phone-default {
    width: 100%;
  }
</style>
<style lang="scss">
.wrapper input {
  min-width: initial !important;
  width: 100%;
}

.currencyDropDown.face.v-menu__content.theme--light.v-menu__content--fixed.menuable__content__active {
  margin-top: 1px !important;
}
.input_doc {
  margin-top: 0 !important;
}
</style>
