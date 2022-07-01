<template>
  <div>
    <modal-container
        custom-dialog-class="setPrice"
        v-if="!isOpen"
        @clickOutside="onConfirm"
        :dialog="isOpen"
        :modalWidth="!tabIndex ? 1200 : 1600"
    >
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            <span>Установака цен</span>
            <span v-if="tabIndex" class="notice">Новые цены будут установлены на: 15.02.2020 </span>
          </div>
          <header-actions is-hide-dots @updateClose="onConfirm(1)"></header-actions>
        </div>
      </template>
      <template v-slot:main>
        <keep-alive>
          <div v-if="!tabIndex" class="dialog-main setFirstPriceTable">
            <div class="top">
              <div class="top_title">
                <span class="required">Выбор Видов цен</span>
              </div>
              <v-row align="start" class="mt-0 mb-0">
                <v-col>
                  <price-table
                      :header="getHeaders"
                      :body="getBody"
                      customClass="tablePrice"
                      @updateGetListIds="() => ''"
                      @updateAmountSum="() => ''"
                      @updateAmountCount="() => ''"
                      @updateDelete="() => ''"
                      @updateItems="() => ''"
                      isCrossDelete
                      isStockTable
                      isCheckItem
                      ref="table"
                  ></price-table>
                </v-col>
                <v-col>
                  <div class="productList">
                    <div class="productList_item">
                      <div class="productList_title">
                        Оптовая для одежды
                      </div>
                      <div class="edit-btn">
                        <button class="btn" @click="onRemoveItem">
                          <svg-sprite width="14" height="14" icon-id="crossSmall"></svg-sprite>
                        </button>
                      </div>
                    </div>
                    <div class="productList_item">
                      <div class="productList_title">
                        Оптовая для одежды
                      </div>
                      <div class="edit-btn">
                        <button class="btn" @click="onRemoveItem">
                          <svg-sprite width="14" height="14" icon-id="crossSmall"></svg-sprite>
                        </button>
                      </div>
                    </div>
                    <div class="productList_item">
                      <div class="productList_title">
                        Розничная для одежды
                      </div>
                      <div class="edit-btn">
                        <button class="btn" @click="onRemoveItem">
                          <svg-sprite width="14" height="14" icon-id="crossSmall"></svg-sprite>
                        </button>
                      </div>
                    </div>
                    <div class="productList_bottom">
                      <span class="reset">Очистить все</span>
                    </div>
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-container class="lefWrapper">
                    <v-row no-gutters>
                      <v-col cols="6">
                        <date-set-price></date-set-price>
                      </v-col>
                      <v-col cols="6">
                        <div>
                          <switch-flag
                              custom-class="priceCheckbox"
                              type="checkbox"
                              id="currentPrice"
                              @updateValue="onHandler"
                              :title="$t('crm.priceModal.currentPrice')"
                          ></switch-flag>
                          <switch-flag
                              custom-class="priceCheckbox"
                              type="checkbox"
                              id="shiftPrice"
                              @updateValue="onHandler"
                              :title="$t('crm.priceModal.shiftPrice')"
                          ></switch-flag>
                          <switch-flag
                              custom-class="priceCheckbox"
                              type="checkbox"
                              id="dependencyPrice"
                              @updateValue="onHandler"
                              :title="$t('crm.priceModal.dependencyPrice')"
                          ></switch-flag>
                          <switch-flag
                              custom-class="priceCheckbox"
                              type="checkbox"
                              id="minPrice"
                              @updateValue="onHandler"
                              :title="$t('crm.priceModal.minPrice')"
                          ></switch-flag>
                        </div>
                      </v-col>

                    </v-row>
                  </v-container>
                  <v-row class="mt-0 mb-0">
                    <v-col class="attention">(*) - Поля, обязательные для заполнения</v-col>
                  </v-row>
                </v-col>
                <v-col cols="6">
                  <div class="selectionOfGoods" :class="{'changeStyle': isFocusSelect}">
                    <v-row>
                      <v-col cols="6">
                        <div class="select-wrap">
                          <div class="select-wrap_title">
                            Подбор номенклатуры
                          </div>
                          <div class="item">
                            <div class="input-wrap">
                              <v-select
                                  class="select"
                                  v-model="selectGoods"
                                  @focus="onFocus"
                                  @blur="onBlur"
                                  :items="goodsItems"
                                  :menu-props="{contentClass: 'currencyDropDown'}"
                                  item-text="title"
                                  item-value="id"
                                  no-data-text="Пусто"
                                  :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.selectView')"
                                  @change="() => ''"
                                  dense
                              ></v-select>
                            </div>
                          </div>
                        </div>
                      </v-col>
                      <v-col cols="6">
                        <div class="select-wrap">
                          <div class="select-wrap_title">
                            Склады
                          </div>
                          <div class="item">
                            <div class="input-wrap">
                              <v-select
                                  class="select"
                                  v-model="selectGoods"
                                  @focus="onFocus"
                                  @blur="onBlur"
                                  :items="goodsItems"
                                  :menu-props="{contentClass: 'currencyDropDown'}"
                                  item-text="title"
                                  item-value="id"
                                  no-data-text="Пусто"
                                  :placeholder="$t('page.suppliers.modal.createSupplier.firstForm.selectView')"
                                  @change="() => ''"
                                  dense
                              ></v-select>
                            </div>
                          </div>
                        </div>
                      </v-col>
                    </v-row>
                  </div>
                </v-col>
              </v-row>
            </div>
          </div>
        </keep-alive>
        <popup-table></popup-table>
