<template>
  <div class="dialog-wrap createWareHouses">
    <v-dialog
        v-model="isModal"
        max-width="1230px"
        class="dialog-large"
        @click:outside="onClose"
    >
      <div class="dialog createWareHousesBody">
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ getMainTitle }}</span>
          </div>
          <header-actions
              @updateClose="onClose"
              @onCloseModal="onClose"
              id="warehouse"
              :title="getMainTitle"
              is-hide-dots
          ></header-actions>
        </div>

        <div class="dialog-content dialog-content-large">
          <template v-if="requestError">
            <div class="error-block" :key="item.title" v-for="item in requestError.title">{{ item }}</div>
          </template>
          <form class="item-form">
            <v-row>
              <v-col style="padding-top: 0" cols="6">
                <v-row>
                  <field
                      cols="12"
                      :title="$t('page.name')"
                      :getCopy="getCopy"
                      customClassCol="pt0"
                      isRequired
                  >
                    <input
                        :type="$t('page.suppliers.modal.createSupplier.thirdForm.name')"
                        :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.enterTheName')"
                        :class="{'error-on-select':  $v.form.name.$error }"
                        @change="setName($event.target.value)"
                        v-model.trim="form.name"
                    >
                  </field>
                </v-row>
                <v-row class="relative">
                  <field
                      cols="6"
                      customClass="select fz"
                      :title="$t('page.wareHouses.createWareHouses.typeWarehouse')"
                  >
                    <v-select
                        class="select-switcher"
                        item-text="title"
                        item-value="id"
                        :items="types"
                        v-model="form.type"
                        return-object
                        dense
                    ></v-select>
                  </field>
                  <select-groups
                      customClass="item-name select"
                      :title="$t('page.suppliers.groups')"
                      :editGroupData="getGroupData"
                      :wareHouses="wareHouses"
                      :isClear="isClear"
                      @updateSelectIds="getListIds"
                      isGroups
                      isReducePopup
                  ></select-groups>
                  <span v-if="this.form.groups" @click="onClearNestedGroup" class="clear">{{$t('page.wareHouses.createGroupsWareHouses.reset')}}</span>
                </v-row>
                <v-row>
                  <field
                      cols="12"
                      customClass="select"
                      :title="$t('page.wareHouses.createWareHouses.responsible')"
                  >
                    <v-select
                        class="select-switcher"
                        :placeholder="$t('page.wareHouses.createWareHouses.chooseResponsible')"
                        item-text="full_name"
                        :menu-props="{ contentClass: 'defaultDropDown resp'}"
                        item-value="id"
                        :items="getManager"
                        v-model="form.responsible"
                        dense
                    ></v-select>
                  </field>
                </v-row>
              </v-col>
              <address-form
                  @updateAddressForm="omAddressWareHouse"
                  :types="{ type: 'suppliers', type_address: 'warehouse', type_list: 'countries' }"
                  :getCopyData="getCopyData"
                  :editData="editData"
                  customClass="pt0"
                  :title="$t('page.wareHouses.createWareHouses.addressWarehouse')"
              >
                <v-row>
                  <v-col cols="6">
                    <div class="item-name item-name_phone">
                      <div class="label-title">{{
                          $t('page.suppliers.modal.createSupplier.secondForm.phoneText')
                        }}
                      </div>
                        <vue-phone
                          v-bind="phoneSettings"
                          :valueField="form.phone"
                          @updateInput="inputHandler"
                        >
                        </vue-phone>
                    </div>
                  </v-col>
                </v-row>
              </address-form>
            </v-row>
          </form>
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel"
                :title="$t(`page.suppliers.modal.createGroups.${getCorrectText ? 'cancel': 'close'}`)"
                color="#5893D4"
                @updateEvent="onClose"
                text
            ></custom-btn>
            <custom-btn
                :title="$t('page.wareHouses.saveTable')"
                custom-class="save"
                :is-disabled="!form.name"
                color="#5893D4"
                @updateEvent="onWareHouse"
            ></custom-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// components
