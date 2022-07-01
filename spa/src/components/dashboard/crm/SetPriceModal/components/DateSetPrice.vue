<template>
  <div class="dataOfSetPrice">
    <div class="dataOfSetPrice_title">
      <span>Дата установки цены</span>
      <tool-tip
          title="- Новые цены будут установлены на начало указанной даты"
      >
        <svg-sprite width="16" height="16" icon-id="question"></svg-sprite>
      </tool-tip>
    </div>
    <calendar
        @updateDate="onChangeDate"
        :date-value="currentDate"
        custom-class="default-calendar"
        date-key="currentDate"
        isCloseOnContentClick
        is-icon
    />
  </div>
</template>

<script>
import Calendar from "@/components/ui/Calendar/Calendar";
import ToolTip from "@/components/ui/ToolTip/ToolTip";
import getCurrentDate from "@/helper/getCurrentDate";

export default {
  name: "DateSetPrice",
  components: {
    Calendar,
    ToolTip
  },
  data: () => ({
    currentDate: getCurrentDate()
  }),
  methods: {
    onChangeDate(params) {
      this[params.key] = params.date;
    },
  }
}
</script>

<style scoped lang="scss">
.dataOfSetPrice {
  &_title {
    display: flex;
    align-items: center;
    font-weight: bold;
    font-size: 13px;
    line-height: 13px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #317CCE;

    span:first-child {
      display: inline-block;
      margin-right: 10px;
    }
  }
}
</style>

<style lang="scss">
.dataOfSetPrice {
  svg {
    position: absolute;
    right: 5px;
    bottom: 5px;
  }

  .v-input {
    max-width: 170px;
  }

  .v-input__slot {
    &::before, &::after {
      display: none !important;
    }

    input {
      font-size: 14px;
      color: #7E7E7E;
    }
  }
}
</style>