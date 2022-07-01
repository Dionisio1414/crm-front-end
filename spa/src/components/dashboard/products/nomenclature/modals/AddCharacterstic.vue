<template>
  <v-dialog
      max-width="920px"
      overlay-opacity="0.7"
      v-model="dialog"

      class="dialog-large update-item"
  >
    <div class="dialog-header"  @click="isEdit = false">
      <div class="header-text" style="margin-bottom: 20px">
        {{$t('nomenclature.add_products_option')}}
      </div>
    </div>
    <div class="dialog-content dialog-content-large"  @click="isEdit = false">
      <div class="item-form add-product-stat">
        <div class="item">
          <div class="search-list">
            <div class="search-block">
              <input type="text" :placeholder="$t('page.search')" v-model="searchCharacteristic">
              <div class="search-icon">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M7.685 14.3335C11.377 14.3335 14.37 11.3487 14.37 7.66674C14.37 3.9848 11.377 1 7.685 1C3.99298 1 1 3.9848 1 7.66674C1 11.3487 3.99298 14.3335 7.685 14.3335Z"
                      stroke="#5893D4" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M16.0002 16L12.3652 12.375" stroke="#5893D4" stroke-linecap="round"
                        stroke-linejoin="round"/>
                </svg>
              </div>
              <v-btn icon class="add-new" @click="createCharacteristic" color="#5893D4">
                <svg-sprite width="30" height="30" icon-id="join_plus"></svg-sprite>
              </v-btn>
            </div>
            <simplebar class="wrap-list" force-visible>
              <ul class="list" v-if="characteristicsList">
                <li
                    v-for="element in searchedCharacteristics"
                    :key="element.id"
                    :class="{selected: selectCharacteristics.find(({id}) => id === element.id)}"
                >
                  <span>{{ element.title }}</span>
                  <span @click.stop="editCharacteristic(element)" class="item-edit">
                      <svg-sprite style="color: rgb(187,219,254)" width="23" height="23" icon-id="editSmall"></svg-sprite>
                    </span>
                  <span class="remove-value" @click="deleteSelectCharacteristic(element.id)" :class="{ disabled: category.characteristics.findIndex(item => item.id === element.id) !== -1}">
                      <svg-sprite width="30" height="30" icon-id="minus"></svg-sprite>
                    </span>
                  <span class="add-value" @click="addSelectCharacteristic(element)">
                      <svg-sprite width="30" height="30" icon-id="add"></svg-sprite>
                    </span>
                </li>
              </ul>
            </simplebar>
          </div>
        </div>
        <div class="item">
          <div class="label-title">{{$t('nomenclature.options_products')}}</div>
        </div>
        <div class="values-list">
          <div class="value-item" v-for="(element, index) in selectCharacteristics"
               :key="`${element.title}${element.id}`">
            <span>{{ element.title }}</span>
            <span v-if="category.characteristics.findIndex(item => item.id === element.id) === -1"
                  @click="deleteChipCharacteristic(index)">
              <svg-sprite  width="12" height="13" icon-id="crossSmall"></svg-sprite>
            </span>
          </div>
        </div>
        <div>
          <characteristic-create
              v-if="isCreate"
              v-click-outside="onclickOutside"
              :closeDetails="closeDetails"
          ></characteristic-create>
          <characteristic-edit
              v-if="isEdit"
              @click.stop
              :closeDetails="closeDetails"
              :characteristicEdit="characteristicEdit"/>
        </div>

      </div>
    </div>
    <div class="dialog-content dialog-content-large">
      <div class="dialog-actions popup-buttons">
        <custom-btn
            custom-class="cancel"
            title="Назад"
            color="#5893D4"
            @updateEvent="close()"
            text
        ></custom-btn>
        <custom-btn
            title="Добавить"
            custom-class="save"
            :is-disabled="!selectCharacteristics.length"
            color="#5893D4"
            @updateEvent="save"
        ></custom-btn>
      </div>
    </div>
  </v-dialog>

</template>

<script>
import {mapActions, mapGetters} from 'vuex'
import CharacteristicCreate from "@/components/dashboard/characteristic/CharacteristicCreate";
import CharacteristicEdit from "@/components/dashboard/characteristic/CharacteristicEdit";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";

