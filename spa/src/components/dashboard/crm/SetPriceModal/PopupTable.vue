<template>
  <div class="test-wrapper">
    <div class="table">
      <div class="top-row-actions-elements">
        <div class="top-row-actions-elements__right-bar">
          <div class="btn-change-value-wrapper">
            <span class="change-text">Изменить на: </span>
            <div class="btn-change-value-holder">
              <button class="btn-change-value btn-change-value--outline">Сумму</button>
              <button class="btn-change-value btn-change-value--default">Процент</button>
            </div>
          </div>
          <div class="input-holder">
            <input type="text">
            <div class="icon-percent">%</div>
          </div>
          <div class="btn-group">
            <button class="btn-complete">
              <SvgSprite
                  :width="15"
                  :height="12"
                  iconId="icon-check"
              />
            </button>
            <button class="btn-cancel">
              <SvgSprite
                  :width="12"
                  :height="12"
                  iconId="icon-close"
              />
            </button>
          </div>
          <div class="btn-round-wrapper">
            <button>
              <SvgSprite
                  :width="36"
                  :height="36"
                  iconId="icon-round-correct"
              />
            </button>
            <button>
              <SvgSprite
                  :width="36"
                  :height="36"
                  iconId="icon-math-round"
              />
            </button>
          </div>
        </div>
      </div>
      <div class="thead thead__wrapper">
        <div class="thead__col-left">
          <div class="thead__row-actions">
            <span class="thead__row-actions-title">товары</span>
            <div class="thead__row-actions-right-bar">
              <span class="thead__row-actions-fake-link">Подобрать товары заново</span>
              <span class="fake-button fake-button--icon-delete">
              <SvgSprite
                :width="36"
                :height="36"
                iconId="icon-delete-round"
                />
              </span>
            </div>
          </div>
          <div class="thead__row thead__row--group-col-left">
            <div class="thead__item">
              <div class="thead__item-content">
                <drop-dots></drop-dots>
              </div>
            </div>
            <div class="thead__item">
              <div class="thead__item-content">
                <div class="fake-checkbox"></div>
              </div>
            </div>
            <div class="thead__item">
              <div class="thead__item-content thead__item-content--title-col">
                Артикул
                <span class="fake-button">
                    <SvgSprite
                        :width="17"
                        :height="12"
                        iconId="icon-double-arrow-sort"
                    />
                  </span>
              </div>
            </div>
            <div class="thead__item">
              <div class="thead__item-content thead__item-content--title-col">
                Наименование
                <span class="fake-button">
                    <SvgSprite
                      :width="17"
                      :height="12"
                      iconId="icon-double-arrow-sort"
                    />
                  </span>
              </div>
            </div>
            <div class="thead__item thead__item--align-right">
              <div class="thead__item-content thead__item-content--title-col">
                Мин. цена
                <span class="fake-button">
                    <SvgSprite
                      :width="17"
                      :height="12"
                      iconId="icon-double-arrow-sort"
                    />
                  </span>
              </div>
            </div>
          </div>
        </div>
        <div class="thead__col-right">
          <div class="thead__col-right-group-wrapper group-wrapper-status-3">
            <div class="thead__col thead__item-content--title-col">
              <div class="thead__item-content-left-bar">
                <span class="fake-button fake-button--sort-down">
                  <SvgSprite
                    :width="16"
                    :height="16"
                    iconId="icon-sort-down"
                  />
                </span>
                <span class="thead__item-content-wrapper">
                  <span class="thead__item-content-text">Оптовая для одежды</span>
                  <span class="fake-button fake-button--not-disable-price">
                    <SvgSprite
                      :width="14"
                      :height="18"
                      iconId="icon-not-disable-price"
                    />
                   </span>
                </span>
              </div>
            </div>
            <div class="thead__row thead__row--group-col-right">
              <div class="thead__item">
                <div class="thead__item-content">Действ. цена</div>
              </div>
              <div class="thead__item">
                <div class="thead__item-content">Отклонение</div>
              </div>
              <div class="thead__item">
                <div class="thead__item-content">Новая цена</div>
              </div>
            </div>
          </div>
          <div class="thead__col-right-group-wrapper group-wrapper-status-1">
            <div class="thead__col thead__item-content--title-col">
              <div class="thead__item-content-left-bar">
                <span class="fake-button fake-button--sort-down">
                  <SvgSprite
                    :width="16"
                    :height="16"
                    iconId="icon-sort-down"
                  />
                </span>
                <span class="thead__item-content-wrapper">
                  <span class="thead__item-content-text">Оптовая для одежды</span>
                  <span class="fake-button fake-button--not-disable-price">
                    <SvgSprite
                      :width="14"
                      :height="18"
                      iconId="icon-not-disable-price"
                    />
                   </span>
                </span>
              </div>
            </div>
            <div class="thead__row thead__row--group-col-right">
              <div class="thead__item">
                <div class="thead__item-content">Новая цена</div>
              </div>
            </div>
          </div>
          <div class="thead__col-right-group-wrapper group-wrapper-status-1 group-wrapper-status-whosale">
            <div class="thead__col thead__item-content--title-col">
              <div class="thead__item-content-left-bar">
                <span class="thead__item-content-wrapper">
                  <span class="thead__item-content-text">Оптовая для одежды</span>
                </span>
              </div>
            </div>
            <div class="thead__row thead__row--group-col-right">
              <div class="thead__item">
                <div class="thead__item-content">Новая цена</div>
              </div>
            </div>
          </div>
          <div class="thead__col-right-group-wrapper group-wrapper-status-3">
            <div class="thead__col thead__item-content--title-col">
              <div class="thead__item-content-left-bar">
                <span class="fake-button fake-button--sort-down">
                  <SvgSprite
                      :width="16"
                      :height="16"
                      iconId="icon-sort-down"
                  />
                </span>
                <span class="thead__item-content-wrapper">
                  <span class="thead__item-content-text">Оптовая для одежды</span>
                  <span class="fake-button fake-button--not-disable-price">
                    <SvgSprite
                        :width="14"
                        :height="18"
                        iconId="icon-not-disable-price"
                    />
                   </span>
                </span>
              </div>
            </div>
            <div class="thead__row thead__row--group-col-right">
              <div class="thead__item">
                <div class="thead__item-content">Действ. цена</div>
              </div>
              <div class="thead__item">
                <div class="thead__item-content">Отклонение</div>
              </div>
              <div class="thead__item">
                <div class="thead__item-content">Новая цена</div>
              </div>
            </div>
          </div>
          <button class="btn-pagination btn-pagination-prev">
            <SvgSprite
              :width="23"
              :height="41"
              iconId="icon-pagination-prev"
            />
          </button>
          <button class="btn-pagination btn-pagination-next">
            <SvgSprite
              :width="23"
              :height="41"
              iconId="icon-pagination-next"
            />
          </button>
        </div>
      </div>
      <div class="table__col-wrapper">
        <div class="table__col table__col--left">
          <div class="table__row">
            <div class="table__col-item">
              <div class="table__col-item-content" title="1">1</div>
            </div>
            <div class="table__col-item">
              <div class="table__col-item-content">
                <div class="fake-checkbox"></div>
              </div>
            </div>
            <div class="table__col-item">
              <div class="table__col-item-content" title="52478454411">52478454411</div>
            </div>
            <div class="table__col-item">
              <div class="table__col-item-content" title="Куртка красная XL">Куртка красная XL</div>
            </div>
            <div class="table__col-item">
              <div class="table__col-item-content table__col-item--editable-field" title="1 500">
                1 500
                <span class="fake-button fake-button--edit">
                  <SvgSprite
                      :width="17"
                      :height="18"
                      iconId="edit_pencil_small"
                  />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="table__col table__col--right">
          <div class="table__row">
            <div class="table__col-item table__col-item--cell-group-3">
              <div class="table__col-item-content" title="600,00">600,00</div>
            </div>
            <div class="table__col-item table__col-item--cell-group-3">
              <div class="table__col-item-content" title="200,00">200,00</div>
            </div>
            <div class="table__col-item table__col-item--cell-group-3 table__col-item--new-price">
              <div class="table__col-item-content" title="800,00">800,00</div>
            </div>
            <div class="table__col-item table__col-item--cell-group-1">
              <div class="table__col-item-content" title="830,00">830,00</div>
            </div>
            <div class="table__col-item table__col-item--cell-group-1">
              <div class="table__col-item-content" title="830,00">830,00</div>
            </div>
            <div class="table__col-item table__col-item--cell-group-3 table__col-item--cell-disabled">
              <div class="table__col-item-content" title="300,00">300,00</div>
            </div>
            <div class="table__col-item table__col-item--cell-group-3 table__col-item--cell-disabled">
              <div class="table__col-item-content" title="1 200,00">1 200,00</div>
            </div>
            <div class="table__col-item table__col-item--cell-group-3 table__col-item--new-price table__col-item--cell-disabled">
              <div class="table__col-item-content" title="300,00">300,00</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import DropDots from "@/components/dashboard/crm/SetPriceModal/DropDots"

