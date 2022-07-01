<template>
  <div :class="[type, customClass]">
    <label :class="type + '-label'">
      <input
          :type="type"
          :name="type"
          @click="onInput"
          v-model="value"
      >
      <span :class="type + '-text'">
        <span v-if="title" class="text">{{ title }}</span>
      </span>
    </label>
  </div>
</template>
<script>
export default {
  name: "SwitchFlag",
  props: {
    customClass: String,
    title: String,
    id: String,
    isChecked: Boolean,
    type: {
      type: String,
      required: true,
      validator: value => {
        const isType = ['checkbox', 'radio'].includes(value);
        if (!isType) console.error('you need to fill in the type "checkbox or radio"');

        return isType;
      }
    }
  },
  data:() => ({
    value: false
  }),
  methods: {
    onInput() {
      const values = {
        value: !this.value,
        id: this.id
      }
      this.$emit('updateValue', values);
    }
  },
  mounted() {
    if (this.isChecked) this.value = this.isChecked;
  }
}
</script>

<style lang="sass" scoped>
.checkbox, .radio
  margin-bottom: 10px
  font-size: 0
  &.priceCheckbox
    .checkbox-text, .radio-text
      .text
        padding-right: 0 !important
        line-height: 1
  .checkbox-text, .radio-text
    &::after
      background-color: #4ECA80

    &::before
      border-color: #4ECA80

.checkbox
  display: inline-block
  &-label
    display: block
    font-weight: normal
    cursor: pointer
    input[type="checkbox"]
      display: none
      &:checked
        + .checkbox-text
          &::after
            opacity: 1
  &-text
    position: relative
    display: inline-block
    min-width: 16px
    min-height: 16px
    .text
      display: inline-block
      font-size: 15px
      line-height: 20px
      padding-left: 25px
      padding-right: 56px
      color: #7E7E7E
    &::before, &::after
      content: ""
      position: absolute
      transform: translateY(-50%)
      top: 40%
    &::before
      left: 0
      border: 1px solid transparent
      width: 16px
      height: 16px
    &::after
      background: transparent
      left: 4px
      width: 8px
      height: 8px
      opacity: 0
      transition: opacity .25s ease-in-out

.radio
  display: inline-block
  &-label
    display: block
    font-weight: normal
    cursor: pointer
    input[type="radio"]
      display: none
      &:checked
        + .radio-text
          &::after
            opacity: 1
  &-text
    display: inline-block
    position: relative
    min-width: 16px
    min-height: 16px
    .text
      display: inline-block
      font-size: 14px
      line-height: 20px
      padding-left: 25px
      padding-right: 56px
      color: #7E7E7E
    &::before, &::after
      content: ""
      position: absolute
      transform: translateY(-50%)
      border-radius: 50%
      top: 50%
    &::before
      left: 0
      border: 1px solid transparent
      width: 16px
      height: 16px
    &::after
      background: transparent
      left: 4px
      width: 8px
      height: 8px
      opacity: 0
      transition: opacity .25s ease-in-out

</style>