<template>
  <div class="page characteristic-page">
    <div class="page-header">
      <div class="actions-section">
        <div class="search-block">
          <input
              type="text"
              :placeholder="$t('page.search')"
              v-model="search"
          />
          <div class="search-icon">
            <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
          </div>
        </div>
        <div class="actions-buttons">
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn v-bind="attrs" v-on="on" icon color="indigo" class="action-btn sortable-btn" @click="sortByName">
                <svg-sprite width="13" height="12" icon-id="sort"></svg-sprite>
              </v-btn>
            </template>
            <span>Сортировка по алфавиту</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn v-bind="attrs" v-on="on" color="#9DCCFF" fab small class="action-btn circle-btn"
                     @click="cloneCheck()"
                     :disabled="selectCharacteristics.length > 1 || selectCharacteristics.length === 0">
                <svg-sprite width="16" height="16" icon-id="contextMenuCopy"></svg-sprite>
              </v-btn>
            </template>
            <span>Копировать</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
              <v-btn v-bind="attrs" v-on="on" color="#9DCCFF" fab small class="action-btn circle-btn"
                     @click="removeItem" :disabled="selectCharacteristics.length === 0">
                <svg-sprite width="13" height="15" icon-id="bin"></svg-sprite>
              </v-btn>
            </template>
            <span>Удалить</span>
          </v-tooltip>
        </div>
      </div>
      <v-btn
          :disabled="!(!create && !edit)"
          class="orange-btn" @click="addItem()"
      >
        <svg-sprite width="14" height="14" icon-id="plus"></svg-sprite>
        <span>{{ $t('page.addButton') }}</span>
      </v-btn>
    </div>
    <div class="card-grid">
      <simplebar class="card list-items" force-visible>
        <div class="check-all">
          <input
              type="checkbox"
              id="all"
              @click="selectAll"
              v-model="allSelected"
          />
          <label for="all">{{ $t("page.chooseAll") }}</label>
        </div>
        <v-skeleton-loader :loading="loading" height="404" type="sentences@10">
          <ul class="list-group fixed-list">
            <li class="list-group-item" :class="{'active': currentId === colorData.id}" @click="editColor(colorData)">
              <span>{{ $t("page.color") }}</span>
            </li>
            <li
                class="list-group-item"
                :class="{'active': currentId === sizeData.id}"
                @click="editSize(sizeData)"
            >
              <span>{{ $t("page.size") }}</span>
            </li>
          </ul>
          <draggable
              class="list-group"
              tag="ul"
              v-model="characteristicsList"
              v-bind="dragOptions"
              @start="drag = true"
              @end="drag = false"
          >
            <transition-group
                type="transition"
                :name="!drag ? 'flip-list' : null"
            >
              <li
                  class="list-group-item"
                  :class="{'active': currentId === element.id}"
                  v-for="(element, index) in characteristicsList"
                  :key="index"
                  @contextmenu.prevent="$refs.menu.open($event, { element, index })"
              >
                <input
                    type="checkbox"
                    v-model="element.check"
                    @click="checkSelectedCheckboxes"
                />
                <div @click="itemEdit(element)">
                  <span>{{ element.title }}</span>
                </div>
                <div class="sort-buttons">
                  <v-btn
                      color="#9DCCFF"
                      fab
                      small
                      class="action-btn circle-btn swap-btn"
                      @click="move(index, index - 1)"
                      :disabled="index === 0"
                  >
                    <svg-sprite width="6" height="12" icon-id="sortArrowUp"></svg-sprite>
                  </v-btn>
                  <v-btn
                      color="#9DCCFF"
                      fab
                      small
                      class="action-btn circle-btn swap-btn"
                      @click="move(index, index + 1)"
                      :disabled="index === characteristicsList.length - 1"
                  >
                    <svg-sprite width="6" height="12" icon-id="sortArrowDown"></svg-sprite>
                  </v-btn>
                </div>
              </li>
            </transition-group>
          </draggable>

          <vue-context class="context-menu" ref="menu" v-slot="{ data }">
            <li class="context-menu-item">
              <div class="item-icon">
                <svg-sprite width="13" height="13" icon-id="contextMenuCopy"></svg-sprite>
              </div>
              <a @click.prevent="onClickCopy($event.target, data)">Копировать</a>
            </li>
            <li class="context-menu-item">
              <div class="item-icon">
                <svg-sprite width="13" height="13" icon-id="contextMenuEdit"></svg-sprite>
              </div>
              <a @click.prevent="onClickEdit($event.target, data)">Редактировать</a>
            </li>
            <li class="context-menu-item">
              <div class="item-icon">
                <svg-sprite width="10" height="11" icon-id="contextMenuRemove"></svg-sprite>
              </div>
              <a @click.prevent="onClickDelete($event.target, data)">Удалить</a>
            </li>
          </vue-context>
        </v-skeleton-loader>
      </simplebar>
      <simplebar class="card detail-item" force-visible>
        <div @click="closeDetails" class="detail-close" v-show="create || edit">
          <svg-sprite width="6" height="12" icon-id="leftArrow"></svg-sprite>
          <span>Назад</span>
        </div>
        <div class="select-item" v-if="!create && !edit">
          <img src="@/assets/icons/area-bg.svg" alt=""/>
          <span>
            {{ $t("page.selectCharacteristic") }}
          </span>
        </div>
        <CharacteristicCreate
            v-if="create"
            @show-alert="onShowAlert"
            :closeDetails="closeDetails"
        />
        <CharacteristicEdit
            v-if="edit"
            @show-alert="onShowAlert"
            v-click-outside="onClickOutside"
            :closeDetails="closeDetails"
            :characteristicEdit="characteristicEdit"
            :key="componentKey"
        />
      </simplebar>
    </div>
    <alert v-if="isAlertShow"
           @close-alert="onCloseAlert"
           @show-item="onShowItem"
           :text-items="alertTextItems"
           :link="alertLink"/>
