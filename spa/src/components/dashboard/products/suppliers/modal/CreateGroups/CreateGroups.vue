<template>
  <div class="dialog-wrap" v-if="dialogEditCategory">
    <v-dialog
        v-model="dialogEditCategory"
        max-width="1230px"
        class="dialog-large"
        @click:outside="close"
    >
      <div class="dialog suppliersModal">
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ $t(`page.suppliers.modal.createGroups.${groupTitle}`) }}</span>
          </div>
          <header-actions isHideDots :is-hide-attach="!isAddTitle" @updateClose="close"></header-actions>
        </div>

        <div class="dialog-content dialog-content-large createGroupContent">
          <form class="item-form">
            <v-row>
              <v-col
                  v-if="!getMove"
                  :offset="!getChild && suppliersGroups.length ? 0: 3"
                  :cols="6"
                  :class="{ 'subgroup': getChild }"
              >
                <div v-if="getChild" class="pathway">
                  <ul v-if="pathList">
                    <li v-for="path in pathList" :key="path.id">{{ path.title }}</li>
                  </ul>
                </div>
                <div class="category-header">
                  <div class="item-name">
                    <div class="label-title" :class="{'error-field': getError || editData}">
                      <span>{{ $t('page.suppliers.modal.createGroups.nameOfGroup') }}</span>
                      <span class="error-text" :key="item" v-for="item in getError">{{item}}</span>
                      <span v-if="isCopyText && getCopy" class="error-text">{{ $t('page.suppliers.modal.createGroups.changeName') }}</span>
                    </div>
                    <input
                        class="inputName"
                        @input="onInput"
                        type="text"
                        :placeholder="$t('page.suppliers.modal.createGroups.enterNameCategory')"
                        v-model="groupName"
                    >
                  </div>
                </div>
              </v-col>
              <nested-groups
                  v-if="!getChild && suppliersGroups.length"
                  :offset="getMove?'3':'0'"
                  :getCopy="getCopy"
                  :getEdit="getEdit"
                  :getMove="getMove"
                  :suppliersGroups="suppliersGroups"
                  :flatList="flatList"
                  :groupName="groupName"
                  :currentId="currentId"
                  :parentId="parentId"
                  :resetId="resetId"
                  @updateGroupId="onGroupId"
              ></nested-groups>
              <v-col class="resetBtnWr" v-if="parentItem && (!getMove && !getCopy && !getEdit)" cols="6" offset="6">
                <span @click="onReset(1)" class="resetBtn">{{ $t('page.suppliers.modal.createGroups.reset') }}</span>
              </v-col>
            </v-row>
          </form>
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel smallText"
                :title="$t(`page.suppliers.modal.createGroups.${getCorrectText ? 'cancel': 'close'}`)"
                color="#5893D4"
                @updateEvent="close"
                text
            ></custom-btn>
            <custom-btn
                custom-class="save smallText"
                :title="$t(`page.suppliers.modal.createGroups.apply.${groupTitle}`)"
                color="#5893D4"
                :is-disabled="!groupName || pending"
                @updateEvent="addNewGroup"
            ></custom-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from "vuex";
// components
import NestedGroups from "@/components/dashboard/products/suppliers/NestedGroups/NestedGroups";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
// constants
import { MODE_TYPES } from "@/constants/constants";
import getUniqueId from "@/helper/getUniqueId";

