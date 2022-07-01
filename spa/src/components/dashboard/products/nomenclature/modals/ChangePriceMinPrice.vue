<template>
  <div>
    <modal-container
        custom-dialog-class="min-price-warning"
        v-if="isOpen && params"
        @clickOutside="onConfirm"
        :dialog="isOpen" :modalWidth="920"
    >
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t('nomenclature.min_price_warning') }}</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="onConfirm">
              <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
            </v-btn>
          </div>
        </div>
      </template>
      <template v-slot:main>
        <div class="dialog-main">
          <div class="massage">
            {{ getMessage }}.
          </div>
          <div class="actions">
                <span class="link" :class="{'active':isChangeMinPrice}" @click="onClickChangeMinPrice">
                   {{ isChangeMinPrice ? $t('nomenclature.newChange_min_price'): $t('nomenclature.change_min_price') }}
                </span>
            <div class="change-min-price-form" v-if="isChangeMinPrice">
              <input type="number" v-model="newMinPrice"> {{ params.currency }}
            </div>
          </div>
        </div>
      </template>
      <template v-slot:footer>
        <div class="dialog-footer popup-buttons">
          <custom-btn
              v-if="!isChangeMinPrice"
              custom-class="cancel"
              :title="$t('nomenclature.close')"
              color="#5893D4"
              @updateEvent="onConfirm"
              text
          ></custom-btn>
          <custom-btn
              v-else
              :title="$t('nomenclature.save')"
              custom-class="save"
              :is-disabled="!newMinPrice"
              color="#5893D4"
              @updateEvent="oChangeMinPrice"
          ></custom-btn>
        </div>
      </template>
    </modal-container>
    <modal-container @closeModal="onCloseModal" :dialog="isConfirm" :modalWidth="600">
      <template v-slot:main>
        <div class="dialog-main">

        </div>
      </template>
      <template v-slot:footer>
        <div class="dialog-footer">
          <button @click="onCancel">cancel</button>
          <button @click="onConfirm">confirm</button>
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
  name: "ChangePriceMinPrice",
  components: {CustomBtn, ModalContainer},
  data() {
    return {
      isConfirm: false,
      isChangeMinPrice: false,
      newMinPrice: ''
    }
  },
  computed: {
    getMessage() {
      return `${this.params?.message} ${this.params?.minPrice} ${this.params?.currency.toLowerCase()}`;
    }
  },
  props: {
    isOpen: Boolean,
    params: Object,
  },
  methods: {
    ...mapActions({
      changeMinPrice: 'changeMinPrice'
    }),
    onClickChangeMinPrice() {
      this.isChangeMinPrice = true
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
    onConfirm() {
      this.isConfirm = false
      this.isChangeMinPrice = false
      this.$emit('closeModal')
    },
    async oChangeMinPrice() {
      await this.changeMinPrice({ value: this.newMinPrice, ...this.params.data })
      this.$emit('closeModal')
    },
    onOpenModal() {
      this.isModalOpen = true
    }
  },
  mounted() {
    this.newMinPrice = this.params.minPrice;
  }
}
</script>

<style scoped lang="scss">
.dialog-main {
  padding-bottom: 0 !important;
}

.link.active {
  text-decoration: none;
  color: #5893D4;
  font-weight: normal;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
