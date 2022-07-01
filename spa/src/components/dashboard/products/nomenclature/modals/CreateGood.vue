<template>
  <div class="dialog-wrap">
    <modal-container
        v-if="isModalOpen"
        custom-dialog-class="nomenclature-create-modal"
        @clickOutside="onCloseModal"
        :dialog="isModalOpen"
        :modalWidth="1200"
    >
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
              <div>{{ $t(`nomenclature.modes.${mode}`) }}
                  <div
                      v-if="canChangeOption"
                      @click="dropDownState =! dropDownState"
                      v-click-outside="onClickOutside"
                      class="products-select"
                  >
                    {{ $t(`nomenclature.modal_resource.${resource.SINGLE_VALUE}`) }}
                    <svg-sprite width="11" height="6" icon-id="arrowDown"></svg-sprite>
                    <ul v-show="dropDownState" class="products-select-option">
                        <li v-for="(resource, index) in resources"
                            :key="resource.SINGLE_VALUE"
                            :class="{'active' : index === resourceIndex}"
                            @click.stop="toggleResource(index)">
                          {{ $t(`nomenclature.modal_resource.${resource.SINGLE_VALUE}`) }}
                        </li>
                    </ul>
                  </div>
                  <span v-else>
                      {{ $t(`nomenclature.modal_resource.${resource.SINGLE_VALUE}`) }}
                  </span>
              </div>
          </div>
          <div class="progress progress-category">
            <progress-bar
                v-if="isModalOpen"
                :title="$t('nomenclature.progressBar')"
                :items="productProgressBar"
                isShowBtn
            />
          </div>
          <header-actions
              id="goods"
              :title="$t(`nomenclature.modes.${mode}`) + ' ' + 'товар'"
              @onCloseModal="onCloseModal"
              @updateClose="onCloseModal"
              :is-hide-attach="!isCreate"
              isHideDots
          ></header-actions>
        </div>
      </template>
      <template v-slot:main>
        <div class="dialog-main">
          <div class="dialog-content dialog-content-large">
            <div class="form-wrapper">
              <div class="from-step-wrapper">
                <div class="create-steps-container" v-if="resourceIndex !== 1">
                  <div v-for="(step, index) in formSteps" :key="index" class="step"
                       :class="{'active': index === currentStep}">
                    <span class="step-number" @click="goToStep(index)">{{ index + 1 }}</span>
                    <span class="step-name">{{ $t(`nomenclature.modal_steps.${step.title}`) }}</span>
                  </div>
                </div>
                <div class="add-property-value"
                     v-if="properties.length && formSteps[currentStep].title === 'products_chars'"
                     @click="openPropertyModal">
                  <svg-sprite width="12" height="12" icon-id="plusBlue"></svg-sprite>
                  Добавить характеристику
                </div>
              </div>
              <simplebar class="dialog-main-body">
                <keep-alive>
                  <component
                      @change-group-state="onChangeGroupState"
                      @changeCategory="changeCategory"
                      @stepUpdated="mergeStepData"
                      @click-choose-btn="openChooseModal"
                      :resource="resource"
                      :data="formData"
                      :is="currentComponent"
                      :categories="categories"
                      :category="category"
                      :mode="mode"
                      :is-group="isGroup"
                      :suppliers="suppliers"
                      :units="units"
                      :pricesTypes="pricesTypes"
                      :choose-modal-resource="chooseModalResources"
                      :countries="countries"/>
                </keep-alive>
              </simplebar>
            </div>
          </div>
        </div>
        <choose-modal
            v-if="isOpenChooseModal"
            @close-modal="onCloseChooseModal"
            @fetch-category-items="onFetchCategoryItemsForChoose"
            @select-item-to-choose="onSelectItemToChose"
            @save-selected-items="onSaveChooseItems"
            :resource="chooseModalResource"
            :categories="categories"
            :title-text="chooseModalTitle"
            :is-modal-open="isOpenChooseModal"
            :items-to-select="filteredItemsToItems"
            :selected-items="chooseModalSelectedItems"
        >
          <template v-slot:products-actions>
            <button :disabled="!chooseModalSelectedItems.length" class="choose-btn"
                    @click="onClickAddChooseSelectedItems" type="button">
              Добавить
            </button>
          </template>
          <template v-slot:main-table>
            <table-with-prices
                v-if="selectedItems"
                @del-select-item="onDelSelectedItem"
                :body="selectedItems.body"
                :headers="selectedItems.headers"
            />
          </template>
        </choose-modal>
      </template>
      <template v-slot:footer>
        <alert
            :text-items="alertTextItems"
            :link="alertLink"
            :custom-class="customAlertClass"
            v-if="isAlertShow">
        </alert>
        <div class="dialog-footer dialog-actions popup-buttons">
          <custom-btn
              custom-class="cancel"
              :title="isFirstStep ? 'Назад' : getCorrectText ? 'Отменить' : 'Закрыть'"
              color="#5893D4"
              @updateEvent="isFirstStep ? goToPrevStep(): onCloseModal()"
              text
          ></custom-btn>
          <custom-btn
              v-if="!lastStep"
              custom-class="save"
              title="Далее"
              color="#5893D4"
              @updateEvent="goToNextStep"
          ></custom-btn>
          <custom-btn
              v-if="lastStep && (isCreate || isCopy)"
              custom-class="save"
              title="Добавить"
              color="#5893D4"
              @updateEvent="viewItem"
          ></custom-btn>
          <custom-btn
              v-if="lastStep && isUpdate"
              custom-class="save"
              title="Сохранить"
              color="#5893D4"
              @updateEvent="changeItem"
          ></custom-btn>
        </div>
      </template>
    </modal-container>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex"
