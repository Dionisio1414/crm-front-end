<template>
  <div class="alert" :class="customClass">
    <div class="wrapper">
      <div v-for="(textItem, index) in textItems" :key="index" :class="textItem.style" class="text-item">
              <span v-if="textItem.translate">
                {{ $t(`nomenclature.alert_massages.${textItem.text}`) }}
              </span>
              <span v-else>{{ textItem.text }}</span>
      </div>
      .
          <span  v-if="link" @click="emitAction"> {{ $t(link.text) }}</span>
      <span @click="onClickClose">
        <svg-sprite width="12" height="12" icon-id="removes"></svg-sprite>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "Alert",
  props: {
    textItems: Array,
    link: [Object, Boolean],
    customClass: String
  },
  methods:{
    emitAction () {
      this.$emit('close-alert')
      this.$emit(this.link.action, this.link.actionParams)
    },
    onClickClose () {
      this.$emit('close-alert')
    }
  }
}
</script>

<style scoped lang="scss">
.alert {
  background: #E3F0FF;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
  border-radius: 30px;
  position: absolute;
  min-width: 100px;
  font-size: 15px;
  line-height: 15px;
  padding: 10px 10px 10px 60px;
  text-align: center;
  color: #5893D4;
  left: 50%;
  transform: translateX(-50%);
  bottom: 150px;
  z-index: 9999;
  white-space: nowrap;

  &.error-alert {
    color: #fff;
    background: #FF9F2F;
    padding-right: 60px;
    bottom: 77px;

    svg {
      display: none;
    }
  }

  .wrapper {
    display: flex;
    align-items: center;

    .text-item {
      margin-left: 5px;

      &:last-child {
        margin-left: 0;
      }

      &.bold {
        font-weight: 500;
      }
    }
  }

  svg {
    margin-left: 60px;
  }
}

</style>