export default {
  name: "CreateGroups",
  components: {
    'nested-groups': NestedGroups,
    'header-actions': HeaderActions,
    'custom-btn': CustomBtn
  },
  props: {
    isChild: Boolean,
    groupTitle: {
      type: String,
      default: 'add'
    },
    suppliersGroups: {
      type: Array,
      default() {
        return [];
      }
    },
  },
  data: () => ({
    isCopyText: false,
    dialogEditCategory: false,
    editData: null,
    childGroupsTitle: '',
    componentKey: 0,
    groupName: '',
    pathList: null,
    parentId: null,
    parentItem: null,
    resetId: '',
    currentId: null
  }),
  computed: {
    ...mapGetters([
        'dataCreate',
        'dataEdit',
        'IDCreatedCategory',
        'requestError',
        'isGroupSuccess',
        'pending',
        'flatList'
    ]),
    getCorrectText() {
      return this.groupTitle === MODE_TYPES.ADD ||
             this.groupTitle === MODE_TYPES.COPY
    },
    isAddTitle() {
      return this.groupTitle === MODE_TYPES.ADD
    },
    getCopy() {
      return this.groupTitle === MODE_TYPES.COPY
    },
    getMove() {
      return this.groupTitle === MODE_TYPES.MOVE
    },
    getEdit() {
      return this.groupTitle === MODE_TYPES.EDIT
    },
    getChild() {
      return this.isChild
    },
    getError() {
      return this.requestError && this.requestError.title
    },
  },
  methods: {
    ...mapActions(['onAddSuppliersGroup', 'onEditGroup']),
    onInput() {
      this.isCopyText = false
    },
    onReset(point) {
      this.childGroupsTitle = '';
      if(!point) this.groupName = '';
      this.parentItem = null;
      this.parentId = null;
      this.currentId = null;
      this.editData = null;
      this.pathList = null;
      this.pathParentList = null;
      this.resetId = getUniqueId();
    },
    open(parentId = 0, value = null, path) {
      if (value) {
        const { currentLabel, currentId,  copyText, parentData: { label, id } } = value;
        const isCopy = copyText ?? '';
        this.currentId = currentId;

        if (currentLabel) this.groupName = `${currentLabel} ${isCopy}`;
        if (label || id) this.childGroupsTitle = label;
        this.parentId = id;
      } else {
        this.parentId = parentId;
      }

      if (path) {
        this.pathList = path;
      }

      if (value) this.editData = value;
      if (this.getCopy) this.isCopyText = true

      this.dialogEditCategory = true;
    },
    onGroupId(item) {
      this.parentItem = item;
    },
    close() {
      this.onReset();

      this.dialogEditCategory = false;
    },
    async addNewGroup() {
      let id = this.parentItem ? this.parentItem.id : this.parentId;

      const group = {
        parent_id: id,
        title: this.groupName,
        order: 0
      };

      if (this.groupTitle === MODE_TYPES.ADD || this.groupTitle === MODE_TYPES.COPY) {
        await this.onAddSuppliersGroup(group);
      }

      if (this.groupTitle === MODE_TYPES.EDIT) {
        if (this.editData) {
          const { currentId } = this.editData;
          group.id = currentId;
        }

        await this.onEditGroup(group)
      }

      if (this.groupTitle === MODE_TYPES.MOVE) {
        if (this.editData) {
          const { currentId } = this.editData;
          group.id = currentId;
        }

        await this.onEditGroup(group)
      }

      this.onReset()
    }
  }
}
</script>

<style scoped lang="scss">
.inputName {
  padding-bottom: 2px;
}

.popup-buttons {
  display: flex;
  align-items: center;
  justify-content: center;
}
.moveTitle {
  font-weight: normal;
  font-size: 18px;
  line-height: 18px;
  text-align: center;
  color: #5893D4;
  margin-bottom: 20px;

  span {
    font-weight: bold;
    font-size: 20px;
  }
}

.subgroup {
  margin: 0 auto;
}
.error-field {
  display: flex;
  justify-content: space-between;

  .error-text {
    color: #FF9F2F;
    text-transform: none;
    font-size: 13px;
    font-weight: 500;
  }
}

.pathway {
  margin-bottom: 20px;
  ul {
    display: flex;
    align-items: center;
    justify-content: center;
    list-style-type: none;
    padding-left: 0;
    li {
      font-size: 16px;
      line-height: 16px;
      color: #5893D4;
      margin-right: 10px;
      position: relative;

      &:after {
        content: '›';
        margin-left: 10px;
      }

      &:last-child {
        padding-right: 0;
        font-weight: 500;

        &:after {
          display: none;
        }
      }
    }
  }
}

.item-name input {
  font-size: 14px;
  color: #7E7E7E;
}

.dialog-content.createGroupContent {
  .category-header {
    padding-top: 0;

    .label-title {
      line-height: 1;
      letter-spacing: 0.02em;
      color: #317CCE;
      margin-bottom: 10px;
    }
  }

  .v-treeview-node__content {
    cursor: pointer;
  }

  .item-form {
    padding: 16px 30px;
    min-height: 250px;
  }

  .item.nested-category {
    .open-drop-down {
      right: 10px;
    }
  }

  .resetBtnWr {
    padding-top: 0;
    margin-top: -15px;
    .resetBtn {
      font-weight: 550;
      font-size: 13px;
      line-height: 13px;
      text-decoration: underline;
      color: rgba(157, 204, 255,.5);
      cursor: pointer;
    }
  }

  .theme--dark.v-btn.v-btn--disabled:not(.v-btn--flat):not(.v-btn--text):not(.v-btn--outlined) {
    background-color: rgba(88, 147, 212, .3) !important;
    color: #fff !important;
  }

  .drop-down {
    &--body {
      max-height: 190px;
      overflow: auto;
    }
  }
}
</style>
<style lang="scss">
@import "src/sass/mixins";
$dialog-max-height: 60% !important;
  .suppliersModal {
    .drop-down.drop-down-categories {
      max-width: initial;
      @include custom-scroll-bar();
    }

    .v-treeview-node__toggle--open + .v-treeview-node__content {
      color: #5893D4;
    }

    .v-treeview-node__content {
      transition: color .3s ease;
      cursor: pointer;
      font-size: 14px;
      color: #7E7E7E;

      &:hover {
        color: #FF9D2B;
      }
    }
  }
</style>
