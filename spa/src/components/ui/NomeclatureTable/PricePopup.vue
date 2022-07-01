<template>
    <div class="price-value" v-click-outside="onClickOutside" @click.stop="onClickPrice">
        {{ priceValue}} {{options.currency_title || ''}}
        <span v-if="priceValue">
                     <svg  @click.stop="onClickEditPrice" width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 3.77778L13.2222 1L2.11111 12.1111L1 16L4.88889 14.8889L16 3.77778ZM11 3.22222L13.7778 6L11 3.22222ZM2.11111 12.1111L4.88889 14.8889L2.11111 12.1111Z"
                          stroke="#BBDBFE" stroke-width="1.39565" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                </span>
        <div class="edit-price" v-if="priceIsEdit" @click.stop>
            <div class="edit-price-form">
                <input min="0.01" @input="onChangeInput" @keypress.enter="onChangePrice" type="number" v-model="localePrice">
                <span class="price-currency">{{options.currency_title || ''}}</span>
            </div>
            <v-btn :disabled="isDisabled || !localePrice" @click="onChangePrice" class="base-btn"
            >{{ $t("page.saveButton") }}
            </v-btn>

        </div>
    </div>
</template>

<script>
    export default {
        name: "PricePopup",
        props: {
            priceValue: Number,
            options: Object,
            priceName: String,
        },
        data () {
          return {
              priceIsEdit: false,
              localePrice: '',
              isDisabled: true,
          }
        },
        created() {
          this.localePrice = this.priceValue
        },
        methods: {
            onClickPrice () {
              this.$emit('clickPrice')
            },
            onChangeInput( ){
              this.isDisabled = false
            },
            onClickEditPrice () {
                this.localePrice = this.priceValue
                this.priceIsEdit = true
                this.isDisabled = true
            },
            onClickOutside () {
                this.priceIsEdit = false
                this.localePrice = this.priceValue
            },
            onChangePrice () {
                this.priceIsEdit = false
                console.log('popap change price');
                this.$emit('changePrice', {
                    value: this.localePrice,
                    priceId: this.options.id,
                    price: this.priceName,
                    currency: this.options.currency_title
                },)
            },
        }

    }
</script>

<style scoped lang="sass">
    .edit-price-form
        margin-bottom: 15px
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button
            -webkit-appearance: none
            margin: 0
    .price-value
        position: relative
        display: flex
        align-items: center
        justify-content: center
        svg
            opacity: 0
            pointer-events: none
            margin-left: 10px
            cursor: pointer
            transition: .5s all linear
            &:hover
                transform: scale(1.1)
        &:hover
            svg
                opacity: 1
                pointer-events: all
    .edit-price
        position: absolute
        width: 175px
        background: #FFFFFF
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1)
        border-radius: 10px
        top: 100%
        left: 0
        display: flex
        align-items: flex-start
        justify-content: center
        padding: 20px
        z-index: 2
        font-size: 15px
        line-height: 15px
        color: #7E7E7E
        flex-wrap: wrap
        .base-btn
            width: 100%
        .price-currency
            margin-left: 5px

        input
            padding: 10px
            outline: none !important
            text-align: center
            max-width: 106px
            font-size: 15px
            line-height: 15px
            color: #7E7E7E
            border: 1px solid #E3F0FF
            box-sizing: border-box
            border-radius: 100px


</style>