<template>
  <div>
    <div class="userSettings userSettings--tariffs">

      <v-row>
        <v-col cols="6">
          <div class="item-form-column userSettings__tariffInfo userSettings__tariff">
            <v-row>
              <v-col cols="7" class="pt-0 pb-0">
                <div class="label-title mb-0">
                  <div class="label-title-text">Текущий тарифный план</div>
                </div>
                <div class="userSettings__tariffType mt-2">Стандартный</div>
                <div class="tariff__price mt-2 ml-4 mb-0">
                  <div class="price mr-2">3 500</div>
                  <div class="vallet">грн/мес</div>
                </div>

              </v-col>
              <v-col cols="5" class="pt-0 pb-0">
                <div class="label-title mb-0">
                  <div class="label-title-text">Следующая оплата</div>
                </div>
                <div class="userSettings__tariffDate">25.12.2020</div>
              </v-col>
            </v-row>            
          </div>
        </v-col>
        <v-col cols="6">
          <div class="item-form-column userSettings__tariffInfo userSettings__tariffStatus">
            <v-row>
              <v-col cols="4" class="pt-0 pb-0">
                <div class="label-title mb-0">
                  <div class="label-title-text">Пользователи</div>
                </div>
                <div class="userSettings__tariffStatusItem">
                  <img src="@/assets/img/users.svg" alt="">
                  <div class="userSettings__tariffCount">
                    <span>25</span>
                    <span>/50</span>
                  </div>
                </div>
              </v-col>
              <v-col cols="4" class="pt-0 pb-0">
                <div class="label-title mb-0">
                  <div class="label-title-text">Товары</div>
                </div>
                <div class="userSettings__tariffStatusItem">
                  <img src="@/assets/img/products.svg" alt="">
                  <div class="userSettings__tariffCount">
                    <span>254</span>
                    <span>/500</span>
                  </div>
                </div>
              </v-col>
              <v-col cols="4" class="pt-0 pb-0">
                <div class="label-title mb-0">
                  <div class="label-title-text">Хранилище</div>
                </div>
                <div class="userSettings__tariffStatusItem">
                  <img src="@/assets/img/cloud.svg" alt="">
                  <div class="userSettings__tariffCount">
                    <span>15</span>
                    <span>/50 Gb</span>
                  </div>
                </div>
              </v-col>
            </v-row> 
          </div>
        </v-col>
      </v-row>

      <div class="compare-table">
        <div class="compare-table__col">
          <div class="compare-heading">
            <div class="compare-heading__title">Базовые возможности</div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Количество пользователей</div>
            </div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Облачное хранилище</div>
            </div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Количество товаров</div>
            </div>
          </div>
          <div class="compare-heading">
            <div class="compare-heading__title">Кастомизация</div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Виджет обзоров продуктов</div>
              <div class="icon">
                <img src="@/assets/img/question.svg" alt="">
              </div>
            </div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Виджет обзоров продуктов</div>
              <div class="icon">
                <img src="@/assets/img/question.svg" alt="">
              </div>
            </div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Виджет обзоров продуктов</div>
              <div class="icon">
                <img src="@/assets/img/question.svg" alt="">
              </div>
            </div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Виджет обзоров продуктов</div>
              <div class="icon">
                <img src="@/assets/img/question.svg" alt="">
              </div>
            </div>
          </div>
          <div class="compare-row">
            <div class="compare-row__col">
              <div class="text">Виджет обзоров продуктов</div>
              <div class="icon">
                <img src="@/assets/img/question.svg" alt="">
              </div>
            </div>
          </div>
        </div>
        <div class="compare-table__col">
          <div class="compare-heading">
            <div class="compare-heading__tariffs">
              <div class="compare-tariffs">
                <div class="compare-tariffs__item">
                  <div class="status">Рекомендуемый</div>
                  <div class="tariff">
                    <div class="wrapper">
                      <div class="tariff__title">
                        <h4>Базовый</h4>
                      </div>
                      <div class="tariff__price">
                        <div class="price">2 500</div>
                        <div class="vallet">грн/мес</div>
                      </div>
                      <ul class="tariff__list" role="list">
                        <li class="tariff__list-item" role="listitem">
                          Поддержка 24/7
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          до 10 пользователей
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          10 Гб облачного хранилища
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          Статистика
                        </li>
                      </ul>
                      <div class="tariff__links">
                        <a class="button button_link" href="#">Подробнее</a>
                      </div>
                      <div class="tariff__btns">
                        <a class="button button_orange" href="#">Перейти</a>
                      </div>
                    </div>
                    <div class="tariff__buttons">
                      <a class="button button_orange" href="#">Перейти</a>
                    </div>
                  </div>
                </div>
                <div class="compare-tariffs__item active">
                  <div class="status">Ваш тарифный план</div>
                  <div class="tariff">
                    <div class="wrapper">
                      <div class="tariff__title">
                        <h4>Стандартный</h4>
                      </div>
                      <div class="tariff__price">
                        <div class="price">3 500</div>
                        <div class="vallet">грн/мес</div>
                      </div>
                      <ul class="tariff__list" role="list">
                        <li class="tariff__list-item" role="listitem">
                          Поддержка 24/7
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          до 30 пользователей
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          10 Гб облачного хранилища
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          Статистика
                        </li>
                      </ul>
                      <div class="tariff__links">
                        <a class="button button_link" href="#">Подробнее</a>
                      </div>
                      <div class="tariff__btns">
                        <a class="button button_orange" href="#"
                          >Перейти
                        </a>
                      </div>
                    </div>
                    <div class="tariff__buttons">
                      <a class="button button_orange" href="#">Перейти</a>
                    </div>
                  </div>
                </div>
                <div class="compare-tariffs__item">
                  <div class="status">Рекомендуемый</div>
                  <div class="tariff">
                    <div class="wrapper">
                      <div class="tariff__title">
                        <h4>Премиум</h4>
                      </div>
                      <div class="tariff__price">
                        <div class="price">5 500</div>
                        <div class="vallet">грн/мес</div>
                      </div>
                      <ul class="tariff__list" role="list">
                        <li class="tariff__list-item" role="listitem">
                          Поддержка 24/7
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          Неограниченное количество пользователей
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          10 Гб облачного хранилища
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          Статистика
                        </li>
                      </ul>
                      <div class="tariff__links">
                        <a class="button button_link" href="#">Подробнее</a>
                      </div>
                      <div class="tariff__btns">
                        <a class="button button_orange" href="#"
                          >Перейти
                        </a>
                      </div>
                    </div>
                    <div class="tariff__buttons">
                      <a class="button button_orange" href="#">Перейти</a>
                    </div>
                  </div>
                </div>
                <div class="compare-tariffs__item">
                  <div class="status">Рекомендуемый</div>
                  <div class="tariff">
                    <div class="wrapper">
                      <div class="tariff__title">
                        <h4>Премиум+</h4>
                      </div>
                      <div class="tariff__price">
                        <div class="price">7 500</div>
                        <div class="vallet">грн/мес</div>
                      </div>
                      <ul class="tariff__list" role="list">
                        <li class="tariff__list-item" role="listitem">
                          Поддержка 24/7
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          Неограниченное количество пользователей
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          10 Гб облачного хранилища
                        </li>
                        <li class="tariff__list-item" role="listitem">
                          Статистика
                        </li>
                      </ul>
                      <div class="tariff__links">
                        <a class="button button_link" href="#">Подробнее</a>
                      </div>
                      <div class="tariff__btns">
                        <a class="button button_orange" href="#">Перейти</a>
                      </div>
                    </div>
                    <div class="tariff__buttons">
                      <a class="button button_orange" href="#">Перейти</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="compare-table__wrapper">
            <div class="wrapper">
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="text">До 20</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">До 50</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">До 100</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">Неограничено</div>
                </div>
              </div>
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="text">6 Гб</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">10 Гб</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">Неограничено</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">Неограничено</div>
                </div>
              </div>
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="text">До 30</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">До 100</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">Неограничено</div>
                </div>
                <div class="compare-row__col">
                  <div class="text">Неограничено</div>
                </div>
              </div>
              <div class="compare-heading"></div>
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
              </div>
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
              </div>
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
              </div>
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
              </div>
              <div class="compare-row">
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/minus.svg" />
                  </div>
                </div>
                <div class="compare-row__col">
                  <div class="icon">
                    <img class="pic" src="@/assets/img/checked.svg" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="userSettings userSettings--payment" style="display: none">
      <div class="label-title mb-0 mt-7">
        <div class="label-title-text">Выберите варианты оплаты:</div>
      </div>
      <div class="welcomeWinPaymentMethods">
        <div class="welcomeWinPaymentMethods__itemWrap">
          <input type="radio" name="paymentMethods" id="paymentMethods_card_pay">
          <label class="welcomeWinPaymentMethods__item" for="paymentMethods_card_pay">
            <img src="@/assets/img/card_pay-blue.svg">
            <span>Оплата картой</span>
          </label>
        </div>
        <div class="welcomeWinPaymentMethods__itemWrap">
          <input type="radio" name="paymentMethods" id="paymentMethods_apple_pay">
          <label class="welcomeWinPaymentMethods__item" for="paymentMethods_apple_pay">
            <img src="@/assets/img/apple_pay-blue.svg">
          </label>
        </div>
        <div class="welcomeWinPaymentMethods__itemWrap">
          <input type="radio" name="paymentMethods" id="paymentMethods_google_pay">
          <label class="welcomeWinPaymentMethods__item" for="paymentMethods_google_pay">
            <img src="@/assets/img/google_pay-blue.svg">
          </label>
        </div>
        <div class="welcomeWinPaymentMethods__itemWrap">
          <input type="radio" name="paymentMethods" id="paymentMethods_liqpay">
          <label class="welcomeWinPaymentMethods__item" for="paymentMethods_liqpay">
            <img src="@/assets/img/liqpay-blue.svg">
          </label>
        </div>
        <div class="welcomeWinPaymentMethods__itemWrap">
          <input type="radio" name="paymentMethods" id="paymentMethods_privat">
          <label class="welcomeWinPaymentMethods__item" for="paymentMethods_privat">
            <img src="@/assets/img/privat-blue.svg">
          </label>
        </div>
			</div>
      <div class="welcomeWinPrint">
        <img src="@/assets/img/print-blue.svg" alt="">
        <a href="#" class="welcomeWinPrint__toPrint">Распечатать счет на оплату</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Tariffs",
};
</script>

