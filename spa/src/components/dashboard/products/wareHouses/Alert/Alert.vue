<template>
  <v-snackbar
      class="alertBlock"
      timeout="5000"
      v-model="snackbar"
  >
    <alert-components
        :getFullTitle="getFullTitle"
        :getExistWarehouse="getExistWarehouse"
        :isDocument="!!isDocument"
        @updateAlertValue="onHandler"
    ></alert-components>
    <template v-slot:action="{ attrs }">
      <v-btn
          text
          v-bind="attrs"
          @click="snackbar = false"
      >
        <svg-sprite style="color: rgb(88,147,212)" width="12" height="12" icon-id="closeWhite"></svg-sprite>
      </v-btn>
    </template>
    <v-overlay opacity="0.2" z-index="-1" :value="snackbar"></v-overlay>
  </v-snackbar>
</template>

<script>
// components
import AlertComponents from "./components/AlertComponent";
// constants
import {DOCUMENT_TYPES, MODE_TYPES} from "@/constants/constants";

export default {
 name: "Alert",
  components: {
    'alert-components': AlertComponents
  },
  props: {
    requestSuccess: [Object, String],
    groupTitle: String
  },
  computed: {
   isDocument() {
     return this.requestSuccess && this.requestSuccess['document_id']
   },
    getExistWarehouse() {
     return this.requestSuccess && this.requestSuccess['isWarehouse']
   },
    isRemoveItem() {
      return this.groupTitle === MODE_TYPES.REMOVE;
    },
    isMoveItem() {
      return  this.groupTitle === MODE_TYPES.MOVE
    },
    getCorrectTitle() {
     switch (this.groupTitle) {
       case MODE_TYPES.ADD:
         return this.$t('page.wareHouses.alert.added')
       case MODE_TYPES.EDIT:
         return this.$t('page.wareHouses.alert.changed')
       case MODE_TYPES.COPY:
         return this.$t('page.wareHouses.alert.copied')
       case MODE_TYPES.MOVE:
         return this.$t('page.wareHouses.alert.moved')
       case MODE_TYPES.REMOVE:
         return this.$t('page.wareHouses.alert.deleted')
       default:
         return ''
     }
    },
    getFullTitle() {
     if (!this.isDocument) {
       if (this.isRemoveItem || this.isMoveItem) {
         return `${this.getCorrectTitle} ${this.requestSuccess}`
       }

       if (this.getExistWarehouse) {
         return `${this.getCorrectTitle} ${this.$t('page.wareHouses.alert.warehouse')} "${this.requestSuccess.title}"`
       }

       if (!this.getExistWarehouse) {
         return `${this.getCorrectTitle} ${this.$t('page.wareHouses.alert.group')} "${this.requestSuccess.title}"`
       }
     } else {
       const { convert_id, type } = this.requestSuccess || {};
       if (type === DOCUMENT_TYPES.RECEIPT_STOCKS) {
         return `${this.$t('page.wareHouses.alert.saveDocument')} ${convert_id}'`
       }

       if (type === DOCUMENT_TYPES.WRITE_OF_STOCKS) {
         return `${this.$t('page.wareHouses.alert.saveDocumentWriteOf')} ${convert_id}'`
       }

       if (type === DOCUMENT_TYPES.TRANSFER_STOCKS) {
         return `${this.$t('page.wareHouses.alert.saveDocumentTransfer')} ${convert_id}'`
       }

       if (type === DOCUMENT_TYPES.PURCHASE) {
         return `${this.$t('page.wareHouses.alert.saveDocumentPurchases')} ${convert_id}'`
       }
     }

      return '';
    }
  },
  mounted() {
   this.snackbar = true;
  },
  data: () => ({
    snackbar: false,
  }),
  methods: {
    onHandler() {
      this.$emit('openInfoBlock', this.requestSuccess)
      this.snackbar = false;
    }
  }
}
</script>

<style lang="scss">
  .alertBlock {
    .v-snack__wrapper {
      background: #E3F0FF !important;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05) !important;
      border-radius: 30px !important;
      min-height: 35px !important;
      bottom: 130px;

      .v-snack__content {
        font-size: 15px;
        line-height: 15px;
        text-align: center;
        color: #5893D4;
      }

      .v-snack__action .v-btn {
        min-width: inherit;
      }
    }
  }
</style>
