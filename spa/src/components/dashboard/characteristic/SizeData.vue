<template>
  <form class="item-form" v-if="characteristic">
    <div class="item-name">
      <label>{{ $t("page.name") }}</label>
      <input
        @input="changeName"
        type="text"
        :placeholder="$t('page.typeName')"
        v-model.trim="characteristicName"
        :disabled="characteristic.disabledEdit"
      />
      <small class="warn" v-if="clone && clone.id == characteristicEdit.id"
      >Измените название характеристики</small
      >
      <small class="warn" v-if="exists"
      >Характеристика <b>{{ characteristicName }}</b> уже существует
      </small>
    </div>
    <div class="item-values">
      <label v-if="characteristic.id === 2">Выберите размер</label>
      <label v-else>{{ $t("page.addValueVariant") }}</label>
      <input
        v-if="characteristic.id !== 2"
        @input="checkValue"
        type="text"
        :placeholder="$t('page.typeName')"
        v-model="itemValue.name"
        @keypress.enter="saveChip"
      />
      <small class="warn" v-if="valueExists"
      >Значения свойства <b>{{ itemValue.name }}</b> уже существует
      </small>
      <div class="selection-bar" v-if="characteristic.id === 1">
        <v-btn class="select-btn btn-file" @click="selectFile">
          <div class="text">Выберете файл</div>
          <input
            type="file"
            accept="image/png, image/jpeg, image/bmp"
            @change="previewImage"
          />
        </v-btn>
        <v-menu
          transition="slide-y-transition"
          bottom
          :close-on-content-click="closeOnContentClick"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              class="select-btn"
              v-bind="attrs"
              v-on="on"
              @click="selectColor"
            >
              <div class="text">Выбрать цвет</div>
            </v-btn>
          </template>
          <v-color-picker
            hide-mode-switch
            v-model="selectValue"
            show-swatches
          ></v-color-picker>
        </v-menu>
        <div class="select-value" v-if="visibleSelectValue">
          <span>Загружено / Выбрано: </span>
          <div
            class="select-style"
            :style="{ background: selectStyle2 }"
          ></div>
          <svg
            @click="clearSelectValue"
            width="12"
            height="13"
            viewBox="0 0 12 13"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z"
              fill="#BBDBFE"
            />
          </svg>
        </div>
      </div>
      <div class="vendor-code" v-if="characteristic.id === 1">
        <div class="vendor-code-header">
          <input
            type="checkbox"
            id="vendor"
            :checked="vendor"
            v-model="vendor"
          />
          <label for="vendor">Артикул значения</label>
        </div>
        <input
          :disabled="!vendor"
          type="text"
          placeholder="00"
          v-model="vendorCode"
        />
      </div>
      <div class="size-grid" v-if="characteristic.id === 2">
        <div class="digital-sizes size-item">
          <div class="size-item-title">Цифровы размеры</div>
          <simplebar class="size-item-content">
            <div class="check-all">
              <input type="checkbox" id="all-digital-sizes" />
              <label for="all-digital-sizes">Все</label>
            </div>
            <ul class="list-group">
              <li class="list-group-item">
                <input type="checkbox" />
                <label>36</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>37</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>38</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>39</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>40</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>41</label>
              </li>
            </ul>
          </simplebar>
        </div>
        <div class="character-sizes size-item">
          <div class="size-item-title">Символьные размеры</div>
          <simplebar class="size-item-content">
            <div class="check-all">
              <input type="checkbox" id="all-character-sizes" />
              <label for="all-character-sizes">Все</label>
            </div>
            <ul class="list-group">
              <li class="list-group-item">
                <input type="checkbox" />
                <label>36</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>37</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>38</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>39</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>40</label>
              </li>
              <li class="list-group-item">
                <input type="checkbox" />
                <label>41</label>
              </li>
            </ul>
          </simplebar>
        </div>
      </div>
      <v-btn
        v-if="characteristic.id !== 2"
        @click="saveChip"
        :disabled="!itemValue.name.length"
        class="base-btn"
      >{{ $t("page.addButton") }}</v-btn
      >
    </div>
  </form>
  <div class="values-list" :class="{'value-list-color' : characteristic.id === 1 }">
    <div
      class="value-item"
      v-for="(value, i) in values"
      :key="i"
      :class="{
          active: value.name.toLowerCase() === itemValue.name.toLowerCase(),
        }"
    >
      <span>{{ value.name }}</span>
      <span v-if="value.code">({{ value.code }})</span>
      <span
        class="select-style"
        :style="{ background: value.selectValue }"
      ></span>
      <svg
        v-if="characteristic.id === 1"
        class="edit"
        @click="editChip(value, i)"
        width="17"
        height="18"
        viewBox="0 0 17 18"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M16 4.27778L13.2222 1.5L2.11111 12.6111L1 16.5L4.88889 15.3889L16 4.27778ZM11 3.72222L13.7778 6.5L11 3.72222ZM2.11111 12.6111L4.88889 15.3889L2.11111 12.6111Z"
          stroke="#BBDBFE"
          stroke-width="1.39565"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>

      <svg
        @click="deleteChip(value, i)"
        class="close"
        width="12"
        height="13"
        viewBox="0 0 12 13"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z"
          fill="#BBDBFE"
        />
      </svg>
    </div>
  </div>
  <div class="used">
    <div class="title">{{ $t("page.usedCategories") }}</div>
    <div class="used-list">
      <router-link to="/">
        <span>Ноутбуки</span>
        <svg
          width="6"
          height="11"
          viewBox="0 0 6 11"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M5.83709 5.07078L1.29221 0.175731C1.1871 0.0624225 1.04677 1.83024e-07 0.897152 1.56863e-07C0.74753 1.30702e-07 0.607207 0.0624224 0.50209 0.17573L0.167391 0.536134C-0.0504 0.770977 -0.0504001 1.15267 0.167391 1.38715L3.98383 5.49772L0.163154 9.61285C0.0580368 9.72616 -2.03951e-06 9.8772 -2.07277e-06 10.0383C-2.10607e-06 10.1995 0.0580367 10.3506 0.163154 10.464L0.497853 10.8243C0.603053 10.9376 0.743293 11 0.892915 11C1.04254 11 1.18286 10.9376 1.28798 10.8243L5.83709 5.92475C5.94246 5.81108 6.00033 5.65932 6 5.49799C6.00033 5.33603 5.94246 5.18436 5.83709 5.07078Z"
            fill="#BBDBFE"
          />
        </svg>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
name: "SizeData"
}
</script>

<style scoped>

</style>