<!--        <div v-if="tabIndex" class="dialog-main setSecondPriceTable">
          <div class="btn-back" @click="tabIndex&#45;&#45;">
            <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
            <span>Назад</span>
          </div>
          <div class="setSecondPriceTable_top">
            <div class="right">
              <div class="wrapTypeOfAllowance">
                <span>Изменить на:</span>
                <label class="typeOfAllowance">
                  <input type="radio" name="typeOfAllowance" value="1" checked>
                  <span class="typeOfAllowance_text">Сумму</span>
                </label>
                <label class="typeOfAllowance">
                  <input type="radio" name="typeOfAllowance" value="2">
                  <span class="typeOfAllowance_text">Процент</span>
                </label>
              </div>
              <numeric-input customClass="setPriceInput" :min="0" @input="onInput"></numeric-input>
              <v-btn-toggle
                  active-class="active"
                  class="toggleBtnGroup"
                  v-model="toggle_exclusive"
                  rounded
                  mandatory
              >
                <v-btn class="toggleBtn">
                  <svg-sprite width="15" height="12" icon-id="v"></svg-sprite>
                </v-btn>
                <v-btn class="toggleBtn">
                  <svg-sprite width="12" height="12" icon-id="crossAnother"></svg-sprite>
                </v-btn>
              </v-btn-toggle>
              <div class="wrZero">
                <span class="zeroBtn">
                <svg-sprite width="18" height="13" icon-id="zero"></svg-sprite>
              </span>
                <span class="eraserBtn">
                <svg-sprite width="15" height="15" icon-id="eraser"></svg-sprite>
              </span>
              </div>
            </div>
          </div>
          <div class="setSecondPriceTable_body">
            <div class="left">
              <div class="left_top">
                <div class="left_top-title">товары</div>
                <div class="left_top-btn">
                  <span>Подобрать товары заново</span>
                  <tooltip-with-icon
                      color="#F4F9FF"
                      customClass="delete"
                      :tooltip-text="$t('page.wareHouses.stockModal.deleteString')"
                      :iconOption="{  width: '13px', height: '15px', iconId: 'bin'}"
                      @updateClickValue="() => ''"
                  ></tooltip-with-icon>
                </div>
              </div>
              <div class="grid-table-wrapper firstTable">
                <div class="grid-table">
                  <div class="thead">
                    <div class="th">1</div>
                    <div class="checkbox th">
                      <switch-flag
                          custom-class="tCh"
                          type="checkbox"
                          id="currentPrice"
                          @updateValue="onHandler"
                      ></switch-flag>
                    </div>
                    <div class="th">
                      Артикул
                    </div>
                    <div class="th">
                      Наименование
                    </div>
                    <div class="th last">
                      Мин. цена
                    </div>
                  </div>
                  <div class="tbody">
                    <div class="tr">
                      <div class="td">1.</div>
                      <div class="checkbox td">
                        <switch-flag
                            custom-class="tCh"
                            type="checkbox"
                            id="currentPrice"
                            @updateValue="onHandler"
                        ></switch-flag>
                      </div>
                      <div class="td">
                        52478454411
                      </div>
                      <div class="td">
                        Куртка красная XL
                      </div>
                      <div class="td last">
                        1 500
                      </div>
                    </div>
                    <div class="tr">
                      <div class="td">1.</div>
                      <div class="checkbox td">
                        <switch-flag
                            custom-class="tCh"
                            type="checkbox"
                            id="currentPrice"
                            @updateValue="onHandler"
                        ></switch-flag>
                      </div>
                      <div class="td">
                        52478454411
                      </div>
                      <div class="td">
                        Куртка красная XL
                      </div>
                      <div class="td last">
                        1 500
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="right">
              <div class="grid-table-wrapper secondTable">
                <div class="grid-table">
                  <div class="thead">
                    <div class="thead_top">
                      <div class="item">
                        <div class="th">Оптовая для одежды</div>
                        <div class="th">Оптовая с наценкой +...</div>
                        <div class="th">Оптовая с наценкой +...</div>
                      </div>
                      <div class="item">
                        <div class="th">Оптовая для одежды</div>
                      </div>
                    </div>
                    <div class="tr">
                      <div class="th">600,00</div>
                      <div class="th">
                        800,00
                      </div>
                      <div class="th">
                        830,00
                      </div>
                      <div class="th last">
                        860,00
                      </div>
                    </div>
                  </div>
                  <div class="tbody">
                    <div class="tr">
                      <div class="td">1.</div>
                      <div class="checkbox td">
                        <switch-flag
                            custom-class="tCh"
                            type="checkbox"
                            id="currentPrice"
                            @updateValue="onHandler"
                        ></switch-flag>
                      </div>
                      <div class="td">
                        52478454411
                      </div>
                      <div class="td">
                        Куртка красная XL
                      </div>
                      <div class="td last">
                        1 500
                      </div>
                    </div>
                    <div class="tr">
                      <div class="td">1.</div>
                      <div class="checkbox td">
                        <switch-flag
                            custom-class="tCh"
                            type="checkbox"
                            id="currentPrice"
                            @updateValue="onHandler"
                        ></switch-flag>
                      </div>
                      <div class="td">
                        52478454411
                      </div>
                      <div class="td">
                        Куртка красная XL
                      </div>
                      <div class="td last">
                        1 500
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>-->
      </template>
      <template v-slot:footer>
        <div class="dialog-footer">
          <custom-btn
              custom-class="cancel"
              :title="!tabIndex ? $t('nomenclature.close') : 'назад'"
              color="#5893D4"
              @updateEvent="onConfirm"
              text
          ></custom-btn>
          <custom-btn
              :title="!tabIndex ? 'Далее к назначению ': 'Установка цен'"
              custom-class="save"
              color="#5893D4"
              @updateEvent="onSwitchTab"
          ></custom-btn>
        </div>
      </template>
    </modal-container>


    <confirm-modal :isConfirm="isConfirm" @updateConfirmBack="onCancel" @updateConfirmClose="onCloseModal"></confirm-modal>
  </div>
