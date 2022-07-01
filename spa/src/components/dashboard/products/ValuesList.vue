<template>
  <div>
    <div class="characteristic-value">
      <div class="radio-group">
        <div v-for="characteristic_value in characteristic.characteristic_value"
             :key="characteristic_value.id"
             @change="onChangeCharacteristicValue(characteristic_value)"

             class="radio-button">
          <label :for="characteristic_value.title.toLowerCase()">
            <input
                type="radio"
                :id="characteristic_value.title.toLowerCase()"
                :name="characteristic.title">
            <div class="radio-button-text">{{ characteristic_value.title }}</div>
          </label>
        </div>
      </div>
    </div>
    <div class="add-property-value" @click="openProperty">
      <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M6 0C6.17681 0 6.34638 0.070238 6.4714 0.195262C6.59643 0.320287 6.66667 0.489856 6.66667 0.666667V6C6.66667 6.17681 6.59643 6.34638 6.4714 6.4714C6.34638 6.59643 6.17681 6.66667 6 6.66667H0.666667C0.489856 6.66667 0.320287 6.59643 0.195262 6.4714C0.070238 6.34638 0 6.17681 0 6C0 5.82319 0.070238 5.65362 0.195262 5.5286C0.320287 5.40357 0.489856 5.33333 0.666667 5.33333H5.33333V0.666667C5.33333 0.489856 5.40357 0.320287 5.5286 0.195262C5.65362 0.070238 5.82319 0 6 0Z"
              fill="#5893D4"/>
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M5.33301 6.00065C5.33301 5.82384 5.40325 5.65427 5.52827 5.52925C5.65329 5.40422 5.82286 5.33398 5.99967 5.33398H11.333C11.5098 5.33398 11.6794 5.40422 11.8044 5.52925C11.9294 5.65427 11.9997 5.82384 11.9997 6.00065C11.9997 6.17746 11.9294 6.34703 11.8044 6.47206C11.6794 6.59708 11.5098 6.66732 11.333 6.66732H6.66634V11.334C6.66634 11.5108 6.5961 11.6804 6.47108 11.8054C6.34606 11.9304 6.17649 12.0007 5.99967 12.0007C5.82286 12.0007 5.65329 11.9304 5.52827 11.8054C5.40325 11.6804 5.33301 11.5108 5.33301 11.334V6.00065Z"
              fill="#5893D4"/>
      </svg>
      Добавить значение
    </div>
  </div>
</template>

<script>


export default {
  name: "ValuesList",
  components: {},
  props: {
    characteristic: Object
  },
  data () {
    return {
      isShowModal: false
    }
  },
  methods: {
    onChangeCharacteristicValue() {
      this.$emit('on-change-calue')
    },
    openProperty () {
     this.$emit('click-add-value', this.characteristic)
    },
    closeProperty () {
      if ( this.isShowModal) {
        this.isShowModal = false
      }
    },
    addValue() {

    }
  }
}
</script>

<style scoped lang="scss">
.characteristic-value {
  margin-top: 20px;
  padding: 20px 25px;
  border: 1px solid #9DCCFF;
  border-radius: 10px;
  height: 190px;
  overflow: auto;

  .radio-button-text, .checkbox-text {

    font-size: 15px;
    font-weight: 400;
    line-height: 1;
    color: #7E7E7E;
    padding-left: 30px;
  }

  .radio-group {
    margin-bottom: 0;

    label {
      padding-top: 0;
      margin-bottom: 20px;
    }

  }
}

.add-property-value {
  font-size: 15px;
  line-height: 1;
  color: #5893D4;
  margin-top: 15px;
  cursor: pointer;
  &:hover {
    text-decoration: underline;
  }
}
</style>