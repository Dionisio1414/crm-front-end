<template>
  <div class="dialog-header">
    <div>
      <span class="supplierTitle">{{title}} - {{ supplierTitle }}</span>
      <span class="supplierId">{{ supplierId }}</span>
    </div>
    <span class="supplierDate">{{ getData }}</span>
    <header-actions
        is-hide-dots
        is-hide-attach
        @updateClose="closeSupplierCard"
    ></header-actions>
  </div>
</template>

<script>
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";

export default {
 name: "supplierCardHeader",
  components: { HeaderActions },
  props: {
    supplierTitle: String,
    supplierId: [String, Number],
    supplierDate: String,
    title: String
  },
  computed: {
   getData() {
     return this.supplierDate  && this.supplierDate.slice(0 ,10);
   }
  },
  methods: {
    closeSupplierCard() {
      this.$emit('updateCloseSupplierCard', false)
    }
  }
}
</script>

<style scoped lang="scss">
  .supplierTitle {
    margin-right: 20px;
    font-weight: bold;
    font-size: 15px;
    line-height: 15px;
    letter-spacing: 0.03em;
    color: #FFFFFF;
  }

  .supplierId {
    font-size: 15px;
    line-height: 15px;
    color: #FFFFFF;
  }

  .supplierDate {
    font-size: 13px;
    line-height: 13px;
    color: #E3F0FF;
    margin: 0 20px 0 auto;
  }
</style>
