<template>
  <div class="value-item">
    <span>{{ fullNumber }}</span>
    <button class="btn" @click="onEdit">
      <svg width="14" height="14" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M21.4696 4.78307L17.6789 1L2.51626 16.1323L1 21.4286L6.30692 19.9153L21.4696 4.78307ZM14.6464 4.02646L18.437 7.80952L14.6464 4.02646ZM2.51626 16.1323L6.30692 19.9153L2.51626 16.1323Z" stroke="#BBDBFE" stroke-width="1.39565" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <button v-if="isDataSafe" class="btn" @click="onRemoveItem">
      <svg width="14" height="14" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"/>
      </svg>
    </button>
  </div>
</template>

<script>
export default {
 name: "ValueItem",
  computed: {
   fullNumber() {
     return `+${this.countryCallingCode} ${this.phoneNumber}`
   }
  },
  props: {
    phoneNumber: String,
    countryCallingCode: String,
    countryCode: String,
    idx: Number,
    id: String,
    isDataSafe: Boolean
  },
  methods: {
    onRemoveItem() {
      this.$emit('updateRemoveItem', this.idx)
    },
    onEdit() {
      this.$emit('updateEdit', this.id)
    }
  }
}
</script>

<style scoped lang="scss">
.value-item {
  max-width: 100%;
  padding: 5px 15px;
  background: #E3F0FF;
  min-height: 29px;
  align-items: center;
  justify-content: center;
  color: #5893D4;
  border-radius: 10px;
  display: flex;
  text-align: center;
  font-size: 13px;
  font-weight: normal;
  margin: 0 10px 0 0;

  & + .value-item {
    margin-top: 10px;
  }
}
</style>