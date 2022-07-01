<template>
  <div class="dialog-wrap">
    <v-dialog
        :value="isSupplierCard"
        @click:outside="onSupplierCard(false)"
        max-width="920px"
        class="dialog-large"
    >
      <div class="dialog suppliersModal">
        <supplier-card-header
            :title="$t('page.suppliers.card.headerTitle')"
            :supplierTitle="getData && getData.title_supplier"
            :supplierId="getData && getData.id"
            :supplierDate="getData && getData.created_at"
            @updateCloseSupplierCard="onSupplierCard"
        ></supplier-card-header>
        <simplebar class="body">
          <group-buttons @updateBuy="onBuy"></group-buttons>
          <bread-crumbs v-if="getBreadCrumbs" :getBreadCrumbs="getBreadCrumbs"></bread-crumbs>
          <collapse-block
              :title="$t('page.suppliers.card.mainInfo')"
          >
            <v-card class="cardCollapse">
              <div class="nameCompany">
                {{ $t('page.suppliers.card.name') }}
                <span>({{ $t('page.suppliers.modal.createSupplier.firstForm.viewInDocument') }})</span>
              </div>
              <div class="text">{{ getData.title_company }}</div>
            </v-card>
            <info-block
                :infoData="getMainInfo"
            ></info-block>
          </collapse-block>
          <collapse-block
              :title="$t('page.suppliers.card.contactData')"
          >
            <info-block
                :info-data="contactData"
            ></info-block>
          </collapse-block>
          <collapse-block
              v-if="contactFaces.length"
              :title="$t('page.suppliers.card.contactFaces')"
          >
            <div class="contactFace">
              <div class="contactFaceItem" :key="item.first_name" v-for="item in contactFaces">
                <div class="title">
                  <span v-if="item.position">{{ item.position.title }}</span>
                </div>
                <div class="name">
                  {{ item.first_name }}  {{ item.middle_name }}  {{ item.last_name }}
                  <span v-if="item.sexValue" class="sex">({{ item.sexValue }})</span>
                </div>
                <div>
                  <table>
                    <tr>
                      <td v-if="item.date_of_birth">{{ item.date_of_birth }}</td>
                      <td v-if="item.document_id && item.number_document">
                        {{ item.document.title }}: {{ item.number_document }}
                      </td>
                    </tr>
                    <tr>
                      <td v-if="item.phone">{{ item.phone }}</td>
                      <td v-if="item.email">{{ item.email }}</td>
                    </tr>
                    <tr v-if="item.source_attractions_id">
                      <td>{{ $t('page.suppliers.modal.createSupplier.thirdForm.sourceOfAttraction') }}: </td>
                      <td>{{ item.attraction.title }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </collapse-block>
        </simplebar>
        <div class="dialog-actions popup-buttons">
          <custom-btn
              custom-class="cancel smallText"
              :title="$t('page.suppliers.modal.createGroups.close')"
              color="#5893D4"
              @updateEvent="onSupplierCard(false)"
              text
          ></custom-btn>
          <custom-btn
              custom-class="save smallText"
              :title="$t('page.contextMenu.edit')"
              color="#5893D4"
              @updateEvent="onEdit"
          ></custom-btn>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
// vuex
import {mapGetters} from "vuex";
// components
import SupplierCardHeader from "./components/SupplierCardHeader";
import GroupButtons from "./components/GroupButtons/GroupButtons";
import BreadCrumbs from "./components/BreadCrumbs";
import CollapseBlock from '../../CollapseBlock/CollapseBlock';
import InfoBlock from './components/InfoBlock/InfoBlock';
import getAddressItem from "@/components/dashboard/products/suppliers/modal/CreateSupplier/forms/helper/getAddressItem";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
// constants
import {MODE_TYPES} from "@/constants/constants";

export default {
 name: "SupplierCard",
  components: {
    "supplier-card-header": SupplierCardHeader,
    "group-buttons": GroupButtons,
    "bread-crumbs": BreadCrumbs,
    "collapse-block": CollapseBlock,
    "info-block": InfoBlock,
    'custom-btn': CustomBtn,
  },
  props:{
    supplierData: Object,
    isSupplierCard: Boolean,
    flatList: Array
  },
  computed: {
    ...mapGetters(['getLists']),
    cities() {
      return this.getLists('lists')['cities']
    },
    countries() {
      return this.getLists('lists')['countries']
    },
    getBreadCrumbs() {
      const { group_id } = this.supplierData;
      return group_id && this.getPathWay(this.flatList, group_id);
    },
   getData() {
     const {
       created_at,
       cells: { id, title_supplier, title_company },
       foreign_company,
     } = this.supplierData;

     const foreignCompany = foreign_company ? this.$t('page.suppliers.card.yes') : this.$t('page.suppliers.card.no');
     return  {
       id,
       title_supplier,
       created_at,
       title_company,
       foreignCompany
     };
   },
    getCurrency() {
      return this.getLists('lists')['currencies']
    },
    getPosition() {
      return this.getLists('lists')['positions']
    },
    getDocument() {
      return this.getLists('core_lists')['type_documents']
    },
    getSourceOfAttraction() {
      return this.getLists('core_lists')['source_attractions']
    },
    getMainInfo() {
      const {
        currency_id,
        cells : {
          actual_address_country,
          manager,
          foreign_company,
          partner_type,
          inn,
          edrpou
        }
      } = this.supplierData;
      const foreignCompany = foreign_company ? this.$t('page.suppliers.card.yes') : this.$t('page.suppliers.card.no');

      const currency = getAddressItem(this.getCurrency, currency_id);
      const sign = currency?.title;

      return  [
        {
          title: this.$t('page.suppliers.card.info.foreignCompany'),
          text: foreignCompany
        },
        {
          title: this.$t('page.suppliers.card.info.currency'),
          text: sign,
        },
        {
          title: this.$t('page.suppliers.card.info.manager'),
          text: manager,
          isHalf: true
        },
        {
          title: this.$t('page.suppliers.card.info.typeOfCounterparty'),
          text: partner_type
        },
        {
          title: this.$t('page.suppliers.card.info.country'),
          text: actual_address_country
        },
        {
          title: this.$t('page.suppliers.card.info.inn'),
          text: inn
        },
        {
          title: this.$t('page.suppliers.card.info.codeErdpo'),
          text: edrpou
        }
      ];
    },
    contactData() {
      const {
        phone,
        email,
        actual_address,
        legal_address_number_housing,
        legal_address_street,
        cells: {
          actual_address_country,
          actual_address_region,
          actual_address_city,
          legal_address_country,
          legal_address_region,
          legal_address_city,
          delivery_address_country,
          delivery_address_region,
          delivery_address_city,
          delivery_address_street
        }
      } = this.supplierData;

      const test = actual_address.map(item => item['individual_address']);

      /*const transformDeliveryAddress = deliveryAddress.map((item) => {
        const { city_id, country_id,number_housing: house, region_id, street, id } = item;
        const city = getAddressItem(this.getCities, city_id);
        const country = getAddressItem(this.getCountries, country_id);
        const region = getAddressItem(this.getRegions, region_id);

        return { city, country, region, street, house, id }
      })*/

      console.log(test)



      // getAddressItem(this.getCurrency, currency_id)



      const fullActualAddress =
          `${actual_address_country ?? ''}
           ${actual_address_region ?? ''}
           ${actual_address_city ?? ''}`;

      const fullDelAddress =
          `${delivery_address_country ?? ''}
           ${delivery_address_region ?? ''}
           ${delivery_address_city ?? ''}
           ${delivery_address_street ?? ''}`;

      const fullLegalAddress =
          `${legal_address_country ?? ''}
           ${legal_address_region ?? ''}
           ${legal_address_city ?? ''}
           ${legal_address_street ?? ''}
           ${legal_address_number_housing ?? ''}`


      return [{
        title: "Фактический адрес",
        text: fullActualAddress,
        isWide: true
      },{
        title: "Юридический адрес",
        text: fullLegalAddress,
        isWide: true
      },{
        title: "Адрес доставки",
        text: fullDelAddress,
        isWide: true
      },{
        title: "Номер телефона",
        text: phone,
        isWide: true,
        isPhone: true
      },{
        title: "Email",
        text: email,
        isWide: true,
        isPhone: true
      }]
    },
    contactFaces() {
      const {
        contacts
      } = this.supplierData;

      return contacts.map((item) => {
        const { position_id, sex_id, document_id } = item;
        const position = position_id ? getAddressItem(this.getPosition, position_id): {};
        const document = document_id ? getAddressItem(this.getDocument, document_id): {};
        const attraction = document_id ? getAddressItem(this.getSourceOfAttraction, document_id): {};
        const sexValue = sex_id ? 'М': 'Ж';

        return {...item, position, sexValue, document, attraction}
      })
    }
  },
  methods: {
    onBuy() {
      this.$emit('updatePurchasesItem')
    },
    onSupplierCard(value) {
      this.$emit('updateSupplierCard', value);
    },
    getCurrentCurrency(id) {
      return this.getCurrency.find(item => item.id === id);
    },
    onEdit() {
      const values = {
        val: this.supplierData, title: MODE_TYPES.EDIT
      }

      this.$emit('updateEdit', values);
      this.onSupplierCard(false);
    },
    getPathWay(array, id, pathList = []) {
      const currentItem = array.find((item) => item.id === id);
      if (!currentItem) return null;

      if (currentItem && currentItem.parent_id) {
        this.getPathWay(array, currentItem.parent_id, pathList);
      }

      pathList.push(currentItem);
      return pathList;
    }
  }
}
</script>

<style scoped lang="scss">
  .cardCollapse {
    box-shadow: none !important;
    background: #F4F9FF;
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    border-radius: 10px;
    width: 100%;
    padding: 20px;
    margin-bottom: 25px;

    .nameCompany {
      font-weight: bold;
      font-size: 13px;
      line-height: 13px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
      color: #317CCE;
      margin-bottom: 15px;

      span {
        color: #9DCCFF;
      }
    }

    .text {
      font-size: 14px;
      line-height: 14px;
      color: #7E7E7E;
      display: block;
    }
  }

  .contactFace {
    box-sizing: border-box;
    border-radius: 10px;
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
  .contactFaceItem {
    border-bottom: 1px solid #F4F9FF;
    margin-bottom: 20px;

    .edit-btn {
      display: flex;
      align-items: center;
      margin-left: auto;

      .btn {
        margin: 0 5px;
        display: inline-block;
      }
    }

    .title {
      font-size: 13px;
      line-height: 13px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
      color: #317CCE;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .name {
      font-weight: 600;
      font-size: 14px;
      line-height: 19px;
      color: #5893D4;
      margin-bottom: 10px;
    }

    td {
      font-size: 14px;
      line-height: 14px;
      color: #7E7E7E;
      padding: 5px 0;
    }
  }


  .body {
    background: #fff;
    padding-bottom: 30px;
    max-height: 500px;
  }

  .popup-buttons {
    display: flex;
    justify-content: center;
    padding: 30px;
    background: #fff;
    position: sticky;
    bottom: 0;
  }
</style>
