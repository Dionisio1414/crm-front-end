<template>
  <div class="add-value">
    <input v-model="value_title" type="text" placeholder="Введите назваине">
    <button
        type="button"
        :disabled="!value_title"
        class="add-value-btn"
        @click="addValue"
    >
      Добавить
    </button>
  </div>
</template>

<script>

export default {
  name: "addValue",
  data() {
    return {
      value_title: '',
    }
  },
  methods: {
    addValue (){
      this.$emit('add-value', this.value_title)
    },
  },
}
</script>

<style scoped lang="scss">
.add-value {
  width: 300px;
  padding: 30px 20px 20px;
  background: #FFFFFF;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
  border-radius: 10px;
  position: absolute;
  z-index: 1;
  bottom: 0;
  transform: translateY(110%);
  input {
    margin-bottom: 20px;
  }
  &-btn {
    font-size: 13px;
    line-height: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9DCCFF;
    border: 1px solid #9DCCFF;
    border-radius: 32px;
    max-width: 160px;
    height: 36px;
    margin: 0 auto;
    width: 100%;
    cursor: pointer;
    &:hover {
      color: #fff;
      background-color: #9DCCFF;
    }
    &:disabled {
      opacity: 0.7;
      pointer-events: none;
    }
  }
}
</style>