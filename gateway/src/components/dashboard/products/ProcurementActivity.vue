<template>
  <div class="card">
    <div class="card-title">
      <div class="title">Активность по закупкам</div>
    </div>
    <div class="card-content">
      <v-data-table
          :headers="headers"
          :items="orders"
          class="elevation-1"
          hide-default-footer
          :items-per-page="13"
      >
        <template v-slot:item.status="{ item }">
          <v-chip :class="[item.fat > 10 ? 'canceled': '', 'status']" :color="getColor(item.fat)" >{{ item.status }}</v-chip>
        </template>
        <template v-slot:item.actions="{ item }">
          <div
              class="mr-2 table-action"
              @click="editItem(item)"
          >
            <svg width="5" height="19" viewBox="0 0 5 19" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="2.5" cy="2.5" r="2.5" fill="#E3F0FF"/>
              <circle cx="2.5" cy="9.5" r="2.5" fill="#E3F0FF"/>
              <circle cx="2.5" cy="16.5" r="2.5" fill="#E3F0FF"/>
            </svg>
          </div>
        </template>
      </v-data-table>
      <div class="see-all">
        <router-link to="/" class="see-all-link">
          <span>Смотреть все</span>
          <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.83709 5.07078L1.29221 0.17573C1.1871 0.0624223 1.04677 0 0.897152 0C0.74753 0 0.607207 0.0624223 0.50209 0.17573L0.167391 0.536134C-0.0503999 0.770977 -0.0503999 1.15267 0.167391 1.38715L3.98384 5.49772L0.163156 9.61285C0.0580385 9.72616 -3.12529e-07 9.8772 -3.17625e-07 10.0383C-3.22727e-07 10.1995 0.0580385 10.3506 0.163156 10.464L0.497855 10.8243C0.603055 10.9376 0.743295 11 0.892917 11C1.04254 11 1.18286 10.9376 1.28798 10.8243L5.83709 5.92475C5.94246 5.81108 6.00033 5.65932 6 5.49799C6.00033 5.33603 5.94246 5.18436 5.83709 5.07078Z" fill="#FF9F2F"/>
          </svg>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ProcurementActivity",
  data: () => ({
    dialog: false,
    headers: [
      {text: 'Дата', align: 'start', sortable: false, value: 'name',},
      { text: 'Статус', value: 'status', sortable: false, },
      { text: 'Номер', value: 'fat', sortable: false, },
      { text: 'Покупатель', value: 'carbs', sortable: false, },
      { text: 'Склад', value: 'protein', sortable: false, },
      { text: '', value: 'actions', sortable: false },
    ],
    orders: [],
    editedIndex: -1,
  }),

  computed: {

  },

  created () {
    this.initialize()
  },

  methods: {
    getColor (fat) {
      if(fat > 10) return '#FFE6E6'
      else return '#C2FFE1'
    },
    initialize () {
      this.orders = [
        {
          name: '30.07.2020',
          status: 'получен',
          fat: 6.0,
          carbs: 'УкрСнабСбыт',
          protein: 4.0,
        },
        {
          name: '30.07.2020',
          status: 'получен',
          fat: 9.0,
          carbs: 'УкрСнабСбыт',
          protein: 4.3,
        },
        {
          name: '30.07.2020',
          status: 'отменен',
          fat: 16.0,
          carbs: 'УкрСнабСбыт',
          protein: 6.0,
        },
        {
          name: '30.07.2020',
          status: 'получен',
          fat: 3.7,
          carbs: 'УкрСнабСбыт',
          protein: 4.3,
        },
        {
          name: '30.07.2020',
          status: 'отменен',
          fat: 16.0,
          carbs: 49,
          protein: 3.9,
        },
        {
          name: '30.07.2020',
          status: 'получен',
          fat: 0.0,
          carbs: 94,
          protein: 0.0,
        },
        {
          name: '30.07.2020',
          status: 'получен',
          fat: 0.2,
          carbs: 98,
          protein: 0,
        },
        {
          name: '30.07.2020',
          status: 'получен',
          fat: 3.2,
          carbs: 87,
          protein: 6.5,
        },
        {
          name: '30.07.2020',
          status: 'отменен',
          fat: 25.0,
          carbs: 51,
          protein: 4.9,
        },
        {
          name: '30.07.2020',
          status: 'отменен',
          fat: 26.0,
          carbs: 65,
          protein: 7,
        },
        {
          name: '30.07.2020',
          status: 'отменен',
          fat: 26.0,
          carbs: 65,
          protein: 7,
        },
        {
          name: '30.07.2020',
          status: 'отменен',
          fat: 26.0,
          carbs: 65,
          protein: 7,
        },
        {
          name: '30.07.2020',
          status: 'отменен',
          fat: 26.0,
          carbs: 65,
          protein: 7,
        },
      ]
    },

    editItem (item) {
      console.log(item)
    },

  }
}
</script>
