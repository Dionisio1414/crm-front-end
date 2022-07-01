<template>
  <div class="item-area" v-click-outside="onClickOutside">
    <div class="item-area-col">
      <form class="item-form">
        <div class="item-name">
          <label class="input-label">{{ $t('page.name') }}</label>
          <input type="text" :placeholder="$t('page.typeName')" v-model.trim="characteristicName">
          <small v-if="invalid" class="warn" >{{$t('page.option')}} <b>{{characteristicName}}</b>{{$t('validation.uniq')}}</small>
        </div>
        <div class="item-values">
          <label class="input-label">{{ $t('page.addValueVariant') }}</label>
          <input @input="checkValue" type="text" :placeholder="$t('page.typeName')" v-model="itemValue" @keypress.enter="saveChip">
          <small class="warn" v-if="valueExists">{{$t('option.option_value')}}<b>{{itemValue}}</b> {{$t('validation.uniq')}}</small>
          <button type="button"
                  v-show="editbleChip"
                  :disabled="!itemValue.length || valueExists"
                  @click="updatePropertyValue"

                  class="base-button base-button--lighten--transparent"
          >{{ $t("page.saveButton") }}
          </button>
          <button type="button"
                  v-show="!editbleChip"
                  :disabled="!itemValue.length || valueExists"
                  @click="saveChip"

                  class="base-button base-button--lighten--transparent"
          >{{ $t("page.addButton") }}
          </button>
        </div>
      </form>
    </div>
    <div class="item-area-col col-list">
      <draggable
        class="values-list"
        tag="div"
        v-model="values"
        v-bind="dragOptions"
        @start="drag = true"
        @end="drag = false"
      >
        <transition-group type="transition" :name="!drag ? 'flip-list' : null">
          <div class="value-item"
               v-for="(value, i) in values"
               :key="i"
               :class="{'active' : value.title.toLowerCase() === itemValue.toLowerCase()}"
          >
            <span>{{value.title}}</span>
            <svg
                class="edit"
                @click="editChip(value, i)"
                width="17"
                height="18"
                viewBox="0 0 17 18"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
              <path
                  d="M16 4.27778L13.2222 1.5L2.11111 12.6111L1 16.5L4.88889 15.3889L16 4.27778ZM11 3.72222L13.7778 6.5L11 3.72222ZM2.11111 12.6111L4.88889 15.3889L2.11111 12.6111Z"
                  stroke="#BBDBFE"
                  stroke-width="1.39565"
                  stroke-linecap="round"
                  stroke-linejoin="round"
              />
            </svg>

            <svg @click="deleteChip(i)" class="close" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"/>
            </svg>
          </div>
        </transition-group>
      </draggable>
      <custom-btn
          :title="$t('page.saveButton')"
          custom-class="base-btn shadow-btn btn-sticky"
          :is-disabled="!characteristicName.length || invalid"
          color="#5893D4"
          @updateEvent="createItem"
      ></custom-btn>
    </div>
  </div>
</template>

<script>

import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
export default {
  name: "characteristicCreate",
  components: {CustomBtn},
  props: {
    closeDetails: {
      type: Function
    },
    set: {
      type: Boolean,
      default: true
    }
  },
  data: () => ({
    characteristicName: '',
    itemValue: '',
    editbleChip: '',
    editbleChipIndex: null,
    values: [],
    // valueExists: false,
    invalid: false,
    drag: false,
  }),
  watch: {
    exists() {
      if (this.$store.getters['characteristics'].find(prop => prop.name === this.characteristicName)) {
        this.invalid = true
      }
    }
  },
  computed: {
    valueExists() {
      if (!this.itemValue || !this.values.length) {
        return false
      }
      const itemValue = this.itemValue.toLowerCase()
      const editableChip = this.editbleChip.toLowerCase()
      return this.values.find(value => value.title.toLowerCase() === itemValue) && itemValue !== editableChip
    },
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    }
  },
  methods: {
    checkValue(){

    },
    updatePropertyValue() {
      this.values[this.editbleChipIndex].title = this.itemValue
      this.itemValue = ''
      this.editbleChip = ''
      this.editbleChipIndex = null
    },
    editChip(value, index) {
      this.itemValue = value.title

      this.editbleChip = value.title
      this.editbleChipIndex = index
    },
    onClickOutside () {
      this.$emit('click-outside')
    },
    saveChip() {
      const values = this.values;
      const itemValue = {
        order: 0,
        characteristic_id: Date.now(),
        title: this.itemValue
      }
      if (this.itemValue && !this.valueExists) {
        values.push(itemValue)
        this.itemValue = ''
      }
    },
    deleteChip(index) {
      this.values.splice(index, 1)
      if (this.editbleChip) {
        this.editbleChip = ''
        this.editbleChipIndex = null
        this.itemValue = ''
      }
    },
    async createItem() {
      const item = {
        title: this.characteristicName,
        characteristic_value: this.values,
        check: false
      }
      // const dataAlert = {
      //   name: localStorage.getItem('locale') == 'ru' ? 'характеристика' : 'характеристика',
      //   title: this.characteristicName,
      //   createdName: localStorage.getItem('locale') == 'ru' ? 'создана' : 'створена'
      // }
      const alertParams = {
        textItems: [
          {text: 'option', style: 'normal', translate: true},
          {text: item.title, style: 'bold', translate: false},
          {text: 'created', style: 'normal', translate: true},
        ],
        link: {
          text: 'Показать',
          action: 'show-item',
          actionParams: item
        }
      }
      try {
        await this.$store.dispatch('createItem', item)
        this.$emit('show-alert', alertParams)
        this.closeDetails()
        // await this.$store.dispatch('alertShow', dataAlert)
      } catch (e) {
        console.log(e)
      }
    }
  },
}
</script>

<style scoped lang="scss">
.btn-sticky {
  position: sticky;
  bottom: 20px;
}

.input-label {
  margin-bottom: 13px;
}
.item-form {
  padding: 0;
}
.base-button {
  height: 40px !important;
  margin: 0 0 0 auto;
  width: 100%;
  max-width: 160px;
}
.label-title{
  color: #317CCE;
}
</style>
