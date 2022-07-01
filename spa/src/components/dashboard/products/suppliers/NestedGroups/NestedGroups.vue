<template>
  <v-col :offset="offset" :cols="cols">
    <div v-if="getMove" class="moveTitle">
      {{ isMove ? $t('page.suppliers.modal.createGroups.moveSuppliers') : $t('page.suppliers.modal.createGroups.moveGroup')}}
      <span v-if="groupName">{{ groupName }}</span>
      <span v-else> {{getSupplierTitle}} в :</span>
    </div>
    <div v-if="pathParentList && getMove && (!getCopy && !getEdit)" class="pathway">
      <ul>
        <li v-for="path in pathParentList" :key="path.id">{{ path.title }}</li>
      </ul>
    </div>

    <div v-if="isSuppliers">
      <div class="treeGroups">
        <v-treeview
            open-all
            expand-icon="$expand"
            item-text="title"
            :items="items"
        >
          <template slot="label" slot-scope="{item}">
            <div class="wareHouseItem" @click="selectGroup(item)">{{ item.title }}</div>
          </template>
        </v-treeview>
      </div>
    </div>
    <div v-else class="item nested-category" v-click-outside="onClickOutside">
      <div v-if="!getMove" class="label-title">
        <span>{{ $t(`page.suppliers.modal.createGroups.${groupTitle}`) }}</span>
      </div>
      <div class="input-wrap">
        <input
            @click.left="showGroups"
            v-model="childGroupsTitle"
            type="text"
            :placeholder="$t('page.suppliers.modal.createGroups.chooseNameOfCategory')"
        >
        <v-btn
            class="open-drop-down"
            @click="showGroups"
            icon
        >
          <svg-sprite style="color: rgb(157,204,255)" width="11" height="6" icon-id="arrowDown"></svg-sprite>
        </v-btn>
        <transition name="fade" mode="out-in" appear>
          <div
              v-if="showDropDown"
              class="drop-down drop-down-categories"
          >
            <div class="drop-down--body">
              <v-treeview
                  open-all
                  activatable
                  item-text="title"
                  selection-type="independent"
                  :items="items"
                  item-disabled="disabled"
              >
                <template slot="label" slot-scope="{item}">
                  <div @click="selectGroup(item)">{{ item.title }}</div>
                </template>
              </v-treeview>
            </div>
          </div>
        </transition>
      </div>
    </div>
    <slot></slot>
  </v-col>
</template>

<script>
// helper
import getPathWay from "@/helper/getPathWay";

export default {
 name: "NestedGroups",
  props: {
    isSuppliers: Boolean,
    currentId: Number,
    parentId: {
      type: Number,
      default: null
    },
    cols: {
      type: String,
      default: '6'
    },
    groupTitle: {
      type: String,
      default: 'nestedGroups'
    },
    getMove: Boolean,
    getCopy: Boolean,
    getEdit: Boolean,
    flatList: Array,
    groupName: String,
    getSupplierTitle: [String, Number],
    resetId: String,
    groupsMoveTitles: {
      type: Array,
      default() {
        return [];
      }
    },
    offset: String,
    isMove: Boolean,
    suppliersGroups: {
      type: Array,
      default() {
        return [];
      }
    },
  },
  watch: {
    resetId(val, oldVal) {
      if (val !== oldVal) {
        this.childGroupsTitle = '';
      }
    }
  },
  computed: {
    items() {
      const copyGroups = this.suppliersGroups.map(item => ({...item}));

      if (this.getCopy) {
        return copyGroups;
      }

      return this.getNodes(copyGroups)
    }
  },
  data() {
   return {
     showDropDown: false,
     childGroupsTitle: '',
     parent_id: null,
     pathParentList: null,
   }
  },
  methods: {
    onClickOutside() {
      this.showDropDown = false;
    },
    getNodes(items) {
      return items.map(item => {
        const disabled = item.id === this.currentId;

        return {
          id: item.id,
          title: item.title,
          disabled: disabled,
          children: item.children ? this.getNodes(item.children) : []
        }
      })
    },
    showGroups() {
      this.showDropDown = !this.showDropDown
    },
    fullPath() {
      this.childGroupsTitle = this.pathParentList.reduce((acc, item, idx) => {
        acc += ` ${idx ? '›' : ''} ${item.title}`;
        return acc;
      }, '');
    },
    selectGroup(item) {
      const { title, id } = item;
      this.childGroupsTitle = title;
      this.parent_id = id;

      this.$emit('updateGroupId', item);

      this.pathParentList = getPathWay(this.flatList, id);

      this.fullPath();

      if (!item.disabled) {
        this.showGroups()
      }
    },
  },
  mounted() {
    if (this.parentId) {
      this.pathParentList = getPathWay(this.flatList, this.parentId);
      this.fullPath();
    }
  }
}
</script>

<style scoped lang="scss">
@import "src/sass/mixins";

.wareHouseItem {
  width: calc(100% - 15px);
  cursor: pointer;
  transition: .3s ease-in;
  color: #7E7E7E;
  height: 45px;
  display: inline-flex;
  align-items: center;
  border-bottom: 1px solid #e3f0ff;
  margin-right: 15px;

  &:not(.groupChild) {
    font-weight: 500;
  }

  &:not(.groupChild):hover {
    color: #FF9D2B;
  }

  &.groupChild {
    cursor: text;
    color: grey;
    pointer-events: none;
  }

  &.active {
    color: #FF9F2F;
  }
}

.treeGroups {
  border: 1px solid #E3F0FF;
  box-sizing: border-box;
  border-radius: 10px;
  max-height: 350px;
  min-height: 350px;
  overflow-y: auto;
  @include custom-scroll-bar();
}

.input-wrap input {
  font-size: 14px;
  color: #7E7E7E;
}

.label-title {
  line-height: 1;
  letter-spacing: 0.02em;
  color: #317CCE;
  margin-bottom: 10px;
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
      vertical-align: middle;

      &:after {
        content: '\203A';
        margin-left: 10px;
        font-size: 24px;
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
.drop-down-categories {
  border: 1px solid #E3F0FF;
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

.item.nested-category {
  .open-drop-down {
    right: 5px;
    top: -10px;
  }
}
</style>
<style lang="scss">
.v-treeview-node--disabled > .v-treeview-node__root {
  .v-treeview-node__content {
    pointer-events: none !important;
  }
}

  .input-wrap input {
    width: 100%;
    padding-bottom: 5px;
    border-bottom: 1px solid #9DCCFF;
    font-size: 14px;
  }

  .v-treeview-node__label {
    cursor: pointer;

    &:hover {
      color: #FF9F2F;
    }
  }

  .v-treeview-node__toggle--open + .v-treeview-node__content {
    font-weight: 550;
    font-size: 15px;
    line-height: 15px;
    color: #5893D4;
  }

  .v-treeview-node__root:hover {
    background: #F4F9FF !important;
  }
</style>
