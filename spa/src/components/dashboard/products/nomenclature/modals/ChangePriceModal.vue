<template>
  <div>
    <modal-container
        v-if="isOpen"
        :custom-dialog-class="['min-price-warning']"
        @clickOutside="onConfirm"
        :dialog="isOpen"
        :modalWidth="920"
    >
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t('nomenclature.updating') }}</span>
          </div>

          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="onConfirm">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                    fill="#BBDBFE"/>
              </svg>
            </v-btn>
          </div>
        </div>
      </template>
      <template v-slot:main>
        <div class="dialog-main">
          <div class="massage">
            {{ $t('nomenclature.enter_new_price_value') }}
          </div>
          <div class="input-wrapper">
            <input type="number" v-model="priceValue">
          </div>
        </div>
      </template>
      <template v-slot:footer>
        <div class="dialog-footer popup-buttons">
          <custom-btn
              custom-class="cancel"
              :title="$t('nomenclature.close')"
              color="#5893D4"
              @updateEvent="onConfirm"
              text
          ></custom-btn>
          <custom-btn
              :is-disabled="!priceValue"
              :title="$t('nomenclature.save')"
              custom-class="save"
              color="#5893D4"
              @updateEvent="onClickSave"
          ></custom-btn>

        </div>
      </template>
    </modal-container>
  </div>
</template>

<script>
import {mapActions} from 'vuex'
import ModalContainer from "../../../../ui/ModalContainer/ModalContainer";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";

export default {
  name: "ChangePriceModal",
  components: {CustomBtn, ModalContainer},
  data() {
    return {
      isConfirm: false,
      isChangeMinPrice: false,
      priceValue: null
    }
  },
  props: {
    isOpen: Boolean,
    data: Object,
  },
  methods: {
    ...mapActions({
      changePrice: 'changePrice'
    }),
    onClickChangeMinPrice() {
      this.isChangeMinPrice = !this.isChangeMinPrice
    },
    toConfirm() {
      this.isConfirm = true
    },
    onCloseModal() {
      this.toConfirm()
    },
    onCancel() {
      this.isConfirm = false
    },
    onClickSave () {
      const data = {
        id: this.data.id,
        value: this.priceValue
      }
      this.$emit('save', data)
    },
    onConfirm() {
      this.$emit('closeModal')
    },
    onOpenModal() {
      this.isModalOpen = true
    }
  }
}
</script>

<style scoped lang="scss">
.massage {
  margin-bottom: 22px;
}
.popup-buttons {
  display: flex;
  flex-direction: row;
  justify-content: center;
}
.input-wrapper {
  width: 100%;
  max-width: 250px;
  height: 32px;
  margin: 0 auto;
  input {
    background: #F4F9FF;
    /* 2 */
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    height: 100%;
    width: 100%;
    outline: none!important;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.03);
    border-radius: 100px;
    font-size: 13px;
    line-height: 13px;
    color: #9DCCFF;
    padding: 0 10px;
    &::-webkit-outer-spin-button,
    &::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

  }
}
</style>
