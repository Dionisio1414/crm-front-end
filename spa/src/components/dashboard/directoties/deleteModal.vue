<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialogConfirm"
        max-width="920px"
    >
      <div class="dialog">
        <div class="dialog-header">
          <div class="header-text">
            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 7.14258H7.5V15.714H6V7.14258Z" fill="white"/>
              <path d="M10.5 7.14258H12V15.714H10.5V7.14258Z" fill="white"/>
              <path d="M0 2.85742V4.28598H1.5V18.5716C1.5 18.9505 1.65804 19.3139 1.93934 19.5818C2.22064 19.8497 2.60218 20.0002 3 20.0002H15C15.3978 20.0002 15.7794 19.8497 16.0607 19.5818C16.342 19.3139 16.5 18.9505 16.5 18.5716V4.28598H18V2.85742H0ZM3 18.5716V4.28598H15V18.5716H3Z" fill="white"/>
              <path d="M6 0H12V1.42856H6V0Z" fill="white"/>
            </svg>
            <span>{{ $t('page.contextMenu.remove') }}</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="dialogConfirm = false">
              <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"/>
              </svg>
            </v-btn>
          </div>
        </div>
        <div class="dialog-body">
          <div class="form">
            <div class="form-content">
              <div class="form-content-text">
                {{ $t('page.isConfirm') }} <strong>{{ $t('page.suppliers.modal.createSupplier.mainTitle.remove') }}</strong> {{ $t(`${options.directory}`) }} <strong>"{{ options.items }}"</strong> ?
              </div>
            </div>
            <div class="form-actions">
              <button
                type="button"
                class="button base-button base-button--transparent"
                @click="cancel"
              >
                {{ $t('page.cancelButton') }}
              </button>
              <button
                type="button"
                class="button base-button base-button--blue"
                @click="agree"
              >
                {{ $t('page.suppliers.modal.createSupplier.firstForm.yes') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>

export default {
  name: "deleteModal",
  data: () => ({
    dialogConfirm: false,
    dialogDone: false,
    resolve: null,
    reject: null,
    message: null,
    title: null,
    options: {
      directory: '',
      name: '',
      width: 290,
      zIndex: 200
    }
  }),

  methods: {

    open(options) {
      this.dialogConfirm = true
      this.options = Object.assign(this.options, options)
      return new Promise((resolve, reject) => {
        this.resolve = resolve
        this.reject = reject
      })
    },

    agree() {
      this.resolve(true)
      this.dialogConfirm = false
      this.dialogDone = true
    },

    cancel() {
      this.resolve(false)
      this.dialogConfirm = false
    },

    deleteConfirm() {
      this.$store.dispatch('deleteConfirm')
      this.dialogConfirm = !this.dialogConfirm
    }
  },
  mounted() {

  }
}
</script>

<style lang="sass" scoped>

</style>