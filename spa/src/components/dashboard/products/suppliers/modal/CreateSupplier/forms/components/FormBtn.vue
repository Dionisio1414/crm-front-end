<template>
  <v-col :cols="cols" :offset="offset" align-self="end">
    <v-btn
        class="btn contactBtn"
        color="#9DCCFF"
        text
        @click="onHandler"
        :disabled="disabled"
    >
      {{ title }}
    </v-btn>
  </v-col>
</template>

<script>
export default {
  name: "FormBtn",
  props: {
    cols: {
      type: String,
      default: '6'
    },
    offset: {
      type: String,
      default: '2'
    },
    title: String,
    disabled: Boolean,
  },
  methods: {
    onHandler() {
      this.$emit('updateValue')
    }
  }
}
</script>

<style scoped lang="scss">
.btn.contactBtn {
  border: 1px solid #9DCCFF;
  font-size: 13px;
  line-height: 13px;
  text-transform: none;
  border-radius: 25px;
  background: #fff;
  width: 100%;
  max-width: 160px;
  color: #9DCCFF !important;
  letter-spacing: inherit;

  &:hover {
    background: #9DCCFF;
    color: #fff !important;
  }

  &:disabled {
    opacity: .6;
    background: #fff;
    color: #9DCCFF !important;
  }
}
</style>
