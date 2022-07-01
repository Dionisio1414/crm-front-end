<template>
  <div class="item-area" v-click-outside="onClickOutside">

    <div class="item-area-col">
      <form class="item-form">
        <div class="item-name">
          <label class="input-label propTitle">{{ $t('page.name') }} <small v-if="isExistTitle">{{isExistTitle}}</small></label>
          <input
              type="text"
              :placeholder="$t('page.typeName')"
              :class="{'error-on-select':  $v.propertyName.$error }"
              @change="$v.propertyName.$touch()"
              v-model="propertyName"
          >
          <small v-if="exists" class="warn">{{$t('validation.uniq')}}</small>
        </div>
        <div class="item-values">
          <label class="input-label">{{ $t('page.addValueVariant') }}</label>
          <input @input="checkValue" type="text" :placeholder="$t('page.typeName')" v-model="itemValue"
                 @keypress.enter="saveChip">
         <!-- <small class="warn" v-if="valueExists">{{$t('characteristics.characteristic_value')}} <b>{{ itemValue }}</b>{{$t('validation.uniq')}}</small>-->
          <small class="warn" v-if="valueExists">{{$t('validation.uniq')}}</small>
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
          v-model="property.values"
          v-bind="dragOptions"
          @start="drag = true"
          @end="drag = false"
      >
        <transition-group type="transition" :name="!drag ? 'flip-list' : null">
          <div class="value-item"
               v-for="(value, i) in  property.values"
               :key="i"
               :class="{'active' : value.title.toLowerCase() === itemValue.toLowerCase() || value.title.toLowerCase() === editbleChip.toLowerCase()}"
          >
            <span>{{ value.title }}</span>
            <span class="edit">
              <svg-sprite @click="editChip(value, i)" width="17" height="18" icon-id="edit_pencil_small"></svg-sprite>
            </span>
            <span @click="deleteChip(i)" class="close">
              <svg-sprite width="12" height="13" icon-id="delete_lines_icon"></svg-sprite>
            </span>
          </div>
        </transition-group>
      </draggable>
      <custom-btn
          :title="$t('page.saveButton')"
          custom-class="base-btn shadow-btn btn-sticky"
          :is-disabled="!propertyName.length || exists"
          color="#5893D4"
          @updateEvent="createProperty"
      ></custom-btn>
    </div>

  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import {required} from 'vuelidate/lib/validators';

export default {
  name: "propertyCreate",
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
    isExistTitle: null,
    propertyName: '',
    itemValue: '',
    editbleChip: '',
    editbleChipIndex: null,
    property: {
      values: []
    },
    invalid: false,
    drag: false,
  }),
  computed: {
    ...mapGetters({
      properties: 'properties'
    }),
    valueExists() {
      if (!this.itemValue || !this.property.values.length) {
        return false
      }
      const itemValue = this.itemValue.toLowerCase()
      const editableChip = this.editbleChip.toLowerCase()
      return this.property.values.find(value => value.title.toLowerCase() === itemValue) && itemValue !== editableChip
    },
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    exists() {
      if (!this.propertyName || !this.properties.length) {
        return false
      }
      return !!this.properties.find(prop => prop.title === this.propertyName)
    }
  },
  methods: {
    ...mapActions({
      createItem: 'createProperty',
    }),
    onClickOutside() {
      this.$emit('click-outside')
    },
    updatePropertyValue() {
      this.property.values[this.editbleChipIndex].title = this.itemValue
      this.itemValue = ''
      this.editbleChip = ''
      this.editbleChipIndex = null
    },
    editChip(value, index) {
      this.itemValue = value.title

      this.editbleChip = value.title
      this.editbleChipIndex = index
    },

    checkValue() {
      // if (this.property.values.length) {
      //   if (this.property.values.find(value => value.title.toLowerCase() === this.itemValue.toLowerCase())) {
      //     this.valueExists = this.itemValue.toLowerCase() !== this.editbleChip.toLowerCase()
      //   } else {
      //     this.valueExists = false
      //   }
      // }
    },
    saveChip() {
      const values = this.property.values;
      const itemValue = {
        id: null,
        order: 0,
        title: this.itemValue
      }

      // const set = this.set;
      // ((set && values.indexOf(itemValue) === -1) || !set) &&
      if (!this.valueExists && this.itemValue) {
        values.push(itemValue)
        this.itemValue = ''
      }
    },
    deleteChip(index) {
      this.property.values.splice(index, 1)
      if (this.editbleChip) {
        this.editbleChip = ''
        this.editbleChipIndex = null
        this.itemValue = ''
      }
    },
    async createProperty() {
      const property = {
        title: this.propertyName,
        property_value: this.property.values || [],
        check: false
      }
      this.isExistTitle = null;

      try {
        const item = await this.createItem(property);
        if (item) {
          await this.$store.dispatch('fetchDataCreate');
          this.$emit('save-property');
          this.$emit('finishedAction')
          const alertParams = {
            textItems: [
              {text: 'char', style: 'normal', translate: true},
              {text: item.title, style: 'bold', translate: false},
              {text: 'created', style: 'normal', translate: true},
            ],
            link: {
              text: 'Показать',
              action: 'show-item',
              actionParams: item
            }
          }
          this.$emit('show-alert', alertParams)
          this.closeDetails();
        } else {
          this.isExistTitle = this.propertyName ? 'Уже есть' : null;
        }
      } catch (e) {
        console.log(e)
      }
    }
  },
  validations: {
    propertyName: {required}
  }
}
</script>

<style scoped lang="scss">
.btn-sticky {
  position: sticky;
  bottom: 0;
}

.item-form {
  padding: 0;
}

.input-label {
  margin-bottom: 13px;
}

.base-button {
  height: 40px !important;
  margin: 0 0 0 auto;
  width: 100%;
  max-width: 160px;
}

.propTitle {
  display: flex;
  justify-content: space-between;
  align-items: center;

  small {
    font-weight: 550;
    font-size: 12px;
    line-height: 12px;
    text-align: right;
    text-transform: none;
    color: #FF9F2F;
  }
}

.error-on-select {
  border-color: #FF9D2B !important;

  & .v-input__slot:before {
    border-color: #FF9D2B !important;
  }
}

.item-name input {
  line-height: 1.2;
}

.item-area {
  margin: 0 -25px;
  &-col {
    padding: 0 25px;
  }
}
</style>
