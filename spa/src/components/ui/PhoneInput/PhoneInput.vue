<template>
  <div class="phoneWrapper">
    <div class="item-field">
      <vue-phone-number-input
          ref="phone"
          v-model="value"
          @update="onUpdate"
          class="phone"
          :error="false"
          size="sm"
          show-code-on-list
          :default-country-code="codeValue"
          no-flags
          :loader="false"
      ></vue-phone-number-input>
      <v-btn  v-if="isDataSafe" icon @click="onAddItem">
        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z"
              stroke="#9DCCFF"/>
          <path
              d="M16.1027 13.8732L20 13.8732L20 16.0765L16.1027 16.0765L16.1027 20L13.8973 20L13.8973 16.0765L10 16.0765L10 13.8732L13.8973 13.8732L13.8973 10L16.1027 10L16.1027 13.8732Z"
              fill="#9DCCFF"/>
        </svg>
      </v-btn>
      <v-btn
          v-else
          @click="onDataSafe"
      >!</v-btn>
    </div>
    <div v-if="list.length" class="values-list">
      <value-item
          v-for="(item, idx) in list"
          :key="item.id"
          :id="item.id"
          :idx="idx"
          :phoneNumber="item.phoneNumber"
          :countryCallingCode="item.countryCallingCode"
          :countryCode="item.countryCode"
          @updateRemoveItem="removeItem"
          @updateEdit="editItem"
          :isDataSafe="isDataSafe"
      ></value-item>
    </div>
  </div>
</template>

<script>
import ValueItem from "@/components/ui/PhoneInput/components/ValueItem";
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "PhoneInput",
  components: {
    'value-item': ValueItem
  },
  props: {
    code: {
      type: String,
      default: 'UA',
    },
  },
  data() {
    return {
      codeValue: null,
      value: '',
      objValue: null,
      list: [],
      isDataSafe: true,
      id: null
    }
  },
  methods: {
    onAddItem() {
      if(this.value.length >= 9) {
        const { countryCallingCode, phoneNumber, countryCode } = this.objValue;
        this.id = getUniqueId();

        this.list.push({ countryCallingCode, phoneNumber, countryCode, id: this.id });
      }

      this.value = '';
    },
    onUpdate(value) {
      this.objValue = value;
    },
    setPlaceHolder() {
      const elem = this.$refs.phone.$el.querySelector('.input-tel__input');

      elem.setAttribute('placeholder', '(___) ___ - __ - __ ');
    },
    onDataSafe() {
      this.isDataSafe = true;

      this.value = '';
    },
    getFilteredList(idx) {
      return this.list.filter((item, index) => index !== idx);
    },
    removeItem(idx) {
      this.list = this.getFilteredList(idx);
    },
    getEditData(id) {
      return this.list.find((item) => item.id === id);
    },
    editItem(id) {
      this.isDataSafe = false;

      const { phoneNumber, countryCode } = this.getEditData(id);

      this.value = phoneNumber;
      this.codeValue = countryCode;
    }
  },
  mounted() {
    this.setPlaceHolder();
    this.codeValue = this.code;
  }
}
</script>

<style lang="scss">
.item-field {
  display: flex;
  align-items: center;

  & + .values-list {
    margin-top: 15px;
  }
}

.phone.vue-phone-number-input.sm {
  .select-country-container {
    flex: 0 0 45px;
    width: 65px;
    min-width: 65px;
    max-width: 65px;
    margin-right: 20px;

    input.country-selector__input {
      border: none;
      border-bottom: 1px solid #9DCCFF;
      font-size: 14px;
      line-height: 1.5;
      color: #7E7E7E;
      padding: 0 !important;
      border-radius: 0 !important;
      height: inherit;
      min-height: inherit;
      box-shadow: none !important;
      font-family: 'HelveticaNeueCyr', serif;
    }
  }

  .input-tel.sm .input-tel__label {
    display: none;
  }

  .input-tel__input {
    border: none;
    border-bottom: 1px solid #9DCCFF;
    font-size: 14px;
    line-height: 1.5;
    color: #7E7E7E;
    box-shadow: none !important;
    border-radius: 0 !important;
    padding: 0 !important;
    height: inherit;
    min-height: inherit;
    font-family: 'HelveticaNeueCyr', serif;
    width: inherit;
  }

  .country-selector__label {
    display: none;
  }

  .country-selector.sm {
    height: inherit;
    min-height: 30px;
  }

  .input-tel.sm {
    height: inherit;
    min-height: 30px;
  }

  .country-selector__toggle {
    top: calc(50% - 12px);

    &__arrow path.arrow {
      fill: #9DCCFF;
    }
  }

  .dots-text {
    color: #9DCCFF;
  }
}
</style>