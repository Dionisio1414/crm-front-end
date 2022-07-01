<template>
  <div class="collapse" :class="customClass">
    <div class="collapseTitle">
      <span class="text">{{ title }}</span>
      <span class="divider"></span>
      <v-btn
          class="open-drop-down"
          :class="{'active': isCollapse && !isDisabled, 'notClickable': isDisabled}"
          @click="onCollapse"
          icon
      >
        <svg-sprite width="11" height="6" icon-id="arrowDown"></svg-sprite>
      </v-btn>
    </div>
    <v-expand-transition>
      <div class="body" v-show="isCollapse">
        <slot></slot>
      </div>
    </v-expand-transition>
  </div>
</template>

<script>
export default {
 name: "CollapseBlock",
  props: {
   title: String,
    customClass: String,
    isDisabled: Boolean
  },
  data() {
   return {
    isCollapse: true
   }
  },
  methods: {
    onCollapse() {
      this.isCollapse = !this.isCollapse;
    }
  }
}
</script>

<style scoped lang="scss">
  .notClickable {
    opacity: .6;
    pointer-events: none;
  }

  .collapse {
    padding: 0 30px;

    &Body {
      padding: 30px 0;
    }

    .open-drop-down.active {
      transform: rotate(180deg);
    }

    &Title {
      display: flex;
      align-items: center;
      margin-bottom: 20px;

      .text {
        font-weight: bold;
        font-size: 13px;
        line-height: 13px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: #FF9F2F;
      }

      .divider {
        flex-grow: 2;
        height: 1px;
        background: #E3F0FF;
        margin: 0 10px;
      }
    }

    &.wareHouse {
      padding: 0;



      &.border {
        border-top: 1px solid #E3F0FF;
        margin-top: -1px;
      }

      .collapseTitle {
        padding: 4px 15px;
        margin-bottom: 0;
        border-bottom: 1px solid #E3F0FF;
      }

      .text {
        font-weight: normal;
        font-size: 15px;
        line-height: 15px;
        color: #7E7E7E;
        opacity: 0.5;
        text-transform: none;
      }

      svg path {
        fill: #7E7E7E;
      }

      .divider {
        background: none
      }
    }
  }
</style>
<style lang="scss">
  .collapse.wareHouse {
   /* .v-treeview-node__children .v-treeview-node__level:first-child {
      display: none;
    }*/

    .v-treeview-node__root {
      min-height: 45px;
    }
  }
</style>
