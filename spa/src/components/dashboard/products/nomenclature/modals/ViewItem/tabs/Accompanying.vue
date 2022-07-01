<template>
  <div class="accompanying">
    <div class="accompanying-header" v-if="isUpdate">
      <paginate-for-table
          :current-page="currentPage"
          :total-page="form.related.total_page"

          @click-next="onClickNext"
          @click-prev="onClickPrev"
          @click-last="onClickLast"
          @click-first="onClickFirst"


      />
      <div class="search-block">
        <input type="text" :placeholder="$t('page.search')" v-model="search">
        <div class="search-icon">
          <svg-sprite width="17" height="17" icon-id="search"></svg-sprite>
        </div>
      </div>
      <div class="accompanying-header-right">
        <custom-btn
            :title="$t('page.сhoose')"
            custom-class="save"
            color="#5893D4"
            @updateEvent="onClickChooseBtn"
        ></custom-btn>
      </div>

      <icon-with-tooltip
          color="#F4F9FF"
          icon="mdi-plus"
          customClass="delete"
          tooltip-text="Добавить строку"
          @push-btn="onClickAddRow"
      />
      <icon-with-tooltip
          color="#F4F9FF"
          customClass="delete"
          :loading="selectedNomenclatures.length < 1"
          @push-btn="onClickDelRow"
          icon="mdi-trash-can-outline"
          tooltip-text="Удалить строку"
      />
    </div>
    <table-with-prices
        @select-item="onSelectItem"
        @toggle-item="onToggleItem"
        @toggle-all="onToggleAll"

        :searchable="isSearchItem"
        :body="form.related.body"
        :headers="form.related.headers"
        :selected-items="selectedNomenclatures"
        with-checkbox
    />
  </div>
</template>

<script>
// components
import TableWithPrices from "@/components/ui/TableWithPrices";
import IconWithTooltip from "@/components/ui/IconWithTooltip";
import CustomBtn from "@/components/ui/CustomBtn/CustomBtn";
import PaginateForTable from "@/components/ui/PaginateForTable";
// constants
import {TABLE_ACTIONS, CHOOSE_MODAL_RESOURCES} from "@/constants/constants";

export default {
  name: "Accompanying",
  components: {PaginateForTable, CustomBtn, IconWithTooltip, TableWithPrices},
  data() {
    return {
      search: '',
      isSearchItem: false,
      selectedNomenclatures: [],
      currentPage: 1,
      resource:CHOOSE_MODAL_RESOURCES.ACCOMPANYING
    }
  },
  props: {
    form: Object,
    mode: String,
  },
  computed: {
    isUpdate() {
      return this.mode === TABLE_ACTIONS.UPDATE
    },
  },
  methods: {
    onClickNext () {
      ++this.currentPage
      this.$emit('go-to-page', {page:this.currentPage, resource:this.resource})
    },
    onClickPrev () {
      --this.currentPage
      this.$emit('go-to-page', {page:this.currentPage, resource:this.resource})
    },
    onClickLast () {
      this.currentPage = this.form.related.total_page
      this.$emit('go-to-page',{page: 1, resource:this.resource})
    },
    onClickFirst ( ) {
      this.currentPage = 1
      this.$emit('go-to-page', {page:this.form.related.total_page, resource:this.resource})
    },
    onSelectItem(item) {
      const resource = CHOOSE_MODAL_RESOURCES.ACCOMPANYING
      this.$emit('add-related-item', item, resource)
      this.isSearchItem = false
    },
    onToggleItem(id) {
      const index = this.selectedNomenclatures.findIndex(item => item.id === id)
      index === -1 ? this.selectedNomenclatures.push({id}) : this.selectedNomenclatures.splice(index, 1)
    },
    onToggleAll(isAllSelected) {
      if (isAllSelected) {
        this.selectedNomenclatures = []
      } else {
        this.selectedNomenclatures = this.form.related.body.map(item => ({id: item.id}))
      }
    },
    onDeleteList() {
      return false
    },
    onClickAddRow() {
      this.isSearchItem = !this.isSearchItem
    },
    onClickDelRow() {
      this.$emit('del-related-rows',[...this.selectedNomenclatures])
      this.selectedNomenclatures = []
    },
    onClickChooseBtn() {
      this.$emit('click-choose-btn',
          {
            chooseResource: CHOOSE_MODAL_RESOURCES.ACCOMPANYING
          }
      )
    },
  }
}
</script>

<style scoped lang="scss">
.transparent-btn {
  min-height: 36px;
  width: 100%;
  max-width: 160px;
  font-weight: bold;
  font-size: 13px;
  line-height: 13px;
  color: #9DCCFF;
  border: solid 1px #9DCCFF;
  border-radius: 32px;

  &:hover {
    background-color: #9DCCFF;
    color: #fff;
  }

  margin-left: 10px;
  margin-right: 10px;
}

.circle-btn {
  height: 36px;
  width: 36px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #9DCCFF;

  &:hover {
    background: #5893D4;
  }
}

.accompanying {
  padding: 0 30px;

  &-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;

    .label-title {
      width: auto;
      white-space: nowrap;
    }

    &-right {
      width: 100%;
      display: flex;
      justify-content: flex-end;
    }


  }

  .search-block {
    max-width: 392px;

    input {
      border: 1px solid #9DCCFF;
    }
  }
}
</style>
