<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="dialog"
        max-width="920px"
        class="dialog-large"
    >
      <div class="dialog filters-form">
        <div class="dialog-header">
          <div class="header-text">
            <svg width="14" height="10" viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="14" height="2" fill="white"/>
              <rect x="2" y="4" width="10" height="2" fill="white"/>
              <rect x="4" y="8" width="6" height="2" fill="white"/>
            </svg>
            <span>Фильтры</span>
          </div>
          <div class="dialog-header-actions">
            <v-btn
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click="cancel"
            >
              {{ $t('page.cancelButton') }}
            </v-btn>
            <v-btn @click="dialog = false" icon color="#5893D4">
              <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                      fill="#BBDBFE"/>
              </svg>
            </v-btn>
            <v-btn icon color="#5893D4" class="action-btn sortable-btn">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                    fill="#BBDBFE"/>
              </svg>
            </v-btn>
          </div>
        </div>
        <div class="dialog-content dialog-content-large">
          <form class="item-form">
            <div class="filters-row">
              <v-row>
                <v-col cols="4">
                  <div class="item-name">
                    <div class="label-title">период</div>
                    <input type="text">
                  </div>
                </v-col>
              </v-row>
            </div>
            <div class="filters-row">
              <v-row>
                <v-col cols="4">
                  <div class="item-name">
                    <div class="label-title">период</div>
                    <div class="checkboxes-container">
                      <label data-v-41ca53f9="" class="checkbox-label checkbox-label__head">
                        <input data-v-41ca53f9="" type="checkbox">
                        Розничная
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Оптовая
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Закупочная
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Все
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Мелкооптовая
                      </label>
                    </div>
                  </div>
                </v-col>
              <v-col cols="4">
                  <div class="item-name">
                    <div class="label-title">период</div>
                    <div class="checkboxes-container">
                      <label data-v-41ca53f9="" class="checkbox-label checkbox-label__head">
                        <input data-v-41ca53f9="" type="checkbox">
                        Розничная
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Оптовая
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Закупочная
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Все
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Мелкооптовая
                      </label>
                    </div>
                  </div>
                </v-col>
              <v-col cols="4">
                  <div class="item-name">
                    <div class="label-title">период</div>
                    <div class="checkboxes-container">
                      <label data-v-41ca53f9="" class="checkbox-label checkbox-label__head">
                        <input data-v-41ca53f9="" type="checkbox">
                        Розничная
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Оптовая
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Закупочная
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Все
                      </label>
                      <label data-v-41ca53f9="" class="checkbox-label">
                        <input data-v-41ca53f9="" type="checkbox">
                        Мелкооптовая
                      </label>
                    </div>
                  </div>
                </v-col>
              </v-row>
            </div>
            <div class="filters-row">
              <v-row>
                <v-col cols="12">
                  <div class="double-slider-wrapper">
                    <v-slider :color="'black'"></v-slider>
                    <v-slider :color="'red'"></v-slider>
                  </div>
                </v-col>
              </v-row>
            </div>
          </form>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: "FilterDocuments",
  data() {
    return {
      dialog: true
    }
  },
  methods: {
    open() {
      this.dialog = true
    },
    cancel() {
      this.dialog = false
    }
  }
}
</script>

<style lang="scss">
.filters-form {
  .filters-row {
    border-bottom: 1px solid #E3F0FF;
    margin-bottom: 25px;
    padding-bottom: 20px;
    .item-name {
      margin-bottom: 0;
    }
    .checkboxes-container {
      width: 100%;
      border: 1px solid #E3F0FF;
      border-radius: 10px;
      height: 240px;
      padding: 15px;
      overflow: auto;
      max-width: 240px;

      .checkbox-label {
        color: #7E7E7E;
        font-size: 15px;
        line-height: 1;
        font-weight: 400;
        display: flex;
        width: 100%;
        align-items: center;
        text-transform: none;
        padding: 10px 0;
        margin-bottom: 8px;

        &__head {
          border-bottom: 1px solid #E3F0FF;
        }

        input[type=checkbox] {
          display: inline;
        }
      }
    }
    .double-slider-wrapper{
      width: 100%;
      position: relative;
      .v-slider__thumb {
        z-index: 15;
      }
      .v-input {
        position: absolute;
        left: 0;
        right: 0;
      }
    }
    &:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
  }
}
</style>