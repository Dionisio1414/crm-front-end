<template>
    <div class="companies-detail" :style="{'opacity': loader ? '.5' : '1', 'pointer-events': loader ? 'none' : 'visible'}">
        <div 
            class="close-details" 
            v-if="stateDetails">
            <button type="button" class="button button-prev" @click="backList">
                <svg class="icon" width="6" height="12" viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.162909 6.42922L4.70779 11.3243C4.8129 11.4376 4.95323 11.5 5.10285 11.5C5.25247 11.5 5.39279 11.4376 5.49791 11.3243L5.83261 10.9639C6.0504 10.729 6.0504 10.3473 5.83261 10.1128L2.01616 6.00228L5.83684 1.88715C5.94196 1.77384 6 1.6228 6 1.46173C6 1.30049 5.94196 1.14944 5.83684 1.03604L5.50215 0.67573C5.39695 0.562422 5.25671 0.5 5.10708 0.5C4.95746 0.5 4.81714 0.562422 4.71202 0.67573L0.162909 5.57525C0.0575425 5.68892 -0.000329774 5.84068 2.08512e-06 6.00201C-0.000329813 6.16397 0.0575425 6.31564 0.162909 6.42922Z"/>
                </svg>
                <span class="btn-text">Назад</span>
            </button>
        </div>
        <div class="select-item" v-if="stateDetails === false">
            <img src="@/assets/icons/area-bg.svg" alt="">
            <span>
                {{ $t('directories.chooseOrganizationText') }}
            </span>
        </div>
        <div class="companies-content organization-accordion" v-if="stateDetails">
            <div class="organization-accordion-item">
                <button type="button" :class="{'toggler': true, 'toggler--active': information}" @click="toggleTabs('information')">
                    <div class="toggler-text">{{ $t('page.suppliers.modal.createSupplier.tabInfo') }}</div>
                    <div class="toggler-line"></div>
                    <div class="toggler-arrow">
                        <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55708e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z" fill="#9DCCFF"/>
                        </svg>
                    </div>
                </button>
                <div :class="{'item-form': true, 'item-form--active': information}">
                    <v-row>
                        <v-col cols="4">
                            <div class="item-name">
                                <div class="label-title">{{ $t('directories.organizationTypeText') }} <span class="mark-required">*</span></div>
                                <div class="select-wrap">
                                    <template v-if="mode !=='change'">
                                        <v-select class="select-switcher"
                                            v-model="localData.main.organization_type_id"
                                            :items="counterpartyTypeList"
                                            item-text="title"
                                            item-value="directory_id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'organization_type_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                    <template v-else>
                                        <v-select class="select-switcher"
                                            v-model="localData.main.organization_type_id"
                                            :items="counterpartyTypeList"
                                            item-text="title"
                                            item-value="directory_id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'organization_type_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                </div>
                            </div>
                        </v-col>
                        <template v-if="localData.main.organization_type_id === 2 || localData.main.organization_type_id === 3">
                            <v-col>
                                <div class="item-name">
                                    <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.firstForm.surname') }} <span class="mark-required">*</span></div>
                                    <input 
                                        type="text" 
                                        name="last_name" 
                                        placeholder="Фамилия"
                                        @keypress="isLetter($event, 35)"
                                        @input="onDouble"
                                        v-model="localData.main.last_name"
                                    >
                                </div>
                            </v-col>
                            <v-col>
                                <div class="item-name">
                                    <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.firstForm.name') }} <span class="mark-required">*</span></div>
                                    <input 
                                        type="text" 
                                        name="first_name"
                                        placeholder="Имя" 
                                        @keypress="isLetter($event, 35)"
                                        @input="onDouble"
                                        v-model="localData.main.first_name"
                                    >
                                </div>
                            </v-col>
                            <v-col>
                                <div class="item-name">
                                    <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.firstForm.patronymic') }} <span class="mark-required">*</span></div>
                                    <input 
                                        type="text" 
                                        name="middle_name" 
                                        placeholder="Отчество"
                                        @keypress="isLetter($event, 35)"
                                        @input="onDouble"
                                        v-model="localData.main.middle_name"
                                    >
                                </div>
                            </v-col>
                        </template>
                        <template v-else>
                            <v-col cols="8">
                                <div class="item-name">
                                    <div class="label-title">{{ $t('directories.nameOrganizationText') }} <span class="mark-required">*</span></div>
                                    <input 
                                        type="text" 
                                        name="organization_name" 
                                        placeholder="Введите наименование организации"
                                        @focus="onFocus" 
                                        @input="onDouble('legal')"
                                        @keypress="isLetter($event, 30)"
                                        v-model="legalData.title">
                                </div>
                            </v-col>
                        </template>
                    </v-row> 

                    <!-- -->

                    <div class="fields">
                        
                        <div class="fields-item" v-if="localData.main.organization_type_id === 2 || localData.main.organization_type_id === 3">
                            <v-row>
                                <v-col cols="8">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('directories.fullName') }} <span class="mark-required">*</span></div>
                                        <input 
                                            type="text" 
                                            name="organization_name" 
                                            placeholder="Введите полное наименование"
                                            @focus="onFocus" 
                                            @keypress="isLetter($event, 50)"
                                            v-model="localData.main.title">
                                    </div>
                                </v-col>
                                <v-col cols="4">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('directories.inn') }}</div>
                                        <input 
                                            :class="{ 'error-on-input': $v.localData.main.inn.minLength === false ? true : false }"
                                            type="text" 
                                            name="inn" 
                                            placeholder="ИНН" 
                                            v-mask="'##########'" 
                                            v-model="localData.main.inn">
                                    </div>
                                </v-col>
                            </v-row>  
                            <v-row>
                                <v-col cols="3">
                                    <div class="item-name">
                                        <div class="label-title">Паспорт</div>
                                    </div>
                                </v-col>    
                            </v-row> 
                            <v-row>
                                <v-col class="pt-0" cols="1">
                                    <div class="item-name">
                                        <input 
                                            type="text" 
                                            name="passport_serial" 
                                            placeholder="CC" 
                                            @keypress="checkLetterSerial"
                                            v-model="localData.main.passport_serial"
                                        >
                                    </div>
                                </v-col>
                                <v-col class="pt-0" cols="2">
                                    <div class="item-name">
                                        <input 
                                            type="text" 
                                            name="passport_serial_number" 
                                            placeholder="Введите Номер" 
                                            v-mask="'##########'"
                                            v-model.number="localData.main.passport_serial_number">
                                    </div>
                                </v-col>
                                <v-col class="pt-0" cols="5">
                                    <div class="item-name">
                                        <input 
                                            type="text" 
                                            name="passport_issued" 
                                            placeholder="Кем выдан" 
                                            v-model="localData.main.passport_issued">
                                    </div>
                                </v-col>
                                <v-col class="pt-0" cols="4">
                                    <div class="item-name">
                                        <!-- <input 
                                            type="text" 
                                            name="organization_date_issued" 
                                            placeholder="Введите дату выдачи" 
                                            v-model="computedDatePassport"
                                        > -->
                                        <masked-input 
                                            class="field"
                                            :class="{'field-error': !isValidDataPassport}"
                                            v-model="localData.main.passport_issued_date" 
                                            mask="11.11.1111" 
                                            placeholder="ДД/ММ/ГГГГ" 
                                        />
                                        <!-- <button 
                                            type="button" 
                                            class="item-button" 
                                            @click="isOpenPassportCalendar = !isOpenPassportCalendar"
                                        >
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
                                        </button> -->
                                        <!-- <v-date-picker
                                            class="base-calendar"
                                            v-if="isOpenPassportCalendar"
                                            v-model="localData.main.passport_issued_date"
                                            width="287"
                                            locale="ru-Ru"
                                            @input="onChangeDate('isOpenPassportCalendar')"
                                        ></v-date-picker> -->
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="4">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('page.citizienText') }}</div>
                                        <input 
                                            type="text" 
                                            name="citizenship" 
                                            placeholder="Гражданство" 
                                            @keypress="isLetter($event, 45)"
                                            v-model="localData.main.citizenship"
                                        >
                                    </div>
                                </v-col>
                                <v-col cols="4">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('page.sexText') }}</div>
                                        <div class="select-wrap">
                                            <template v-if="mode !=='change'">
                                                <v-select class="select-switcher"
                                                    :label="sexList[0].title"
                                                    :items="sexList"
                                                    item-text="title"
                                                    item-value="directory_id"
                                                    solo
                                                    dense
                                                    hide-details
                                                    @change="onChangeMainSelect($event, 'sex_id', 'main')"
                                                    :menu-props="{ contentClass: 'base-select'}"
                                                    return-object
                                                />
                                            </template>
                                            <template v-else>
                                                <v-select class="select-switcher"
                                                    v-model="localData.main.sex_id"
                                                    :items="sexList"
                                                    item-text="title"
                                                    item-value="directory_id"
                                                    solo
                                                    dense
                                                    hide-details
                                                    @change="onChangeMainSelect($event, 'sex_id', 'main')"
                                                    :menu-props="{ contentClass: 'base-select'}"
                                                    return-object
                                                />
                                            </template>
                                        </div>
                                    </div>
                                </v-col>
                                <v-col cols="4">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.firstForm.dateOfBirthday') }}</div>
                                        <!-- <input 
                                            type="text" 
                                            name="organization_code"
                                            placeholder="Дата рождения" 
                                            v-model="computedDateBirth"
                                        > -->
                                        <masked-input 
                                            class="field"
                                            :class="{'field-error': !isValidDateBirth}"
                                            v-model="localData.main.date_of_birth" 
                                            mask="11.11.1111" 
                                            placeholder="ДД/ММ/ГГГГ" 
                                        />
                                        <!-- <button type="button" class="item-button" @click="isOpenBirthCalendar = !isOpenBirthCalendar">
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
                                            v-if="isOpenBirthCalendar"
                                            v-model="localData.main.date_of_birth"
                                            width="287"
                                            locale="ru-Ru"
                                            @input="onChangeDate('isOpenBirthCalendar')"
                                        ></v-date-picker> -->
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                        <div class="fields-item" v-if="localData.main.organization_type_id === 1">
                            <v-row>
                                <v-col cols="8">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('directories.fullnameOrganizationText') }} *</div>
                                        <input 
                                            type="text" 
                                            name="passport_serial" 
                                            placeholder="Введите полное наименование организации"
                                            @focus="onFocus('legal')"
                                            @keypress="isLetter($event, 50)"
                                            v-model="legalData.full_title"
                                        >
                                    </div>
                                </v-col>
                                <v-col cols="4">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('page.edrpouText') }}</div>
                                        <input 
                                            :class="{ 'error-on-input': $v.legalData.edrpou.minLength === false ? true : false }"
                                            type="text" 
                                            name="passport_serial" 
                                            v-mask="'########'" 
                                            placeholder="Введите ЕДРПОУ" 
                                            v-model="legalData.edrpou"
                                        >
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="4">
                                    <div class="item-name">
                                        <div class="label-title">{{ $t('directories.inn') }}</div>
                                        <input 
                                            :class="{ 'error-on-input': $v.legalData.inn.minLength === false ? true : false }"
                                            type="text" 
                                            name="passport_serial" 
                                            v-mask="'##########'" 
                                            placeholder="Введите ИНН" 
                                            v-model="legalData.inn"
                                        >
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                    
                    </div>

                    <!-- -->

                    <v-row class="row--dirOrgLast">
                        <v-col cols="4">
                            <div class="item-name">
                                <div class="label-title">{{ $t('page.mainCashbox') }}</div>
                                <div class="select-wrap">
                                    <template v-if="mode !=='change'">
                                        <v-select class="select-switcher"
                                            :label="mainInfo.mainCashbox[0].name"
                                            :items="mainInfo.mainCashbox"
                                            item-text="name"
                                            item-value="id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'cashbox_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                    <template v-else>
                                        <v-select class="select-switcher"
                                            v-model="localData.main.cashbox_id"
                                            :items="mainInfo.mainCashbox"
                                            item-text="name"
                                            item-value="id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'cashbox_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="4">
                            <div class="item-name">
                                <div class="label-title">{{ $t('page.checkingAccountText') }}</div>
                                <div class="select-wrap">
                                    <template v-if="mode !=='change'">
                                        <v-select class="select-switcher"
                                            :label="mainInfo.checkingAccount[0].name"
                                            :items="mainInfo.checkingAccount"
                                            item-text="name"
                                            item-value="id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'checking_account_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                    <template v-else>
                                        <v-select class="select-switcher"
                                            v-model="localData.main.checking_account_id"
                                            :items="mainInfo.checkingAccount"
                                            item-text="name"
                                            item-value="id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'checking_account_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                </div>
                            </div>
                        </v-col>
                        <v-col cols="4">
                            <div class="item-name">
                                <div class="label-title">{{ $t('page.priceTypesText') }}</div>
                                <div class="select-wrap">
                                    <template v-if="mode !=='change'">
                                        <v-select class="select-switcher"
                                            v-if="getFirstPriceItem"
                                            v-model="localData.main.price_id"
                                            :items="priceTypesList"
                                            item-text="title"
                                            item-value="id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'price_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                    <template v-else>
                                        <v-select class="select-switcher"
                                            v-if="getFirstPriceItem"
                                            v-model="localData.main.price_id"
                                            :items="priceTypesList"
                                            item-text="title"
                                            item-value="id"
                                            solo
                                            dense
                                            hide-details
                                            @change="onChangeMainSelect($event, 'price_id', 'main')"
                                            :menu-props="{ contentClass: 'base-select'}"
                                            return-object
                                        />
                                    </template>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <div class="validation-rules">(*) - {{ $t('page.suppliers.modal.createSupplier.firstForm.error.requiredFields') }}</div>
                        </v-col>
                    </v-row>
                </div>
            </div>

            <div class="organization-accordion-item">
                <button type="button" :class="{'toggler': true, 'toggler--active': contacts}" @click="toggleTabs('contacts')">
                    <div class="toggler-text">{{ $t('directories.contactsInformationText') }}</div>
                    <div class="toggler-line"></div>
                    <div class="toggler-arrow">
                        <svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55708e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z" fill="#9DCCFF"/>
                        </svg>
                    </div>
                </button>
                <div :class="{'item-form': true, 'item-form--active': contacts}">
                    <LegalAddress
                        :modeTypes="mode"
                        :data="localData.contact"
                        @updateData="updateLegalData"
                    >
                    </LegalAddress>

                    <v-row>
                        <v-col class="pt-0" cols="12">
                            <div class="checkbox">
                                <label class="checkbox-label">
                                    <template v-if="mode !=='change'">
                                        <input 
                                            type="checkbox"
                                            v-model="localData.contact.is_legal_equal_actual" 
                                            @change="doubleData"
                                        >                                    
                                    </template>
                                    <template v-else>
                                        <input type="checkbox" 
                                            @change="doubleData" 
                                            v-model="localData.contact.is_legal_equal_actual"
                                        >    
                                    </template>
                                    <div class="checkbox-text">
                                        <div class="text">{{ $t('page.suppliers.modal.createSupplier.secondForm.checkBoxPlaceholder') }}</div>
                                    </div>
                                </label>
                            </div>
                        </v-col>
                    </v-row>

                    <v-row align="start">
                        <v-col cols="4">
                            <div class="item-name item-name_phone">
                                <div class="label-title">{{ $t('page.suppliers.modal.createSupplier.thirdForm.phoneNumber') }}</div>
                                <vue-phone
                                    :defaultCountry="phoneSettings.defaultCountry"    
                                    :preferredCountries="phoneSettings.preferredCountries"
                                    :isRepeater="phoneSettings.isRepeater"
                                    :isFlags="true"
                                    :customPhoneClass="['phone-default']"
                                    :disabled="phoneSettings.disabledField"
                                    :repeaterValuesArray="phoneSettings.contactsValues"
                                    phoneLabel="Номер телефона"
                                    valueField=""
                                    @input="inputHandler"
                                    @save="saveHandler"
                                    @add="valuesHandler"
                                    @remove="removeHandler"
                                >
                                </vue-phone>
                            </div>
                        </v-col>
                        <v-col cols="7" class="offset-md-1">
                            <div class="item-name item-name_actions">
                                <div class="label-title">Email</div>
                                <input 
                                    type="text" 
                                    placeholder="emailexample@gmail.com" 
                                    v-model.trim="email"
                                    :class="{'error-on-input': $v.email.$invalid}"
                                >

                                <button 
                                    v-if="mode_type == 'add' || mode_type === 'edit_phone'" 
                                    type="button" 
                                    class="add-value" 
                                    @click="addValue('emails')"
                                    :disabled="emailValidate === false"
                                >
                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z" stroke="#9DCCFF"></path><path data-v-24021d1c="" d="M16.1027 13.8732L20 13.8732L20 16.0765L16.1027 16.0765L16.1027 20L13.8973 20L13.8973 16.0765L10 16.0765L10 13.8732L13.8973 13.8732L13.8973 10L16.1027 10L16.1027 13.8732Z" fill="#9DCCFF"></path></svg>
                                </button>
                                <button 
                                    class="add-value" 
                                    type="button" 
                                    v-if="mode_type == 'edit_email'" 
                                    @click="saveValue('email')"
                                    :disabled="emailValidate === false"
                                >
                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z" stroke="#9DCCFF"/>
                                    <path d="M19.0424 10L12.928 16.1041L10.9449 14.1244L9 16.066L10.983 18.0457L12.9407 20L14.8856 18.0584L21 11.9543L19.0424 10Z" fill="#9DCCFF"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="values-list">
                                <template v-for="(item, index) in localData.contact.values">
                                    <div class="value-item" :key="index" v-if="item.email" :class="{'value-item--editable': editableElemIndex === index}">
                                        <span>{{ item.email }}</span>
                                        <svg @click="editValue('email', index)" class="edit" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16 3.77778L13.2222 1L2.11111 12.1111L1 16L4.88889 14.8889L16 3.77778ZM11 3.22222L13.7778 6L11 3.22222ZM2.11111 12.1111L4.88889 14.8889L2.11111 12.1111Z" stroke="#BBDBFE" stroke-width="1.39565" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <svg @click="deleteValue('email', index, item.email)" class="close" width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z" fill="#BBDBFE"/>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                        </v-col>
                    </v-row>
                    <ActualAddress 
                        :key="addressKey"
                        :addressData="addressData"
                        modeType=""
                        :doubleDataObject="doubleDataObject"
                        @addValue="addAddressValues"
                        @deleteValue="deleteAddressValues"
                        @saveValues="saveAddressValues"
                    />
                </div>
            </div>
        </div>
        <div class="organizations-buttons" v-if="stateDetails">
            <v-btn
                @click="saveCompany"
                :disabled="computedValidate || loader || !isValidDateBirth || !isValidDataPassport"
                class="base-btn shadow-btn">{{ $t('page.saveButton') }}
            </v-btn>
        </div>
    </div>