import {eventBus} from '@/main'
// components
import GoodsFormFirst from "@/components/dashboard/products/nomenclature/modals/froms/GoodsFormFirst";
import GoodsFormSecond from "@/components/dashboard/products/nomenclature/modals/froms/GoodsFormSecond";
import GoodsFormThird from "@/components/dashboard/products/nomenclature/modals/froms/GoodsFormThird";
import GoodsFormFourth from "@/components/dashboard/products/nomenclature/modals/froms/GoodsFormFourth";
import ProgressBar from "@/components/ui/ProgressBar/ProgressBar";
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer";
import ChooseModal from "@/components/ui/ChooseModal/ChooseModal";
import TableWithPrices from "@/components/ui/TableWithPrices";
import Alert from "@/components/ui/Alert";
import ServiceForm from "./froms/ServiceForm";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
// constants
import {NOMENCLATURE_RESOURCES, TABLE_ACTIONS, CHOOSE_MODAL_RESOURCES} from "@/constants/constants";
// services
import {fetchItems, selectItems} from "@/services/choose";
// validate
import {required} from 'vuelidate/lib/validators';

export default {
  name: "CreateGood",
  components: {
    HeaderActions,
    Alert,
    TableWithPrices,
    ChooseModal,
    ServiceForm,
    ModalContainer,
    ProgressBar,
    GoodsFormFirst,
    GoodsFormSecond,
    GoodsFormThird,
    GoodsFormFourth,
    CustomBtn
  },
  created() {
    this.currentStep = 0
    if (this.mode !== TABLE_ACTIONS.CREATE) {
      this.form[this.resource.SINGLE_VALUE] = JSON.parse(JSON.stringify(this.data))
    }
    if (this.mode === TABLE_ACTIONS.COPY) {
      this.form[this.resource.SINGLE_VALUE].short_title += `(${this.$t('nomenclature.copy')})`
      this.form[this.resource.SINGLE_VALUE].sku = ''
    }
  },
  data() {
    return {
      selectedItems: null,
      chooseModalTitle: 'test',
      chooseModalItems: [],
      chooseModalSelectedItems: [],
      itemsToSelect: {},
      isOpenChooseModal: false,
      canCreate: false,
      selectedResourceIndex: 0,
      chooseModalResources: CHOOSE_MODAL_RESOURCES,
      chooseModalResource: {},
      dialogGood: false,
      currentStep: 0,
      product: {},
      service: {},
      defaultData: null,
      isSelectedCategory: false,
      dropDownState: false,
      currentItemId: null,
      customAlertClass: 'error-alert',
      isAlertShow: false,
      alertTextItems: [{text: 'validation_error', style: 'bold', translate: true}],
      alertLink: false,
      validationResult: true,
      form: {
        product: {
          desc: null,
          category_id: null,
          categories: [],
          min_price: null,
          short_title: null,
          dock_title: null,
          sku: null,
          lower_limit: null,
          unit_id: 1,
          supplier_id: null,
          country_id: null,
          barcode_supplier: null,
          barcode: null,
          is_visible: true,
          prices: [],
          price: [],
          related: [],
          characteristic_value: [],
          characteristic_color_value: null,
          base_characteristic_value: null,
          weight: null,
          volume: null,
          weight_id: 1,
          volume_id: 1,
          property_value: [],
          files: []
        },
        service: {
          desc: null,
          category_id: '',
          price: [],
          categories: [],
          short_title: '',
          is_visible: false,
          dock_title: [
            {
              code: "ru",
              title: ""
            },
            {
              code: "ua",
              title: ""
            }
          ],
          sku: null,
          unit_id: 1,
          supplier_id: null,
          min_price: null,
        }
      },
      steps: {
        productSteps: [
          {component: 'GoodsFormFirst', title: 'main_info', isValid: false},
          {component: 'GoodsFormSecond', title: 'products_options', isValid: true},
          {component: 'GoodsFormThird', title: 'products_chars', isValid: true},
          // {component: 'GoodsFormFourth', title: 'additional_info', isValid: true}
        ],
        serviceSteps: [
          {component: 'GoodsFormFirst', title: 'main_info', isValid: false}
        ],
        kitSteps: [
          {component: 'GoodsFormFirst', title: 'main_info', isValid: false},
          {component: 'GoodsFormFirst', title: 'choose_products', isValid: false},
          {component: 'GoodsFormFirst', title: 'products_options', isValid: false},
          {component: 'GoodsFormFirst', title: 'products_chars', isValid: false},
          {component: 'GoodsFormFirst', title: 'choose_products', isValid: false},
        ]
      },
      directories: [
        {directory: 'units', formValue: 'unit_id'},
        {directory: 'countries', formValue: 'country_id'},
        {directory: 'pricesTypes', formValue: 'unit_id'},
        {directory: 'suppliers', formValue: 'supplier_id'},
      ],
    }
  },
  validations: {
    form: {
      product: {
        category_id: {required},
        short_title: {required},
        dock_title: {required},
      }
    },
  },
  props: {
    isModalOpen: Boolean,
    isGroup: Boolean,
    categories: Array,
    mode: String,
    data: Object,
    resourceIndex: Number,
    units: Array,
    pricesTypes: Array,
    countries: Array,
    suppliers: Array
  },
  computed: {
    ...mapGetters({
      category: 'category'
    }),
    getCorrectText() {
      return this.mode === TABLE_ACTIONS.CREATE ||
          this.mode === TABLE_ACTIONS.COPY
    },
    isFirstStep() {
      return this.currentStep
    },
    formData() {
      const resource = this.resource.SINGLE_VALUE
      return this.form[resource]
    },
    properties() {
      if (this.category) {
        return this.category.properties.filter(property => (property.id !== 1 && property.id !== 2))
      }
      return []

    },
    resources() {
      return [
        {...NOMENCLATURE_RESOURCES.PRODUCTS},
        {...NOMENCLATURE_RESOURCES.SERVICES},
        /*{...NOMENCLATURE_RESOURCES.KITS},*/
      ]
    },
    filteredItemsToItems() {
      if (this.selectedItems) {
        const body = this.itemsToSelect.body.filter(itemToSelect => {
          return !this.selectedItems.body.find(selectedItem => {
                return selectedItem.id === itemToSelect.id
              }
          )

        })
        return {
          ...this.itemsToSelect,
          body
        }
      }
      return this.itemsToSelect

    },
    formSteps() {
      return this.steps[`${this.resource.SINGLE_VALUE}Steps`]
    },
    isCreate() {
      return this.mode === TABLE_ACTIONS.CREATE
    },
    isUpdate() {
      return this.mode === TABLE_ACTIONS.UPDATE
    },
    isCopy() {
      return this.mode === TABLE_ACTIONS.COPY
    },
    lastStep() {
      return this.currentStep === this.formSteps.length - 1
    },
    currentComponent() {
      return this.formSteps[this.currentStep].component
    },
    resource() {
      return this.resources[this.resourceIndex]
    },
    canChangeOption() {
      return this.mode === 'create' &&
          (this.resource.SINGLE_VALUE === 'service' || !this.currentStep)
    },
    productProgressBar() {
      return [
        {
          title: 'short_title',
          value: 5,
          exist: !!this.form.product.short_title
        },
        {
          title: 'category',
          value: 5,
          exist: !!this.form.product.category_id
        },
        {
          title: 'sku',
          value: 5,
          exist: !!this.form.product.sku
        },
        {
          title: 'full_title',
          value: 5,
          exist: !!this.form.product.dock_title
        },
        {
          title: 'selling_price',
          value: 10,
          //TO DO
          exist: true
        },
        {
          title: 'purchase_price',
          value: 10,
          //TO DO
          exist: true
        },
        {
          title: 'lower_limit',
          value: 10,
          exist: !!this.form.product.lower_limit
        },
        {
          title: 'min_price',
          value: 10,
          exist: !!this.form.product.min_price
        },
        {
          title: 'desc',
          value: 10,
          exist: !!this.form.product.desc
        },
        {
          title: 'option',
          value: 10,
          exist: !!this.form.product.base_characteristic_value
        }
      ]
    }
  },
  methods: {
    ...mapActions({
      fetchCategory: 'fetchCategory',
      resetCategory: 'resetCategory',
      createItem: 'createNomenclatureItem',
      fetchProperties: 'fetchCategoriesProperties',
      fetchCategoriesCharacteristics: 'fetchCategoriesCharacteristics',
      createNomenclatureItem: 'createProduct',
      resetNomenclatureValidations: 'resetNomenclatureValidations'
    }),
    onClickOutside() {
      this.dropDownState = false;
    },
    openPropertyModal() {
      eventBus.$emit('on-click-add-property-value')
    },
    onCloseChooseModal() {
      this.isOpenChooseModal = false
    },
    openChooseModal({chooseResource}) {
      this.chooseModalResource = chooseResource
      this.isOpenChooseModal = true
    },
    async onClickAddChooseSelectedItems() {
      const data = this.chooseModalSelectedItems
      const url = this.chooseModalResource.SELECT_ITEMS
      try {
        const res = await selectItems(url, data)
        this.chooseModalSelectedItems = []
        if (this.selectedItems) {
          this.selectedItems.body.push(...res.data.data.body)
        } else {
          this.selectedItems = res.data.data
        }
      } catch (e) {
        console.log(e);
      }
    },
    async onFetchCategoryItemsForChoose(url) {
      try {
        const res = await fetchItems(url)
        this.itemsToSelect = res.data.data
      } catch (e) {
        console.log(e);
      }
    },
    toConfirm() {
      this.isConfirm = true
    },
    onCloseModal() {
      this.resetCategory()
      this.$emit('close-modal')
    },
    onCancel() {
      this.isConfirm = false
    },
    onConfirm() {
      this.isConfirm = false
      this.isModalOpen = false
    },
    onOpenModal() {
      this.isModalOpen = true
    },
    viewItem() {
      const currentForm = this.steps[`${this.resource.SINGLE_VALUE}Steps`]
      const invalid = currentForm.some(step => !step.isValid)
      if (invalid) {
        const index = currentForm.findIndex(step => !step.isValid)
        this.goToStep(index)
        this.showAlert()
        eventBus.$emit('validate-nomenclature-form')
      } else {
        const item = this.form[this.resource.SINGLE_VALUE]
        this.$emit('view-item', {item, isGroup: this.is_group})
      }
    },
    showAlert() {
      this.isAlertShow = true
      setTimeout(
          () => {
            this.isAlertShow = false
          },
          1000
      )

    },
    onCreate(params) {
      this.$emit('create', params)
    },
    toggleResource(index) {
      this.$emit('toggle-resource', index)
      this.dropDownState = false
    },
    onChangeGroupState(val) {
      this.$emit('change-group-state', val)
    },
    getDefaultDirectoriesValue({directory, formValue}) {
      if (this[directory].length > 0) {
        const defaultListValue = this[directory][0]
        this.service[formValue] = defaultListValue.id
        this.product[formValue] = defaultListValue.id
      }
    },
    onSelectItemToChose({id}) {
      const index = this.chooseModalSelectedItems.findIndex(item => item.id === id)
      if (index === -1) {
        this.chooseModalSelectedItems.push({id})
      } else {
        this.chooseModalSelectedItems.splice(index, 1)
      }
    },
    onSaveChooseItems(choseResource) {
      const nomenclature = this.resource.SINGLE_VALUE
      const property = choseResource.NAME
      this.form[nomenclature][property] = this.selectedItems
      this.isOpenChooseModal = false
    },
    onDelSelectedItem(id) {
      const index = this.selectedItems.body.findIndex(item => item.id === id)
      this.selectedItems.body.splice(index, 1)
    },
    goToNextStep() {
      this.currentStep++
      if (this.currentStep === this.formSteps.length - 1) {
        this.canCreate = true
      }
    },
    goToPrevStep() {
      this.currentStep--
      if (this.currentStep !== this.formSteps.length - 1) {
        this.canCreate = false
      }
    },
    mergeStepData(step) {
      this.form[step.resource] = {...this.form[step.resource], ...step.data }
      const formStep = this.formSteps.find(item => item.title === step.title)
      formStep.isValid = step.isValid
    },
    changeItem() {
      const params = {
        item: this.form[this.resource.SINGLE_VALUE],
        resource: this.resource.SINGLE_VALUE
      }

      this.$emit('change-item', params)
    },
    goToStep(index) {
      this.currentStep = index;
      this.canCreate = (this.currentStep === this.formSteps.length - 1);
    },
    changeCategory(id) {
      this.fetchCategory(id)
      this.fetchProperties(id)
      this.fetchCategoriesCharacteristics(id)
    }
  },

}
</script>

