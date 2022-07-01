<template>
  <div class="contactFaceItem">
    <div class="contactFaceItem_title">
      <span v-if="position">{{ position }}</span>
      <div class="edit-btn">
        <button class="btn" @click="onEdit">
          <svg-sprite width="14" height="14" icon-id="editSmall"></svg-sprite>
        </button>
        <button v-if="isDataSafe" class="btn" @click="onRemoveItem">
          <svg-sprite width="14" height="14" icon-id="crossSmall"></svg-sprite>
        </button>
      </div>
    </div>
    <div class="name">
      {{ getFullName }}
      <span v-if="getSex" class="sex">({{ getSex }})</span>
    </div>
    <div>
      <table>
        <tr>
          <td v-if="birthdayDay">{{ birthdayDay }}</td>
          <td v-if="document && number">{{ document }}: {{ number }}</td>
        </tr>
        <tr>
          <td v-if="mobilePhone">{{ mobilePhone }}</td>
          <td v-if="email">{{ email }}</td>
        </tr>
        <tr v-if="sourceOfAttraction">
          <td>{{ $t('page.suppliers.modal.createSupplier.thirdForm.sourceOfAttraction') }}: </td>
          <td>{{ sourceOfAttraction }}</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
export default {
 name: "ContactFace",
  computed: {
   getFullName() {
     return `${this.surname} ${this.name} ${this.patronymic}`
   },
    getSex() {
     return this.sex && this.sex.slice(0,1).toLowerCase();
    },
  },
  props: {
    name: {
      type: String,
      default: ''
    },
    surname: {
      type: String,
      default: ''
    },
    patronymic: {
      type: String,
      default: ''
    },
    position: {
      type: String,
      default: ''
    },
    mobilePhone: String,
    email: String,
    sex: {
      type: String,
      default: ''
    },
    document: {
      type: String,
      default: ''
    },
    number: String,
    sourceOfAttraction: {
      type: String,
      default: ''
    },
    birthdayDay: String,
    idx: Number,
    id: String,
    isDataSafe: Boolean
  },
  methods: {
   onRemoveItem() {
     this.$emit('updateRemoveItem', this.idx)
   },
    onEdit() {
      this.$emit('updateEdit', this.id)
    }
  },
  mounted() {
    console.log(this.position,'position')
  }
}
</script>

<style scoped lang="scss">
  .contactFaceItem {
    border-bottom: 1px solid #F4F9FF;
    margin-bottom: 20px;

    .edit-btn {
      display: flex;
      align-items: center;
      margin-left: auto;

      .btn {
        margin: 0 5px;
        display: inline-block;
      }
    }

    &_title {
      font-weight: bold;
      font-size: 13px;
      line-height: 13px;
      letter-spacing: 0.02em;
      text-transform: uppercase;
      color: #317CCE;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .name {
      font-weight: 600;
      font-size: 14px;
      line-height: 19px;
      color: #5893D4;
      margin-bottom: 10px;
    }

    td {
      font-size: 14px;
      line-height: 14px;
      color: #7E7E7E;
      padding: 5px 0;
    }
  }
</style>