<style>
.tariff {
    position: relative;
    text-align: center;
    cursor: pointer
}

.tariff::before {
    content: "";
    position: absolute;
    bottom: -20px;
    left: 0;
    right: 0;
    width: 100%;
    height: 35px
}

.tariff:hover .wrapper {
    /* transform: translateY(-15px) */
}

.tariff:hover .tariff__buttons {
    top: calc(100% + 20px);
    opacity: 1
}

.tariff .wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: .625em;
    padding: 2.1875em .9375em;
    box-shadow: 0 0 20px rgba(0,0,0,.1);
    height: 100%;
    min-height: 100%;
    z-index: 1;
    transition: .75s ease-in-out
}

.tariff__title {
    position: relative;
    padding-bottom: 1.6875em;
    margin-bottom: 1.6875em
}

.tariff__title::before {
    content: "";
    background: #ff9f2f;
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    width: 5.6875em;
    height: 3px
}

.tariff__title h4 {
    font-size: 1.375em;
    color: #2564d3
}

.tariff__price {
    margin-bottom: 2.1875em
}

.tariff__price .price {
    font: 28px HelveticaNeueCyrBold,sans-serif;
    color: #75c5ff;
    font-weight: 700;
}

.tariff__price .vallet {
    font-family: HelveticaNeueCyrMedium,sans-serif;
    color: #2564d3;
    font-weight: 700;
}