<!--    <Alert/>-->
    <Dialog ref="confirm"/>
    <DialogSave ref="confirmSave">
      <div class="header-text" slot="dialog-header">
        <span>Копирование</span>
      </div>
      <div class="dialog-text" slot="dialog-text" v-if="clone">
        Сохранить скопированое свойство
        <span style="font-weight: 550">{{ clone.name }}</span> ?
      </div>
    </DialogSave>
  </div>
</template>

<script>
// vuex
import {mapGetters} from "vuex";
import {eventBus} from "@/main";
// components
import CharacteristicCreate from "@/components/dashboard/characteristic/CharacteristicCreate"
import CharacteristicEdit from "@/components/dashboard/characteristic/CharacteristicEdit"
import Alert from "@/components/dashboard/products/nomenclature/ui/Alert"
// import Alert from "@/components/ui/Alert"
import Dialog from "@/components/ui/dialog/DialogDelete"
import DialogSave from "@/components/ui/dialog/DialogSave"

Array.prototype.move = function (from, to) {
  this.splice(to, 0, this.splice(from, 1)[0]);
  return this;
};

export default {
  name: "Characteristics",
  components: {
    CharacteristicCreate,
    CharacteristicEdit,
    Alert,
    Dialog,
    DialogSave,
  },
  data: () => ({
    currentId: null,
    search: "",
    create: false,
    edit: false,
    drag: false,
    loading: true,
    characteristicEdit: null,
    delete: false,
    selected: [],
    allSelected: false,
    dataSelect: [],
    componentKey: 0,
    checkSave: false,
    alertTextItems: '',
    alertLink: false,
    isAlertShow: false
  }),
  created() {
    // eventBus.$on('delete', data => {
    //  this.delete = true
    // })
  },
  computed: {
    ...mapGetters([
      "deleteConfirm",
      "characteristics",
      "changeItem",
      "colorData",
      "sizeData",
    ]),
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost",
      };
    },
    selectCharacteristics() {
      if (this.characteristics) {
        return this.characteristics.filter((prop, index) => {
          if (prop.check) {
            const data = {
              element: prop,
              index: index,
            };
            this.dataSelect = data;
            console.log(this.dataSelect);
            return this.dataSelect;
          }
        });
      } else {
        return "";
      }
    },
    characteristicsList: {
      get() {
        if (this.characteristics) {
          return this.characteristics.filter((prop) =>
              prop.title.toLowerCase().includes(this.search.toLowerCase())
          )
        } else {
          return null
        }
      },
      set(value) {
        this.$store.dispatch("updateDataList", value);
      },
    },
    checkedCharacteristics() {
      if (this.properties) {
        return this.properties.filter((prop) => {
          if (!prop.check) {
            return true;
          }
        });
      } else {
        return "";
      }
    },
    clone() {
      return this.$store.getters["clone"];
    },
  },
  methods: {
    onShowItem (params) {
      this.itemEdit(params)
    },
    onShowAlert({textItems, link}) {
      this.alertTextItems = textItems
      this.alertLink = link || false
      this.showAlert()
    },

    onCloseAlert () {
      this.isAlertShow = false
    },

    showAlert() {
      this.isAlertShow = true
      setTimeout(
          () => {
            this.isAlertShow = false
          },
          2500
      )
    },
    move(from, to) {
      this.characteristicsList = this.characteristicsList.move(from, to);
    },

    forceRerender() {
      this.componentKey += 1;
    },

    /* The method to clone the item that was checked */
    cloneCheck() {
      let text = "";
      this.onClickCopy(text, this.dataSelect);
      this.characteristicsList.forEach((prop) => (prop.check = false));
    },

    /* Methods for context menu */
    async onClickCopy(text, data) {
      /* data -  is the element that was clicked to copy from the context menu */
      //await this.$store.dispatch('clearClone')
      await this.$store.dispatch("cloneItem", data);
      await this.itemEdit(this.$store.getters["cloneCharacteristic"]);
    },
    onClickEdit(text, data) {
      this.itemEdit(data.element);
    },
    onClickDelete(text, data) {
      data.element.check = true;
      this.removeItem();
    },

    /* Method for transferring data to a popup */
    openDialog() {
      eventBus.$emit("openDialog", {
        name: "property",
        names: "properties",
      });
    },

    selectAll() {
      this.characteristicsList.forEach((prop) => {
        if (!this.allSelected) {
          prop.check = true;
          this.checkSelectedCheckboxes();
          this.closeDetails();
        } else {
          prop.check = false;
          this.checkSelectedCheckboxes();
        }
      });
    },

    checkSelectedCheckboxes() {
      const selected = [];
      this.characteristicsList.forEach((prop) => {
        if (prop.check) {
          selected.push(prop);
        }
      });
      this.selected = selected;
    },

    async removeItem() {
      this.checkSelectedCheckboxes();
      if (
          await this.$refs.confirm.open("Delete", "Are you sure?", {
            name:
                this.selected.length > 1
                    ? "выбраные характеристики"
                    : `свойство  ${this.selected[0].title}`,
            nameLength:
                this.selected.length > 1
                    ? "выбраные характеристики"
                    : `свойство  ${this.selected[0].title}`,
          })
      ) {
        console.log("YES DELETE");
        await this.$store.dispatch("removeItem", this.selected);
        this.allSelected = false;
        this.closeDetails();
      } else {
        console.log("NO DELETE");
        this.characteristicsList.forEach((prop) => {
          prop.check = false;
        });
      }
    },

    onClickOutside() {
      console.log("onClickOutside");
    },

    async saveConfirm() {
      this.checkSelectedCheckboxes();
      if (await this.$refs.confirmSave.open("Save", "Are you sure?")) {
        console.log("YES SAVE");
        await this.$store.dispatch("updateItem", this.$store.getters["clone"]);
        await this.$store.dispatch("clearClone");
        this.allSelected = false;
      } else {
        await this.$store.dispatch("clearClone");
        this.closeDetails();
        console.log("NO SAVE");
      }
    },

    sortByName() {
      this.$store.dispatch("sortList");
    },

    sort() {
      this.characteristicsList = this.characteristicsList.sort(
          (a, b) => a.order - b.order
      );
    },

    editColor(item) {
      this.currentId = item.id;
      this.closeDetails()
      this.edit = true;
      this.characteristicEdit = item;
      this.forceRerender();
    },

    editSize(item) {
      this.currentId = item.id;
      this.closeDetails()
      this.edit = true;
      this.characteristicEdit = item;
      this.forceRerender();
    },

    itemEdit(item) {
      this.currentId = item.id;

      this.closeDetails()
      this.edit = true;
      this.characteristicEdit = item;
      this.forceRerender();
    },
    addItem() {
      this.create = true;
    },
    closeDetails() {
      this.create = false;
      this.characteristicEdit = null;
      this.edit = false;
    },
  },
  beforeDestroy() {
    if (this.$store.getters["clone"]) {
      this.saveConfirm();
    }
  },
  async mounted() {
    await this.$store.dispatch("fetchData");
    this.loading = false;
  },

  deactivated() {
    console.log("About has been deactivated");
  },
};
</script>

<style lang="sass" scoped>
.list-group-item.active::before
  content: ''
  background: #F4F9FF
  position: absolute
  left: 0
  right: 0
  top: 0
  bottom: 0
  z-index: -1
  pointer-events: none
.fixed-list
  padding-left: 0
  .list-group-item
    padding-left: 50px
.detail-item
  overflow: auto

  &::-webkit-scrollbar
    width: 2px

  &::-webkit-scrollbar-track
    background: transparent

  &::-webkit-scrollbar-thumb
    background: #9DCCFF
</style>