export default {
  name: "PopupTable",
  components: {
    SvgSprite,
    DropDots
  },

  data() {
    return {
      isVisibleSwitcher: false
    }
  },
}
</script>

<style scoped lang="scss">
.test-wrapper {
  padding: 30px 30px 50px;
}

.thead {
  &__col-right {
    display: flex;
    position: relative;

    .btn-pagination {
      position: absolute;
      top: 0;
    }

    .btn-pagination-next,
    .btn-pagination-prev {
      display: flex;

      &:hover {
        svg {
          fill-opacity: 1;
        }
      }

      svg {
        transition: fill 0.3s, fill-opacity 0.3s;
        fill: #9DCCFF;
        fill-opacity: 0.3;
      }
    }

    .btn-pagination-next {
      right: 0;
    }

    .btn-pagination-prev {
      left: 0;
    }
  }

  &__row-actions-title {
    color: #317CCE;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.02em;
    font-weight: 600;
  }

  &__row-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  &__row-actions-right-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }

  &__row-actions-fake-link {
    transition: color 0.3s;
    cursor: pointer;
    color: #9DCCFF;
    position: relative;
    padding: 0 0 2px;
    font-size: 13px;
    line-height: 1;

    &:hover {
      color: #FF951A;

      &:after {
        background-color: #FF951A;
      }
    }

    &:after {
      transition: background-color 0.3s;
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 100%;
      height: 1px;
      background-color: #9DCCFF;
    }
  }

  &__item-content-text {
    display: inline-block;
    -webkit-box-orient: vertical;
    display: block;
    display: -webkit-box;
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
    width: 100%;
  }

  &__col-right-group-wrapper {
    display: grid;

    .thead__item-content--title-col {
      margin: 0 3px 2px 0;
    }

    &:last-of-type {
      .thead__item-content--title-col {
        margin: 0 0 2px 0;
      }
    }

    &.group-wrapper-status-1 {
      grid-template-columns: 161.4px;

      .thead__item-content-text {
        -webkit-line-clamp: 1;
        max-height: 17px;
        min-height: 17px;
      }

      .fake-button--not-disable-price {
        margin: 0 0 0 7px;
      }

      .thead__item {
        text-align: right;
      }

      .thead__row--group-col-right {
        grid-template-columns: repeat(1, 158px);
        text-align: center;
      }
    }

    &.group-wrapper-status-whosale {
      .thead__col {
        background-color: #F2F2F2;
      }

      .thead__item-content--title-col {
        color: #7E7E7E;
      }

      .thead__item {
        color: #7E7E7E;
      }
    }

    &.group-wrapper-status-2 {
      grid-template-columns: 216px;

      .thead__item-content-text {
        -webkit-line-clamp: 1;
        max-height: 17px;
        min-height: 17px;
      }

      .thead__row--group-col-right {
        grid-template-columns: repeat(2, 107px);
      }
    }

    &.group-wrapper-status-3 {
      grid-template-columns: 323px;
      //grid-template-columns: 322px;

      .thead__item-content-text {
        -webkit-line-clamp: 1;
        max-height: 17px;
        min-height: 17px;
      }

      .thead__row--group-col-right {
        grid-template-columns: repeat(3, 107px);
      }
    }

    .thead__col {
      background-color: #F4F9FF;
      padding: 12px 19px 12px 28px;
    }
  }

  &__wrapper {
    display: grid;
    grid-template-columns: minmax(414px, 561px) minmax(300px, 1fr);
    column-gap: 10px;
    align-items: flex-end;
  }

  &__item-content-wrapper {
    display: flex;
    align-items: center;
    width: 100%;
  }

  &__item {
    &--align-right {
     text-align: right;
    }

    &--no-data {
      display: none;
    }
  }

  &__row {
    min-height: 50px;

    &--group-col-left {
      display: grid;
      align-items: center;
      grid-template-columns: minmax(30px, 41px) 30px minmax(113px, 113px) minmax(166px, 194px) 1fr;
      border-radius: 10px 0 0 0;
      border: 1px solid #E3F0FF;
      padding: 12px 20px 12px 15px;

      .thead__item {
        padding: 0 8px;
      }
    }

    &--group-col-right {
      display: grid;
      align-items: center;
      border-width: 1px 1px 1px 1px;
      border-color: #E3F0FF;
      border-style: solid ;
      text-align: right;
      font-size: 14px;

      .thead__item {
        color: #5893D4;
        font-weight: 500;
        padding: 12px 11px 12px 7px;
      }
    }
  }

  &__item-content {
    &--title-col {
      color: #5893D4;
      font-weight: 500;
      font-size: 15px;
    }
  }

  &__item-content-left-bar {
    display: flex;
    align-items: center;
    line-height: 1;
  }
}