<style scoped lang="scss">
.dialog-header {
  position: relative;
}

.form-wrapper {
  width: 100%;
}

.add-property-value {
  text-align: right;
  font-size: 15px;
  line-height: 1;
  color: #5893D4;
  cursor: pointer;
  white-space: nowrap;

  svg {
    margin-right: 10px;
  }

  &:hover {
    text-decoration: underline;
  }
}

.from-step-wrapper {
  display: flex;
  align-items: center;
  padding-top: 20px;

  .create-steps-container {
    padding: 0;
  }
}

.products-select {
  width: 131px;
  border: 1px solid #9DCCFF;
  box-sizing: border-box;
  border-radius: 25px;
  padding: 14px 15px 11px;
  margin-left: 7px;
  position: relative;
  display: inline-flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 500;
  font-size: 15px;
  line-height: 15px;
  cursor: pointer;
  /* identical to box height */

  text-transform: uppercase;

  /* 7 */

  color: #FFFFFF;

  &-option {
    position: absolute;
    background: #FFFFFF;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    font-weight: 550;
    font-size: 15px;
    line-height: 15px;
    color: #7E7E7E;
    list-style: none;
    left: 0;
    right: 0;
    top: 100%;
    z-index: 2;
    padding: 10px 0;


    li {
      padding: 11px 15px 4px;
      margin-bottom: 9px;

      &.active {
        background: #F4F9FF;
      }

    }

  }
}
</style>
