<template>
  <v-dialog
      v-model="dialogConfirm"
      max-width="920px"
  >
    <div class="dialog">
      <div class="dialog-header">
        <div class="header-text">
          <span class="icon">
            <svg width="28" height="20" viewBox="0 0 28 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <mask id="path-1-outside-1" maskUnits="userSpaceOnUse" x="0" y="0" width="28" height="20" fill="black">
              <rect fill="white" width="28" height="20"/>
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M21.35 8.04C21.0141 6.33772 20.0976 4.80486 18.7571 3.70325C17.4165 2.60163 15.7351 1.99961 14 2C11.11 2 8.6 3.64 7.35 6.04C5.88023 6.19883 4.52101 6.89521 3.53349 7.99532C2.54597 9.09543 1.99983 10.5217 2 12C2 15.31 4.69 18 8 18H21C23.76 18 26 15.76 26 13C26 10.36 23.95 8.22 21.35 8.04ZM16.8526 7L13.9912 9.8614L11.1474 7.01765L10.0177 8.14742L12.8614 10.9912L10 13.8526L11.1281 14.9807L13.9895 12.1193L16.8702 15L18 13.8702L15.1193 10.9895L17.9807 8.12814L16.8526 7Z"/>
              </mask>
              <path
                  d="M18.7571 3.70325L17.8047 4.86214L17.8047 4.86214L18.7571 3.70325ZM21.35 8.04L19.8784 8.33039L20.1007 9.4571L21.2464 9.53642L21.35 8.04ZM14 2V3.5L14.0003 3.5L14 2ZM7.35 6.04L7.51116 7.53132L8.30946 7.44505L8.68037 6.7329L7.35 6.04ZM3.53349 7.99532L2.41724 6.99332H2.41724L3.53349 7.99532ZM2 12H3.5V11.9998L2 12ZM13.9912 9.8614L12.9305 10.9221L13.9912 11.9827L15.0518 10.9221L13.9912 9.8614ZM16.8526 7L17.9132 5.93934L16.8526 4.87868L15.7919 5.93934L16.8526 7ZM11.1474 7.01765L12.2081 5.95699L11.1474 4.89633L10.0868 5.95699L11.1474 7.01765ZM10.0177 8.14742L8.95699 7.08676L7.89633 8.14742L8.95699 9.20809L10.0177 8.14742ZM12.8614 10.9912L13.9221 12.0518L14.9827 10.9912L13.9221 9.93051L12.8614 10.9912ZM10 13.8526L8.93934 12.7919L7.87868 13.8526L8.93934 14.9132L10 13.8526ZM11.1281 14.9807L10.0675 16.0414L11.1281 17.102L12.1888 16.0414L11.1281 14.9807ZM13.9895 12.1193L15.0502 11.0587L13.9895 9.998L12.9289 11.0587L13.9895 12.1193ZM16.8702 15L15.8096 16.0607L16.8702 17.1213L17.9309 16.0607L16.8702 15ZM18 13.8702L19.0607 14.9309L20.1213 13.8702L19.0607 12.8096L18 13.8702ZM15.1193 10.9895L14.0587 9.92889L12.998 10.9895L14.0587 12.0502L15.1193 10.9895ZM17.9807 8.12814L19.0414 9.1888L20.102 8.12814L19.0414 7.06748L17.9807 8.12814ZM17.8047 4.86214C18.8768 5.74314 19.6097 6.96902 19.8784 8.33039L22.8216 7.74961C22.4185 5.70642 21.3184 3.86658 19.7094 2.54435L17.8047 4.86214ZM14.0003 3.5C15.388 3.49969 16.7326 3.98115 17.8047 4.86214L19.7094 2.54435C18.1004 1.22212 16.0823 0.49953 13.9997 0.5L14.0003 3.5ZM8.68037 6.7329C9.68266 4.80851 11.6919 3.5 14 3.5V0.5C10.5281 0.5 7.51734 2.47149 6.01963 5.3471L8.68037 6.7329ZM4.64973 8.99732C5.39012 8.17251 6.4092 7.6504 7.51116 7.53132L7.18884 4.54868C5.35126 4.74726 3.65189 5.61791 2.41724 6.99332L4.64973 8.99732ZM3.5 11.9998C3.49987 10.8915 3.90934 9.82213 4.64973 8.99732L2.41724 6.99332C1.1826 8.36874 0.499785 10.1519 0.5 12.0002L3.5 11.9998ZM8 16.5C5.51843 16.5 3.5 14.4816 3.5 12H0.5C0.5 16.1384 3.86157 19.5 8 19.5V16.5ZM21 16.5H8V19.5H21V16.5ZM24.5 13C24.5 14.9316 22.9316 16.5 21 16.5V19.5C24.5884 19.5 27.5 16.5884 27.5 13H24.5ZM21.2464 9.53642C23.0753 9.66304 24.5 11.1634 24.5 13H27.5C27.5 9.55655 24.8247 6.77696 21.4536 6.54358L21.2464 9.53642ZM15.0518 10.9221L17.9132 8.06066L15.7919 5.93934L12.9305 8.80074L15.0518 10.9221ZM10.0868 8.07831L12.9305 10.9221L15.0518 8.80074L12.2081 5.95699L10.0868 8.07831ZM11.0783 9.20809L12.2081 8.07831L10.0868 5.95699L8.95699 7.08676L11.0783 9.20809ZM13.9221 9.93051L11.0783 7.08676L8.95699 9.20809L11.8007 12.0518L13.9221 9.93051ZM11.0607 14.9132L13.9221 12.0518L11.8007 9.93051L8.93934 12.7919L11.0607 14.9132ZM12.1888 13.9201L11.0607 12.7919L8.93934 14.9132L10.0675 16.0414L12.1888 13.9201ZM12.9289 11.0587L10.0675 13.9201L12.1888 16.0414L15.0502 13.18L12.9289 11.0587ZM17.9309 13.9393L15.0502 11.0587L12.9289 13.18L15.8096 16.0607L17.9309 13.9393ZM16.9393 12.8096L15.8096 13.9393L17.9309 16.0607L19.0607 14.9309L16.9393 12.8096ZM14.0587 12.0502L16.9393 14.9309L19.0607 12.8096L16.18 9.92889L14.0587 12.0502ZM16.9201 7.06748L14.0587 9.92889L16.18 12.0502L19.0414 9.1888L16.9201 7.06748ZM15.7919 8.06066L16.9201 9.1888L19.0414 7.06748L17.9132 5.93934L15.7919 8.06066Z"
                  fill="white" mask="url(#path-1-outside-1)"/>
            </svg>

          </span>
          Статус {{ $t(`nomenclature.out_actual_conformation_text.headers.${this.headerResource}`) }}
        </div>
        <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="dialogConfirm = false">
          <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z"
                fill="#BBDBFE"/>
          </svg>
        </v-btn>
      </div>
      <div class="dialog-content dialog-main">
        <div class="text-wrapper">
          <div v-for="(textItem, index) in textItems" :key="index" :class="textItem.style" class="text-item">
              <span v-if="textItem.translate">
                {{ $t(`nomenclature.out_actual_conformation_text.${textItem.text}`) }}
              </span>
            <span v-else>{{ textItem.text }}</span>
          </div>
          ?
        </div>
        <div class="dialog-actions popup-buttons">
          <custom-btn
              :title="$t('page.cancelButton')"
              custom-class="cancel"
              color="#5893D4"
              text
              @updateEvent="cancel"
          ></custom-btn>
          <custom-btn
              title="Да"
              custom-class="save"
              color="#5893D4"
              text
              @updateEvent="agree"
          ></custom-btn>
        </div>
      </div>
    </div>
  </v-dialog>
</template>

<script>
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";

export default {
  name: "AutActualConformation",
  components: {
    CustomBtn
  },
  data: () => ({
    dialogConfirm: false,
    resolve: null,
    reject: null,
    textItems: null,
    headerResource: '',
    actual: false
  }),
  methods: {
    open(textItems, headerResource) {
      this.textItems = textItems
      this.dialogConfirm = true
      this.headerResource = headerResource;

      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      }).then((value) => {
        return value;
      });
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

<style scoped lang="scss">
.dialog-main {
  padding: 15px;
}

.text-wrapper {
  display: flex;
  flex-direction: row;
  margin-bottom: 40px;

  .text-item {
    margin-right: 5px;
    &.normal {
      white-space: nowrap;
    }

    &.bold {
      font-weight: bold;
    }
  }
}

</style>
