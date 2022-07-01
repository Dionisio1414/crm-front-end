<template>
  <modal-container
      :dialog="isConfirm"
      :modalWidth="920"
      custom-dialog-class="confirm"
  >
    <template v-slot:header>
      <div class="dialog-header">
        <div class="header-text">
          <span>{{ $t('confirm.close') }}</span>
        </div>
        <header-actions is-hide-attach is-hide-dots @updateClose="onCancel"></header-actions>
      </div>
    </template>
    <template v-slot:main>
      <div class="dialog-main">{{ $t('confirm.title') }}</div>
    </template>
    <template v-slot:footer>
      <div class="dialog-footer">
        <custom-btn
            custom-class="cancel"
            :title="$t('confirm.cancel')"
            color="#5893D4"
            @updateEvent="onCancel"
            text
        ></custom-btn>
        <custom-btn
            :title="$t('confirm.close')"
            custom-class="save"
            color="#5893D4"
            @updateEvent="onCloseModal"
        ></custom-btn>
      </div>
    </template>
  </modal-container>
</template>

<script>
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";

export default {
  name: "ConfirmModal",
  components: {
    ModalContainer,
    CustomBtn,
    HeaderActions
  },
  props: {
    isConfirm: Boolean
  },
  methods: {
    onCancel() {
      this.$emit('updateConfirmBack');
    },
    onCloseModal() {
      this.$emit('updateConfirmClose');
    }
  }
}
</script>

<style scoped lang="scss">
.confirm {
  .dialog-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
  }

  .dialog-main {
    text-align: center;
    font-size: 18px;
    line-height: 130%;
    color: #5893D4;
    padding-bottom: 0;
  }
}
</style>