<template>
  <div class="property-container" v-click-outside="onClickOutside">
    <div class="property-name">{{ property_value.property.title}}</div>
    <div class="property-value">
      <span></span>
      <input @click="onClick" type="text" :readonly="!isEdit" v-model="localeData">
    </div>
  </div>
</template>

<script>
import ClickOutside from "vue-click-outside";

export default {
  name: "ViewProperty",
  props: ['property_value'],
  data() {
    return {
      isEdit: false,
      localeData: {}
    }
  },
  created() {
    this.localeData = this.property_value.title || '--'
  },
  methods: {
    onClick () {
      if(this.property_value.editable) {
        this.isEdit = true
      }
    },
    onClickOutside () {
      this.isEdit = false
      if (this.isEdit && this.localeData.title && this.localeData.title !== this.property_value.title ) {
        this.$emit('change-value', {
          id:this.property_value.id,
          data:this.localeData.property_value
        })

      }
    },
  },
  directives: {
    ClickOutside
  }
}
</script>

<style scoped lang="scss">
.property-container {
  display: flex;
  width: 100%;
  align-items: center;
  margin-bottom: 11px;

  .property-name {
    font-weight: bold;
    font-size: 12px;
    line-height: 12px;
    text-transform: uppercase;
    width: 274px;
    color: #9DCCFF;
  }

  .property-value {
    width: calc(100% - 274px)
  }

  input {
    border: 1px solid #E3F0FF;
    box-sizing: border-box;
    padding: 10px 15px;
    border-radius: 100px;
    font-size: 13px;
    line-height: 1;
    color: #9DCCFF;
    min-height: 38px;
    outline: none!important;
    &:read-only {
      font-size: 14px;
      color: #7E7E7E;
      border: none;
    }
  }
}
</style>