<template>
  <div class="dialog-wrap createGroupWareHouses">
    <v-dialog
        v-model="isModal"
        max-width="1230px"
        class="dialog-large"
        @click:outside="onClose"
    >
      <div class="dialog createGroupWareHousesModal">
        <div class="dialog-header">
          <div class="header-text">
            <span>{{ getMainTitle }}</span>
          </div>
          <header-actions
              @updateClose="onClose"
              @onCloseModal="onClose"
              id="groups"
              :title="getMainTitle"
              is-hide-dots
          ></header-actions>
        </div>

        <div class="dialog-content dialog-content-large">
          <form class="item-form">
            <v-row>
              <v-col cols="6">
                <div class="item-name">
                  <div class="label-title">
                    <span>{{ $t('page.suppliers.modal.createGroups.nameOfGroup') }}</span>
                  </div>
                  <input
                      type="text"
                      :placeholder="$t('page.suppliers.modal.createGroups.nameOfGroup')"
                      :class="{'error-on-select':  $v.groupName.$error }"
                      @change="$v.groupName.$touch()"
                      v-model.trim="groupName"
                  >
                </div>
              </v-col>
              <select-groups
                  :title="$t('page.wareHouses.createWareHouses.addWarehouse')"
                  :requestErrorWarehouse="requestErrorWarehouse"
                  :wareHouses="wareHouses"
                  :getEditData="editData"
                  @updateSelectIds="getListIds"
                  isChips
                  isReducePopup
              ></select-groups>
            </v-row>
            <v-row>
              <select-groups
                  :title="$t('page.suppliers.modal.createGroups.nestedGroups')"
                  :wareHouses="wareHouseGroupsList"
                  :parentGroup="editData"
                  @updateSelectIds="getParentId"
                  :isClear="isClear"
                  isMoveStock
                  isReducePopup
              ></select-groups>
            </v-row>
            <v-row v-if="parentId">
              <span @click="onClearNestedGroup" class="clear">{{$t('page.wareHouses.createGroupsWareHouses.reset')}}</span>
            </v-row>
          </form>
          <div class="dialog-actions popup-buttons">
            <custom-btn
                custom-class="cancel"
                :title="$t(`page.suppliers.modal.createGroups.${getCorrectText ? 'cancel': 'close'}`)"
                color="#5893D4"
                @updateEvent="onClose"
                text
            ></custom-btn>
            <custom-btn
                :title="$t('page.wareHouses.saveTable')"
                custom-class="save"
                :is-disabled="!groupName"
                color="#5893D4"
                @updateEvent="addWHGroup"
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
import SelectGroups from "@/components/dashboard/products/wareHouses/SelectGroups/SelectGroups";
import HeaderActions from "@/components/ui/HeaderActions/HeaderActions";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
// constants
import {MODE_TYPES} from "@/constants/constants";
import getUniqueId from "@/helper/getUniqueId";
// validate
import {required} from 'vuelidate/lib/validators';
// mixin
import mixin from '@/mixins/mixinTabs';

export default {
  name: "CreateGroupWareHouses",
  mixins: [mixin],
  components: {
    'select-groups': SelectGroups,
    'header-actions': HeaderActions,
    'custom-btn': CustomBtn,
  },
  props: {
    wareHouses: Array,
    groupTitle: String,
    isGroupWareHouse: Boolean,
    editData: Object,
    wareHouseGroupsList: Array
  },
  computed: {
    ...mapGetters(['requestErrorWarehouse']),
    getMainTitle() {
      if (this.groupTitle === MODE_TYPES.ADD) {
        return this.$t('page.wareHouses.createGroupsWareHouses.addGroupOfWarehouse');
      }

      return this.$t('page.wareHouses.createGroupsWareHouses.editGroupOfWarehouse');
    },
    getCorrectText() {
      return this.groupTitle === MODE_TYPES.ADD ||
          this.groupTitle === MODE_TYPES.COPY
    }
  },
  data: () => ({
    isModal: false,
    groupName: '',
    listIds: [],
    parentId: 0,
    isClear: ''
  }),
  methods: {
    ...mapActions(['addWareHouse', 'addWareHouseGroup', 'editWareHouseGroup']),
    onClose() {
      this.isModal = false;
      this.$emit('updateStateModal');
    },
    getListIds(iDs) {
      this.listIds = iDs;
    },
    getParentId({ warehouse_group_id }) {
      this.parentId = warehouse_group_id ?? 0;
    },
    onClearNestedGroup() {
      this.isClear = getUniqueId();
    },
    async addWHGroup() {
      const data = {
        title: this.groupName,
        data: this.listIds,
        parent_id: this.parentId
      }

      if (this.groupTitle === MODE_TYPES.ADD) {
        await this.addWareHouseGroup(data);
      }

      if (this.groupTitle === MODE_TYPES.EDIT) {
        data.id = this.editData.id;
        await this.editWareHouseGroup(data)
      }
    },
  },
  mounted() {
    this.isModal = this.isGroupWareHouse;

    if (this.editData) {
      const { title } = this.editData;
      this.groupName = title;
    }
  },
  validations: {
    groupName: {required}
  }
}
</script>

<style scoped lang="scss">
.error-on-select {
  border-bottom: 1px solid #FF9D2B;
}

.popup-btn:disabled {
  border: 1px solid #9DCCFF;
  box-sizing: border-box;
  border-radius: 32.5652px;
  color: #9DCCFF !important;
}

.clear {
  font-weight: 550;
  font-size: 13px;
  line-height: 13px;
  text-decoration: underline;
  color: #9DCCFF;
  cursor: pointer;
  padding: 8px 12px 0;
}

.popup-btn.save {
  transition: background-color .2s ease;

  &:hover {
    background-color: #FF9F2F !important;
  }

  &:before {
    display: none !important;
  }
}

.v-btn:not(.v-btn--text):not(.v-btn--outlined):hover:before {
  opacity: 1 !important;
  background: orange !important;
}

.popup-buttons {
  margin-top: 40px;
}

.createGroupWareHousesModal .item-form {
  min-height: inherit;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

</style>

<style lang="scss">
.v-text-field.v-input--is-focused > .v-input__control > .v-input__slot:after {
  display: none;
}

.createGroupWareHousesModal {
  .item-form {
    padding: 18px 30px 0;
  }

  .dialog-content .item-form .item-name .label-title {
    margin-bottom: 17px;
  }

  .wareHouses, .moveStock {
    .v-input__icon.v-input__icon--append i {
      position: relative;
      left: 5px;
    }
  }

  .v-application--is-ltr .v-chip .v-icon--right {
    margin-left: 15px !important;
  }
}

.moveStock {
  .label-title {
    margin-bottom: 0 !important;
  }
}

.createGroupWareHouses {
  .dialog-content .item-form {
    padding: 20px 30px;
  }

  .v-select__selections {
    position: absolute;
    top: 30px;
  }
}

.item-name input {
  line-height: 1.1;
}

.wareHouseDropDown {
  margin-top: 30px !important;
  min-width: 270px !important;
}
</style>
