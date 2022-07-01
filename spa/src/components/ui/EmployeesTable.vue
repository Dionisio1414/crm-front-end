<template>
  <div class="table-wrapper">
    <div class="table-responsive">
      <v-data-table
          :headers="formatHeader"
          :items="[body, formatHeader]"
          :sortable="true"
          :sort-desc.sync="sortDesc"
          :sort-by.sync="sortBy"
          :items-per-page="10"
          hide-default-header
          hide-default-footer
          class="table"
          ref="table"
      >
        <template v-slot:header="{props}">
          <thead>
          <tr>
            <th>
              <div class="checkbox">
                <label class="checkbox-label">
                  <input type="checkbox" @click="toggleAll" v-model="selected">
                  <div class="checkbox-text">
                  </div>
                </label>
              </div>
            </th>
            <th v-for="header in props.headers" :key="header.id"
                :ref="header.value"
                v-show="header.is_visible"
                @click.prevent="toggleOrder($event, header.value)"
            >
                <div class="th-block">
                  <span class="header-title">{{ header.title }}</span>
                  <span class="sortable-icon" v-if="header.is_sortable">
                    <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <g opacity="0.5">
                      <path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"/>
                      <path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"/>
                      </g>
                    </svg>
                  </span>
                </div>
            </th>

            <th class="thTable-settings">
              <div class="table-settings" v-click-outside="hideOutsideSettings">
                <button type="button" class="btn-settings" @click="showSettings">
                  <span class="icon">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.5765 6.9505L16.193 6.64975C16.0727 6.28143 15.9237 5.92218 15.7478 5.57611L16.5137 4.38478C16.6505 4.17192 16.6204 3.89246 16.4416 3.71365L14.2863 1.55841C14.1075 1.37961 13.8281 1.34953 13.6152 1.48631L12.4239 2.2522C12.0778 2.07628 11.7186 1.92728 11.3503 1.80698L11.0495 0.423523C10.9958 0.176331 10.7769 0 10.5239 0H7.47606C7.2231 0 7.0042 0.176331 6.9505 0.423523L6.64975 1.80698C6.28143 1.92728 5.92218 2.07628 5.57611 2.2522L4.38478 1.48631C4.17192 1.34953 3.89246 1.37961 3.71365 1.55841L1.55841 3.71365C1.37961 3.89246 1.34953 4.17192 1.48631 4.38478L2.2522 5.57611C2.07628 5.92218 1.92728 6.28143 1.80698 6.64975L0.423523 6.9505C0.176331 7.00433 0 7.2231 0 7.47606V10.5239C0 10.7769 0.176331 10.9957 0.423523 11.0495L1.80698 11.3503C1.92728 11.7186 2.07628 12.0778 2.2522 12.4239L1.48631 13.6152C1.34953 13.8281 1.37961 14.1075 1.55841 14.2863L3.71365 16.4416C3.89246 16.6204 4.17192 16.6505 4.38478 16.5137L5.57611 15.7478C5.92218 15.9237 6.28143 16.0727 6.64975 16.193L6.9505 17.5765C7.0042 17.8237 7.2231 18 7.47606 18H10.5239C10.7769 18 10.9958 17.8237 11.0495 17.5765L11.3503 16.193C11.7186 16.0727 12.0778 15.9237 12.4239 15.7478L13.6152 16.5137C13.8281 16.6505 14.1075 16.6205 14.2863 16.4416L16.4416 14.2863C16.6204 14.1075 16.6505 13.8281 16.5137 13.6152L15.7478 12.4239C15.9237 12.0778 16.0727 11.7186 16.193 11.3503L17.5765 11.0495C17.8237 10.9957 18 10.7769 18 10.5239V7.47606C18 7.2231 17.8237 7.00433 17.5765 6.9505ZM12.2271 9C12.2271 10.7794 10.7794 12.2271 9 12.2271C7.22063 12.2271 5.7729 10.7794 5.7729 9C5.7729 7.22063 7.22063 5.7729 9 5.7729C10.7794 5.7729 12.2271 7.22063 12.2271 9Z"
                            fill="#9DCCFF"/>
                    </svg>
                  </span>
                </button>
                <div class="settings-popup" v-if="settingsFlag">
                  <div class="settings-title">{{ $t('page.wareHouses.setTable') }}</div>
                  <div class="settings-default">
                    <button type="button" class="btn btn-default" @click="resetHeaders">
                      <span class="btn-default-text">{{ $t('page.wareHouses.setDefault') }}</span>
                      <svg :class="{'rotating': rotating}" class="btn-default-icon" width="15" height="12" viewBox="0 0 15 12" fill="none"
                           xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M2.67596 4.82511C2.63242 4.78146 2.5807 4.74683 2.52376 4.7232C2.46681 4.69957 2.40577 4.68741 2.34412 4.68741C2.28247 4.68741 2.22142 4.69957 2.16448 4.7232C2.10754 4.74683 2.05581 4.78146 2.01227 4.82511L0.137454 6.69993C0.0494437 6.78794 0 6.90731 0 7.03177C0 7.15624 0.0494437 7.27561 0.137454 7.36362C0.225464 7.45163 0.344832 7.50107 0.469297 7.50107C0.593763 7.50107 0.71313 7.45163 0.801141 7.36362L2.34412 5.8197L3.8871 7.36362C3.97511 7.45163 4.09447 7.50107 4.21894 7.50107C4.34341 7.50107 4.46277 7.45163 4.55078 7.36362C4.63879 7.27561 4.68824 7.15624 4.68824 7.03177C4.68824 6.90731 4.63879 6.78794 4.55078 6.69993L2.67596 4.82511ZM14.8623 3.8877C14.8188 3.84405 14.767 3.80942 14.7101 3.78579C14.6532 3.76216 14.5921 3.75 14.5305 3.75C14.4688 3.75 14.4078 3.76216 14.3508 3.78579C14.2939 3.80942 14.2422 3.84405 14.1986 3.8877L12.6556 5.43162L11.1127 3.8877C11.0246 3.79969 10.9053 3.75025 10.7808 3.75025C10.6563 3.75025 10.537 3.79969 10.449 3.8877C10.361 3.97571 10.3115 4.09508 10.3115 4.21954C10.3115 4.34401 10.361 4.46338 10.449 4.55139L12.3238 6.42621C12.3673 6.46986 12.4191 6.50449 12.476 6.52812C12.5329 6.55174 12.594 6.56391 12.6556 6.56391C12.7173 6.56391 12.7783 6.55174 12.8353 6.52812C12.8922 6.50449 12.9439 6.46986 12.9875 6.42621L14.8623 4.55139C14.9059 4.50785 14.9406 4.45613 14.9642 4.39918C14.9878 4.34224 15 4.28119 15 4.21954C15 4.15789 14.9878 4.09685 14.9642 4.0399C14.9406 3.98296 14.9059 3.93124 14.8623 3.8877Z"
                              fill="#5893D4"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M7.49946 0.937894C6.71875 0.937295 5.95028 1.13192 5.26397 1.50405C4.57765 1.87619 3.99528 2.41402 3.56984 3.06863C3.49989 3.16781 3.39419 3.23598 3.27499 3.25882C3.15579 3.28165 3.03239 3.25735 2.93074 3.19104C2.82909 3.12473 2.75712 3.02158 2.72998 2.90329C2.70284 2.785 2.72265 2.66079 2.78523 2.5568C3.44656 1.54049 4.41862 0.765117 5.55651 0.346251C6.6944 -0.0726156 7.93719 -0.112541 9.09962 0.232425C10.2621 0.577391 11.2819 1.28878 12.0071 2.26054C12.7323 3.2323 13.124 4.41241 13.1239 5.62495C13.1239 5.74938 13.0745 5.86872 12.9865 5.9567C12.8985 6.04469 12.7792 6.09412 12.6548 6.09412C12.5303 6.09412 12.411 6.04469 12.323 5.9567C12.235 5.86872 12.1856 5.74938 12.1856 5.62495C12.1856 4.38186 11.6918 3.18969 10.8128 2.3107C9.93378 1.43171 8.74161 0.937894 7.49853 0.937894H7.49946ZM2.34371 5.15624C2.46801 5.15624 2.58723 5.20562 2.67513 5.29352C2.76303 5.38142 2.81241 5.50064 2.81241 5.62495C2.81206 6.63567 3.13844 7.61943 3.74287 8.4295C4.3473 9.23958 5.1974 9.83257 6.16638 10.12C7.13536 10.4075 8.1713 10.3741 9.11972 10.0247C10.0681 9.67526 10.8782 9.02866 11.4291 8.18126C11.4616 8.1273 11.5046 8.08044 11.5556 8.04347C11.6066 8.0065 11.6645 7.98019 11.7259 7.96611C11.7873 7.95202 11.8509 7.95046 11.9129 7.9615C11.9749 7.97254 12.034 7.99597 12.0868 8.03038C12.1395 8.06479 12.1848 8.10948 12.2199 8.16178C12.255 8.21408 12.2792 8.27291 12.291 8.33477C12.3029 8.39663 12.3021 8.46024 12.2888 8.5218C12.2756 8.58337 12.25 8.64162 12.2137 8.69309C11.5524 9.7094 10.5803 10.4848 9.44241 10.9036C8.30452 11.3225 7.06174 11.3624 5.89931 11.0175C4.73688 10.6725 3.71705 9.96112 2.99185 8.98935C2.26664 8.01759 1.8749 6.83748 1.875 5.62495C1.875 5.56332 1.88715 5.50229 1.91077 5.44536C1.93438 5.38844 1.96899 5.33673 2.01261 5.29319C2.05623 5.24965 2.10801 5.21515 2.16499 5.19165C2.22196 5.16815 2.28301 5.15612 2.34464 5.15624H2.34371Z"
                              fill="#5893D4"/>
                      </svg>
                    </button>
                  </div>
                  <draggable
                      class="settings-list"
                      tag="div"
                      v-bind="dragOptions"
                      @update="checkMove"
                      @start="drag = true"
                      @end="drag = false">
                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                      <div v-for="(val, index) in computedHeader" :key="val.id" class="settings-list-item">
                        <div class="checkbox">
                          <label class="checkbox-label">
                            <input type="checkbox" v-model="val.is_visible">
                            <div class="checkbox-text">
                              <div class="text">{{ val.title }}</div>
                            </div>
                          </label>
                        </div>
                        <div class="sorting">
                          <v-btn color="#9DCCFF" fab small
                                 class="action-btn circle-btn swap-btn"
                                 @click.stop="move(index, index - 1)"
                                 :disabled="!index"
                          >
                            <svg width="6" height="12" viewBox="0 0 6 12" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                              <path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="white"/>
                            </svg>
                          </v-btn>
                          <v-btn color="#9DCCFF" fab small
                                 class="action-btn circle-btn swap-btn"
                                 @click.stop="move(index, index + 1)"
                                 :disabled="index === (settingsTable.header.length-1)"
                          >
                            <svg width="6" height="12" viewBox="0 0 6 12" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                              <path
                                  d="M3 12L6 9L5.3 8.3L3.5 10.1L3.5 2.18557e-07L2.5 3.0598e-07L2.5 10.1L0.699999 8.3L-2.62268e-07 9L3 12Z"
                                  fill="white"/>
                            </svg>
                          </v-btn>
                        </div>
                      </div>
                    </transition-group>
                  </draggable>
                  <button 
                    type="button" 
                    class="base-button base-button--blue" 
                    @click="saveTableSettings"
                  >
                    {{ $t('page.wareHouses.saveTable') }}
                  </button>
                </div>
              </div>
            </th>
          </tr>
          </thead>
        </template>
        <template v-slot:body="{items}">
          <tbody>
            <template v-for="(val, index) in items[0]">
              <tr
                :class="{'is-not-active': val.cells.is_user === 0 && val.cells.is_not_active === 1}" 
                :key="val.id" 
                @contextmenu.prevent="$refs.menu.open($event, { val, index })"
              >

                <td>
                  <div class="checkbox" @click.stop="(() => false)">
                      <label class="checkbox-label">
                      <input type="checkbox" @change="selectItem(val, selected)" :checked="selected">
                      <div class="checkbox-text"></div>
                      </label>
                  </div>
                </td>
                <td v-for="(value, key) in items[1]" :key="key" :ref="key" @click="onClickRow(val)" >

                  <!-- {{ value.value === 'is_user' ? val.cells[value.value] }} -->
                  <span v-if="value.value === 'contact'">
                    <template v-if="val.cells[value.value] !== null">
                      <template v-if="val.cells[value.value].split(',')[0] === ''">
                        {{ val.cells[value.value].split(',')[1] }} 
                      </template>
                      <template v-else>
                        <template v-for="val in val.cells[value.value].split(',')">
                          {{ val }}
                        </template>
                      </template>
                    </template>
                  </span>
                  <span v-else-if="value.value === 'date_dismissal' || value.value === 'date_receipt' || value.value === 'date_of_birth'">
                    {{ val.cells[value.value] ? formatDate(String(val.cells[value.value]).slice(0, 10)) : "" }} 
                  </span>
                  <span v-else-if="value.value === 'document_issued_date' || value.value === 'document_validity_date'">
                    {{ val.cells[value.value] ? formatDate(String(val.cells[value.value]).slice(0, 10)) : "" }}
                  </span>
                  <template v-else-if="value.value === 'is_not_active'">
                    <span class="status-user" :class="{'status-user--active': val.cells[value.value] === 0}">
                      <template v-if="val.cells[value.value] === 0">
                        Активный
                      </template>
                      <template v-else>
                        Неактивный
                      </template>
                    </span>
                  </template>
                  <template v-else-if="value.value === 'is_user' || value.value === 'is_employee'">
                    <span v-if="val.cells[value.value] === 0" class="user-icon">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="22" height="22" rx="11" fill="white"/>
                        <circle cx="11" cy="11" r="10.5" stroke="#9DCCFF"/>
                        <path d="M6 12V10H16V12H6Z" fill="#9DCCFF"/>
                      </svg>
                    </span>
                    <span v-else class="user-icon">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="22" height="22" rx="11" fill="white"/>
                        <circle cx="11" cy="11" r="10.5" stroke="#6FCF97"/>
                        <path d="M6 12V10H16V12H6Z" fill="#6FCF97"/>
                        <path d="M10 6L12 6L12 16L10 16L10 6Z" fill="#6FCF97"/>
                      </svg>
                    </span>
                  </template>
                  <span v-else>{{ val.cells[value.value] }}</span>
                  
                </td>
                <td></td>
              </tr>
            </template>
          </tbody>
        </template>
      </v-data-table>
    </div>
    <div class="table-controls">
      <div class="controls-list" v-if="actions.length">
          <div class="controls-list-item" v-for="(val, index) in actions" :key="index">

            <button class="control-btn" type="button" v-if="val.type === 'copy'">
              <span class="icon">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M19.0332 6.12219V19.2548H5.87422V6.12219H19.0332ZM19.0332 4.663H5.87422C5.48645 4.663 5.11455 4.81674 4.84035 5.09039C4.56615 5.36404 4.41211 5.73519 4.41211 6.12219V19.2548C4.41211 19.6418 4.56615 20.013 4.84035 20.2866C5.11455 20.5603 5.48645 20.714 5.87422 20.714H19.0332C19.421 20.714 19.7929 20.5603 20.0671 20.2866C20.3413 20.013 20.4953 19.6418 20.4953 19.2548V6.12219C20.4953 5.73519 20.3413 5.36404 20.0671 5.09039C19.7929 4.81674 19.421 4.663 19.0332 4.663Z"
                      fill="white"/>
                  <path
                      d="M1.4875 11.9588H0.0253906V1.74454C0.0253906 1.35754 0.179434 0.98639 0.453633 0.71274C0.727832 0.43909 1.09973 0.285355 1.4875 0.285355H11.7223V1.74454H1.4875V11.9588Z"
                      fill="white"/>
                </svg>
              </span>
            </button>

            <button :disabled="selectedItems.length !== 1" @click="onClickChange" class="control-btn" type="button" v-if="val.type === 'edit'">
              <span class="icon">
                <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M21.3953 5.06823L17.6047 1.28516L2.44205 16.4174L0.925781 21.7137L6.23271 20.2005L21.3953 5.06823ZM14.5722 4.31161L18.3628 8.09468L14.5722 4.31161ZM2.44205 16.4174L6.23271 20.2005L2.44205 16.4174Z"
                      stroke="white" stroke-width="1.39565" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
            </button>

            <button :disabled="selectedItems.length < 1" @click="onClickDel" class="control-btn" type="button" v-if="val.type === 'delete'">
              <span class="icon">
                <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6.64453 7.58203H8.10664V16.3371H6.64453V7.58203Z" fill="white"/>
                  <path d="M11.0312 7.58203H12.4934V16.3371H11.0312V7.58203Z" fill="white"/>
                  <path
                      d="M0.796875 3.20312V4.66231H2.25899V19.2541C2.25899 19.6411 2.41303 20.0123 2.68723 20.2859C2.96143 20.5596 3.33332 20.7133 3.7211 20.7133H15.418C15.8058 20.7133 16.1777 20.5596 16.4519 20.2859C16.7261 20.0123 16.8801 19.6411 16.8801 19.2541V4.66231H18.3422V3.20312H0.796875ZM3.7211 19.2541V4.66231H15.418V19.2541H3.7211Z"
                      fill="white"/>
                  <path d="M6.64453 0.285156H12.493V1.74434H6.64453V0.285156Z" fill="white"/>
                </svg>
              </span>
            </button>

          </div>
      </div>
      <div class="controls-scroll-top">
        <button type="button" class="btn-top" @click="onToTop">
              <span class="icon">
                <svg width="17" height="8" viewBox="0 0 17 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M7.83666 0.217211L0.271583 6.27705C0.0964707 6.4172 -6.10079e-08 6.6043 -5.22877e-08 6.8038C-4.35674e-08 7.00329 0.0964707 7.19039 0.271583 7.33055L0.82857 7.77681C1.19151 8.0672 1.78139 8.0672 2.14378 7.77681L8.49647 2.68822L14.8562 7.78246C15.0313 7.92261 15.2648 8 15.5137 8C15.7629 8 15.9963 7.92261 16.1716 7.78246L16.7284 7.33619C16.9035 7.19593 17 7.00894 17 6.80944C17 6.60995 16.9035 6.42285 16.7284 6.28269L9.15643 0.217211C8.98076 0.0767225 8.74622 -0.000440703 8.49689 1.80479e-06C8.24659 -0.000440697 8.01219 0.0767225 7.83666 0.217211Z"/>
                </svg>
              </span>
        </button>
      </div>
    </div>
    <vue-context class="context-menu" ref="menu" :close-on-click="closeOnClick" v-slot="{ data }">
      <li class="context-menu-item" v-for="(val, index) in actions" :key="index">
        <template v-if="val.type === 'copy'">
          <div class="item-icon">
            <svg width="13" height="13" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M14.8567 4.5725V14.8578H4.57151V4.5725H14.8567ZM14.8567 3.42969H4.57151C4.26842 3.42969 3.97775 3.55009 3.76343 3.76441C3.54911 3.97873 3.42871 4.26941 3.42871 4.5725V14.8578C3.42871 15.1609 3.54911 15.4516 3.76343 15.6659C3.97775 15.8802 4.26842 16.0006 4.57151 16.0006H14.8567C15.1598 16.0006 15.4505 15.8802 15.6648 15.6659C15.8791 15.4516 15.9995 15.1609 15.9995 14.8578V4.5725C15.9995 4.26941 15.8791 3.97873 15.6648 3.76441C15.4505 3.55009 15.1598 3.42969 14.8567 3.42969Z"
                  fill="#F4F9FF"/>
              <path
                  d="M1.1428 9.1425H0V1.14281C0 0.83972 0.120402 0.549041 0.334719 0.334722C0.549035 0.120403 0.839711 0 1.1428 0H9.14241V1.14281H1.1428V9.1425Z"
                  fill="#F4F9FF"/>
            </svg>
          </div>
          <a @click.prevent="onClickContextCopy($event, data)">Копировать</a>
        </template>
        <template v-if="val.type === 'edit'">
          <div class="item-icon">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M12.252 3.03704L10.2149 1L2.06677 9.14815L1.25195 12L4.1038 11.1852L12.252 3.03704ZM8.58529 2.62963L10.6223 4.66667L8.58529 2.62963ZM2.06677 9.14815L4.1038 11.1852L2.06677 9.14815Z"
                  stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <a @click.prevent="onClickContextChange($event, data)">Редактировать</a>
        </template>
        <template v-if="val.type === 'delete'">
          <div class="item-icon">
            <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3.14258 3.92822H3.92829V8.64251H3.14258V3.92822Z" fill="white"/>
              <path d="M5.5 3.92822H6.28571V8.64251H5.5V3.92822Z" fill="white"/>
              <path
                  d="M0 1.57153V2.35725H0.785714V10.2144C0.785714 10.4228 0.868495 10.6226 1.01584 10.77C1.16319 10.9173 1.36304 11.0001 1.57143 11.0001H7.85714C8.06553 11.0001 8.26538 10.9173 8.41273 10.77C8.56008 10.6226 8.64286 10.4228 8.64286 10.2144V2.35725H9.42857V1.57153H0ZM1.57143 10.2144V2.35725H7.85714V10.2144H1.57143Z"
                  fill="white"/>
              <path d="M3.14258 0H6.28543V0.785714H3.14258V0Z" fill="white"/>
            </svg>
          </div>
          <a @click.prevent="onClickContextDelete($event, data)">Удалить</a>
        </template>
      </li>
      <!-- <li class="context-menu-item">
        <div class="item-icon">
          <svg width="13" height="13" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M14.8567 4.5725V14.8578H4.57151V4.5725H14.8567ZM14.8567 3.42969H4.57151C4.26842 3.42969 3.97775 3.55009 3.76343 3.76441C3.54911 3.97873 3.42871 4.26941 3.42871 4.5725V14.8578C3.42871 15.1609 3.54911 15.4516 3.76343 15.6659C3.97775 15.8802 4.26842 16.0006 4.57151 16.0006H14.8567C15.1598 16.0006 15.4505 15.8802 15.6648 15.6659C15.8791 15.4516 15.9995 15.1609 15.9995 14.8578V4.5725C15.9995 4.26941 15.8791 3.97873 15.6648 3.76441C15.4505 3.55009 15.1598 3.42969 14.8567 3.42969Z"
                fill="#F4F9FF"/>
            <path
                d="M1.1428 9.1425H0V1.14281C0 0.83972 0.120402 0.549041 0.334719 0.334722C0.549035 0.120403 0.839711 0 1.1428 0H9.14241V1.14281H1.1428V9.1425Z"
                fill="#F4F9FF"/>
          </svg>
        </div>
        <a @click.prevent="onClickContextCopy($event, data)">Копировать</a>
      </li>
      <li class="context-menu-item">
        <div class="item-icon">
          <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M12.252 3.03704L10.2149 1L2.06677 9.14815L1.25195 12L4.1038 11.1852L12.252 3.03704ZM8.58529 2.62963L10.6223 4.66667L8.58529 2.62963ZM2.06677 9.14815L4.1038 11.1852L2.06677 9.14815Z"
                stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <a @click.prevent="onClickContextChange($event, data)">Редактировать</a>
      </li>
      <li class="context-menu-item">
        <div class="item-icon">
          <svg width="10" height="11" viewBox="0 0 10 11" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3.14258 3.92822H3.92829V8.64251H3.14258V3.92822Z" fill="white"/>
            <path d="M5.5 3.92822H6.28571V8.64251H5.5V3.92822Z" fill="white"/>
            <path
                d="M0 1.57153V2.35725H0.785714V10.2144C0.785714 10.4228 0.868495 10.6226 1.01584 10.77C1.16319 10.9173 1.36304 11.0001 1.57143 11.0001H7.85714C8.06553 11.0001 8.26538 10.9173 8.41273 10.77C8.56008 10.6226 8.64286 10.4228 8.64286 10.2144V2.35725H9.42857V1.57153H0ZM1.57143 10.2144V2.35725H7.85714V10.2144H1.57143Z"
                fill="white"/>
            <path d="M3.14258 0H6.28543V0.785714H3.14258V0Z" fill="white"/>
          </svg>
        </div>
        <a @click.prevent="onClickContextDelete($event, data)">Удалить</a>
      </li> -->
    </vue-context>
    <div class="total-quantity">
      <span>Количество элементов: {{ total }}</span>
      <span>Выбрано элементов: {{ selectedItems.length }}</span>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex"