.table {
  width: max-content;
  color: #7E7E7E;

  &__row {
    display: grid;
    align-items: center;
    border-bottom: 1px solid #E3F0FF;
    min-height: 50px;
    padding: 12px 20px 12px 15px;

    &:last-child {
      border-bottom: 0;
    }
  }

  &__col-wrapper {
    display: grid;
    grid-template-columns: minmax(400px, 561px) minmax(300px, max-content);
    column-gap: 10px;
    font-size: 15px;
    //width: max-content;
    max-height: 502px;
    overflow-y: auto;
    scrollbar-color: #fff rgba(0,0,0,0);
    scrollbar-width: thin;

    &::-webkit-scrollbar {
      width: 2px
    }

    &::-webkit-scrollbar-track {
      background-color: transparent
    }

    &::-webkit-scrollbar-thumb {
      background-color: #9DCCFF
    }
  }

  &__col-item {
    padding: 0 7px;

    &--editable-field {
      &:hover {
        .fake-button {
          opacity: 1;
          visibility: visible;
        }
      }
    }

    &--no-data {
      display: none;
    }
  }

  &__col-wrapper {
    //display: grid;
    //grid-template-columns: minmax(400px, 561px) minmax(300px, 1fr);
    //column-gap: 10px;
  }

  &__col {
    border-width: 0 1px 1px 1px;
    border-color: #E3F0FF;
    border-style: solid;

    &--left {
      border-radius: 0 0 10px 10px;

      .thead__item {
        text-align: right;
        padding: 0 7px;

        &:not(:last-child) {
          text-align: left;
        }
      }

      .table__row {
        grid-template-columns: minmax(30px, 41px) 30px minmax(113px, 113px) minmax(166px, 193px) 1fr;
      }

      .table__col-item {
        &:last-child {
          text-align: right;
        }
      }
    }

    &--right {
      border-radius: 0 0 10px 10px;
      //width: max-content;

      .table__row {
        display: flex;
        padding: 0;
      }

      .table__col-item {
        text-align: right;
        padding: 13px 9px 13px 6px;

        .table__col-item-content {
          width: 90px;
        }

        &--cell-group-3 {
          min-width: 107.4px;
        }

        &--cell-group-2 {
          min-width: 107px;
        }

        &--cell-group-1 {
          min-width: 161px;
          text-align: right;
          //min-width: 164px;

          .table__col-item-content {
            width: 140px;
          }
        }

        &--cell-disabled {
          color: #C8E2FF;
        }

        &--new-price {
          font-weight: 600;
        }
      }
    }
  }

  &__col-item-content {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;

    &--bold-font {
      font-weight: 700;
    }
  }

  .fake-button {
    display: inline-block;
    margin: 0 0 0 10px;
    cursor: pointer;

    svg {
      transition: fill 0.3s;
    }

    &--sort-down {
      display: inline-block;
      height: 16px;
      margin: 0 10px 0 0;
    }

    &--edit {
      transition: opacity 0.3s, visibility 0.3s;
      opacity: 0;
      visibility: hidden;
    }

    &--dots {
      margin: 0;
    }

    &--not-disable-price {
      display: inline-block;
      height: 18px;
      margin: 0 10px 0 0;
    }

    &--icon-delete {
      svg {
        fill: #9DCCFF;
      }

      &:hover {
       svg {
         fill: #5893D4;
       }
      }
    }
  }

  .fake-checkbox {
    width: 16px;
    height: 16px;
    background-color: red;
  }

  .table-column-price {
    display: grid;
    grid-template-columns: 350px;

    &__title {
      padding: 8px 23px 8px;
      background-color: #F4F9FF;
    }

    &__column-title-wrapper {
      padding: 12px 0 12px 0;
    }

    &__column-title {
      padding: 0 7px;
    }

    &__col-item {
      border-bottom: 1px solid #E3F0FF;
      padding: 12px 20px 12px 15px;
    }

    &__column {
      text-align: right;
    }
  }

  &__item--no-data {
    display: none;
  }

  .col-left-status-2 {
    grid-template-columns: minmax(400px, 400px) minmax(300px, 1fr);
  }
}

