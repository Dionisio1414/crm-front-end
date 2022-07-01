<template>
  <v-menu
      bottom
      origin="center center"
      :content-class="`btn-dots ${wrapperClass}`"
      :min-width="minWidth"
      :left="left"
      internal-activator
      offset-x
      :close-on-content-click="false"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        class="btn-open-switcher"
        v-bind="attrs"
        v-on="on"
      >
      <span class="fake-button fake-button--dots">
        <SvgSprite
          :width="5"
          :height="19"
          iconId="icon-dots"
        />
      </span>
      </v-btn>
    </template>

    <div class="switcher-wrapper">
      <switch-nested
          customClass="choose-attribute"
          :firstTitle="$t('crm.priceModal.switch.firstTitle')"
          :secondTitle="$t('crm.priceModal.switch.secondTitle')"
          @updateSwitch="onSwitch"
      >
      </switch-nested>
    </div>
  </v-menu>
</template>

<script>
import SwitchNested from "@/components/dashboard/products/wareHouses/SwitchNested/SwitchNested"
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"

export default {
  name: "DropDots",
  components: {
    SwitchNested,
    SvgSprite
  },
  props: {
    customClass: String,
    minWidth: String,
    left: Boolean,
    wrapperClass: {
      type: String,
      default: ''
    },
  },
  data() {
    return {
      isVisibleSwitcher: false
    }
  },
  methods: {
    onHandler(idx) {
      this.$emit('updateMenu', idx)
    },
    onSwitch() {
      console.log('onSwitch')
    },
    // onShowSwitcher() {
    //   this.isVisibleSwitcher = !this.isVisibleSwitcher;
    // }
  }

}
</script>

<style lang="scss">

.v-menu__content.theme--light.v-menu__content--fixed.menuable__content__active.btn-dots {
  border: 0;
  border-radius: 0;
  box-shadow: none;
  margin: 0 0 0 15px;
}

.btn-open-switcher.v-btn:not(.v-btn--round).v-size--default {
  height: auto;
  min-width: auto;
  padding: 0;
}

.btn-open-switcher.theme--light.v-btn:not(.v-btn--flat):not(.v-btn--text):not(.v-btn--outlined) {
  background-color: transparent;
}

.btn-open-switcher {
  .v-btn {
    &:before {
      background-color: transparent;
    }
  }
}

.btn-open-switcher.v-btn:not(.v-btn--text):not(.v-btn--outlined):hover:before {
  opacity: 0;
}

.switch-nesting {
  &.choose-attribute {
    background-color: #F4F9FF;
    border-radius: 10px;
    min-width: 157px;
    min-height: 48px;
    border: 1px solid #E3F0FF;
    padding: 11px 13px;

    &:focus-visible {
      outline: none;
    }

    .v-input--switch__thumb {
      background-color: #4ECA80;
    }

    .v-input--switch__track {
      border: 1px solid #4ECA80;
    }

    .v-input--switch .v-input--selection-controls__input {
      width: 33px;
    }

    .v-input--switch__thumb {
      width: 18px;
      height: 18px;
    }
    .v-input--switch--inset .v-input--switch__track {
      top: calc(50% - 15px);
    }

    .v-application--is-ltr .v-input--switch--inset .v-input--selection-controls__ripple,
    .v-application--is-ltr .v-input--switch--inset .v-input--switch__thumb {
      transform: translate(0px, 0) !important
    }
    .v-application--is-ltr .v-input--switch--inset.v-input--is-dirty .v-input--selection-controls__ripple,
    .v-application--is-ltr .v-input--switch--inset.v-input--is-dirty .v-input--switch__thumb {
      transform: translate(23px, 0) !important;
    }

    input[type=checkbox] {
      min-width: 42px;
      margin-right: 0;
    }

    .v-input--selection-controls.v-input {
      margin-left: 8px;
      margin-right: 9px;
    }

    .v-input--selection-controls:hover .v-input--switch__thumb {
      box-shadow: none !important;
    }
  }
}

</style>