</template>

<script>
// components
// import NumericInput from '@/components/ui/NumericInput/NumericInput';
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import ConfirmModal from "@/components/ui/ConfirmModal/ConfirmModal";
import SwitchFlag from "@/components/ui/SwitchFlag/SwitchFlag";
// import TooltipWithIcon from "@/components/dashboard/products/wareHouses/StockModal/components/TooltipWithIcon";
import PriceTable from "../PriceTable/PriceTable";
import DateSetPrice from "./components/DateSetPrice";
import PopupTable from "./PopupTable";

export default {
  name: "SetPriceModal",
  components: {
    //NumericInput,
    ModalContainer,
    HeaderActions,
    PriceTable,
    ConfirmModal,
    CustomBtn,
    SwitchFlag,
    DateSetPrice,
    // TooltipWithIcon,
    PopupTable
  },
  props: {
    isOpen: Boolean,
    pricesTable: Object
  },
  computed: {
    getHeaders() {
      return this.pricesTable.headers.map(item => ({...item}));
    },
    getBody() {
      return this.pricesTable.body.map(item => ({...item}));
    },
  },
  data: () => ({
    toggle_exclusive: undefined,
    tabIndex: 1,
    isFocusSelect: false,
    isConfirm: false,
    selectedItems: null,
    selectGoods: {},
    goodsItems: [
      {
       id: 1,
       title: "По приходным документам"
      },
      {
        id: 2,
        title: "По категории товара"
      },
      {
        id: 3,
        title: "По виду цен"
      },
      {
        id: 4,
        title: "Из остатков на складах"
      },
      {
        id: 5,
        title: "Из списка товаров"
      }
    ],
    checkBoxes: {
      currentPrice: false,
      shiftPrice: false,
      dependencyPrice: false,
      minPrice: false
    }
  }),
  methods: {
    onInput(value) {
      console.log(value)
    },
    onSwitchTab() {
      !this.tabIndex ? this.tabIndex++ : this.tabIndex--;
    },
    onCloseModal() {
      this.isConfirm = false;
      this.$emit('updateOpen')
    },
    onCancel() {
      this.isConfirm = false
    },
    onConfirm(key) {
      if (key) {
        this.isConfirm = true;
        return false;
      }

      if (!this.tabIndex) {
        this.isConfirm = true
      } else {
        this.tabIndex--;
      }

    },
    onOpenModal() {
      this.isModalOpen = true
    },
    onHandler({value, id}) {
      this.checkBoxes[id] = value;
    },
    onRemoveItem() {
      console.log(12123)
    },
    onFocus() {
      this.isFocusSelect = true;
    },
    onBlur() {
      this.isFocusSelect = false;
    }
  },
  async mounted() {
    this.selectGoods = this.goodsItems && this.goodsItems[0];
  }
}
</script>