import Field from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/Field";
import AddressForm from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/components/AddressForm";
import SelectGroups from "@/components/dashboard/products/wareHouses/SelectGroups/SelectGroups";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import VuePhone from '@/components/ui/VuePhone/VuePhone';
// validate
import {required} from 'vuelidate/lib/validators';
import {TYPE_OF_WAREHOUSE} from "@/components/dashboard/products/wareHouses/helper";
import getAddressItem from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/helper/getAddressItem";
import {MODE_TYPES} from "@/constants/constants";
// helper
import getFlatMap from "@/helper/getFlatMap";
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "CreateWareHouse",
  components: {
    'address-form': AddressForm,
    'field': Field,
    'select-groups': SelectGroups,
    'header-actions': HeaderActions,
    'custom-btn': CustomBtn,
    VuePhone
  },
  props: {
    wareHouses: Array,
    isWareHouse: Boolean,
    editData: Object,
    groupTitle: String,
  },
  computed: {
    ...mapGetters(['getLists', 'requestError']),
    getCorrectText() {
      return this.groupTitle === MODE_TYPES.ADD ||
          this.groupTitle === MODE_TYPES.COPY
    },
    getPhone() {
      return this.getCopyData ? this.getCopyData.phone : ''
    },
    getManager() {
      return this.getLists('lists')['employees']
    },
    getGroupData() {
      if (this.editData) {
        const { warehouse_group_id } = this.editData;
        const formatWareHouses = getFlatMap(this.wareHouses);

        return getAddressItem(formatWareHouses, warehouse_group_id);
      }

      return null;
    },
    getCopy() {
      return this.groupTitle === MODE_TYPES.COPY;
    },
    getTransformData() {
      let { employee_id, phone, title, warehouse_group_id, warehouse_type_id } = this.editData;

      if (this.getCopy) {
        title = `${title} (${this.$t('page.wareHouses.stockModal.copy')})`
      }

      const responsible = getAddressItem(this.getManager, employee_id);
      const type = getAddressItem(TYPE_OF_WAREHOUSE, warehouse_type_id);

      return {
        name: title,
        phone: phone && phone[0],
        type,
        groups: warehouse_group_id,
        responsible,
        address: []
      }
    },
    getMainTitle() {
      if (this.groupTitle === MODE_TYPES.ADD) {
        return this.$t('page.wareHouses.createWareHouses.addWarehouse');
      }

      if (this.groupTitle === MODE_TYPES.COPY) {
        return this.$t('page.wareHouses.createWareHouses.copyWarehouse');
      }

      return this.$t('page.wareHouses.createWareHouses.editWarehouse');
    },
    getCopyData() {
      if (!this.editData) return null;

      console.log(this.editData)

      const { city_id: city, country_id: country, region_id: region, street, number_house: house } = this.editData;

      /*const city = city_id ? getAddressItem(this.getCities, city_id): {};
      const country = country_id ? getAddressItem(this.getCountries, country_id): {};
      const region = region_id ? getAddressItem(this.getRegions, region_id): {};*/


      return { city, country, region, street, house }
    }
  },
  data: () => ({
    isClear: '',
    isModal: false,
    codes: [
      {name: "+380"},
      {name: "+780"},
      {name: "+280"}
    ],
    types: TYPE_OF_WAREHOUSE,
    phoneCode: '',
    form: {
      name: '',
      phone: '',
      type: {title: 'Оптовый', id: 1},
      groups: null,
      responsible: '',
      address: []
    },
    phoneSettings: {
      defaultCountry: 'UA',
      preferredCountries: ['UA', 'RU'],
      customPhoneClass: ['phone-default'],
      isFlags: true
    }
  }),
  validations: {
    form: {
      name: {required}
    }
  },
  methods: {
    ...mapActions(['addWareHouse', 'editWareHouse']),
    onClearNestedGroup() {
      this.isClear = getUniqueId();
    },
    inputHandler(val) {
      this.form.phone = val
    },
    setName(value) {
      this.form.name = value
      this.$v.form.name.$touch()
    },
    getListIds({ warehouse_group_id }) {
      this.form.groups = warehouse_group_id ?? null;
    },
    onClose() {
      this.isModal = false;
      this.$emit('updateStateModal');
    },
    omAddressWareHouse(obj) {
      const { city: { id: city_id }, country: { id: country_id }, region: { id: region_id }, street = '', house: number_house } = obj;
      this.form.address = { city_id, country_id, region_id, street, number_house };
    },
    changeCodes(value) {
      this.phoneCode = value
    },
    async onWareHouse() {
      const { address, groups, name: title, phone, responsible, type } = this.form;
      const warehouse_group_id = groups;
      const warehouse_type_id = type && type.id;
      const employee_id = (typeof responsible === "number") ? responsible : responsible ? responsible.id: null;

      const values = {
        ...address,
        title,
        phone: phone && [phone],
        employee_id,
        warehouse_type_id,
        warehouse_group_id
      }

      if (this.groupTitle === MODE_TYPES.ADD || this.groupTitle === MODE_TYPES.COPY) {
        await this.addWareHouse(values);
      }

      if (this.groupTitle === MODE_TYPES.EDIT) {
        values.id = this.editData.id;
        await this.editWareHouse(values)
      }
    }
  },
  mounted() {
    if (this.editData) {
      this.form = this.getTransformData;
    }

    this.isModal = this.isWareHouse;
  }
}
</script>

<style lang="scss" scoped>
.relative {
  position: relative;
}

.clear {
  font-weight: 550;
  font-size: 13px;
  line-height: 13px;
  text-decoration: underline;
  color: #9DCCFF;
  cursor: pointer;
  padding: 8px 12px 0;
  position: absolute;
  bottom: 10px;
  right: 210px;
}

.pt0 {
  padding-top: 0;
}
.popup-buttons {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: center;
}

.error-block {
  font-size: 14px;
  margin-top: 10px;
  color: #FF9F2F;
}
</style>

<style lang="scss">
@import "createWareHouse";
</style>
