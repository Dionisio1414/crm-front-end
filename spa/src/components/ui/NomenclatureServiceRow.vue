<template>
    <tr :key="item.id"
        v-click-outside="onClickOutside"
        @contextmenu.prevent="openMenu($event)"
        :class="{'selected': selected}">
        <td>
            <div class="wrapper">

                <div class="checkbox" @click.stop="(() => false)">
                    <label class="checkbox-label">
                        <input type="checkbox" @change="selectItem(item.id )" v-model="selected" :checked="selected">
                        <div class="checkbox-text"></div>
                    </label>
                </div>
                <span class="icon">
        <svg width="21" height="25" viewBox="0 0 21 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
                  d="M20.6869 17.4633C20.4951 17.3616 18.7832 16.501 17.7694 17.5758C17.7347 17.6126 17.7 17.651 17.6656 17.6907C16.7572 17.4344 15.9993 17.574 15.5053 18.0975C14.9028 18.7361 14.3265 19.7879 14.0435 20.3431L13.0057 20.5631L10.2279 18.6H12.132C13.2701 18.6 14.1535 18.056 14.6194 17.0683C14.8317 16.6106 14.9486 16.1098 14.9621 15.6C14.9621 15.4409 14.9025 15.2883 14.7963 15.1757C14.6902 15.0632 14.5462 15 14.3961 15H1.56603C1.41591 15 1.27194 15.0632 1.16579 15.1757C1.05964 15.2883 1 15.4409 1 15.6C1 15.7591 1.05964 15.9117 1.16579 16.0243C1.27194 16.1368 1.41591 16.2 1.56603 16.2H13.731C13.6954 16.325 13.649 16.4462 13.5923 16.5622C13.3141 17.126 12.8364 17.4 12.132 17.4H8.35842C7.80362 17.3994 7.58277 18.1738 8.04446 18.4992L12.5727 21.6992C12.6346 21.7429 12.7039 21.7733 12.7769 21.7886C12.8498 21.8039 12.9248 21.8038 12.9977 21.7883L16.7713 20.9883C16.8558 20.9704 16.9353 20.9323 17.0038 20.8768C17.0722 20.8214 17.1279 20.7501 17.1664 20.6683C17.1513 20.6698 18.0063 18.9803 18.5699 18.4241C18.7966 18.1838 19.2517 18.2209 19.6451 18.3301L17.7798 22.285L12.1242 23.7837L5.46222 22.2143C5.42196 22.2048 5.38083 22.2 5.33958 22.2H1.56603C1.41591 22.2 1.27194 22.2632 1.16579 22.3757C1.05964 22.4883 1 22.6409 1 22.8C1 22.9591 1.05964 23.1117 1.16579 23.2243C1.27194 23.3368 1.41591 23.4 1.56603 23.4H5.27746L12.0093 24.9857C12.0951 25.0059 12.1842 25.0047 12.2694 24.9821L18.3071 23.3821C18.3863 23.3611 18.4603 23.3222 18.524 23.268C18.5877 23.2139 18.6396 23.1458 18.6761 23.0683L20.9402 18.2683C20.9735 18.1979 20.9933 18.1211 20.9986 18.0425C21.0038 17.9639 20.9945 17.885 20.9709 17.8103C20.9474 17.7355 20.9103 17.6664 20.8616 17.6069C20.8129 17.5474 20.7534 17.4986 20.6869 17.4633ZM16.2839 19.8679L15.5301 20.0279C15.7827 19.6084 16.0561 19.2105 16.3058 18.9459C16.4473 18.7959 16.669 18.7569 16.8958 18.7682C16.6319 19.1987 16.4139 19.6111 16.2839 19.8679Z"
                  fill="#5893D4"/>
          <path d="M4 15.5V23" stroke="#5893D4"/>
          <path d="M1 14L1 25" stroke="#5893D4"/>
          <path
                  d="M7.75793 7.95241L8.51341 8.0459L8.70399 8.50604L8.2361 9.10636C8.14927 9.21772 8.10615 9.35698 8.11483 9.49792C8.12352 9.63886 8.18343 9.77176 8.28327 9.87161L9.8785 11.4669C9.97835 11.5667 10.1112 11.6266 10.2522 11.6353C10.3931 11.644 10.5324 11.6009 10.6437 11.5141L11.244 11.0462L11.7042 11.2367L11.7976 11.9922C11.815 12.1324 11.883 12.2614 11.9888 12.3549C12.0946 12.4484 12.2309 12.5 12.3721 12.5H14.6281C14.7693 12.5 14.9056 12.4484 15.0114 12.3549C15.1172 12.2614 15.1852 12.1324 15.2026 11.9922L15.296 11.2367L15.7562 11.0462L16.3565 11.5141C16.4678 11.6009 16.6071 11.644 16.748 11.6353C16.8889 11.6266 17.0218 11.5667 17.1217 11.4669L18.7169 9.87161C18.8168 9.77176 18.8767 9.63886 18.8854 9.49792C18.894 9.35698 18.8509 9.21772 18.7641 9.10636L18.2962 8.50604L18.4868 8.0459L19.2423 7.95241C19.3824 7.93507 19.5114 7.8671 19.6049 7.7613C19.6984 7.65549 19.75 7.51916 19.75 7.37795V5.12186C19.75 4.98065 19.6984 4.84432 19.6049 4.73851C19.5114 4.63271 19.3824 4.56474 19.2423 4.54739L18.4868 4.45391L18.2962 3.99377L18.7641 3.39344C18.8509 3.28209 18.894 3.14283 18.8854 3.00189C18.8767 2.86095 18.8168 2.72805 18.7169 2.6282L17.1215 1.03292C17.0217 0.933071 16.8888 0.873164 16.7478 0.864475C16.6069 0.855786 16.4676 0.898915 16.3563 0.985743L15.756 1.45365L15.2958 1.26306L15.2024 0.50756C15.185 0.367457 15.117 0.238538 15.0112 0.145062C14.9054 0.0515854 14.7691 -4.32923e-06 14.6279 2.72482e-10H12.3719C12.2307 1.50366e-06 12.0944 0.0516183 11.9886 0.145134C11.8828 0.23865 11.8148 0.367615 11.7974 0.507753L11.704 1.26326L11.2438 1.45384L10.6435 0.985936C10.5322 0.899108 10.3929 0.855979 10.252 0.864668C10.1111 0.873357 9.97816 0.933264 9.87831 1.03311L8.28308 2.6282C8.18323 2.72805 8.12333 2.86095 8.11464 3.00189C8.10595 3.14283 8.14908 3.28209 8.23591 3.39344L8.7038 3.99377L8.51322 4.45391L7.75774 4.54739C7.6176 4.56474 7.48864 4.63271 7.39513 4.73851C7.30162 4.84432 7.25 4.98065 7.25 5.12186V7.37795C7.25001 7.51918 7.30165 7.65555 7.3952 7.76135C7.48875 7.86716 7.61776 7.93511 7.75793 7.95241ZM8.40787 5.63352L8.99023 5.56145C9.09183 5.54888 9.18827 5.50959 9.26973 5.44758C9.35119 5.38558 9.41475 5.30308 9.45392 5.20849L9.90252 4.12526C9.94171 4.03069 9.95511 3.92741 9.94137 3.82597C9.92762 3.72453 9.88722 3.62854 9.82428 3.54781L9.46343 3.08506L10.3352 2.2133L10.7979 2.57417C10.8786 2.63712 10.9746 2.67753 11.076 2.69128C11.1775 2.70503 11.2808 2.69164 11.3753 2.65246L12.4585 2.20385C12.5531 2.16468 12.6356 2.10112 12.6976 2.01966C12.7596 1.93819 12.7989 1.84174 12.8115 1.74014L12.8835 1.15776H14.1163L14.1883 1.74014C14.2009 1.84174 14.2402 1.93819 14.3022 2.01966C14.3642 2.10112 14.4467 2.16468 14.5413 2.20385L15.6245 2.65246C15.7191 2.69164 15.8223 2.70503 15.9238 2.69128C16.0252 2.67753 16.1212 2.63712 16.2019 2.57417L16.6647 2.2133L17.5364 3.08506L17.1755 3.54781C17.1126 3.62853 17.0722 3.72451 17.0584 3.82596C17.0446 3.9274 17.058 4.03068 17.0972 4.12526L17.5458 5.20849C17.585 5.30308 17.6485 5.38558 17.73 5.44758C17.8114 5.50959 17.9079 5.54888 18.0095 5.56145L18.5918 5.63352V6.86629L18.0095 6.93836C17.9079 6.95093 17.8114 6.99022 17.73 7.05222C17.6485 7.11423 17.585 7.19673 17.5458 7.29131L17.0972 8.37454C17.058 8.46913 17.0446 8.57241 17.0584 8.67385C17.0722 8.77529 17.1126 8.87127 17.1755 8.952L17.5364 9.41475L16.6647 10.2865L16.2019 9.92564C16.1212 9.86269 16.0252 9.82228 15.9238 9.80853C15.8223 9.79477 15.7191 9.80817 15.6245 9.84735L14.5413 10.296C14.4467 10.3351 14.3642 10.3987 14.3022 10.4801C14.2402 10.5616 14.2009 10.6581 14.1883 10.7597L14.1163 11.342H12.8835L12.8115 10.7597C12.7989 10.6581 12.7596 10.5616 12.6976 10.4801C12.6356 10.3987 12.5531 10.3351 12.4585 10.296L11.3753 9.84735C11.2808 9.80817 11.1775 9.79477 11.076 9.80853C10.9746 9.82228 10.8786 9.86269 10.7979 9.92564L10.3352 10.2865L9.46343 9.41475L9.82428 8.952C9.88724 8.87127 9.92765 8.77529 9.94142 8.67385C9.95518 8.57241 9.94179 8.46913 9.90262 8.37454L9.45402 7.29131C9.41484 7.19673 9.35129 7.11423 9.26983 7.05222C9.18837 6.99022 9.09192 6.95093 8.99032 6.93836L8.40797 6.86629L8.40787 5.63352Z"
                  fill="#5893D4"/>
          <path
                  d="M13.5019 8.75C14.0803 8.74956 14.6406 8.5486 15.0874 8.18136C15.5342 7.81411 15.8399 7.30331 15.9523 6.73597C16.0647 6.16864 15.977 5.57987 15.704 5.06998C15.431 4.56009 14.9896 4.16063 14.4552 3.93966C13.9207 3.71869 13.3261 3.68987 12.7727 3.85812C12.2194 4.02637 11.7415 4.38128 11.4205 4.86238C11.0994 5.34348 10.9552 5.921 11.0122 6.49655C11.0692 7.0721 11.3241 7.61007 11.7333 8.0188C11.9654 8.25116 12.2411 8.43537 12.5446 8.56085C12.8481 8.68633 13.1735 8.75061 13.5019 8.75ZM12.5137 5.26183C12.7092 5.06638 12.9582 4.93328 13.2293 4.87935C13.5004 4.82542 13.7813 4.8531 14.0367 4.95887C14.2921 5.06464 14.5103 5.24376 14.6639 5.47358C14.8175 5.7034 14.8994 5.9736 14.8994 6.25C14.8994 6.5264 14.8175 6.79659 14.6639 7.02641C14.5103 7.25623 14.2921 7.43535 14.0367 7.54113C13.7813 7.6469 13.5004 7.67457 13.2293 7.62064C12.9582 7.56672 12.7092 7.43362 12.5137 7.23817C12.3835 7.10867 12.2801 6.9547 12.2096 6.78512C12.139 6.61553 12.1027 6.43367 12.1027 6.25C12.1027 6.06633 12.139 5.88447 12.2096 5.71488C12.2801 5.54529 12.3835 5.39132 12.5137 5.26183Z"
                  fill="#5893D4"/>
        </svg>
      </span>
            </div>
        </td>
        <td v-for="(value, key) in items[1]" :key="key" :ref="key" @click="selectItem(item)">
            <div v-if="value.value ==='is_visible'" class="table-switcher">
                <switcher @toggle="toggleVisible(item)" :value="item.cells[value.value]"></switcher>
            </div>
            <price-popup v-else-if="value.value.includes('price_')"
                         @clickPrice="onClickViewItem(item)"
                         :price-value="item.cells[value.value]"
                         :options="item.cells[`${value.value}_option`]"
                         :price-name="value.value"
                         @changePrice="onChangePrice">

            </price-popup>
            <span v-else @click.stop="onClickViewItem">{{ item.cells[value.value] }}</span>
        </td>
        <td></td>
        <td class="row-actions">
            <div class="nomenclature-actions">

        <span :class="{'active': isEditPurchase}">
          <div v-if="isEditPurchase" class="nomenclature-counter">
          <input class="small" type="number" v-model="purchaseCount">
          <div class="nomenclature-counter-buttons">
             <span @click.stop="purchaseCount +=1">+</span>
            <span @click.stop="purchaseCount -=1">-</span>
          </div>
          <span class="units">шт</span>
          <button class="nomenclature-counter-submit"></button>
        </div>
                <svg @click="openPurchaseModal" width="20" height="24" viewBox="0 0 20 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M4.45126 4.60156H0V6.44171H2.98918L5.56221 18.1064H5.57336V18.4027H17.649V18.1745L19.686 9.28566L20 8.28186H5.25289L4.72342 5.87954L4.45126 4.60156Z"
                      fill="#BBDBFE"/>
                <path
                        d="M8.35974 23.0045C8.85246 23.0045 9.32499 22.8106 9.67339 22.4655C10.0218 22.1205 10.2175 21.6524 10.2175 21.1644C10.2175 20.6763 10.0218 20.2083 9.67339 19.8632C9.32499 19.5181 8.85246 19.3242 8.35974 19.3242C7.86702 19.3242 7.39449 19.5181 7.04609 19.8632C6.69768 20.2083 6.50195 20.6763 6.50195 21.1644C6.50195 21.6524 6.69768 22.1205 7.04609 22.4655C7.39449 22.8106 7.86702 23.0045 8.35974 23.0045Z"
                        fill="#BBDBFE"/>
                <path
                        d="M16.7195 21.1644C16.7195 21.6524 16.5237 22.1205 16.1753 22.4655C15.8269 22.8106 15.3544 23.0045 14.8617 23.0045C14.369 23.0045 13.8964 22.8106 13.548 22.4655C13.1996 22.1205 13.0039 21.6524 13.0039 21.1644C13.0039 20.6763 13.1996 20.2083 13.548 19.8632C13.8964 19.5181 14.369 19.3242 14.8617 19.3242C15.3544 19.3242 15.8269 19.5181 16.1753 19.8632C16.5237 20.2083 16.7195 20.6763 16.7195 21.1644Z"
                        fill="#BBDBFE"/>
                <path
                        d="M11.5183 0.000284549L15.6055 5.93045L13.4035 5.93045L13.4035 10.1211L9.63315 10.1211L9.63315 5.93045L7.43121 5.93045L11.5183 0.000284549Z"
                        fill="#BBDBFE"/>
              </svg>
              </span>
                <span :class="{'active': isEditSales  }">
          <div v-if="isEditSales" class="nomenclature-counter">
          <input class="small" type="number" v-model="salesCount">
          <div class="nomenclature-counter-buttons">
            <span @click.stop="salesCount +=1">+</span>
            <span @click.stop="salesCount -=1">-</span>
          </div>
          <span class="units">шт</span>
          <button class="nomenclature-counter-submit"></button>
        </div>
                <svg @click="openSalesModal" width="20" height="20" viewBox="0 0 20 20" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M8.18154 12.7266H5.45436V14.545C5.45436 15.0469 5.0471 15.4542 4.5453 15.4542C4.0435 15.4542 3.63624 15.0469 3.63624 14.545V12.7266H0.90906C0.407259 12.7266 0 13.1339 0 13.6358V19.0911C0 19.593 0.407259 20.0003 0.90906 20.0003H8.18154C8.68335 20.0003 9.0906 19.593 9.0906 19.0911V13.6358C9.0906 13.1339 8.68335 12.7266 8.18154 12.7266Z"
                        fill="#BBDBFE"/>
                <path
                        d="M19.0897 12.7266H16.3626V14.545C16.3626 15.0469 15.9553 15.4542 15.4535 15.4542C14.9517 15.4542 14.5444 15.0469 14.5444 14.545V12.7266H11.8173C11.3155 12.7266 10.9082 13.1339 10.9082 13.6358V19.0911C10.9082 19.593 11.3155 20.0003 11.8173 20.0003H19.0897C19.5915 20.0003 19.9988 19.593 19.9988 19.0911V13.6358C19.9988 13.1339 19.5915 12.7266 19.0897 12.7266Z"
                        fill="#BBDBFE"/>
                <path d="M9.92134 10.0004L5.92188 4.1408H8.07658V0H11.7661V4.1408H13.9208L9.92134 10.0004Z"
                      fill="#BBDBFE"/>
              </svg>
              </span>
            </div>
        </td>
        <context-menu
                @context-event="onContextMenuEvent"
                ref="contextMenuParent"
                :state="menuState"
                :actions="actions"/>
    </tr>