.tariff__list {
    list-style: none;
    padding: 0;
    margin: 0 0 1.875em 0
}

.tariff__list-item {
    font-size: .9375em;
    line-height: 1.125em;
    color: #4f4f4f
}

.tariff__list-item:not(:last-child) {
    margin-bottom: .9375em
}

.tariff__list-item--disabled {
    color: rgba(79,79,79,.3)
}

.tariff__links {
    margin-top: auto
}

.tariff__btns .button {
    display: block;
    padding: .6875em 0
}

.tariff__buttons {
    position: absolute;
    opacity: 0;
    top: 0;
    width: 100%;
    transition: .75s ease-in-out
}

.tariff__buttons .button {
    display: block;
    line-height: 1
}
.compare {
    margin-top: 26.5625rem;
    margin-bottom: 12.5rem
}

.compare.hidden {
    display: none
}

.compare .container {
    max-width: 1570px;
    margin: 0 auto;
    padding-left: 15px;
    padding-right: 15px
}

.compare .container .row {
    display: flex;
    flex-wrap: wrap;
    margin-left: -15px;
    margin-right: -15px
}

.compare .container .row .column {
    box-sizing: border-box;
    word-wrap: break-word;
    margin-left: 15px;
    margin-right: 15px;
    width: calc(100% / 12 * 12 - 30px)
}