<style scoped lang="scss">
.select-wrap {
  &_title {
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #317CCE;
  }
}

.wrZero {
  margin: 0 10px;

  .btnR {
    border-radius: 50%;
    transition: background-color .3s ease-in;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    margin: 0 5px;
    cursor: pointer;
  }

  .zeroBtn {
    @extend .btnR;
    background: #9DCCFF;

    &:hover {
      background: #5893D4;
    }
  }

  .eraserBtn {
    @extend .btnR;

    background: #FF9F2F;

    &:hover {
      background: #FF951A;
    }
  }
}


.btn-back {
  font-size: 15px;
  line-height: 15px;
  color: #5893D4;
  cursor: pointer;
  display: inline-flex;

  span {
    display: inline-block;
    margin-left: 5px;
  }
}

.notice {
  display: inline-block;
  margin-left: 30px;
  font-size: 13px;
  line-height: 13px;
  color: #FFFFFF;
  text-transform: none;
}

.attention {
  font-size: 15px;
  line-height: 15px;
  color: #9DCCFF;
}

.selectionOfGoods {
  background: #F4F9FF;
  border: 1px solid #F4F9FF;
  border-radius: 10px;
  padding: 20px;

  &.changeStyle {
    border: 1px solid #E3F0FF;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    background: #ffffff;
  }
}

