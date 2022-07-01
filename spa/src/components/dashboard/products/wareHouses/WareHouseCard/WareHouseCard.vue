<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="isModal"
        max-width="920px"
        class="dialog-large"
        @click:outside="onClose"
    >
      <div class="dialog suppliersModal">
        <supplier-card-header
            title="Склад"
            :supplierTitle="getDataHeader.title"
            :supplierId="getDataHeader.id"
            :supplierDate="getDataHeader.created_at"
            @updateCloseSupplierCard="onWareHouseCard"
        ></supplier-card-header>
        <div class="body">
          <div class="breadcrumb" v-if="getBreadCrumbs && getBreadCrumbs.groupTitle">
            <span class="breadcrumbTitle">Группа</span>
            <div class="breadcrumbWr">
              <span>{{getBreadCrumbs.groupTitle}} <span class="caret">&#8250;</span><strong>{{ getBreadCrumbs.title }}</strong></span>
            </div>
          </div>
          <info-block
              :infoData="getMainInfo"
          ></info-block>
        </div>
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
              @click="onClose"
          >
            {{ $t('page.suppliers.modal.createGroups.cancel') }}
          </v-btn>
          <v-btn
              @click="onEdit"
              class="popup-btn"
              color="#5893D4"
              dark
          >
            {{ $t("page.contextMenu.edit") }}
          </v-btn>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import SupplierCardHeader from "@/components/dashboard/products/suppliers/modal/SupplierCard/components/SupplierCardHeader";
import InfoBlock from "@/components/dashboard/products/suppliers/modal/SupplierCard/components/InfoBlock/InfoBlock";
import {TYPE_OF_WAREHOUSE} from "@/components/dashboard/products/wareHouses/helper";


export default {
  name: "WareHouseCard",
  components: {
    'supplier-card-header': SupplierCardHeader,
    "info-block": InfoBlock
  },
  props: {
    requestSuccess: [Object, String],
    isWareHousesCard: Boolean,
    editData: Object,
    wareHouses: Array
  },
  mounted() {
    this.isModal = this.isWareHousesCard;
  },
  computed: {
    ...mapGetters(['getLists']),
    getDataHeader() {
      const { created_at, title, id } = this.editData || this.requestSuccess;

      return {created_at, title, id};
    },
    getCountries() {
      return this.getLists('lists')['countries']
    },
    getRegions() {
      return this.getLists('lists')['regions']
    },
    getCities() {
      return this.getLists('lists')['cities']
    },
    getManager() {
      return this.getLists('lists')['employees']
    },
    getAddress() {
      const {
        street,
        number_house,
        country_id,
        region_id,
        city_id,
      } = this.requestSuccess || this.editData;

      const country = this.getTitle(this.getCountries, country_id);
      const region = this.getTitle(this.getRegions, region_id);
      const city = this.getTitle(this.getCities, city_id);
      const streetSt = street ? street : '';
      const house = number_house ? number_house : '';

      return `${country} ${region} ${city} ${streetSt} ${house}`;

    },
    getBreadCrumbs() {
      const { warehouse_group_id, title } = this.editData || this.requestSuccess;

      const groupTitle = this.getTitle(this.wareHouses, warehouse_group_id);
      return {
        title, groupTitle
      };
    },
    getMainInfo() {
      if (this.editData || this.requestSuccess) {
        const { employee_id, warehouse_type_id, phone } = this.editData || this.requestSuccess;

        const employee = this.getTitle(this.getManager, employee_id);
        const typeWarehouse = warehouse_type_id ? TYPE_OF_WAREHOUSE.find(item => item.id === +warehouse_type_id) : '';

        return  [
          {
            title: "тип склада",
            text: typeWarehouse.title,
          },
          {
            title: 'ответственный',
            text: employee,
            isHalf: true
          },
          {
            title: 'Адрес склада',
            text: this.getAddress,
            isWide: true
          },
          {
            title: 'номер телефона',
            text: phone && phone[0],
            isWide: true
          }
        ];
      } else {
        return []
      }

    },
  },
  data: () => ({
    isModal: false
  }),
  methods: {
    onClose() {
      this.isModal = false;
      this.$emit('updateStateModal');
    },
    onEdit() {
      this.isModal = false;
      this.$emit('updateEditWareHouse', this.editData)
    },
    onWareHouseCard() {
      this.isModal = false;
    },
    getTitle(array, id) {
      return array.reduce((acc, item) => {
        if (item.id === +id) {
          acc = item.title || item['full_name'];
        }

        return acc;
      }, '');
    },
  }
}
</script>

<style scoped lang="scss">
  .breadcrumb {
    margin-bottom: 30px;

    &Wr {
      span {
        font-size: 15px;
        line-height: 15px;
        color: #7E7E7E;
      }

      span.caret {
        font-size: 24px;
        font-weight: normal;
        vertical-align: middle;
        display: inline-block;
        margin: 0 5px;
        color: #317CCE;
      }
    }

    &Title {
      font-weight: bold;
      font-size: 13px;
      line-height: 13px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
      color: #317CCE;
      margin-bottom: 15px;
      display: block;
    }
  }
  .body {
    padding: 30px;
    background: #fff;
  }
  .dialog-actions.popup-buttons {
    padding: 20px 30px;
    background: #fff;
    text-align: center;
  }
</style>
