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
            <div class="bodyTitle">Перенести склад <strong v-if="getTitle">{{ getTitle }}</strong> в :</div>
            <div v-if="isDirty" class="pathway">
              <ul v-if="pathList">
                <li v-for="path in pathList" :key="path.id">{{ path.title }}</li>
              </ul>
            </div>
            <div class="treeGroups">
              <tree-view
                  :items="items"
                  isMove
                  @updateBelongItem="onHandler"
              ></tree-view>
            </div>
          </div>
          <div class="dialog-actions popup-buttons">
            <v-btn
                class="popup-btn transparent-btn"
                color="#5893D4"
                text
                @click="onClose"
            >
              {{ $t('page.suppliers.modal.createGroups.close') }}
            </v-btn>
            <v-btn
                class="popup-btn"
                color="#5893D4"
                :disabled="!isDirty"
                dark
                @click="onMoveWareHouse"
            >
              Пененести
            </v-btn>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>

<script>
// vuex
import {mapActions} from "vuex";
// components
import TreeView from '@/components/dashboard/products/wareHouses/TreeView/TreeView';
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
// helper
import getFlatMap from "@/helper/getFlatMap";
import getPathWay from "@/helper/getPathWay";

export default {
  name: "MoveWareHouse",
  components: {
    TreeView,
    HeaderActions
  },
  props: {
    isMoveWareHouse: Boolean,
    items: Array,
    editData: Object
  },
  computed: {
    getTitle() {
      return this.editData.title || '';
    },
    getFlatList() {
      return getFlatMap(this.items);
    },
  },
  data: () => ({
    isModal: false,
    currentGroupId: null,
    isDirty: false,
    pathList: null
  }),
  methods: {
    ...mapActions(['editWareHouseGroup', 'onMoveWarehouseToGroup']),
    onHandler({ id }) {
      this.pathList = getPathWay(this.getFlatList, id);
      this.currentGroupId = id;
      this.isDirty = true;
    },
    onClose() {
      this.isModal = false;
      this.$emit('updateStateModal');
    },
    async onMoveWareHouse() {
      const { id } = this.editData;

      const values = {
        currentWareHouseId: this.currentGroupId,
        id
      }

      await this.onMoveWarehouseToGroup(values);
    }
  },
  mounted() {
    this.isModal = this.isMoveWareHouse;
  }
}
</script>

<style scoped lang="scss">
@import "src/sass/mixins";

  .iconMove {
    margin-right: 10px;
  }

  .header-text{
    line-height: 1;
  }

  .popup-btn:disabled {
    border: 1px solid #9DCCFF;
    box-sizing: border-box;
    border-radius: 32.5652px;
    color: #9DCCFF !important;
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

  .moveWareHouse {
    .body {
      width: 100%;
      padding: 30px 60px;

      .bodyTitle {
        text-align: center;
        margin-bottom: 10px;

        strong {
          border-bottom: 1px solid #317CCE;
        }
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

    .breadcrumb {
      margin: 10px 0;
      text-align: center;

      &Wr {
        span {
          font-size: 16px;
          line-height: 16px;
          color: #317CCE;
        }

        span.caret {
          font-size: 24px;
          font-weight: normal;
          vertical-align: middle;
          display: inline-block;
          margin: 0 5px;
          color: #317CCE;
        }
      }

      &Title {
        font-weight: bold;
        font-size: 13px;
        line-height: 13px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: #317CCE;
        margin-bottom: 15px;
        display: block;
      }
    }
  }
</style>
<style lang="scss">
.v-treeview-node__toggle--open + .v-treeview-node__content .text {
  color: #317CCE !important;
}
</style>
