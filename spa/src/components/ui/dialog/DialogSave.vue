<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialogConfirm"
        max-width="920px"
    >
      <div class="dialog">
        <div class="dialog-header">
          <slot name="dialog-header"></slot>
          <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="dialogConfirm = false">
            <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"/>
            </svg>
          </v-btn>
        </div>
        </div>
        <div class="dialog-content">
          <slot name="dialog-text"></slot>
          <div class="dialog-actions popup-buttons">
            <v-btn
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click.native="cancel"
            >
              Отменить
            </v-btn>
            <v-btn
                class="popup-btn"
                color="#5893D4"
                dark

                @click.native="agree"
            >
              Сохранить
            </v-btn>
          </div>
        </div>
    </v-dialog>
  </div>
</template>

<script>

export default {
  name: "Dialog",
  data: () => ({
    dialogConfirm: false,
    resolve: null,
    reject: null,
    message: null,
    title: null
  }),

  methods: {

    open(title, message) {
      this.dialogConfirm = true
      this.title = title
      this.message = message
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
  mounted() {

  }
}
</script>

<style lang="sass" scoped>

</style>