.compare-table {
    position: relative;
    display: flex;
    /* border-bottom: 2px solid #cfebff; */
    margin-top: 340px;
}

.compare-table::before {
    content: "";
    position: absolute;
    top: -2.875rem;
    left: 0;
    right: 0;
    background: #cfebff;
    border-radius: 3.25rem 3.25rem 0 0;
    width: 100%;
    height: 2.875rem
}

.compare-table__wrapper {
    scroll-behavior: smooth;
    -ms-overflow-style: none;
    scrollbar-width: none
}

.compare-table__wrapper::-webkit-scrollbar {
    display: none;
    background: 0 0;
    width: 0
}

.compare-table__col {
    position: relative
}

.compare-table__col:first-child {
    flex: 0 1 15%
}

.compare-table__col:first-child .compare-heading {
    padding-left: 20px
}

.compare-table__col:first-child .compare-row {
    padding-left: 20px
}

.compare-table__col:first-child .compare-row .text {
    margin-left: 0
}

.compare-table__col:first-child .compare-row .icon {
    margin-left: 5px
}

.compare-table__col:first-child .compare-row__col .text {
    color: #75c5ff
}

.compare-table__col:last-child {
    flex: 0 1 85%
}

.compare-table__col:last-child::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 100px
}

.compare-table__col:last-child .compare-heading {
    padding-right: 80px
}

.compare-table__col:last-child .compare-row {
    padding-right: 80px
}

.compare-table__col:last-child .compare-row__col {
    text-align: center
}

.compare-table__col:last-child .compare-row__col .text {
    color: #2564d3
}

.compare-table__col .compare-heading {
    background: #cfebff;
    display: flex;
    align-items: center;
    height: 4.375rem
}

.compare-table__col .compare-heading__title {
    font: 1.125rem/1 HelveticaNeueCyrMedium,sans-serif;
    color: #2564d3;
    white-space: nowrap;
}

.compare-table__col .compare-heading__tariffs {
    position: absolute;
    top: -16.25rem;
    width: calc(100% - 80px)
}