</template>
<script>
// import { VueTelInput } from 'vue-tel-input'
import MaskedInput from 'vue-masked-input'
import { mapGetters, mapActions } from "vuex"
import { validationMixin } from 'vuelidate'
import { required, email, minLength } from 'vuelidate/lib/validators'
import ActualAddress from "@/components/dashboard/directoties/company/address/ActualAddress"
import LegalAddress from "@/components/dashboard/directoties/company/address/LegalAddress"
import VuePhone from '@/components/ui/VuePhone/VuePhone'
import moment from "moment"

export default {
    name: "CreateOrganizations",
    props: {
        data: Object,
        stateDetails: Boolean,
        mode: String
    },
    mixins: [validationMixin],
    components: {
        MaskedInput,
        ActualAddress,
        LegalAddress,
        VuePhone,
    },
    validations: {
        email: { email },
        legalData: {
            title: { required },
            full_title: { required },
            inn: { minLength: minLength(10) },
            edrpou: { minLength: minLength(8) }
        },
        localData: {
            main: {
                first_name: { required },
                last_name: { required },
                middle_name: { required },
                title: { required },
                inn: { minLength: minLength(10) },
                edrpou: { minLength: minLength(8) }
            }
        }
    },
    data: () => ({
        loader: false,
        copyData: {},
        picker_birth: null,
        picker_passport: null,
        isOpenBirthCalendar: false,
        isOpenPassportCalendar: false,
        information: true,
        contacts: false,
        checkeds: false,
        phone: null,
        phoneCopy: null,
        phoneStatus: null,
        defaultCountry: 'UA',
        number_code: "+38",
        email: "",
        numbers: [],
        mode_type: 'add',
        editableElemIndex: null,
        addressKey: 0,
        addressData: [],
        country: '',
        city: '',
        region: '',
        isDouble: true,
        isLegalDouble: true,
        editPhoneValue: '',
        doubleDataObject: {},
        isValidDataPassport: true,
        isValidDateBirth: true,
        legalData: {
            cashbox_id: 1,
            checking_account_id: 1,
            price_id: 1,
            edrpou: null,
            inn: null,
            title: null,
            full_title: null,
        },
        localData: {
            main: {
                organization_type_id: 2,
                title: null,
                full_title: null,
                first_name: null,
                last_name: null,
                middle_name: null,
                inn: null,
                passport_serial: null,
                passport_serial_number: null,
                passport_issued: null,
                passport_issued_date: null,
                citizenship: null,
                sex_id: 1,
                date_of_birth: null,
                cashbox_id: 1,
                checking_account_id: 1,
                price_id: 1,
                edrpou: null
            },
            contact: {
                legal_country_id: null,
                legal_region_id: null,
                legal_city_id: null,
                legal_city: null,
                legal_number_house: null,
                is_legal_equal_actual: false,
                values: []
            }
        },

        mainInfo: {
            organizationTypeSelect: "ФОП",
            sexSelect: "Мужской",
            sellingPriceSelect: "Р/С 1",
            checkingAccountSelect: "Оптовая",
            mainCashboxSelect: "Касса 1",
            organizationTypes: [
                { name: 'ФОП', id: 1 },
                { name: 'Физическое лицо', id: 2 },
                { name: 'Юридическое лицо', id: 3 }
            ],
            sex: [
                { name: 'Мужской', id: 1 },
                { name: 'Женский', id: 2 }
            ],
            mainCashbox: [
                { name: "Касса 1", id: 1 },
                { name: "Касса 2", id: 2 },
            ],
            checkingAccount: [
                { name: "Р/С 1", id: 1 },
                { name: "Р/С 2", id: 2 },
                { name: "Р/С 3", id: 3 }
            ],
            sellingPrice: [
                { name: "Оптовая", id: 1 },
                { name: "Закупочная", id: 2 },
                { name: "Рыночная", id: 3 }
            ],
        },
        contactsInfo: {
            phone: "",
            countriesSelect: "Украина",
            regionsSelect: "Харьковская область",
            citiesSelect: "Харьков",
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
            codes: [
                { name: "+380" },
                { name: "+780" },
                { name: "+280" }
            ]
        },
        phoneSettings: {
            defaultCountry: 'UA',
            preferredCountries: ['UA', 'RU'],
            isRepeater: true,
            disabledField: false,
            contactsValues: []
        },
        // settingsAutocomplete: {
        //     country_id: null,
        //     region_id: null,
        //     city_id: null,
        //     type_region: REGIONS,
        //     type_country: COUNTRIES,
        //     type_city: CITIES,
        //     uniq: null,
        //     loading_regions: false,
        //     loading_cities: false,
        // }
    }),
    computed: {
        ...mapGetters([
            'getLists',
            'getAddress'
        ]),
        // getAddressCountries() {
        //     return this.getAddress('organizations', 'countries')
        // },
        sexList() {
            return this.getLists('core_lists')['sex']
        },
        counterpartyTypeList() {
            return this.getLists('core_lists')['counterparty_type']
        },
        priceTypesList() {
            return this.getLists('lists')['prices_types'].types ? this.getLists('lists')['prices_types'].types : [] 
        },
        computedDateBirth() {
            return this.inverseDate(this.localData.main.date_of_birth)
        },
        computedDatePassport() {
            return this.inverseDate(this.localData.main.passport_issued_date)
        },
        computedValidate() {
            if( !this.$v.localData.$invalid && this.localData.main.organization_type_id === 2 || 
                !this.$v.localData.$invalid && this.localData.main.organization_type_id === 3
            ) {
                return false
            } else if(
                !this.$v.legalData.$invalid && this.localData.main.organization_type_id === 1
            ) {
                return false
            } else {
                return true
            }
        },
        emailValidate() {
            // const emailRegexpPattern = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
            // emailRegexpPattern.test(this.email)
            if(this.email !== "" && !this.$v.email.$invalid) return true
            else return false
        },
        filterActualAddress() {
            if(this.localData.contact.values.length) {
                return this.localData.contact.values.filter(item => {
                    if(!item.phone && !item.email) return item
                })
            } else {
                return []
            }
        },
        filterAnotherAddress() {
            if(this.localData.contact.values.length) {
                return this.localData.contact.values.filter(item => {
                    if(item.phone || item.email) return item
                })
            } else {
                return []
            }
        },
        getFirstPriceItem() {
            if(this.priceTypesList.length) return this.priceTypesList.find(item => item)
            else return null 
        },
    },
     watch: {
        'computedDateBirth': function(val) {
        if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
            this.isValidDateBirth = false
        } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
            this.isValidDateBirth = true
        } else {
            this.isValidDateBirth = true
        }
        },
        'computedDatePassport': function(val) {
        if(!moment(val, 'YYYY-MM-DD', true).isValid() && val !== null) {
            this.isValidDataPassport = false
        } else if(val === null && moment(val, 'YYYY-MM-DD', true).isValid() === false) {
            this.isValidDataPassport = true
        } else {
            this.isValidDataPassport = true
        }
        },
    },
    methods: {
        ...mapActions([
            'updateCompanies',
            'changeCompanyItem',
            'getAddressList',
            'getAddressListWithPaginate',
            'fetchLists'
        ]),
        inverseDate(date) {
            if(!date) return null
            const [day, month, year] = date.split('.') 
            return `${year}-${month}-${day}`
        },
        checkLetterSerial(e) {
            const regExp = /^([а-яё]+|[a-z]+)$/i
            const char = String.fromCharCode(e.keyCode)
            const value = e.target.value

            if(regExp.test(char) && value.length + 1 <= 4) return true
            else e.preventDefault()
        
        },
        updateLegalData(val) {
            console.log(val)
            this.localData.contact = { ...this.localData.contact, ...val }
        },
        saveHandler(val) {
            const withoutPhoneObj = this.localData.contact.values.filter(item => !item.phone)
            const defaultObject = val.map(item => {
                return {
                    phone: item.field,
                    email: null,
                    country_id: null,
                    region_id: null,
                    city_id: null,
                    city: null,
                    number_house: null
                }
            })
            this.localData.contact.values = [ ...withoutPhoneObj, ...defaultObject ]
        },
        removeHandler(val, index) {
            console.log(val, index)
            const withoutPhoneObj = this.localData.contact.values.filter(item => !item.phone)
            const defaultObject = val.map(item => {
                return {
                    phone: item.field,
                    email: null,
                    country_id: null,
                    region_id: null,
                    city_id: null,
                    city: null,
                    number_house: null
                }
            })
            // console.log([ ...withoutPhoneObj, ...defaultObject ])
            this.localData.contact.values = [ ...withoutPhoneObj, ...defaultObject ]
        },
        valuesHandler(val, index) {
            console.log('valuesHandler', val, index)
            const phonesArray = val.map(item => {
                return {
                    phone: item.field,
                    email: null,
                    country_id: null,
                    region_id: null,
                    city_id: null,
                    city: null,
                    number_house: null
                }
            })
            console.log('phonesArray', phonesArray)
            this.localData.contact.values.push(phonesArray.pop())
        },
        inputHandler(val) {
            this.phoneSettings = val.code
        },
        checkNumberValidate({ country: { dialCode }, number: { e164 } }) {
            this.phoneCopy = e164
            this.number_code = dialCode
            console.log('value', e164)
        },
        checkNumber(str, obj) {
            const { isValid } = obj
            this.phone = str
            if(isValid) this.phoneStatus = true
            else this.phoneStatus = false
            console.log(str, obj)
        },
        forceRender(property) {
            this[property] += 1
        },
        clearFields(type, obj) {
            if(type === 'organization_type_id' && obj.directory_id === 1) {
                this.localData.main = {
                    organization_type_id: obj.directory_id,
                    title: null,
                    full_title: null,
                    first_name: null,
                    last_name: null,
                    middle_name: null,
                    inn: null,
                    passport_serial: null,
                    passport_serial_number: null,
                    passport_issued: null,
                    passport_issued_date: null,
                    citizenship: null,
                    sex_id: 1,
                    date_of_birth: null,
                    cashbox_id: 1,
                    checking_account_id: 1,
                    price_id: 1,
                    edrpou: null
                }
                this.isDouble = true
            } else if(type === 'organization_type_id' && obj.directory_id === 2 || type === 'organization_type_id' && obj.directory_id === 3) {
                this.legalData = {
                    cashbox_id: 1,
                    checking_account_id: 1,
                    price_id: 1,
                    edrpou: null,
                    inn: null,
                    title: null,
                    full_title: null,
                }
                this.isLegalDouble = true
            } else {
                return false
            }
        },
        addValue(type) {
            let numbers, emails, self = this
            if(type === 'numbers') {
                if(this.phoneStatus) {
                    numbers = {
                        phone: self.phoneCopy,
                        email: null,
                        country_id: null,
                        region_id: null,
                        city_id: null,
                        city: null,
                        number_house: null
                    }
                    self.localData.contact.values.push(numbers)
                    self.phone = ''
                    self.editPhoneValue = ''
                } else {
                    return false
                }
            }

            if(type === 'emails') {
                if(this.emailValidate) {
                    emails = {
                        phone: null,
                        email: self.email,
                        country_id: null,
                        region_id: null,
                        city_id: null,
                        city: null,
                        number_house: null
                    }
                    self.localData.contact.values.push(emails)
                    self.email = ''
                } else {
                    return false
                }
            }
        },
        deleteValue(type, index, item) {
            console.log(item)
            this.localData.contact.values.splice(index, 1)
            if(type === 'email') this.email = ''
            else this.phone = ''            
        },
        editValue(type, index) {
            const editableElementIndex = this.localData.contact.values.findIndex((item, i) => i === index)
            this.editableElemIndex = index
            this.mode_type = `edit_${type}`
            if(type === 'email') {
                this[type] = this.localData.contact.values[editableElementIndex][type]
            } else {
                this[type] = this.localData.contact.values[editableElementIndex][type]
            }
        },
        saveValue(type) {
            const currentIndex = this.editableElemIndex
            console.log(this.localData.contact.values[currentIndex][type], this[type])
            this.localData.contact.values[currentIndex][type] = this[type]
            this.mode_type = 'add'
            this.editableElemIndex = null
            this[type] = ''
        },
        changeCodes({ iso2 }) { this.defaultCountry = iso2 },
        onChangeDate(value) { this[value] = false },
        onChangeMainSelect(obj, value, tab, ...rest) {
            console.log(obj, value, tab)
            this.localData[tab][value] = obj.id ? +obj.id : +obj.directory_id
            if(rest[0] !== undefined) this[rest[0]] = obj
            if(Object.prototype.hasOwnProperty.call(obj, 'directory_id')) this.clearFields(value, obj)
        },
        isLetter(e, len) {
            console.log(e.target.value.length, len)
            let char = String.fromCharCode(e.keyCode)
            if(/^[А-Яа-яёA-Za-z\s]+$/.test(char) && e.target.value.length < len) return true
            else e.preventDefault()
        },
        onDouble(type = '') {
            if(type === 'legal') {
                const { title } = this.legalData
                if(this.isLegalDouble) this.legalData.full_title = title 
            } else {
                const { 
                    main: {
                        last_name,
                        first_name,
                        middle_name
                    }
                } = this.localData
                const fullName = `${last_name ? last_name : ''} ${first_name ? first_name : ''} ${middle_name ? middle_name : ''}`
                if(this.isDouble) this.localData.main.title = fullName 
            }
        },
        onFocus(type = '') {
            if(type === 'legal') {
                if(this.legalData.full_title) this.isLegalDouble = false
            } else {
                if(this.localData.main.title) this.isDouble = false
            }
        },
        backList() {
            this.$emit('backList')
        },
        formatDate(date) {
            if (!date) return null
            const [year, month, day] = date.split('-')
            return `${day}.${month}.${year}`
        },
        toggleTabs(value) {
            switch(value) {
                case 'information':
                    this.information = true
                    this.contacts = false
                    break
                case 'contacts':
                    this.contacts = true
                    this.information = false
                    if(this.mode === 'change') this.localData.contact = this.data.contact
                    break
                default:
                    this.information = true
                    this.contacts = false
            } 
        },
        doubleData({ target }) {
            const { legal_city, legal_number_house, legal_country_id, legal_region_id, legal_city_id } = this.localData.contact
            if(target.checked) {
                this.localData.contact.is_legal_equal_actual = true
                console.log(legal_country_id, legal_region_id, legal_city_id)
                this.doubleDataObject = {
                    phone: null,
                    email: null,
                    country: legal_country_id,
                    region: legal_region_id,
                    cities: legal_city_id,
                    city: legal_city,
                    number_house: legal_number_house,
                    country_title: null,
                    region_title: null,
                    city_title: null
                }
                console.log('doubleDataObject', this.doubleDataObject)
            } else {
                this.localData.contact.is_legal_equal_actual = false
            }
        },
        doubleDataChange() {
            const { legal_city, legal_number_house } = this.localData.contact

            this.addressData.push({
                phone: null,
                email: null,
                country: this.country,
                region: this.region,
                cities: this.city,
                city: legal_city,
                number_house: legal_number_house
            })
        },
        async saveCompany() {
            let data = {}, currentOrganizationId = this.localData.main.organization_type_id
            if(this.mode === 'create_organizations') {
                if(currentOrganizationId === 2 || currentOrganizationId === 3) {
                    data = {
                        main: { 
                            ...this.localData.main,
                            date_of_birth: this.localData.main.date_of_birth !== '' && this.localData.main.date_of_birth !== null ? this.inverseDate(this.localData.main.date_of_birth) : this.localData.main.date_of_birth,
                            passport_issued_date: this.localData.main.passport_issued_date !== '' && this.localData.main.passport_issued_date !== null ? this.inverseDate(this.localData.main.passport_issued_date) : this.localData.main.passport_issued_date,
                        },
                        contact: { ...this.localData.contact },
                        is_default: this.copyData.is_default ? this.copyData.is_default : false, 
                        title: this.localData.main.title, 
                        archive: false
                    }
                    data.main.inn = !this.localData.main.inn ? null : parseInt(this.localData.main.inn)
                } else {
                    data = {
                        ...this.localData, 
                        main: {
                            ...this.localData.main,
                            ...this.legalData,
                            inn: !this.legalData.inn ? null : parseInt(this.legalData.inn)
                        },
                        is_default: this.copyData.is_default ? this.copyData.is_default : false, 
                        title: this.legalData.title, 
                        archive: false
                    }
                }
                this.loader = true
                await this.updateCompanies({ data, type: 'organizations' })
                this.$emit('backList', { modeType: 'create', title: 'directories.organizations' })
            } else if(this.mode === 'change') {
                const { id } = this.data
                if(currentOrganizationId === 2 || currentOrganizationId === 3) {
                    data = {
                        main: { 
                            ...this.localData.main,
                            date_of_birth: this.localData.main.date_of_birth !== '' && this.localData.main.date_of_birth !== null ? this.inverseDate(this.localData.main.date_of_birth) : this.localData.main.date_of_birth,
                            passport_issued_date: this.localData.main.passport_issued_date !== '' && this.localData.main.passport_issued_date !== null ? this.inverseDate(this.localData.main.passport_issued_date) : this.localData.main.passport_issued_date,
                        },
                        contact: { ...this.localData.contact },
                        is_default: this.copyData.is_default ? this.copyData.is_default : false, 
                        title: this.localData.main.title, 
                        archive: false
                    }
                    data.main.inn = !this.localData.main.inn ? null : parseInt(this.localData.main.inn)
                } else {
                    data = {
                        ...this.localData, 
                        main: {
                            ...this.localData.main,
                            ...this.legalData,
                            inn: !this.legalData.inn ? null : parseInt(this.legalData.inn)
                        },
                        is_default: this.copyData.is_default, 
                        title: this.legalData.title, 
                        archive: false
                    }
                }
                console.log(id, data)
                this.loader = true
                await this.changeCompanyItem({ data, type: 'organizations', id })
                this.$emit('backList', { modeType: 'change', title: '' })
            } else {
                return false
            }
        },
        async addAddressValues(value) {
            console.log('addAddressValues value', value)
            await this.localData.contact.values.push(value)
            this.addressData = [ ...this.filterActualAddress ]
        },
        deleteAddressValues(index) {
            console.log('deleteAddressValues', index)
            this.localData.contact.values.splice(index, 1)
            this.addressData = [ ...this.filterActualAddress ]
            // this.addressData = this.localData.contact.values
        },
        saveAddressValues(value) {
            const anotherAddress = [ ...this.filterAnotherAddress ]
            this.addressData = value
            this.localData.contact.values = [ ...anotherAddress, ...this.addressData]
        },
        initData() {
            console.log('init data', this.data.contact)
            if(this.mode === 'change' && this.stateDetails === true) { 
                Object.assign(this.copyData, this.data)
                const dataBirth = this.data.main.date_of_birth !== null ? this.formatDate(this.data.main.date_of_birth.slice(0, 10)) : null
                const dataPassport = this.data.main.passport_issued_date !== null ? this.formatDate(this.data.main.passport_issued_date.slice(0, 10)) : null
                console.log('dataBirth', dataBirth)
                this.localData.main = {
                    ...this.data.main,
                    price_id: this.data.main.price_id !== null ? this.getFirstPriceItem?.id : null,
                    cashbox_id: this.data.main.cashbox_id !== null ? this.data.main.cashbox_id : 1,
                    checking_account_id: this.data.main.checking_account_id !== null ? this.data.main.checking_account_id : 1,
                    date_of_birth: dataBirth,
                    passport_issued_date: dataPassport,
                    sex_id: this.data.main.sex_id ? this.data.main.sex_id : 1
                }
                this.localData.contact = { ...this.data.contact }
                this.legalData = {
                    cashbox_id: this.data.main.cashbox_id,
                    checking_account_id: this.data.main.checking_account_id,
                    edrpou: this.data.main.edrpou,
                    full_title: this.data.main.full_title,
                    inn: this.data.main.inn,
                    price_id: this.data.main.price_id,
                    title: this.data.main.title
                }

                this.phoneSettings.contactsValues = this.data.contact.values.filter(item => item.phone).map(item => { 
                    return { field: item.phone, code: this.phoneSettings.defaultCountry }
                })

            }
        }
    },
    async created() {
        this.loader = true
        await this.fetchLists({ type: '', resources: 'prices_types' })
        await this.fetchLists({ type: 'core', resources: 'sex' })
        await this.initData()
        this.loader = false
    },
    mounted() {
        if(this.getFirstPriceItem) this.localData.main.price_id = this.getFirstPriceItem.id
        if(this.mode === 'change' && Object.keys(this.data).length && this.data.main?.organization_type_id) { 
            this.localData.main.organization_type_id = this.copyData.main?.organization_type_id
            this.addressData = this.data.contact.values
        }
    }
}
</script>
<style lang="sass">
        
  .field
      &-error
        border-bottom: 1px solid #FF9F2F!important

  .item-name
    .theme--light.v-text-field 
        > 
            .v-input__control 
                > 
                    .v-input__slot
                        &:before
                            display: none
    .v-text-field.v-input--is-focused 
        > .v-input__control 
            > .v-input__slot:after
                display: none
    .v-input__control
        div[role="combobox"]
            .v-input__append-inner
                border-bottom: 1px solid #9DCCFF
                padding-left: 0!important
                margin-top: 7px!important
    + .values-list
      margin-top: 15px
    &_phone
      align-items: flex-end
      justify-content: space-between
      .select-wrap
        flex: 0 1 calc(25% - 5px)
      input
        flex: 0 1 calc(75% - 5px)
      button
        line-height: 1
    &_actions
      align-items: center
      justify-content: space-between
      input, 
      .select-wrap
        flex: 0 1 91%
    
  .page-company.with-tabs
    .card-grid
      .card
        &:first-child
          flex: 0 1 calc(17.5% - 20px)
        &:nth-child(2)
          flex: 0 1 calc(30% - 20px)
        &:last-child
          flex: 0 1 calc(54.5% - 20px)
  .page
    .companies-detail
      .organization-accordion-item
        .item-form
          .row
            margin-bottom: 6px
            &--dirOrgLast
              margin-top: 32px
            > .col
              &:not(.pt-0)
                .item-name
                  .label-title
                    margin-bottom: 0
                    + input
                      margin-top: 7px
                  .select-wrap
                    margin-top: 2px
                  // > input
                  //   margin-top: 13px
      .organizations-buttons
        margin-top: 16px
  .mark-required
    color: #9dccff
</style>