</template>

<script>
    import {eventBus} from "@/main";
    import Switcher from "@/components/ui/Swithcer";
    import ContextMenu from "../ui/ContextMenu/ContextMenu";
    import {NOMENCLATURE_RESOURCES} from "../../constants/constants"
    import PricePopup from "./NomeclatureTable/PricePopup";

    export default {
        name: "NomenclatureServiceRow",
        components: {
            PricePopup,
            Switcher, ContextMenu
        },
        props: ['items', 'item', 'selected', 'actions'],
        data() {
            return {
                isEditPurchase: false,
                isEditSales: false,
                menuState: false,
                resource: NOMENCLATURE_RESOURCES.SERVICES,
                salesCount: 0,
                purchaseCount: 0
            }
        },
        computed: {
            argumentsItem() {
                return {
                    id: this.item.id,
                    parentId: null,
                    isGroup: false,
                    isChild: false,
                    isKit: false,
                    resource: this.resource,
                    title: this.item.cells.short_title
                }
            }
        },
        mounted() {
            eventBus.$on('selectedAllNomenclatures', (val) => {
                if (!val && !this.selected) {
                    this.selectItem()
                }
            })
        },
        methods: {
            openMenu(event) {
                eventBus.$emit('close-context-menu')
                this.$refs.contextMenuParent.open(event)
            },
            onClickViewItem() {
                this.$emit('view-item', this.argumentsItem)
            },
            onChangePrice(params) {
                const data = {
                    ...this.argumentsItem,
                    ...params
                }
                this.$emit('changePrice', data)
            },
            onContextMenuEvent(action) {
                this.$emit(`emit-row-action`, {argumentsItem: this.argumentsItem, action})
            },
            onClickChangeItem(item) {
                this.$emit('changeItem', item)
            },
            openSalesModal() {
                this.isEditSales = !this.isEditSales
                this.isEditPurchase = false
            },
            openPurchaseModal() {
                this.isEditPurchase = !this.isEditPurchase
                this.isEditSales = false
            },
            onClickOutside() {
                this.isEditPurchase = false
                this.isEditSales = false
            },
            selectItem() {
              this.$emit('select-item',  this.argumentsItem)
            },
          toggleVisible() {
            this.$emit('toggleVisible', {
              ...this.argumentsItem,
              is_visible: this.item.cells.is_visible,
            })
          },

        }
    }