.productList {
  &_item {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    background: #E3F0FF;
    border-radius: 10px;
    padding: 2px 10px;
    margin-bottom: 10px;
    min-height: 30px;

    &:nth-of-type(odd) {
      margin-right: 10px;
    }
  }

  &_title {
    font-size: 13px;
    line-height: 13px;
    color: #317CCE;
  }

  .edit-btn {
    margin-left: 10px;
    display: flex;
    align-items: center;
  }

  &_bottom {
    margin-top: 10px;
    font-size: 0;
    .reset {
      font-weight: 550;
      font-size: 13px;
      line-height: 13px;
      text-decoration-line: underline;
      color: #9DCCFF;
      cursor: pointer;
    }
  }
}

.lefWrapper {
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
  border-radius: 10px;
}

.dialog-footer {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
}

.top_title {
  font-weight: bold;
  font-size: 13px;
  line-height: 13px;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  color: #317CCE;
}

.required:after {
  content: '*';
  color: #9DCCFF;
  margin-left: 2px;
}

.setSecondPriceTable {
  &_top {
    display: flex;
    justify-content: flex-end;

    .right {
      display: flex;
      align-items: center;
      .wrapTypeOfAllowance {
        & > span {
          font-size: 14px;
          line-height: 14px;
          color: #5893D4;
          margin-right: 5px;
        }

        .typeOfAllowance {
          display: inline-flex;
          align-self: center;
          justify-content: center;
          cursor: pointer;
          margin: 0 5px;

          input[type="radio"] {
            display: none;
          }

          input[type="radio"]:checked  + .typeOfAllowance_text {
            background: #9DCCFF;
            color: #fff;
          }

          &_text {
            font-weight: 550;
            font-size: 12px;
            line-height: 12px;
            padding: 10px 5px;
            color: #9DCCFF;
            width: 80px;
            border: 1px solid #BBDBFE;
            box-sizing: border-box;
            border-radius: 100px;
            text-align: center;
            transition: all .3s ease-in;

            &:hover {
              background: #9DCCFF;
              color: #fff;
            }
          }
        }
      }
    }
  }

  &_body {
    display: flex;
    justify-content: space-between;

    .left {
      width: 560px;
      margin-right: 10px;

      .left_top {
        display: flex;
        justify-content: space-between;
        align-items: center;

        &-btn {
          font-weight: 550;
          font-size: 13px;
          line-height: 13px;
          text-align: right;
          text-decoration-line: underline;
          color: #9DCCFF;
          cursor: pointer;

          &:hover {
            text-decoration-line: none;
          }
        }

        &-title {
          font-weight: bold;
          font-size: 13px;
          line-height: 13px;
          letter-spacing: 0.02em;
          text-transform: uppercase;
          color: #317CCE;
        }
      }
    }

    .right {
      width: calc(100% - 570px);
    }
  }
}
</style>

<style lang="scss">
@import "style";

.toggleBtnGroup {
  &.theme--light.v-btn-toggle:not(.v-btn-toggle--group) .v-btn.v-btn {
    border-color: #9DCCFF !important;
  }
  .toggleBtn {
    height: 32px !important;
    background: #fff !important;
    min-width: 40px !important;
    max-width: 40px;

    svg {
      color: #9DCCFF;
    }

    &.active {
      background: #9DCCFF !important;
      svg {
        color: #fff;
      }
    }

    &:before {
      display: none;
    }

    .v-ripple__container {
      display: none;
    }
  }
}

.select {
  .v-input__slot {
    &:before {
      border-color: #9DCCFF !important;
    }

    &:after {
      display: none;
    }
  }
}

.currencyDropDown {
  &.v-menu__content.theme--light.v-menu__content--fixed.menuable__content__active {
    margin-top: 27px !important;
  }
}

.selectionOfGoods {
  .input__slot {
    &::before, &::after {
      display: none !important;
    }
  }
}
</style>