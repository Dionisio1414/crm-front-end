<template>
  <v-menu
      v-model="isPopover"
      content-class="popover"
      :close-on-content-click="false"
      min-width="410"
      offset-y
      auto
      top
  >
    <template v-slot:activator="{ on, attrs }">
      <custom-btn
          :isDisabled="isPopover"
          :title="$t('page.wareHouses.stockModal.save')"
          custom-class="save"
          color="#5893D4"
          :attrs="attrs"
          :on="on"
      ></custom-btn>
    </template>
    <div class="dialog-actions popup-buttons">
      <custom-btn
          :title="$t('page.wareHouses.stockModal.save')"
          custom-class="save"
          color="#5893D4"
          :is-disabled="isEdit && isCapitalizedDocument"
          @updateEvent="onSaveDocument(1)"
      ></custom-btn>
      <custom-btn
          custom-class="orangeBtn"
          :title="$t('page.wareHouses.stockModal.post')"
          color="#FF9F2F"
          :isDisabled="isDisabled || !isCapitalizedDocument && (isEdit && isCapitalizedDocument)"
          @updateEvent="onSaveDocument((isEdit && isCapitalizedDocument) ? 1 : 0)"
      ></custom-btn>
    </div>
  </v-menu>
</template>

<script>
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";

export default {
  name: "PopoverMenu",
  components: {
    'custom-btn': CustomBtn
  },
  props: {
    isCapitalizedDocument: Boolean,
    isEdit: Boolean,
    isDisabled: {
      type: Boolean,
      default: false
    }
  },
  data: () => ({
    isPopover: false,
  }),
  methods: {
    onSaveDocument(point) {
      this.$emit('updateSaveDocument', point)
    }
  }
}
</script>

<style lang="scss">
  .popover {
    border: none !important;
    box-shadow: none !important;
    transform: translate(-100px, -60px);

    .customBtn {
      margin: 0 7px;
    }
  }
</style>