</script>

<style scoped lang="sass">
    td
        &.row-actions
          position: sticky
          right: 1px
        .wrapper
            display: flex
            align-items: center

            .icon
                margin-left: 25px

        .nomenclature-actions
            display: flex
            justify-content: space-between

            span
                position: relative

                path
                    transition: .5s all

                &.active, &:hover
                    svg
                        path
                            fill: #5893D4

            .nomenclature-counter
                width: 175px
                height: 121px
                background: #FFFFFF
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1)
                border-radius: 10px
                display: flex
                align-items: center
                justify-content: space-around
                padding: 20px
                position: absolute
                right: 0
                top: 100%
                z-index: 2

            .nomenclature-counter-buttons
                display: flex
                flex-direction: column
                margin-left: 1px
                margin-right: 10px

                span
                    position: relative
                    width: 25px
                    height: 18px
                    background: #E3F0FF
                    border-radius: 10px
                    text-align: center
                    color: #5893D4
                    cursor: pointer
                    font-weight: 500

                    &:first-child
                        margin-bottom: 5px

            input
                border: 1px solid #E3F0FF
                height: 32px
                border-radius: 100px
                max-width: 106px
                outline: none !important
                padding: 0 10px
                text-align: center

                &::-webkit-outer-spin-button,
                &::-webkit-inner-spin-button
                    -webkit-appearance: none
                    margin: 0

                &.small
                    max-width: 80px

</style>
