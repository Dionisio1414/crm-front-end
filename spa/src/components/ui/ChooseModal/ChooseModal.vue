<template>
  <div class="dialog-wrap">
    <modal-container
        v-if="isModalOpen"
        :custom-dialog-class="['choose-modal']"
        :dialog="isModalOpen"
        :modalWidth="1234"
        @clickOutside="cancel"
    >
      <template v-slot:header>
        <div class="dialog-header">
          <div class="header-text">
            {{ titleText }}
          </div>
          <div class="dialog-header-actions">
            <!--<v-btn icon color="#5893D4">
              <svg-sprite width="15" height="20" icon-id="attach"></svg-sprite>
            </v-btn>-->
            <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="cancel">
              <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
            </v-btn>
          </div>
        </div>
      </template>
      <template v-slot:main>
        <simplebar class="choose-modal-main">
          <v-row>
            <v-col cols="4" class="categories-list">
              <div class="categories-list-header">
                <div class="page-title" :class="{'pending': choosePopupPending}">
                  <span class="page-title-tab label-title" :class="{'active': activeTab === getTabsCategory.BY_CATEGORY}">{{ $t('nomenclature.categories') }}</span>
                  <span class="page-title-tab label-title" @click="getAllGoods" :class="{'active': activeTab === getTabsCategory.ALL}">Все</span>
                </div>
                <div class="search-block">
                  <input type="text" :placeholder="$t('page.search')" v-model="search">
                  <div class="search-icon">
                    <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
                  </div>
                </div>
              </div>
              <simplebar class="categories-list-wrapper" :class="{'pending': choosePopupPending}">
                <div class="tree-menu">
                  <category-menu
                      v-for="category in searchedCategory"
                      :key="category.id"
                      :label="category.title"
                      :nodes="category.children"
                      :depth="0"
                      :id="category.id"
                      @change-category="onChangeActiveCategory"
                  />
                </div>
              </simplebar>
            </v-col>
            <v-col cols="8">
              <div class="product-actions">
                <slot name="products-actions"/>
              </div>
              <div class="table-wrapper chooseModal_warehouse" :class="{'pending': choosePopupPending}">
                <div class="table-responsive">
                  <v-data-table
                      v-if="itemsToSelect"
                      :headers="itemsToSelect.headers"
                      :items="[itemsToSelect.body, itemsToSelect.headers]"
                      class="table no-horizontal-scroll"
                      ref="table"
                      hide-default-header
                      hide-default-footer
                  >
                    <template v-slot:header="{props}">
                      <thead>
                      <tr>
                        <th>
                          <div class="checkbox">
                            <label class="checkbox-label">
                              <input type="checkbox" :checked="isSelectedAll" @click="toggleAll">
                              <span class="checkbox-text"></span>
                            </label>
                          </div>
                        </th>
                        <th v-for="header in props.headers"
                            :key="header.title"
                            :ref="header.value">
                          <div class="th-block">
                            <span class="header-title">{{ header.title }}</span>
                            <span class="sortable-icon" v-if="header.is_sortable">
                              <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
                            </span>
                          </div>
                        </th>
                      </tr>
                      </thead>
                    </template>
                    <template v-slot:body="{items}">
                      <tbody>
                      <template v-for="(val) in items[0]">
                        <item-groups
                            v-if="val['is_groups'] && val.groups"
                            :key="val.id"
                            :dataVal="val"
                            :dataValGroups="val.groups"
                            @updateRest="onRest"
                            @updateGroupsChecked="onGroupsChecked"
                            :isSelectedAll="isSelectedAll"
                            :selectedItemsForChoose="selectedItemsForChoose"
                        ></item-groups>
                        <tr
                            v-if="!val['is_groups']"
                            @click="onRest(val.id)"
                            :key="val.id"
                            :class="{
                              'is-group': val['is_groups'],
                              'disabledTr': val.isDisabled,
                              'short_title': val.short_title,
                              'isRest': isRest
                            }">
                          <td>
                            <div class="checkbox" @click.stop="(() => false)">
                              <label class="checkbox-label">
                                <input type="checkbox"
                                       @change="selectItem(val.id)"
                                       :checked="val.isDisabled || checkSelectedState(val.id)"
                                >
                                <span class="checkbox-text"
                                      :class="{'disabled': !!val.is_editable}"></span>
                              </label>
                            </div>
                          </td>
                          <td v-for="(value, key) in items[1]"
                              :key="key"
                              :ref="key">{{ val.cells[value.value] }}
                          </td>
                        </tr>
                      </template>
                      </tbody>
                    </template>
                  </v-data-table>
                </div>
              </div>
            </v-col>
          </v-row>
          <slot name="rest"/>
          <slot name="main-table"/>
        </simplebar>
      </template>
      <template v-slot:footer>
        <div class="dialog-footer dialog-actions popup-buttons">
          <custom-btn
              custom-class="cancel"
              title="Отменить"
              color="#5893D4"
              @updateEvent="cancel"
              text
          ></custom-btn>
          <custom-btn
              title="Сохранить"
              custom-class="save"
              :is-disabled="isDisabledBtn"
              color="#5893D4"
              @updateEvent="save"
          ></custom-btn>
        </div>
      </template>
    </modal-container>
  </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import {eventBus} from "@/main";
