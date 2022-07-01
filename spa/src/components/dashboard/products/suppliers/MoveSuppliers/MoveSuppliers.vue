<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="isModal"
        max-width="920"
        class="dialog-large"
        @click:outside="onClose"
    >
      <div class="dialog moveWareHouse">
        <div class="dialog-header">
          <div class="header-text">
            <span class="iconMove">
              <svg-sprite width="20" height="20" icon-id="moveLarge"></svg-sprite>
            </span>
            <span>Перенести</span>
          </div>
          <header-actions
              @updateClose="onClose"
              is-hide-attach
              is-hide-dots
          ></header-actions>
        </div>
        <div class="dialog-content dialog-content-large">
          <div class="body">
            <div class="bodyTitle">Перенести группу <strong>{{ getTitle }}</strong> в :</div>
            <div v-if="isDirty" class="pathway">
              <ul v-if="pathList">
                <li v-for="path in pathList" :key="path.id">{{ path.title }}</li>
              </ul>
            </div>
            <div class="treeGroups">
              <tree-view
                  :items="getItems"
                  @updateBelongItem="onHandler"
                  item-disabled="disabled"
                  isMove
              ></tree-view>
            </div>
          </div>
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel"
                :title="$t('page.suppliers.modal.createGroups.close')"
                color="#5893D4"
                @updateEvent="onClose"
                text
            ></custom-btn>
            <custom-btn
                custom-class="save"
                :title="$t('page.suppliers.modal.createGroups.apply.move')"
                color="#5893D4"
                @updateEvent="onMoveWareHouse"
                :is-disabled="pending || !isDirty"
                text
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
import TreeView from '@/components/dashboard/products/wareHouses/TreeView/TreeView';
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
// helper
import getFlatMap from "@/helper/getFlatMap";
import getPathWay from "@/helper/getPathWay";
export default {
  name: "MoveSuppliers",
  components: {
    TreeView,
    HeaderActions,
    CustomBtn
  },
  props: {
    isModal: Boolean,
    isMoveSuppliers: Boolean,
    items: Array,
    editData: Object
  },
  computed: {
    ...mapGetters(['pending']),
    getTitle() {
      return this.editData.currentLabel || '';
    },
    getFlatList() {
      return getFlatMap(this.items);
    },
    getItems() {
      return this.getNodes(this.items)
    }
  },
  data: () => ({
    currentGroupId: null,
    isDirty: false,
    pathList: null
  }),
  methods: {
    ...mapActions(['onEditGroup']),
    getNodes(items) {
      const { currentId } = this.editData;
      return items.map(item => {
        const disabled = item.id === currentId;

        return {
          id: item.id,
          title: item.title,
          disabled: disabled,
          children: item.children ? this.getNodes(item.children) : []
        }
      })
    },
    onHandler({ id }) {
      this.pathList = getPathWay(this.getFlatList, id);
      this.currentGroupId = id;
      this.isDirty = true;
    },
    onClose() {
      this.$emit('updateStateModal');
    },
    async onMoveWareHouse() {
      const { currentLabel, currentId } = this.editData;

      const group = {
        parent_id: this.currentGroupId,
        id: currentId,
        title: currentLabel,
        order: 0
      };

      await this.onEditGroup(group)
    }
  }
}
</script>

<style scoped lang="scss">
@import "./moveSuppliers";
</style>

<style lang="scss">
  .v-treeview-node__toggle--open + .v-treeview-node__content .text {
    color: #317CCE !important;
  }
</style>
