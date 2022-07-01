<template>
  <div class="forms-wrapper-item userSettings userSettings--security">
    <v-row>
      <v-col cols="6">
        <div class="item-form-column userSettings__auth">
          <div class="label-title mb-0">
            <div class="label-title-text">Двухфакторная аутентификация</div>
          </div>

          <div class="field-wrapper userSettings__authDefault">
            <div class="field-actions">
              <div class="form-field">
                <div class="label-title">
                  <!-- *** label-title-status--on *** -->
                  <div class="label-title-status">Не включена</div>
                </div>
              </div>
            </div>
            <div data-v-bc6967d8="" class="form-actions pt-0">
              <button type="button" class="base-button base-button--blue">
                Включить
              </button>
            </div>
          </div>

          <div class="userSettings__authAdd form-content" style="display:flex">

            <div class="label-subtitle mb-5">Присылать код:</div>
            <div class="userSettings__authAddVar">
              <div class="radio-wrapper-row">
                <div class="radio">
                  <label class="radio-label">
                    <input type="radio" name="radio" value="true" checked />
                    <div class="radio-text">
                      <div class="text">по SMS</div>
                    </div>
                  </label>
                </div>
              </div>
              <div class="radio-wrapper-row">
                <div class="radio">
                  <label class="radio-label">
                    <input type="radio" name="radio" value="true" />
                    <div class="radio-text">
                      <div class="text">на E-mail</div>
                    </div>
                  </label>
                </div>
              </div>
            </div>
            <button class="base-button base-button--lighten">Отправить</button>

            <div class="userSettings__authAddDetail mt-5">
              <div class="userSettings__authAddInfo">
                На ваш номер телефона
                <span>отправлено SMS с кодом для подтверждения</span> включения
                двухфакторной аутентификации. <br />
                Действует 10 минут.
                <button class="userSettings__authAddRepeat">
                  Отправить заново
                </button>
              </div>
              <div class="label-title mt-7 mb-0">
                <div class="label-title-text">код подтверждения</div>
              </div>
              <div class="userSettings__authAddCode">
                <div class="form-field">
                  <input class="field" type="text" name="code" placeholder="Введите код" ref="code" />
                </div>
                <button type="button" class="btn-action save">
                  <SvgSprite :width="30" :height="30" iconId="save_mark" />
                </button>
                <div class="userSettings__authAddWrong">Неверный код подтверждения</div>
              </div>
            </div>
          </div>
        </div>
      </v-col>
      <v-col cols="6">
        <div class="item-form-column item-form-column--fill">
          <div class="userSettings__sessTitle">
            <div class="field-wrapper">
              <div class="field-actions">
                <div class="form-field">
                  <div class="label-title">
                    <div class="label-title-text">Управление сессиями</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="userSettings__sessDelay">
            <div class="item-name">
              <div class="label-subtitle mb-5">длительность</div>
              <div class="select-wrap">
                <template>
                  <v-select
                    class="select-switcher"
                    item-text="title"
                    item-value="directory_id"
                    solo
                    dense
                    hide-details
                    return-object
                  />
                </template>
              </div>
            </div>
          </div>

          <div class="label-subtitle mb-5 mt-5">Активные:</div>
          <div class="userSettings__sessList">
            <div class="userSettings__session userSettings__session--main">
              <div class="userSettings__sessionInfo">
                <div class="userSettings__sessionAgent">Chrome, Windows</div>
                <div class="userSettings__sessionStatus">Текущая</div>
                <div class="userSettings__sessionLoc">
                  188.191.146.253 - Kharkiv, Ukraine
                </div>
              </div>
              <button
                class="userSettings__sessionEnd userSettings__sessionEnd--main"
              >
                Завершить другие
              </button>
            </div>

            <div class="userSettings__session">
              <div class="userSettings__sessionInfo">
                <div class="userSettings__sessionAgent">Chrome, Windows</div>
                <div class="userSettings__sessionStatus">18.12.20</div>
                <div class="userSettings__sessionLoc">
                  188.191.146.253 - Kharkiv, Ukraine
                </div>
              </div>
              <button class="userSettings__sessionEnd userSettings__sessionEnd">
                Завершить
              </button>
            </div>

            <div class="userSettings__session">
              <div class="userSettings__sessionInfo">
                <div class="userSettings__sessionAgent">Chrome, Windows</div>
                <div class="userSettings__sessionStatus">18.12.20</div>
                <div class="userSettings__sessionLoc">
                  188.191.146.253 - Kharkiv, Ukraine
                </div>
              </div>
              <button class="userSettings__sessionEnd userSettings__sessionEnd">
                Завершить
              </button>
            </div>

            <div class="userSettings__session">
              <div class="userSettings__sessionInfo">
                <div class="userSettings__sessionAgent">Chrome, Windows</div>
                <div class="userSettings__sessionStatus">18.12.20</div>
                <div class="userSettings__sessionLoc">
                  188.191.146.253 - Kharkiv, Ukraine
                </div>
              </div>
              <button class="userSettings__sessionEnd userSettings__sessionEnd">
                Завершить
              </button>
            </div>

            <div class="userSettings__session">
              <div class="userSettings__sessionInfo">
                <div class="userSettings__sessionAgent">Chrome, Windows</div>
                <div class="userSettings__sessionStatus">18.12.20</div>
                <div class="userSettings__sessionLoc">
                  188.191.146.253 - Kharkiv, Ukraine
                </div>
              </div>
              <button class="userSettings__sessionEnd userSettings__sessionEnd">
                Завершить
              </button>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
    <div class="dialog-footer pb-0">
      <div class="form-actions pt-0">
        <button type="button" class="base-button base-button--transparent"> Отменить </button>
        <button type="button" class="base-button base-button--blue"> Сохранить </button>
      </div>
    </div>
  </div>
