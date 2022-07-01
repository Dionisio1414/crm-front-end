<template>
  <div class="wrapper">
    <div class="step-tabs">
      <div class="step-tabs-item" :class="{'step-tabs-item--active': index === currentStep}" v-for="(val, index) in stepTabs" :key="index">
        <button type="button" class="base-btn" @click="goTo(index)">
          {{ val.title }}
        </button>
      </div>
    </div>
    <div class="forms-wrapper">
      <div class="forms-wrapper-item" v-if="currentStep === 0">
        <div class="item-form">
          <v-row>
            <v-col cols="3">
              <div class="item-name">
                <div class="label-title">Дата приема</div>
                <input type="text" v-model="computedDateReceipt">
                <button type="button" class="item-button" @click="isOpenCalendarReceipt = !isOpenCalendarReceipt">
                    <span class="icon">
                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.1251 2H13.5001V3H15.0001V14H1.00013V3H2.50013V2H0.875129C0.758247 2.00195 0.642895 2.02691 0.535662 2.07345C0.428428 2.11999 0.331414 2.1872 0.250159 2.27125C0.168905 2.35529 0.105002 2.45451 0.0621014 2.56325C0.0192006 2.67199 -0.00185798 2.78812 0.000128526 2.905V14.095C-0.00185798 14.2119 0.0192006 14.328 0.0621014 14.4367C0.105002 14.5455 0.168905 14.6447 0.250159 14.7288C0.331414 14.8128 0.428428 14.88 0.535662 14.9265C0.642895 14.9731 0.758247 14.998 0.875129 15H15.1251C15.242 14.998 15.3574 14.9731 15.4646 14.9265C15.5718 14.88 15.6688 14.8128 15.7501 14.7288C15.8314 14.6447 15.8953 14.5455 15.9382 14.4367C15.9811 14.328 16.0021 14.2119 16.0001 14.095V2.905C16.0021 2.78812 15.9811 2.67199 15.9382 2.56325C15.8953 2.45451 15.8314 2.35529 15.7501 2.27125C15.6688 2.1872 15.5718 2.11999 15.4646 2.07345C15.3574 2.02691 15.242 2.00195 15.1251 2Z" fill="#9DCCFF"/>
                            <path d="M3 6H4V7H3V6Z" fill="#9DCCFF"/>
                            <path d="M6 6H7V7H6V6Z" fill="#9DCCFF"/>
                            <path d="M9 6H10V7H9V6Z" fill="#9DCCFF"/>
                            <path d="M12 6H13V7H12V6Z" fill="#9DCCFF"/>
                            <path d="M3 8.5H4V9.5H3V8.5Z" fill="#9DCCFF"/>
                            <path d="M6 8.5H7V9.5H6V8.5Z" fill="#9DCCFF"/>
                            <path d="M9 8.5H10V9.5H9V8.5Z" fill="#9DCCFF"/>
                            <path d="M12 8.5H13V9.5H12V8.5Z" fill="#9DCCFF"/>
                            <path d="M3 11H4V12H3V11Z" fill="#9DCCFF"/>
                            <path d="M6 11H7V12H6V11Z" fill="#9DCCFF"/>
                            <path d="M9 11H10V12H9V11Z" fill="#9DCCFF"/>
                            <path d="M12 11H13V12H12V11Z" fill="#9DCCFF"/>
                            <path d="M4 4C4.13261 4 4.25979 3.94732 4.35355 3.85355C4.44732 3.75979 4.5 3.63261 4.5 3.5V0.5C4.5 0.367392 4.44732 0.240215 4.35355 0.146447C4.25979 0.0526784 4.13261 0 4 0C3.86739 0 3.74021 0.0526784 3.64645 0.146447C3.55268 0.240215 3.5 0.367392 3.5 0.5V3.5C3.5 3.63261 3.55268 3.75979 3.64645 3.85355C3.74021 3.94732 3.86739 4 4 4Z" fill="#9DCCFF"/>
                            <path d="M12 4C12.1326 4 12.2598 3.94732 12.3536 3.85355C12.4473 3.75979 12.5 3.63261 12.5 3.5V0.5C12.5 0.367392 12.4473 0.240215 12.3536 0.146447C12.2598 0.0526784 12.1326 0 12 0C11.8674 0 11.7402 0.0526784 11.6464 0.146447C11.5527 0.240215 11.5 0.367392 11.5 0.5V3.5C11.5 3.63261 11.5527 3.75979 11.6464 3.85355C11.7402 3.94732 11.8674 4 12 4Z" fill="#9DCCFF"/>
                            <path d="M5.5 2H10.5V3H5.5V2Z" fill="#9DCCFF"/>
                        </svg>
                    </span>
                </button>
                <v-date-picker
                    class="base-calendar"
                    v-if="isOpenCalendarReceipt"
                    v-model="main.date_receipt"
                    width="287"
                    locale="ru-Ru"
                    @input="onChangeDate('isOpenCalendarReceipt')"
                ></v-date-picker>
              </div>
            </v-col>
            <v-col cols="3">
              <div class="item-name">
                <div class="label-title">дата увольнения</div>
                <input type="text" v-model="computedDateDismissal">
                <button type="button" class="item-button" @click="isOpenCalendarDismissal = !isOpenCalendarDismissal">
                    <span class="icon">
                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.1251 2H13.5001V3H15.0001V14H1.00013V3H2.50013V2H0.875129C0.758247 2.00195 0.642895 2.02691 0.535662 2.07345C0.428428 2.11999 0.331414 2.1872 0.250159 2.27125C0.168905 2.35529 0.105002 2.45451 0.0621014 2.56325C0.0192006 2.67199 -0.00185798 2.78812 0.000128526 2.905V14.095C-0.00185798 14.2119 0.0192006 14.328 0.0621014 14.4367C0.105002 14.5455 0.168905 14.6447 0.250159 14.7288C0.331414 14.8128 0.428428 14.88 0.535662 14.9265C0.642895 14.9731 0.758247 14.998 0.875129 15H15.1251C15.242 14.998 15.3574 14.9731 15.4646 14.9265C15.5718 14.88 15.6688 14.8128 15.7501 14.7288C15.8314 14.6447 15.8953 14.5455 15.9382 14.4367C15.9811 14.328 16.0021 14.2119 16.0001 14.095V2.905C16.0021 2.78812 15.9811 2.67199 15.9382 2.56325C15.8953 2.45451 15.8314 2.35529 15.7501 2.27125C15.6688 2.1872 15.5718 2.11999 15.4646 2.07345C15.3574 2.02691 15.242 2.00195 15.1251 2Z" fill="#9DCCFF"/>
                            <path d="M3 6H4V7H3V6Z" fill="#9DCCFF"/>
                            <path d="M6 6H7V7H6V6Z" fill="#9DCCFF"/>
                            <path d="M9 6H10V7H9V6Z" fill="#9DCCFF"/>
                            <path d="M12 6H13V7H12V6Z" fill="#9DCCFF"/>
                            <path d="M3 8.5H4V9.5H3V8.5Z" fill="#9DCCFF"/>
                            <path d="M6 8.5H7V9.5H6V8.5Z" fill="#9DCCFF"/>
                            <path d="M9 8.5H10V9.5H9V8.5Z" fill="#9DCCFF"/>
                            <path d="M12 8.5H13V9.5H12V8.5Z" fill="#9DCCFF"/>
                            <path d="M3 11H4V12H3V11Z" fill="#9DCCFF"/>
                            <path d="M6 11H7V12H6V11Z" fill="#9DCCFF"/>
                            <path d="M9 11H10V12H9V11Z" fill="#9DCCFF"/>
                            <path d="M12 11H13V12H12V11Z" fill="#9DCCFF"/>
                            <path d="M4 4C4.13261 4 4.25979 3.94732 4.35355 3.85355C4.44732 3.75979 4.5 3.63261 4.5 3.5V0.5C4.5 0.367392 4.44732 0.240215 4.35355 0.146447C4.25979 0.0526784 4.13261 0 4 0C3.86739 0 3.74021 0.0526784 3.64645 0.146447C3.55268 0.240215 3.5 0.367392 3.5 0.5V3.5C3.5 3.63261 3.55268 3.75979 3.64645 3.85355C3.74021 3.94732 3.86739 4 4 4Z" fill="#9DCCFF"/>
                            <path d="M12 4C12.1326 4 12.2598 3.94732 12.3536 3.85355C12.4473 3.75979 12.5 3.63261 12.5 3.5V0.5C12.5 0.367392 12.4473 0.240215 12.3536 0.146447C12.2598 0.0526784 12.1326 0 12 0C11.8674 0 11.7402 0.0526784 11.6464 0.146447C11.5527 0.240215 11.5 0.367392 11.5 0.5V3.5C11.5 3.63261 11.5527 3.75979 11.6464 3.85355C11.7402 3.94732 11.8674 4 12 4Z" fill="#9DCCFF"/>
                            <path d="M5.5 2H10.5V3H5.5V2Z" fill="#9DCCFF"/>
                        </svg>
                    </span>
                </button>
                <v-date-picker
                    class="base-calendar"
                    v-if="isOpenCalendarDismissal"
                    v-model="main.date_dismissal"
                    width="287"
                    locale="ru-Ru"
                    @input="onChangeDate('isOpenCalendarDismissal')"
                ></v-date-picker>
              </div>
              <div class="checkbox">
                <label class="checkbox-label">
                    <input type="checkbox" v-model="main.is_not_active">
                    <div class="checkbox-text">
                        <div class="text">Сотрудник неактивный</div>
                    </div>
                </label>
              </div>
            </v-col>
            <v-col cols="3">
              <div class="item-name">
                <div class="label-title">Оклад</div>
                <input type="text" v-model="main.salary" placeholder="Введите данные">
              </div>
            </v-col>
            <v-col cols="3">
              <div class="item-name">
                <div class="label-title">Код инн *</div>
                <input type="text" v-model="main.inn" placeholder="Введите данные" :class="{'error-on-input': isValid == false && $v.main.$invalid == true ? true : false}">
              </div>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12">
              <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
            </v-col>
          </v-row>
        </div>
      </div>
      <div class="forms-wrapper-item" v-if="currentStep === 1">
        <div class="item-form">
          <v-row>
            <v-col cols="6">
              <v-row>
                <v-col cols="4">
                  <div class="item-name item-name_phone">
                    <div class="label-title">Мобильный телефона</div>
                    <div class="select-wrap">
                        <v-select class="select-switcher"
                            :items="computedData.codes"
                            label="+__"
                            item-text="name"
                            item-value="name"
                            solo
                            dense
                            hide-details
                            menu-props="codes"
                            @change="changeCodePhone($event, 'mobile_phone')"
                        />
                    </div>
                    <masked-input mask="(111) 111 11 11" placeholder="(___) ___ - __ - __" v-model="contact.mobile_phone" />
                </div>
                </v-col>
                <v-col cols="8">
                  <div class="item-name">
                      <div class="label-title">Email</div>
                      <input type="email" placeholder="Введите почту" v-model="contact.email">
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                    <div class="item-name item-name_phone">
                      <div class="label-title">Рабочий телефон</div>
                      <div class="select-wrap">
                          <v-select class="select-switcher"
                              :items="computedData.codes"
                              label="+__"
                              item-text="name"
                              item-value="name"
                              solo
                              dense
                              hide-details
                              menu-props="codes"
                              @change="changeCodePhone($event, 'work_phone')"
                          />
                      </div>
                      <masked-input v-model="contact.work_phone" mask="(111) 111 11 11" placeholder="(___) ___ - __ - __" />
                  </div>
                </v-col>
                <v-col cols="8">
                  <div class="item-name">
                      <div class="label-title">Skype</div>
                      <input type="text" placeholder="Введите свой скайп" v-model="contact.skype">
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                    <div class="item-name item-name_phone">
                      <div class="label-title">Внутренний телефон</div>
                      <div class="select-wrap">
                          <v-select class="select-switcher"
                              :items="computedData.codes"
                              label="+__"
                              item-text="name"
                              item-value="name"
                              solo
                              dense
                              hide-details
                              menu-props="codes"
                              @change="changeCodePhone($event, 'internal_phone')"
                          />
                      </div>
                      <masked-input v-model="contact.internal_phone" mask="(111) 111 11 11" placeholder="(___) ___ - __ - __" />
                  </div>
                </v-col>
                <v-col cols="8">
                  <div class="item-name">
                      <div class="label-title">Zoom</div>
                      <input type="text" placeholder="Введите свой zoom" v-model="contact.zoom">
                  </div>
                </v-col>
              </v-row>
            </v-col>
            <v-col cols="6">
              <v-row>
                <v-col cols="4">
                    <div class="item-name">
                        <div class="select-wrap">
                            <div class="label-title">Адрес проживания</div>
                            <v-select class="select-switcher"
                                :items="countriesList"
                                item-text="title"
                                item-value="id"
                                solo
                                dense
                                hide-details
                                @change="onChangeMainSelect($event, 'contact', 'residence_country_id')"
                            />
                        </div>
                    </div>
                </v-col>
                <v-col cols="4">
                    <div class="item-name">
                        <div class="select-wrap">
                            <v-select class="select-switcher"
                                :items="citiesList.cities"
                                item-text="title"
                                item-value="id"
                                solo
                                dense
                                hide-details
                                menu-props="regions"
                                @change="onChangeMainSelect($event, 'contact', 'residence_region_id')"
                            />
                        </div>
                    </div>
                </v-col>
                <v-col cols="4">
                  <div class="item-name">
                    <div class="select-wrap">
                        <v-select class="select-switcher"
                            :items="citiesList.regiones"
                            item-text="title"
                            item-value="id"
                            solo
                            dense
                            hide-details
                            menu-props="cities"
                            @change="onChangeMainSelect($event, 'contact', 'residence_city_id')"
                        />
                    </div>
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="item-name">
                      <input type="text" placeholder="Улица" v-model="contact.residence_street">
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="item-name">
                      <input type="text" placeholder="Номер дома/корпуса" v-model="contact.residence_number_house">
                  </div>
                </v-col>
                <v-col cols="12">
                  <div class="checkbox">
                      <label class="checkbox-label">
                          <input type="checkbox" v-model="contact.is_equal_residence_registration">
                          <div class="checkbox-text">
                              <div class="text">Адрес прописки соответствует адресу проживания</div>
                          </div>
                      </label>
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4">
                    <div class="item-name">
                        <div class="select-wrap">
                            <div class="label-title">Адрес по прописке</div>
                            <v-select class="select-switcher"
                                :items="countriesList"
                                item-text="title"
                                item-value="id"
                                solo
                                dense
                                hide-details
                                @change="onChangeMainSelect($event, 'contact', 'registration_country_id')"
                            />
                        </div>
                    </div>
                </v-col>
                <v-col cols="4">
                    <div class="item-name">
                        <div class="select-wrap">
                            <v-select class="select-switcher"
                                :items="citiesList.cities"
                                item-text="title"
                                item-value="id"
                                solo
                                dense
                                hide-details
                                menu-props="regions"
                                @change="onChangeMainSelect($event, 'contact', 'registration_region_id')"
                            />
                        </div>
                    </div>
                </v-col>
                <v-col cols="4">
                  <div class="item-name">
                    <div class="select-wrap">
                        <v-select class="select-switcher"
                            :items="citiesList.regiones"
                            item-text="title"
                            item-value="id"
                            solo
                            dense
                            hide-details
                            menu-props="cities"
                            @change="onChangeMainSelect($event, 'contact', 'registration_city_id')"
                        />
                    </div>
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="item-name">
                      <input type="text" placeholder="Улица" v-model="contact.registration_street">
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="item-name">
                      <input type="text" placeholder="Номер дома/корпуса" v-model="contact.registration_number_house">
                  </div>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </div>
      </div>
      <div class="forms-wrapper-item" v-if="currentStep === 2">
        <div class="item-form">
          <v-row>
            <v-col cols="6">
              <v-row>
                <v-col cols="12">
                    <div class="item-name item-name_actions">
                      <div class="label-title">Документ</div>
                      <div class="select-wrap">
                          <v-select class="select-switcher"
                              :items="documentsList"
                              item-text="title"
                              item-value="directory_id"
                              solo
                              dense
                              hide-details
                              @change="onChangeMainSelect($event, 'docs', 'directory_document_id')"
                          />
                      </div>
                      <button v-if="mode == 'add'" type="button" class="add-value" @click="addValue" :disabled="!$v.docs.serial.required || !$v.docs.serial_number.required || !$v.docs.passport_issued.required || !$v.docs.passport_issued_date.required || !$v.docs.validity_date">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z" stroke="#9DCCFF"></path><path data-v-24021d1c="" d="M16.1027 13.8732L20 13.8732L20 16.0765L16.1027 16.0765L16.1027 20L13.8973 20L13.8973 16.0765L10 16.0765L10 13.8732L13.8973 13.8732L13.8973 10L16.1027 10L16.1027 13.8732Z" fill="#9DCCFF"></path></svg>
                      </button>
                      <button type="button" v-if="mode == 'edit'" @click="saveValue">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z" stroke="#9DCCFF"/>
                          <path d="M19.0424 10L12.928 16.1041L10.9449 14.1244L9 16.066L10.983 18.0457L12.9407 20L14.8856 18.0584L21 11.9543L19.0424 10Z" fill="#9DCCFF"/>
                        </svg>
                      </button>
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="item-name">
                    <input type="text" placeholder="МТ" v-model="docs.serial">
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="item-name">
                    <input type="text" placeholder="Код" v-model="docs.serial_number">
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="item-name">
                    <input type="text" v-model="computedDateIssuedDate">
                    <button type="button" class="item-button" @click="isOpenCalendarIssued = !isOpenCalendarIssued">
                        <span class="icon">
                            <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1251 2H13.5001V3H15.0001V14H1.00013V3H2.50013V2H0.875129C0.758247 2.00195 0.642895 2.02691 0.535662 2.07345C0.428428 2.11999 0.331414 2.1872 0.250159 2.27125C0.168905 2.35529 0.105002 2.45451 0.0621014 2.56325C0.0192006 2.67199 -0.00185798 2.78812 0.000128526 2.905V14.095C-0.00185798 14.2119 0.0192006 14.328 0.0621014 14.4367C0.105002 14.5455 0.168905 14.6447 0.250159 14.7288C0.331414 14.8128 0.428428 14.88 0.535662 14.9265C0.642895 14.9731 0.758247 14.998 0.875129 15H15.1251C15.242 14.998 15.3574 14.9731 15.4646 14.9265C15.5718 14.88 15.6688 14.8128 15.7501 14.7288C15.8314 14.6447 15.8953 14.5455 15.9382 14.4367C15.9811 14.328 16.0021 14.2119 16.0001 14.095V2.905C16.0021 2.78812 15.9811 2.67199 15.9382 2.56325C15.8953 2.45451 15.8314 2.35529 15.7501 2.27125C15.6688 2.1872 15.5718 2.11999 15.4646 2.07345C15.3574 2.02691 15.242 2.00195 15.1251 2Z" fill="#9DCCFF"/>
                                <path d="M3 6H4V7H3V6Z" fill="#9DCCFF"/>
                                <path d="M6 6H7V7H6V6Z" fill="#9DCCFF"/>
                                <path d="M9 6H10V7H9V6Z" fill="#9DCCFF"/>
                                <path d="M12 6H13V7H12V6Z" fill="#9DCCFF"/>
                                <path d="M3 8.5H4V9.5H3V8.5Z" fill="#9DCCFF"/>
                                <path d="M6 8.5H7V9.5H6V8.5Z" fill="#9DCCFF"/>
                                <path d="M9 8.5H10V9.5H9V8.5Z" fill="#9DCCFF"/>
                                <path d="M12 8.5H13V9.5H12V8.5Z" fill="#9DCCFF"/>
                                <path d="M3 11H4V12H3V11Z" fill="#9DCCFF"/>
                                <path d="M6 11H7V12H6V11Z" fill="#9DCCFF"/>
                                <path d="M9 11H10V12H9V11Z" fill="#9DCCFF"/>
                                <path d="M12 11H13V12H12V11Z" fill="#9DCCFF"/>
                                <path d="M4 4C4.13261 4 4.25979 3.94732 4.35355 3.85355C4.44732 3.75979 4.5 3.63261 4.5 3.5V0.5C4.5 0.367392 4.44732 0.240215 4.35355 0.146447C4.25979 0.0526784 4.13261 0 4 0C3.86739 0 3.74021 0.0526784 3.64645 0.146447C3.55268 0.240215 3.5 0.367392 3.5 0.5V3.5C3.5 3.63261 3.55268 3.75979 3.64645 3.85355C3.74021 3.94732 3.86739 4 4 4Z" fill="#9DCCFF"/>
                                <path d="M12 4C12.1326 4 12.2598 3.94732 12.3536 3.85355C12.4473 3.75979 12.5 3.63261 12.5 3.5V0.5C12.5 0.367392 12.4473 0.240215 12.3536 0.146447C12.2598 0.0526784 12.1326 0 12 0C11.8674 0 11.7402 0.0526784 11.6464 0.146447C11.5527 0.240215 11.5 0.367392 11.5 0.5V3.5C11.5 3.63261 11.5527 3.75979 11.6464 3.85355C11.7402 3.94732 11.8674 4 12 4Z" fill="#9DCCFF"/>
                                <path d="M5.5 2H10.5V3H5.5V2Z" fill="#9DCCFF"/>
                            </svg>
                        </span>
                    </button>
                    <v-date-picker
                        class="base-calendar"
                        v-if="isOpenCalendarIssued"
                        v-model="docs.passport_issued_date"
                        width="287"
                        locale="ru-Ru"
                        @input="onChangeDate('isOpenCalendarIssued')"
                    ></v-date-picker>
                  </div>
                </v-col>
                <v-col cols="6">
                  <div class="item-name">
                    <input type="text" v-model="computedDateValidity">
                    <button type="button" class="item-button" @click="isOpenCalendarValidity = !isOpenCalendarValidity">
                        <span class="icon">
                            <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1251 2H13.5001V3H15.0001V14H1.00013V3H2.50013V2H0.875129C0.758247 2.00195 0.642895 2.02691 0.535662 2.07345C0.428428 2.11999 0.331414 2.1872 0.250159 2.27125C0.168905 2.35529 0.105002 2.45451 0.0621014 2.56325C0.0192006 2.67199 -0.00185798 2.78812 0.000128526 2.905V14.095C-0.00185798 14.2119 0.0192006 14.328 0.0621014 14.4367C0.105002 14.5455 0.168905 14.6447 0.250159 14.7288C0.331414 14.8128 0.428428 14.88 0.535662 14.9265C0.642895 14.9731 0.758247 14.998 0.875129 15H15.1251C15.242 14.998 15.3574 14.9731 15.4646 14.9265C15.5718 14.88 15.6688 14.8128 15.7501 14.7288C15.8314 14.6447 15.8953 14.5455 15.9382 14.4367C15.9811 14.328 16.0021 14.2119 16.0001 14.095V2.905C16.0021 2.78812 15.9811 2.67199 15.9382 2.56325C15.8953 2.45451 15.8314 2.35529 15.7501 2.27125C15.6688 2.1872 15.5718 2.11999 15.4646 2.07345C15.3574 2.02691 15.242 2.00195 15.1251 2Z" fill="#9DCCFF"/>
                                <path d="M3 6H4V7H3V6Z" fill="#9DCCFF"/>
                                <path d="M6 6H7V7H6V6Z" fill="#9DCCFF"/>
                                <path d="M9 6H10V7H9V6Z" fill="#9DCCFF"/>
                                <path d="M12 6H13V7H12V6Z" fill="#9DCCFF"/>
                                <path d="M3 8.5H4V9.5H3V8.5Z" fill="#9DCCFF"/>
                                <path d="M6 8.5H7V9.5H6V8.5Z" fill="#9DCCFF"/>
                                <path d="M9 8.5H10V9.5H9V8.5Z" fill="#9DCCFF"/>
                                <path d="M12 8.5H13V9.5H12V8.5Z" fill="#9DCCFF"/>
                                <path d="M3 11H4V12H3V11Z" fill="#9DCCFF"/>
                                <path d="M6 11H7V12H6V11Z" fill="#9DCCFF"/>
                                <path d="M9 11H10V12H9V11Z" fill="#9DCCFF"/>
                                <path d="M12 11H13V12H12V11Z" fill="#9DCCFF"/>
                                <path d="M4 4C4.13261 4 4.25979 3.94732 4.35355 3.85355C4.44732 3.75979 4.5 3.63261 4.5 3.5V0.5C4.5 0.367392 4.44732 0.240215 4.35355 0.146447C4.25979 0.0526784 4.13261 0 4 0C3.86739 0 3.74021 0.0526784 3.64645 0.146447C3.55268 0.240215 3.5 0.367392 3.5 0.5V3.5C3.5 3.63261 3.55268 3.75979 3.64645 3.85355C3.74021 3.94732 3.86739 4 4 4Z" fill="#9DCCFF"/>
                                <path d="M12 4C12.1326 4 12.2598 3.94732 12.3536 3.85355C12.4473 3.75979 12.5 3.63261 12.5 3.5V0.5C12.5 0.367392 12.4473 0.240215 12.3536 0.146447C12.2598 0.0526784 12.1326 0 12 0C11.8674 0 11.7402 0.0526784 11.6464 0.146447C11.5527 0.240215 11.5 0.367392 11.5 0.5V3.5C11.5 3.63261 11.5527 3.75979 11.6464 3.85355C11.7402 3.94732 11.8674 4 12 4Z" fill="#9DCCFF"/>
                                <path d="M5.5 2H10.5V3H5.5V2Z" fill="#9DCCFF"/>
                            </svg>
                        </span>
                    </button>
                    <v-date-picker
                        class="base-calendar"
                        v-if="isOpenCalendarValidity"
                        v-model="docs.validity_date"
                        width="287"
                        locale="ru-Ru"
                        @input="onChangeDate('isOpenCalendarValidity')"
                    ></v-date-picker>
                  </div>
                </v-col>
                <v-col cols="12">
                  <div class="item-name">
                    <input type="text" placeholder="Кем выдан" v-model="docs.passport_issued">
                  </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12">
                  <div class="values-list">
                    <div class="value-item" v-for="(val, index) in documents" :key="index" :class="{'value-item--editable': editableElemIndex === index}">
                      <span>
                        <template v-for="(item, key) in val">
                          <template v-if="key === 'directory_document_id'">
                            <template v-for="doc in localFormData.docs">
                              <template v-if="doc.id === val['directory_document_id']">
                                {{ doc.name }}
                              </template>
                            </template>
                          </template>
                          <template v-else>
                            — {{ item }}
                          </template>
                        </template>
                      </span>
                      <svg @click="editValue(index)" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg" class="edit"><path d="M16 3.77778L13.2222 1L2.11111 12.1111L1 16L4.88889 14.8889L16 3.77778ZM11 3.22222L13.7778 6L11 3.22222ZM2.11111 12.1111L4.88889 14.8889L2.11111 12.1111Z" stroke="#BBDBFE" stroke-width="1.39565" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                      <svg @click="deleteValue(index)" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg" class="close"><path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"></path></svg>
                    </div>
                  </div>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </div>
      </div>
    </div>
    <div class="form-actions">
      <button type="button" class="btn btn--transparent" @click="prev">Назад</button>
      <button type="button" class="btn" @click="next" v-if="currentStep !== stepTabs.length - 1">Далее</button>
      <button type="button" class="btn" v-if="currentStep === stepTabs.length - 1" @click="createEmployee">Добавить</button>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex"
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import MaskedInput from 'vue-masked-input'
export default {
  name: "StepsCreateUsers",
  props: {
    mainData: Object
  },
  mixins: [validationMixin],
  components: {
    MaskedInput
  },
  validations: {
    main: {
      inn: { required },
    },
    docs: {
      serial: { required },
      serial_number: { required },
      passport_issued: { required },
      passport_issued_date: { required },
      validity_date: { required }
    }
  },
  data: () => ({
    currentStep: 0,
    isOpenCalendarReceipt: false,
    isOpenCalendarDismissal: false,
    isOpenCalendarIssued: false,
    isOpenCalendarValidity: false,
    mode: 'add',
    editableElemIndex: null,
    isValid: true,
    stepTabs: [
      { title: 'Основные данные' },
      { title: 'Контактные данные' },
      { title: 'Документы' },
    ],
    docs: {
      directory_document_id: 1,
      serial: null,
      serial_number: null,
      passport_issued: null,
      passport_issued_date: new Date().toISOString().substr(0, 10),
      validity_date: new Date().toISOString().substr(0, 10),
    },
    main: {
      inn: "",
      salary: "",
      date_receipt: null,
      date_dismissal: null,
      is_not_active: false,
    },
    contact: {
      mobile_phone: null,
      work_phone: null,
      internal_phone: null,
      email: null,
      skype: null,
      zoom: null,
      residence_country_id: 1,
      residence_region_id: 1,
      residence_city_id: 1,
      residence_street: "Kulture",
      residence_number_house: 14,
      is_equal_residence_registration: false,
      registration_country_id: 1,
      registration_region_id: 1,
      registration_city_id: 1,
      registration_street: "Saltovka",
      registration_number_house: 123
    },
    documents: [],
    currentDocID: null,
    localFormData: {
      codes: [
        { name: "+380" },
        { name: "+780" },
        { name: "+280" }
      ],
      countries: [
          { name: "Украина", id: 1 },
          { name: "Польша", id: 2 },
          { name: "Германия", id: 3 }
      ],
      regions: [
          { name: "Харьковская область", id: 1 },
          { name: "Запорожская область", id: 2 },
          { name: "Киевская область", id: 3 }
      ],
      cities: [
          { name: "Харьков", id: 1 },
          { name: "Киев", id: 2 },
          { name: "Запорожье", id: 3 }
      ],
      docs: [
        { name: 'Паспорт гражданина Украины', id: 1 },
        { name: 'Военный билет', id: 2 },
        { name: 'Свидетельство о рождении', id: 3 },
        { name: 'Водительское удостоверение', id: 4 },
      ]
    },
  }),
  computed: {
    ...mapGetters([
      'getLists'
    ]),
    computedData() {
      return this.localFormData
    },
    computedDateReceipt() {
        return this.formatDate(this.main.date_receipt)
    },
    computedDateDismissal() {
        return this.formatDate(this.main.date_dismissal)
    },
    computedDateIssuedDate() {
      return this.formatDate(this.docs.passport_issued_date)
    },
    computedDateValidity() {
      return this.formatDate(this.docs.validity_date) !== null ? this.formatDate(this.docs.validity_date) : '' 
    },
    countriesList() {
      return this.getLists('lists')['countries']
    },
    citiesList() {
      return this.getLists('lists')['cities']
    },
    documentsList() {
      return this.getLists('core_lists')['type_documents']
    },
  },
  created () {
    this.defaultData = JSON.parse(JSON.stringify(this.localFormData))
    console.log(this.defaultData);
  },
  methods: {
    onSave() {
      this.$emit('save', {
        action: this.mode,
        resources: 'positions',
        data: {...this.localFormData},
        alertItem: 'Отделение'
      })
      this.dialog = false
    },
    // changeDefaultState() {
    //   this.localFormData.is_default = !this.localFormData.is_default
    //   if (this.mode !== 'create') {
    //     this.$emit('changeDefault', {id: this.localFormData.id, resources: 'positions'})
    //   }
    // },
    addValue() {
      const { directory_document_id, serial, serial_number, passport_issued, passport_issued_date, validity_date } = this.docs
      const currentDoc = this.localFormData.docs.find(item => item.id === directory_document_id).id
      const transformIssuedDate = this.formatDate(passport_issued_date)
      const transformValidityDate = this.formatDate(validity_date)
      this.currentDocID = currentDoc
      this.documents.push({
        directory_document_id,
        serial,
        serial_number,
        passport_issued_date: transformIssuedDate,
        validity_date: transformValidityDate,
        passport_issued,
      })
      this.docs = {
        directory_document_id: 1,
        serial: null,
        serial_number: null,
        passport_issued: null,
        passport_issued_date: null,
        validity_date: null,
      }
    },
    editValue(index) {
      const editableElementIndex = this.documents.findIndex((item, i) => i === index)
      this.editableElemIndex = index
      this.mode = 'edit'
      this.docs = {
        directory_document_id: this.documents[editableElementIndex].directory_document_id,
        serial: this.documents[editableElementIndex].serial,
        serial_number: this.documents[editableElementIndex].serial_number,
        passport_issued: this.documents[editableElementIndex].passport_issued,
        passport_issued_date: this.inverseDate(this.documents[editableElementIndex].passport_issued_date),
        validity_date: this.inverseDate(this.documents[editableElementIndex].validity_date),
      }
      console.log('editableElement', editableElementIndex, this.documents[editableElementIndex]) 
    },
    deleteValue(index) {
        this.documents.splice(index, 1)
        this.editableElemIndex = null
    },
    saveValue() {
      const currentIndex = this.editableElemIndex 
      this.documents[currentIndex] = {
        directory_document_id: this.docs.directory_document_id,
        serial: this.docs.serial,
        serial_number: this.docs.serial_number,
        passport_issued_date: this.formatDate(this.docs.passport_issued_date),
        validity_date: this.formatDate(this.docs.validity_date),
        passport_issued: this.docs.passport_issued,
      }
      this.docs = {
        directory_document_id: 1,
        serial: null,
        serial_number: null,
        passport_issued_date: null,
        validity_date: null,
        passport_issued: null,
      }
      this.mode = 'add'
      this.editableElemIndex = null
    },
    changeCodePhone(code, property) {
      let generateTel = (code + ' ' + this.contact[property]).replace( /\s/g, "").replace(/[{()}]/g, '')
      console.log(generateTel)
      this.contact[property] = ''
      this.contact[property] = generateTel
      generateTel = null
    },
    goTo(index) {
      this.currentStep = index
    },
    next() {
      this.currentStep++
    },
    prev() {
      if(this.currentStep > 0) this.currentStep--
      if(this.currentStep === 0) {
        this.$emit('checkStep')
      }
    },
    inverseDate(date) {
      if(!date) return null
      const [day, month, year] = date.split('.')
      return `${year}-${month}-${day}`
    },
    formatDate(date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}.${month}.${year}`
    },
    onChangeDate(value) {
      this[value] = false
    },
    onChangeMainSelect(id, part, property) {
      this[part][property] = id
    },
    createEmployee() {
      if(this.$v.main.$invalid) {
        this.currentStep = 0
        this.isValid = false
      }
    },
    open(mode, itemData) {
      this.mode = mode
      console.log(this.mode, this.defaultData)
      const item = mode === 'create' ? JSON.parse(JSON.stringify(this.defaultData)) : JSON.parse(JSON.stringify(itemData))
      this.localFormData = item
      console.log(this.defaultData);
      this.dialog = true
    }
  }
}
</script>

<style scoped lang="sass">
  .wrapper
    width: 100%
    .step-tabs
      display: flex
      padding: 20px 30px
      &-item
        &:not(:last-child)
          margin-right: 20px
        .base-btn
          background: transparent
          border: 1px solid #9DCCFF
          border-radius: 25px
          font-size: 14px
          line-height: 1
          min-height: 36px
          min-width: 220px
          color: #9DCCFF
          transition: .25s ease-in-out
        &--active
          .base-btn
            background: #5893D4
            border-color: #5893D4
            color: #fff
    .item-form
      min-height: 0
    .values-list
      .value-item
        text-align: left
        max-width: 100%
        span
          max-width: 100%
          white-space: normal

.form-actions
  display: flex
  justify-content: center
  padding: 0 30px
  > .btn
      &:not(:last-child)
        margin-right: 30px
.btn
  background: #5893D4
  font-size: 13px
  line-height: 1
  font-weight: bold
  border: 1px solid #5893D4
  border-radius: 32px
  min-height: 40px
  min-width: 196px
  color: #fff
  &--transparent
    background: transparent
    color: #5893D4
</style>