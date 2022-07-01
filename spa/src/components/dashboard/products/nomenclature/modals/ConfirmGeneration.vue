<template>
  <v-dialog
      v-model="dialogConfirm"
      max-width="920px"
  >
    <div class="dialog">
      <div class="dialog-header">
        <div class="header-text">
          <span>Готово</span>
        </div>
        <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="dialogConfirm = false">
          <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z"
                fill="#BBDBFE"/>
          </svg>
        </v-btn>
      </div>
      <div class="dialog-content">
        <div class="dialog-text">
          Создана группа товаров
          <span class="text-uppercase" style="font-weight: 550"></span> {{ name }}
          сгенерировать вариации ?
        </div>
        <div class="dialog-actions popup-buttons">
          <v-btn
              class="popup-btn transparent-btn"
              color="#5893D4"
              text
              @click.native="agree"
          >
            Сгенерировать Вариции
          </v-btn>
          <v-btn
              class="popup-btn"
              color="#5893D4"
              dark

              @click.native="cancel"
          >
            Ок
          </v-btn>
        </div>
      </div>
    </div>
  </v-dialog>
</template>

<script>
export default {
  name: "ConfirmGeneration",
  data: () => ({
    dialogConfirm: false,
    resolve: null,
    reject: null,
    name: null
  }),
  methods: {
    generate() {
      this.$emit('generate')
    },
    open(name) {
      this.name = name
      this.dialogConfirm = true
      return new Promise((resolve, reject) => {
        this.resolve = resolve
        this.reject = reject
      })
    },

    agree() {
      this.resolve(true)
      this.dialogConfirm = false
    },

    cancel() {
      this.resolve(false)
      this.dialogConfirm = false
    }

  },
}
</script>

<style scoped>

</style>