export default {
  name: "AddCharacterstic",
  components: {CustomBtn, CharacteristicEdit, CharacteristicCreate},
  data() {
    return {
      dialog: false,
      isEdit: false,
      isCreate: false,
      characteristicEdit: {
        title: ''
      },
      editChar: false,
      searchCharacteristic: '',
      selectCharacteristics: [],
      characteristicsList: [],
    }
  },
  computed: {
    ...mapGetters({
      characteristics: 'characteristics',
      colorData: 'colorData',
      sizeData: 'sizeData',
    }),
    searchedCharacteristics() {
      if (this.searchCharacteristic.length >= 3) {
        return [...this.characteristicsList].filter(item => item.title.includes(this.searchCharacteristic))
      } else return this.characteristicsList
    }

  },
  props: [
    'category'
  ],
  methods: {
    ...mapActions({
      fetchData: 'fetchData',
      changeCategory: 'changeCategory'
    }),
    createCharacteristic() {
      this.isCreate = true
    },
    onclickOutside() {
      if (this.isEdit) {
        this.isEdit = false
      }
      if (this.isCreate) {
        this.isCreate = false
      }
    },
    open() {
      this.dialog = true
      this.fetchData().then(
          () => {
            this.selectCharacteristics = []
            this.showSelectedCharacteristics()
            this.characteristicsList = [
              this.colorData,
              this.sizeData,
              ...this.characteristics
            ]
          }
      )
    },
    showSelectedCharacteristics() {
      if (this.category.characteristics.length > 0) {
        this.selectCharacteristics.push(...this.category.characteristics)
      }
    },
    save() {
      const updatedCategory = JSON.parse(JSON.stringify(this.category))
      console.log(updatedCategory);
      updatedCategory.characteristics = [...this.selectCharacteristics].map(item => { return {id:item.id}})
      this.changeCategory(updatedCategory).then(() => this.close())
    },
    agree() {

    },
    close() {
      this.dialog = false
      this.selectCharacteristics = []
    },
    deleteChipCharacteristic(index) {
      this.selectCharacteristics.splice(index, 1)
    },
    deleteSelectCharacteristic(id) {
      const index = this.selectCharacteristics.findIndex(item => item.id === id)
      this.selectCharacteristics.splice(index, 1)
    },
    addSelectCharacteristic(item) {
      this.selectCharacteristics.push(item)
    },
    editCharacteristic(item) {
      // console.log(item);
      this.characteristicEdit = item
      this.isEdit = true
    },
    closeDetails() {
      this.isCreate = false;
      this.isEdit = false;
      this.characteristicsList = [
        this.colorData,
        this.sizeData,
        ...this.characteristics
      ]
    },

  },
}
</script>

<style lang="scss">
.item-form.add-product-stat {
  padding-top: 30px!important;
}
.popup-buttons {
  display: flex;
  justify-content: center;
}
.add-product-stat {
  .selection-bar {
    width: 100%;
    display: flex;
    margin-bottom: 20px;
  }
  .value-list-color {
    .value-item {
      min-height: 42px;
      max-width: 240px;
    }
    .select-style {
      width: 18px;
      height: 18px;
    }
  }

  .select-value {
    display: flex;
    align-items: center;
    span{
      font-size: 14px;
      color: #9DCCFF;
      display: inline-block;
      margin-right: 10px;
    }

    .select-style {
      margin-right: 10px
    }
    svg {
      cursor: pointer

    }
  }


  .table-responsive .v-data-table__wrapper {
    overflow-y: auto;
    height: 100%;
    max-height: 200px;
  }
  position: relative;

  .remove-value {
    &.disabled {
      pointer-events: none;
      opacity: .3;
    }
  }

  .values-titles {
    display: flex;
    width: 100%;
    justify-content: space-between;

    label {
      &:last-child {
        text-align: right !important;
      }
    }
  }

  /*.size-grid {
    width: 100%;
    display: flex;
    padding-top: 20px;

    .size-item {
      &:last-child {
        margin-right: 0;

        .custom-size-content {
          display: flex;
          svg {
            cursor: pointer;
            &.disabled {
              opacity: .5;
              cursor: auto;
            }
          }

          input {
            margin-right: 10px;
          }
        }
      }

      &-title {
        font-size: 14px;
        color: #7E7E7E;
      }

      input {
        margin-bottom: 0;

        &:before {
          border-color: #9DCCFF;
        }

        &:after {
          background-color: #9DCCFF;
        }
      }

      label {
        font-weight: normal;
      }

      .list-group {
        list-style: none;
        padding: 0;
        margin: 0;

        li {
          height: 36px;
          border-bottom: 1px solid #E3F0FF;
          padding-left: 20px;
          padding-right: 20px;
        }
      }

      min-width: 0;
      max-width: 230px;
      width: 30%;
      margin-right: 40px;

      .size-item-content {
        width: 100%;
        background: #fff;
        border: 1px solid #E3F0FF;
        padding: 5px 0;
        border-radius: 10px;
        max-height: 158px;
      }
    }

  }*/

  .item-area {
    width: 655px;
    margin: 0 auto;
    height: auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    position: absolute;
    top: 30px;
    z-index: 2;
    left: 50px;
    justify-content: space-between;

    .item-form {
      min-height: 0;
      padding: 0;

      .selection-bar .select-btn {
        width: 108px !important;
        height: 80px !important;
        background-color: transparent !important;
        border: 1px solid #BBDBFE;
        border-radius: 10px;
        font-size: 13px;
        color: #3282B8;
        text-transform: none;
        letter-spacing: 0;
        box-shadow: none;
        margin-right: 20px;
        padding: 20px;
        white-space: normal;
      }

      .btn-file input {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
      }
    }

    .base-btn {
      background-color: #9DCCFF !important;
      color: #fff;
      box-shadow: none;
      min-width: 160px;
      height: 40px !important;
      max-height: 50px;
      width: 100%;
      max-width: 160px;
      border-radius: 38px;
      font-size: 13px;
      text-transform: capitalize;
      font-weight: bold;
      letter-spacing: 0;
      transition: background-color 0.4s ease;
    }


    .col-list {
      flex-grow: 0;
      align-items: flex-end;

      .values-list {
        max-height: 115px;
        overflow: auto;
      }

      .used {
        display: none;
      }
    }
  }
}

.dialog-content {
  .add-product-stat {
    width: 100%;
    max-width: 760px;
    padding: 0;

    .search-block {
      width: 87%;
      max-width: 100%;
    }

    .search-list {
      height: 300px;
      margin-bottom: 26px;

      .wrap-list {
        height: 70%;
      }
    }
  }
}

.list li span ~ span {
  margin-right: 0;
}
.values-list .value-item span ~ span {
  margin-right: 0;
}
</style>
