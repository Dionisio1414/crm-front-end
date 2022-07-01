<template>
  <div class="page with-tabs">
    <div class="page-header">
      <h2 class="page-title">{{$t('report.reportTitle')}}</h2>
      <div class="workTime_top">
        <div class="left" v-if="departmentsId">
          <work-time-calendar
              :tabIndex="currentTabIndex"
              :typeOfAbsenceObj="typeOfAbsenceObj"
              @updateDate="onDate"
              isDatePickerTop
              isThroughTime
          ></work-time-calendar>
        </div>
        <div class="right">
          <report-select
              v-if="!isBlockTop"
              :items="typeOfAbsenceObj"
              customClass="absenceType"
              :title="$t('report.absenceTypeSelect')"
          ></report-select>
          <report-select
              v-if="departmentsList"
              :rangeDays="rangeDays"
              :items="departmentsList"
              customClass="department"
              :title="$t('report.department')"
              @updateReportSelect="onReportSelect"
              isDepartment
          ></report-select>
        </div>
      </div>
    </div>
    <div class="card-grid">
      <tabs-list
          :tabs="tabs"
          :idx="currentTabIndex"
          @updateTabs="checkTab"
      ></tabs-list>
      <div class="card detail-item">
        <component
            v-if="workTimeList"
            :typeOfAbsenceObj="typeOfAbsenceObj"
            :workTimeList="workTimeList"
            :is="currentComponent"
        ></component>
      </div>
    </div>
  </div>
</template>

<script>
// vuex
import {mapActions, mapGetters} from 'vuex';
// components
import WorkTime from "@/components/dashboard/report/WorkTime/WorkTime";
import AbsenceSchedule from "@/components/dashboard/report/AbsenceSchedule/AbsenceSchedule";
import WorkTimeCalendar from "@/components/ui/WorkTimeCalendar/WorkTimeCalendar";
import ReportSelect from "./components/ReportSelect";
import TabsList from "./components/TabsList/TabsList";

export default {
  name: "report",
  components: {
    WorkTime,
    AbsenceSchedule,
    WorkTimeCalendar,
    ReportSelect,
    TabsList,
  },
  computed: {
    ...mapGetters(['workTimeList', 'departmentsList', 'departmentsListDefaultId']),
    currentComponent() {
      return this.tabsComponents[this.currentTabIndex]
    },
    computedDepId() {
      return this.departmentsList && this.departmentsList[0].id;
    }
  },
  data:() => ({
    rangeDays: null,
    departmentsId: null,
    workItemsItems: [
      {id: 1, title: 'Все отделы'},
      {id: 2, title: 'Все отделы1'}
    ],
    isBlockTop: true,
    tabs: [
      {title: "report.workTimeTitle"},
      {title: "report.absenceScheduleTitle"},
    ],
    tabsComponents: ['workTime', 'AbsenceSchedule'],
    currentTabIndex: 0,
    typeOfAbsenceObj: [
      {title: 'Все типы', color: '#5893D4', id: 1},
      {title: 'Отпуск ежегодный', color: '#69B9E7', id: 2},
      {title: 'Командировка', color: '#FFCA2A', id: 3},
      {title: 'Больничный', color: '#FF9A40', id: 4},
      {title: 'Отпуск декретный', color: '#CA79FC', id: 5},
      {title: 'Отгул за свой счет', color: '#BDBDBD', id: 6},
      {title: 'прогул', color: '#FF6D6D', id: 7},
      {title: 'Другое', color: '#7DE2A7', id: 8}]
  }),
  methods: {
    ...mapActions(['getWorkTimeList', 'getDepartmentsList', 'actionWorkTimeList']),
    onDate(date) {
      this.rangeDays = date;

      const values = {
        company_departments_id: this.departmentsId
      };

      if (date && date.length > 1) {
        values.date_from =  this.rangeDays[0];
        values.date_to =  this.rangeDays[1];
      }

      //await this.actionWorkTimeList(values);
    },
    onReportSelect(id) {
      this.departmentsId = id;
    },
    checkTab(index, title) {
      this.isBlockTop = title === "report.workTimeTitle";
      this.currentTabIndex = index;
    },
  },
  async mounted() {
    await Promise.all([this.getWorkTimeList(), this.getDepartmentsList()]);
    this.departmentsId = this.departmentsListDefaultId;
  }
}
</script>

<style scoped lang="scss">
.right {
  display: flex;
  align-items: center;

  .absenceType {
    margin-right: 30px;
  }
}

.workTime_top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 42px;
  width: 85%;
  padding: 0 20px;
}

.page-title {
  width: 16.66%;
}
</style>

<style lang="scss">
@import "src/sass/mixins";

.currencyDropDown {
  max-height: 200px !important;
  margin-top: 32px !important;
  box-shadow: 0 3px 7px rgba(0, 0, 0, 0.1);
  border-radius: 0 0 10px 10px !important;

  .v-sheet.v-list {
    padding: 0 !important;
  }

  .selectItem {
    font-weight: 550;
    font-size: 12px;
    line-height: 12px;
    text-transform: uppercase;
  }

  .v-list-item {
    padding: 0 8px;

    &:before {
      display: none;
    }

    &.v-list-item--link.v-list-item--active {
      background-color: #F4F9FF;
    }

    &:hover {
      background: #F4F9FF;
      .v-list-item__title {
        color: #317CCE !important;
      }
    }
  }

  @include custom-scroll-bar()
}
</style>