.compare-table__col .compare-heading__tariffs .compare-tariffs {
    width: 100%;
    display: flex;
    justify-content: space-between
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item {
    position: relative;
    flex: 0 1 calc(25% - 15px)
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item.active::before {
    display: block
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item.active .status {
    display: block
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item::before {
    content: "";
    display: none;
    background: #5893d4;
    position: absolute;
    top: -55px;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    border-radius: .625rem .625rem 0 0;
    width: calc(100% + 39px);
    height: 100%
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item .status {
    font: 1rem HelveticaNeueCyrBlack,sans-serif;
    font-weight: 700;
    display: block;
    position: absolute;
    top: -55px;
    text-align: center;
    padding: 1.25rem 0;
    width: 100%;
    color: #fff
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item .tariff {
    position: relative
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item .tariff .wrapper {
    padding-bottom: 1.625rem
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item .tariff__price {
    margin-bottom: .9375rem
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item .tariff__list {
    display: none
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item .tariff__links {
    display: none
}

.compare-table__col .compare-heading__tariffs .compare-tariffs__item .tariff__buttons {
    display: none
}

.compare-table__col .compare-row {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #cfebff;
    height: 4.375rem
}

.compare-table__col .compare-row__col {
    display: flex;
    align-items: center;
    flex: 1
}

.compare-table__col .compare-row__col .text {
    line-height: 1.375rem;
    margin: auto
}

.compare-table__col .compare-row__col .icon {
    line-height: 1;
    margin: auto;
    cursor: pointer
}

.compare-table__col .compare-row__col .icon .pic {
    fill: transparent;
    stroke: #75c5ff;
    margin: auto;
    max-width: 1.25rem;
    max-height: 1.25rem;
}

.compare-table .compare-header {
    display: flex;
    background: #cfebff;
    border-radius: 6.25rem 6.25rem 0 0;
    padding: 6.875rem 7.1875rem 1.4375rem 7.1875rem
}

.pricing {
    margin-bottom: 11.25rem
}

.pricing .container {
    max-width: 1570px;
    margin: 0 auto;
    padding-left: 15px;
    padding-right: 15px
}

.pricing .container .row {
    display: flex;
    flex-wrap: wrap;
    margin-left: -15px;
    margin-right: -15px
}

.pricing .container .row .column {
    box-sizing: border-box;
    word-wrap: break-word;
    margin-left: 15px;
    margin-right: 15px
}

.pricing__buttons {
    margin-bottom: 7.3125rem
}

.pricing__tariffs {
    position: relative;
    padding-top: 3.125rem
}

.pricing__tariffs::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: #2564d3;
    width: 100%;
    height: 12.375rem
}

.pricing__tariffs .container .row .column {
    position: relative
}

.pricing__tariffs .container .row .column .tns-ovh {
    min-height: 32.5rem
}

.pricing__tariffs .container .row .column .tns-ovh .tns-inner {
    padding-top: 1.25rem
}

.pricing__tariffs .container .row .column:first-child {
    width: calc(100% / 12 * 4 - 30px)
}

.pricing__tariffs .container .row .column:last-child {
    width: calc(100% / 12 * 8 - 30px)
}

.pricing__tariffs-slider {
    display: flex;
    justify-content: space-between
}

.pricing__tariffs-slide {
    flex: 0 1 calc(25% - 10px)
}

.pricing__tariffs-slide .tariff {
    flex: 0 1 calc(25% - 10px);
    height: 100%
}

.pricing__tariffs-slide .tariff__buttons .button {
    padding: .8125em 3.4375em
}

.pricing__tariffs-content {
    position: relative
}

.pricing__tariffs-content .caption {
    margin-bottom: .9375rem;
    color: #fff
}

.pricing__tariffs-content .caption h1 {
    font: 2.875rem HelveticaNeueCyrBold,sans-serif
}

.pricing__tariffs-content .subcaption {
    margin-bottom: 4.1875rem;
    color: #fff
}

.pricing__tariffs-content .subcaption h2 {
    font: 2.25rem HelveticaNeueCyrRoman,sans-serif
}

.pricing__tariffs-content .description {
    font-family: HelveticaNeueCyrLight,sans-serif;
    line-height: 1.375rem;
    color: #4f4f4f
}

.pricing__tariffs-content .description p {
    margin: 0
}

.button_orange {
    background: #ff9f2f;
    border-color: #ff9f2f;
    color: #fff!important;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 700;
}

.userSettings__tariffType {
  display: inline-block;
  font-size: 13px;
  line-height: 13px;
  color: #317CCE;
  padding: 8px 15px;
  background: #E3F0FF;
  border-radius: 10px;
}
.userSettings__tariff .tariff__price {
  display: inline-flex;
  align-items: flex-end;
}
.userSettings__tariff .tariff__price .price {
  line-height: 28px;
}
.userSettings__tariffDate {
  font-size: 15px;
  color: #7e7e7e;
  line-height: 15px;
  margin-top: 18px;
}
.userSettings__tariffInfo {
  min-height: 97px;
}
.userSettings__tariffStatusItem {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  margin-top: 11px;
}
.userSettings__tariffCount {
  margin-left: 15px;
}
.userSettings__tariffCount span:first-child {
  font-size: 18px;
  font-weight: 550;
  line-height: 18px;
  color: #5893D4;
}
.userSettings__tariffCount span:last-child {
  font-size: 14px;
  font-weight: 400;
  line-height: 14px;
  color: #7e7e7e;
  display: inline-block;
  margin-left: 5px;
}
.userSettings--payment .welcomeWinPaymentMethods {
  padding: 0;
  justify-content: flex-start;
  margin-top: 30px;
}
.userSettings--payment .welcomeWinPaymentMethods__item span {
  color: #9DCCFF;
}
.userSettings--payment .welcomeWinPaymentMethods__itemWrap,
.userSettings--payment .welcomeWinPaymentMethods__itemWrap:nth-child(4n+4) {
  margin-right: 20px;
}
.userSettings--payment .welcomeWinPaymentMethods__item:hover {
  background: #9DCCFF;
}
.userSettings--payment .welcomeWinPaymentMethods__item img {
  transition: none;
}
.userSettings--payment .welcomeWinPaymentMethods__item:hover img {
    filter: brightness(0) invert(1) opacity(0.7);
}
.userSettings--payment .welcomeWinPaymentMethods__item:hover span {
    color: #E6F3FD;
}
.userSettings--payment .welcomeWinPrint {
  text-align: left;
  display: flex;
  justify-content: flex-start;
  align-items: center;
}
.userSettings--payment .welcomeWinPrint__toPrint {
  color: #9DCCFF!important;
  padding-left: 0;
}
.userSettings--payment .welcomeWinPrint img {
  margin-right: 10px;
}
.userSettings--payment .welcomeWinPrint__toPrint::before {
  display: none;
}





</style>