</template>

<script>
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite";

export default {
  name: "Security",
  components: {
    SvgSprite,
  },
};
</script>

<style lang="sass">
.userSettings
  &__auth
    padding-bottom: 30px
    .label-title-status
      font-size: 14px
      font-weight: 400
      color: #7E7E7E
      text-transform: none
      &--on
        color: #4ECA80
    .form-actions
      text-align: left
      margin-top: 30px
    &Default
      margin: 12px 0 0 0!important
    &Add
      margin: 20px 0 0 0!important
      display: flex
      flex-wrap: wrap
      justify-content: space-between
      align-items: flex-end
      .label-subtitle
        width: 100%
      .base-button
        width: 100%
        max-width: 160px
      &Detail
        .label-title-text
          &::before
            display: none
      &Var
        .radio-wrapper-row
          &:not(:last-child)
            margin-bottom: 6px
      &Info
        font-size: 13px
        line-height: 17px
        color: #AFAFAF
        span
          font-weight: 700
        button
          color: #5893D4
          text-decoration: underline
        
      &Code
        display: flex
        justify-content: flex-start
        align-items: center
        .form-field
          width: 100%
          max-width: 230px
        button
          margin-left: 10px
      &Wrong
        margin-left: auto
        font-size: 13px
        font-weight: 550
        color: #FF9F2F

  &__sessDelay
    max-width: 176px
    .v-input__slot
      background: transparent!important

  &__sessList
    max-height: 220px
    overflow: auto

  &__session
    display: flex
    justify-content: space-between
    align-items: flex-start
    &Info
      max-width: 204px
      display: flex
      flex-wrap: wrap
      justify-content: space-between
      align-items: center
    &Agent
      font-size: 15px
      color: #7e7e7e
      line-height: 15px
      max-width: 70%
      white-space: nowrap
      text-overflow: ellipsis
      overflow: hidden
    &Status
      font-size: 13px
      line-height: 13px
      color: #7e7e7e
      margin-left: auto
    &Loc
      width: 100%
      font-size: 13px
      line-height: 13px
      color: #7e7e7e
      margin-top: 8px
    &End
      font-size: 13px
      line-height: 13px
      color: #5893d4
      text-decoration: underline
      &--main
        color: #FF9F2F
    &:not(:last-child)
      border-bottom: 1px solid #E3F0FF
      padding-bottom: 15px
      margin-bottom: 15px
    &--main
      .userSettings__sessionStatus
        color: #9dccff


.label-subtitle
  color: #9dccff
  text-transform: uppercase
  font-size: 13px
  font-weight: 700

</style>