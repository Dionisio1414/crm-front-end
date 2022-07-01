<template>
  <div class="forms-wrapper-item userSettings userSettings--notif">
    <v-row>
      <v-col cols="6">
        <div class="item-form-column">
          <v-row>
            <v-col cols="6" class="userSettings__types">
              <div class="userSettings__type">
                <img src="@/assets/img/desktop.svg" alt="">
                <span>Desktop уведомления</span>
                <switcher @toggle="onToggleVisible" :value="switchShow"></switcher>
              </div>
              <div class="userSettings__type">
                <img src="@/assets/img/email.svg" alt="">
                <span>Email уведомления</span>
                <switcher @toggle="onToggleVisible" :value="switchShow"></switcher>
              </div>
              <div class="userSettings__type">
                <img src="@/assets/img/push.svg" alt="">
                <span>Push уведомления</span>
                <switcher @toggle="onToggleVisible" :value="switchShow"></switcher>
              </div>
            </v-col>
            <v-col cols="5" class="offset-md-1 pt-0 pb-0">
              <div class="userSettings__volume">
                <button class="userSettings__volumeActive">Со звуком</button>
                <button>Без звука</button>
              </div>
            </v-col>
          </v-row>
        </div>
      </v-col>
      <v-col cols="6" class="userSettings__notifList">
        <div class="checkbox-wrapper">
          <input type="checkbox" id="entered-heandle0" />
          <label for="entered-heandle0">Создание документов</label>
        </div>
        <div class="checkbox-wrapper">
          <input type="checkbox" id="entered-heandle1" />
          <label for="entered-heandle1">Новые задачи</label>
        </div>
        <div class="checkbox-wrapper">
          <input type="checkbox" id="entered-heandle2" />
          <label for="entered-heandle2">Изменение задач</label>
        </div>
        <div class="checkbox-wrapper">
          <input type="checkbox" id="entered-heandle3" />
          <label for="entered-heandle3">Закрытие задач</label>
        </div>
        <div class="checkbox-wrapper">
          <input type="checkbox" id="entered-heandle4" />
          <label for="entered-heandle4">Создание события</label>
        </div>
        <div class="checkbox-wrapper">
          <input type="checkbox" id="entered-heandle5" />
          <label for="entered-heandle5">Добавление элемента (номенклатуры, категории, характеристики и т.д.)</label>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>

import Switcher from "@/components/ui/Swithcer";

export default {
  name: "SettingsNotifications",
  components: {
    Switcher
  },
}
</script>

<style lang="sass">
  .userSettings
    &--notif
      .userSettings__types
        padding: 0 12px
      .userSettings__type
        display: flex
        justify-content: flex-start
        align-items: center
        &:not(:last-child)
          margin-bottom: 30px
        span
          display: inline-block
          margin-left: 15px
          font-size: 14px
          font-weight: 550
          line-height: 14px
          color: #7e7e7e
          white-space: nowrap
        .switcher
          margin-left: auto
      .userSettings__volume
        button
          font-size: 12px
          font-weight: 550
          line-height: 12px
          width: 91px
          text-align: center
          padding: 7px 15px
          border: 1px solid #BBDBFE
          white-space: nowrap
          border-radius: 100px
          color: #BBDBFE
          &:not(:last-child)
            margin-right: 10px
        &Active
          background: #BBDBFE
          color: #fff!important
      .userSettings__notifList
        .checkbox-wrapper
          display: flex
          align-items: center
          &:not(:last-child)
            margin-bottom: 15px
          label
            font-size: 14px
            line-height: 14px
            color: #7e7e7e
          input[type=checkbox]
            &::before
              border-color: #7CE1A4
              cursor: pointer
            &::after
              background-color: #7CE1A4
              cursor: pointer





</style>