// components
import ModalContainer from "../ModalContainer/ModalContainer";
import CategoryMenu from "../../dashboard/products/nomenclature/CtegoryMenu";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import {TABS_CATEGORY} from "@/constants/constants";
import ItemGroups from "./components/ItemGroups";

export default {
  name: "ChooseModal",
  components: {
    CategoryMenu,
    ModalContainer,
    CustomBtn,
    ItemGroups
  },
  props: {
    categories: Array,
    isModalOpen: Boolean,
    resource: Object,
    titleText: String,
    customClass: String,
    itemsToSelect: Object,
    selectedItems: Array,
    modalResources: Object,
    isRest: Boolean,
    selectedItemsForChoose: Array,
    warehouseId: Number,
    isStock: Boolean,
    isAnalog: Boolean,
    isNomenclature: Boolean,
    getTabIdx: Number,
  },
  data: () => ({
    searchResult: false,
    search: '',
    isSelectedAll: false,
    activeTab: TABS_CATEGORY.ALL
  }),
  computed: {
    ...mapGetters(['choosePopupPending']),
    searchedCategory() {
      return this.searchResult || this.categories
    },
    isDisabledBtn() {
      if (Array.isArray(this.selectedItems)) {
        return !this.selectedItems.length
      }

      return true
    },
    getTabsCategory() {
      return TABS_CATEGORY;
    }
  },
  methods: {
    ...mapActions(['getSelectProductAll']),
    checkSelectedState (id) {
      const index = this.selectedItemsForChoose.findIndex(item => item.id === id)
      return index !== -1
    },
    save() {
      this.$emit('save-selected-items', this.resource)
    },
    cancel() {
      this.$emit('close-modal')
    },
    onGroupsChecked(array) {
      this.$emit('select-item-to-choose', array)
    },
    selectItem(id) {
      this.$emit('select-item-to-choose', { id, resources: this.modalResources })
    },
    toggleAll() {
      this.isSelectedAll = !this.isSelectedAll;
      this.$emit('toggle-all-items-to-select', !this.isSelectedAll)
    },
    onChangeActiveCategory({ id }) {
      let url = '';
      if(this.isStock) {
        url = `${this.resource.FETCH_CATEGORIES_ITEMS}${id}`;
      } else {
        if (this.isNomenclature) {
          url = { id };
        } else if (this.isAnalog) {
          url = `${this.resource.FETCH_CATEGORIES_ITEMS}${id}`;
        } else {
          url = `${this.resource.FETCH_CATEGORIES_ITEMS}${id}?warehouse_id=${this.warehouseId}`;
        }
      }

      this.$emit('fetch-category-items', url)
    },
    onRest(id) {
      this.$emit('updateRest', id)
    },
    getAllGoods() {
      if (this.activeTab === TABS_CATEGORY.BY_CATEGORY) {
        this.activeTab = TABS_CATEGORY.ALL;
        eventBus.$emit('resetActiveTab')
        eventBus.$emit('getAllProduct')
      }
    }
  },
  mounted() {
    eventBus.$on('updateTabCategory', () => {
      this.activeTab = TABS_CATEGORY.BY_CATEGORY;
    })
  }
}
</script>

<style scoped lang="scss">
.categories-list-wrapper.pending {
  pointer-events: none;
  opacity: .6;
}
.page-title {
  display: flex;
  align-items: center;

  &.pending {
    pointer-events: none;
    cursor: none;
  }

  &-tab.active {
    color: #317CCE;
  }

  &-tab {
    color: rgba(49, 124, 206, .4);
  }

  &-tab:first-child {
    margin-right: 10px;
  }

  &-tab:last-child {
    cursor: pointer;
  }
}

.choose-modal {
  border: none;
}

.table-wrapper {
  max-height: 300px;
  height: 100%;
}

.checkbox-text {
  display: inline-block;
}

.dialog-footer {
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #FFFFFF 100%);

}

.disabledTr {
  opacity: .6;
  pointer-events: none;
}
</style>
<style lang="scss">
.no-horizontal-scroll {
  .v-data-table__wrapper {
    overflow-x: hidden !important;
  }

  tr {
    td:nth-of-type(n+4), th:nth-of-type(n+4) {
      padding: 14.5px 5px !important;
    }
  }
}

.choose-modal-main {
  max-height: 662px;
  height: inherit;

  .isRest {
    cursor: copy;
  }

  .chooseModal_warehouse {
    &.pending {
      pointer-events: none;
      opacity: .6;
    }

    .v-data-table.table > .v-data-table__wrapper > table {
      & > tbody > tr > td {
        padding: 14.5px 15px;

        &.short_title {
          overflow-x: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          max-width: 200px;
        }
      }

      & > thead > tr > th {
        padding: 14.5px 15px;
      }
    }
  }
}
</style>