import ClickOutside from 'vue-click-outside'
import axios from 'axios'
import { PROTOCOL } from '@/domain'

Array.prototype.move = function (from, to) {
  this.splice(to, 0, this.splice(from, 1)[0])
  return this
}

export default {
  name: "EmployeesTable",
  props: {
    total: Number,
    header: Array,
    body: Array,
    resource: String,
    actions: Array
  },
  data: () => ({
    selectedItems: [],
    selected: false,
    sortDesc: false,
    sortBy: null,
    favorite: null,
    settingsFlag: false,
    drag: false,
    closeOnClick: false,
    rotating: false,
    settingsTable: {
      copyHeader: null,
      header: null,
      body: null,
      transformHeaders: null,
    }
  }),
  computed: {
    ...mapGetters(['loading', 'paginationCounter']),
    dragOptions() {
      return {
        animation: 200,
        group: "description",
        disabled: false,
        ghostClass: "ghost"
      }
    },
    isEmpty() {
      return !this.body.length
    },
    computedHeader: {
      get() {
        return [...this.settingsTable.header].sort((a, b) => a.order - b.order)
      },
      set(value) {
        console.log('computed property value', value)
        return value
      }
    },
    formatHeader() {
      if (this.header) {
        return [...this.header].filter(item => item.is_visible).sort((a, b) => a.order - b.order)
      }
      return []
    },
  },
  methods: {
    ...mapActions(['getPaginationItems', 'getPaginationCounter']),
    transformHeadersHandler(headers) {
      let queryKeys = ['id', 'is_visible', 'order']
      let headersQuery = {data: []}
      headers.forEach(item => {
        let obj = Object.keys(item)
            .filter(key => queryKeys.includes(key))
            .reduce((obj, key) => {
              return {
                ...obj,
                [key]: item[key]
              }
            }, {})
        headersQuery.data.push(obj)
      })
      this.settingsTable.transformHeaders = headersQuery
    },
    toggleAll() {
      this.selectedItems = []
      if (!this.selected) {
        this.body.forEach(item => this.selectedItems.push(item.id))
      }
    },
    toggleVisible(item) {
      item.cells.is_visible = !item.cells.is_visible
      this.$emit('toggleVisible', {id: item.id, is_visible:item.cells.is_visible})
    },
    selectItem(val) {
      const index = this.selectedItems.findIndex(item => item.id === val.id)
      if (index === -1) this.selectedItems.push(val)
      else this.selectedItems.splice(index, 1)
    },
    toggleOrder(e, column) {
      this.sortDesc = !this.sortDesc
      this.sortBy = column
      console.log(e, this.sortDesc, this.sortBy)
    },
    onClickDel() {
      this.$emit('deleteSelected', [...this.selectedItems])
      console.log('selectedItems', this.selectedItems)
      this.selected = false
    },
    onClickChange() {
      const item = this.body.find(item => item.id === this.selectedItems[0].id)
      this.onClickRow(item)
    },
    onClickContextChange(e, { val }) {
      this.$emit('clickRow', val)
      this.selected = false
    },
    onClickContextDelete(e, { val }) {
      this.$emit('deleteSelected', [val])
    },
    onClickContextCopy(e, data) {
      console.log('Copy', e.target, data)
    },
    onClickRow(val) {
      // console.log(val)
      this.$emit('clickRow', val)
      // this.$emit('changeItem', val)
    },
    clearChecked() {
      this.selectedItems = []
      this.selected = false
    },
    showSettings() {
      this.settingsTable.header = JSON.parse(JSON.stringify(this.header))
      this.settingsFlag = !this.settingsFlag
    },
    hideOutsideSettings() {
      this.settingsFlag = false
    },
    move(to, from) {
      this.computedHeader.move(from, to)
      this.computedHeader.forEach((item, index) => {
        item.order = index
      })
    },
    formatDate(date) {
      if (!date) return null
      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },
    async resetHeaders() {
      this.rotating = true
      await this.$store.dispatch('resetHeaders', { resource: this.resource })
      this.rotating = false
      this.settingsFlag = !this.settingsFlag
    },
    async saveTableSettings() {
      this.transformHeadersHandler(this.settingsTable.header)
      const token = JSON.parse(localStorage.getItem('token')),
          domain = JSON.parse(localStorage.getItem('domain')),
          data = this.settingsTable.transformHeaders
      this.header = [...this.settingsTable.header]
      console.log(data, domain, token)
      this.hideOutsideSettings()
      try {
        await axios.put(`${PROTOCOL}://${domain}/api/v1/directories/${this.resource}/headers`,
            {...data},
            {headers: {'Authorization': `Bearer ${token}`}}
        )
      } catch (e) {
        console.log(e)
      }
    },
    checkMove(e) {
      this.move(e.newIndex, e.oldIndex)
    },
    scrollPagination() {
      const ref = this.$refs.table.$el
      const listElm = ref.querySelector('.v-data-table__wrapper')

      listElm.addEventListener('scroll', () => {
        if(listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight) {
          this.pagination()
          console.log('scrolledToEnd')
        }
      })
    },
    pagination() {
      const isAmountPage = +this.total > (this.paginationCounter*10)
      console.log('isAmountPage', isAmountPage)
      if(isAmountPage) {
        this.getPaginationCounter()
        this.$emit('scrolledToEnd', this.paginationCounter)
        console.log('getPaginationCount')
      }
    },
    onToTop() {
      const ref = this.$refs.table.$el
      const scrollBlock = ref.querySelector('.v-data-table__wrapper')
      scrollBlock.scrollTo({ top: 0, left: 0, behavior: "smooth" })
    },
  },
  mounted() {
    this.scrollPagination()
  },
  directives: {
    ClickOutside
  }
}
</script>
<style lang="sass" scoped>

