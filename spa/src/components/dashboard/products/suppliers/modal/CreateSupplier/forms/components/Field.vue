<template>
  <v-col :cols="cols" :class="customClassCol">
    <div class="item-name" :class="customClass">
      <div v-if="title" class="label-title">
        <span :class="{'required': isRequired}">{{title}}</span>
        <span v-if="isExistTheSamePhone && isDataSafe" class="error-text">Уже существует</span>
        <span v-if="getCopy" class="copy">Измените наименование</span>
        <span class="copy" v-if="isValidInn">
          {{ $t('page.suppliers.modal.createSupplier.firstForm.error.innExist') }}
        </span>
      </div>
      <slot></slot>
    </div>
  </v-col>
</template>

<script>
export default {
  name: "field",
  props: {
    cols: String,
    title: String,
    customClass: String,
    customClassCol: String,
    isExistTheSamePhone: Boolean,
    isDataSafe: Boolean,
    getCopy: Boolean,
    isRequired: Boolean,
    isValidInn: Boolean
  }
}
</script>

<style scoped lang="scss">
  .pt0 {
    padding-top: 0 !important;
  }

  .fz .v-input {
    font-size: 14px;
    color: #7E7E7E;
  }

  .copy {
    font-weight: 550;
    font-size: 12px;
    line-height: 12px;
    text-align: right;
    color: #FF9F2F;
    text-transform: none;
  }

  .required:after {
    content: '*';
    color: #9DCCFF;
    margin-left: 2px;
  }

  .label-title {
    display: flex;
    justify-content: space-between;
  }

  .manager .label-title {
    margin-bottom: 0 !important;
    color: #317CCE;
  }

  .dialog-content .item-form .item-name.birthday .label-title {
    margin-bottom: 7px;
  }
</style>
