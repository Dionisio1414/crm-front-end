<template>
  <v-menu
      bottom
      origin="center center"
      transition="slide-y-transition"
      :content-class="`menuBtn ${wrapperClass}`"
      :min-width="minWidth"
      :left="left"
      internal-activator
      offset-y
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
          :class="customClass"
          v-bind="attrs"
          v-on="on"
      >
        <slot></slot>
        <span class="text">{{ title }}</span>
        <span class="icon">
            <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55708e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z" :fill="colorIcon"/>
            </svg>
        </span>
      </v-btn>
    </template>

    <v-list
        class="dropDown"
        :class="customDropDown"
    >
      <v-list-item
          v-for="(item, i) in items"
          @click="onHandler(i)"
          :disabled="item.disabled"
          :key="i"
      >
        <v-list-item-title>
          <svg v-if="isIcon" width="11" height="15" viewBox="0 0 11 15" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.21739 0H0.956522C0.6875 0 0.460824 0.090332 0.276495 0.270996C0.0921649 0.45166 0 0.673828 0 0.9375V14.0625C0 14.3262 0.0921649 14.5483 0.276495 14.729C0.460824 14.9097 0.6875 15 0.956522 15H10.0435C10.3125 15 10.5392 14.9097 10.7235 14.729C10.9078 14.5483 11 14.3262 11 14.0625V4.6875L6.21739 0ZM10.0435 5.08301V5.15625H5.73913V0.9375H5.8288L10.0435 5.08301ZM0.956522 14.0625V0.9375H4.78261V6.09375H10.0435V14.0625H0.956522Z" fill="#317CCE"/>
          </svg>
          {{ item.title }}
        </v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>
export default {
 name: "WareHMenu",
  props: {
    title: String,
    customClass: String,
    customDropDown: String,
    items: Array,
    minWidth: String,
    isIcon: Boolean,
    left: Boolean,
    isCapitalizedDocument: Boolean,
    wrapperClass: {
      type: String,
      default: ''
    },
    colorIcon: {
      type: String,
      default: '#ffffff'
    }
  },
  methods: {
    onHandler(idx) {
      this.$emit('updateMenu', idx)
    }
  }

}
</script>

<style lang="scss">

.commonStyle {
  width: 100%;
  border-radius: 100px;
  display: flex;
  justify-content: space-between;
  height: 40px !important;
  text-transform: inherit;
  font-weight: bold !important;
  font-size: 13px !important;
  line-height: 13px;
  padding: 0 15px !important;

  .text {
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
  }
}

.tableMenu {
  @extend .commonStyle;
  background-color: #9DCCFF !important;
  color: #FFFFFF !important;
  width: 74px;
  margin-left: 10px;
  transition: background-color .2s ease-in;
}

.createMenu {
  @extend .commonStyle;
  width: 156px;
  background-color: #9DCCFF !important;
  color: #FFFFFF !important;
  max-width: 160px;
  margin-left: 10px;
  transition: background-color .2s ease-in;

  .v-btn__content {
    justify-content: flex-end;

    .text {
      display: inline-block;
      margin: 0 15px 0 10px;
    }
  }

  &:hover:before {
    display: none;
  }

  &:hover {
    opacity: 1 !important;
    background-color: #5893D4 !important;
  }
}

.menuBtn {
  z-index: 0;
  box-shadow: none !important;
  border-radius: 0 0 20px 20px !important;
  border: none !important;
  border-top: 1px solid #9DCCFF !important;

  &.createWrapper, &.otherWrapper {
    padding: 10px 0;
    border-radius: 0 0 10px 10px !important;
    border: none !important;
    margin-top: 5px;
    background: #FFFFFF;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.05) !important;
  }
}

.otherMenu {
  background: none !important;


  &:before {
    display: none;
  }

  .v-ripple__container {
    display: none !important;
  }

  .text {
    margin: 0 10px;
    font-size: 14px;
    color: #5893D4;
    text-transform: none;
  }
}

.otherWrapper {
  margin-top: 0 !important;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05) !important;
  border-radius: 0 0 10px 10px;

  .theme--light.v-list-item--disabled .v-list-item__title {
    color: #BDBDBD;
  }
}

.otherDropDown .v-list-item {
  min-height: 30px;
  &__title {
    font-size: 14px;
    color: #7E7E7E;
  }

  &:hover {
    background: #e3f0ff;

    .v-list-item__title {
      color: #5893D4;
    }
  }

}

.addMenu {
  @extend .commonStyle;
  color: #FFFFFF !important;
  max-width: 260px;
  background-color: #5893D4 !important;
  height: 40px !important;
  z-index: -0;
  transition: border-radius, background-color .2s ease;

  &:before {
    display: none;
  }
  
  &:not([aria-expanded='true']):hover {
    background-color: #FF951A !important;
  }

  &[aria-expanded='true'] {
    border-radius: 20px 20px 0 0;
  }

  .plus {
    font-size: 24px;
  }

  .text {
    flex-grow: 1;
    text-align: left;
    padding-left: 10px;
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
  }
}

.dropDown {
  padding: 0;
  &.add {
    background: #5893D4 !important;

    .v-list-item {
        padding-left: 20px;
        cursor: pointer;
        min-height: 40px;
        margin-top: -2px;

        &:hover {
          background: #9DCCFF !important;
        }

        .v-list-item__title {
          flex-grow: 1;
          text-align: left;
          padding-left: 20px;
          color: #fff;
          font-weight: 550;
          font-size: 13px;
          line-height: 13px;
        }
    }
  }

  &.create {
    .v-list-item {
      cursor: pointer;
      min-height: 40px;
      margin-top: -2px;

      .v-list-item__title {
        font-size: 14px;
        color: #7E7E7E;

        svg {
          margin-right: 5px;
        }
      }

      &:hover {
        background: #e3f0ff;

        .v-list-item__title {
          color: #1976d2;
        }
      }
    }
  }

}
</style>