.top-row-actions-elements {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  margin: 0 0 10px;

  &__right-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }

  .btn-change-value-wrapper {
    display: flex;
    align-items: center;
    margin: 0 20px 0 0;
  }

  .change-text {
    color: #5893D4;
    font-size: 14px;
    margin: 0 10px 0 0;
  }

  .btn-change-value {
    margin: 0 10px 0 0;

    &:last-child {
      margin: 0;
    }
  }

  .input-holder {
    max-width: 117px;
    width: 100%;
    position: relative;
    margin: 0 10px 0 0;

    .icon-percent {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      right: 11px;
      font-size: 15px;
      color: #9DCCFF;
    }

    input {
      width: 100%;
      height: 32px;
      border: 1px solid #E3F0FF;
      border-radius: 32px;
      padding: 5px 30px 5px 15px;
      font-size: 13px;
      color: #7E7E7E;
      line-height: 32px;
    }
  }

  .btn-group {
    button {
      transition: background-color 0.3s;
      min-width: 40px;
      min-height: 32px;
    }

    .btn-complete {
      background-color: #9DCCFF;
      border-radius: 32px 0 0 32px;

      &:hover {
        background-color: #5893D4;
      }

      svg {
        fill: #fff;
      }
    }

    .btn-cancel {
      border-top: 1px solid #9DCCFF;
      border-bottom: 1px solid #9DCCFF;
      border-radius: 0 32px 32px 0;

      &:hover {
        background-color: #9DCCFF;
        border-top: 1px solid #9DCCFF;
        border-bottom: 1px solid #9DCCFF;

        svg {
          fill: #fff;
        }
      }

      svg {
        transition: fill 0.3s;
        fill: #9DCCFF;
      }
    }
  }

  .btn-round-wrapper {
    display: flex;
    align-items: center;
    margin: 0 0 0 20px;

    button {
      display: flex;
      margin: 0 10px 0 0;

      &:last-child {
        margin: 0;
      }
    }
  }
}

.btn-change-value {
  transition: border 0.3s, background-color 0.3s, color 0.3s;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  min-width: 80px;
  min-height: 30px;
  text-decoration: none;
  border-radius: 80px;
  font-size: 12px;
  padding: 5px 10px;
  border: 1px solid transparent;

  &--outline {
    color: #9DCCFF;
    border: 1px solid #BBDBFE;

    &:hover {
      color: #fff;
      border: 1px solid #9DCCFF;
      background-color: #9DCCFF;
    }
  }

  &--default {
    color: #fff;
    background-color: #9DCCFF;
    border: 1px solid transparent;

    &:hover {
      background-color: transparent;
      border: 1px solid #9DCCFF;
      color: #9DCCFF;
    }
  }
}

.thead__col-right-group-wrapper ~ .thead__col-right-group-wrapper {
  .thead__row--group-col-right {
    border-width: 1px 1px 1px 0;
  }
}

</style>