.thTable-settings
  position: absolute
  right: 0
  top: 2px
  background: none !important
  &:after
    content: ''
    position: absolute
    top: 0
    right: 0
    left: 0
    bottom: 0
    background: rgba(255, 255, 255, .5) !important
  .table-settings
    z-index: 1

.user-icon
  display: block
  text-align: center

.status-user
  display: block
  font-size: 11px
  line-height: 15px
  font-weight: 600
  background: #E0E0E0
  border-radius: 5px
  text-transform: uppercase
  padding: 6px 5px
  width: max-content
  margin: auto
  color: #828282
  &--active
    background: #A7FD9F
    color: #1E8216
  
.settings-popup
  max-height: 450px

.fade-enter-active,
.fade-leave-active
  transition: .25s ease-in-out

.fade-enter,
.fade-leave-to
  opacity: 0

.flip-list-move
  transition: transform .5s

.table-switcher
  display: flex
  align-items: center
  justify-content: center

  .switcher
    width: 33px
    height: 18px
    border: 1px solid #9DCCFF

    box-sizing: border-box
    border-radius: 50px
    position: relative

    &-caret
      width: 12px
      height: 12px
      background: #9DCCFF
      //background: #4ECA80
      border-radius: 50%
      position: absolute
      left: 5%
      top: 50%
      transform: translateY(-50%)
      transition: all .5s


    &.active
      border: 1px solid #7CE1A4

      .switcher-caret
        transform: translateY(-50%) translateX(16px)
        background: #4ECA80
        border: 1px solid #7CE1A4


</style>
