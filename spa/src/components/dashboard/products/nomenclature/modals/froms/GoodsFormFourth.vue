<template>
    <form class="item-form create-new-item">
        <v-row class="nomenclature-top-row">
            <v-col cols="3">
                <div class="select-wrap">
                    <div class="label-title">статус товар (на сайте)</div>
                    <v-select class="select-switcher"
                              @change="emitFormData"
                              :items="statusStates"
                              v-model="form.is_visible"
                              item-text="title"
                              item-value="value"
                              solo
                              dense
                    >
                    </v-select>
                </div>
            </v-col>
        </v-row>
        <div class="accompanying">
            <div class="accompanying-header">
                <div class="label-title">{{$t('nomenclature.view_item_tabs.accompanying')}}
                    {{$t(`nomenclature.modal_resource.products`)}}
                </div>
                <div class="accompanying-header-right">
                    <div class="search-block">
                        <input type="text" :placeholder="$t('page.search')" v-model="search">
                        <div class="search-icon">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M7.685 14.3335C11.377 14.3335 14.37 11.3487 14.37 7.66674C14.37 3.9848 11.377 1 7.685 1C3.99298 1 1 3.9848 1 7.66674C1 11.3487 3.99298 14.3335 7.685 14.3335Z"
                                        stroke="#5893D4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16.0002 16L12.3652 12.375" stroke="#5893D4" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <button type="button" @click="onClickChooseBtn" class="transparent-btn">{{$t('page.сhoose')}}
                    </button>
                    <button type="button" class="circle-btn">
                        <svg width="13" height="15" viewBox="0 0 13 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4.33203 5.35742H5.41536V11.786H4.33203V5.35742Z" fill="#F4F9FF"/>
                            <path d="M7.58398 5.35742H8.66732V11.786H7.58398V5.35742Z" fill="#F4F9FF"/>
                            <path d="M0 2.14258V3.214H1.08333V13.9282C1.08333 14.2124 1.19747 14.4849 1.40063 14.6858C1.6038 14.8868 1.87935 14.9996 2.16667 14.9996H10.8333C11.1207 14.9996 11.3962 14.8868 11.5994 14.6858C11.8025 14.4849 11.9167 14.2124 11.9167 13.9282V3.214H13V2.14258H0ZM2.16667 13.9282V3.214H10.8333V13.9282H2.16667Z"
                                  fill="#F4F9FF"/>
                            <path d="M4.33203 0H8.66536V1.07142H4.33203V0Z" fill="#F4F9FF"/>
                        </svg>
                    </button>
                </div>
            </div>
            <table-with-prices v-if="isLoadRelated"
                               :body="data.related.body"
                               :headers="data.related.headers"
                               :with-checkbox="true"
            />
        </div>
    </form>
</template>

<script>
    import TableWithPrices from "../../../../../ui/TableWithPrices";
    import {CHOOSE_MODAL_RESOURCES, TABLE_ACTIONS} from "../../../../../../constants/constants";
    import {selectItems} from "../../../../../../services/choose";
    import httpClient from "../../../../../../services/http-client/http-client";

    export default {
        name: "GoodsFormFourth",
        components: {TableWithPrices},
        data() {
            return {
                search: '',
                isLoadRelated: false,
                chooseResource: CHOOSE_MODAL_RESOURCES.ACCOMPANYING,
                form: {
                    barcode_supplier: null,
                    barcode: null,
                    is_visible: false,
                },
                statusStates: [
                    {
                        title: 'Активный',
                        value: true
                    },
                    {
                        title: 'Не Активный',
                        value: false
                    }
                ]
            }
        },
        props: {
            data: Object,
            mode: String,
            chooseModalResource: Object,
        },
        async created() {
            const data = JSON.parse(JSON.stringify(this.data))
            Object.keys(this.form).forEach((item => {
                this.form[item] = data[item]
            }))
            try {
                const url = `products/nomenclatures/get-groups-nomenclatures/${this.data.id}`
                const table = await this.mode === TABLE_ACTIONS.CREATE ?
                    selectItems(this.chooseResource.SELECT_ITEMS, this.form.related) :
                    httpClient.get(url)
                this.form.related = table.data.data
                this.isLoadRelated = true
                this.emitFormData()

            } catch (e) {
                console.log(e);
            }
        },
        methods: {
            validateForm() {
                return false
            },
            emitFormData() {
                this.$emit('stepUpdated', {data: this.form, isValid: true, resource: 'product', title:'additional_info'})
            },
            onClickChooseBtn() {
                this.$emit('click-choose-btn',
                    {
                        chooseResource: this.chooseModalResource.ACCOMPANYING
                    }
                )
            }
        }

    }
</script>

<style scoped lang="scss">
    .nomenclature-top-row {
        margin-bottom: 100px;
    }

    .transparent-btn {
        min-height: 36px;
        width: 100%;
        max-width: 160px;
        font-weight: bold;
        font-size: 13px;
        line-height: 13px;
        color: #9DCCFF;
        border: solid 1px #9DCCFF;
        border-radius: 32px;

        &:hover {
            background-color: #9DCCFF;
            color: #fff;
        }

        margin-left: 10px;
        margin-right: 10px;
    }

    .circle-btn {
        height: 36px;
        width: 36px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #9DCCFF;

        &:hover {
            background: #5893D4;
        }
    }

    .accompanying {
        &-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;

            .label-title {
                width: auto;
                white-space: nowrap;
            }

            &-right {
                width: 100%;
                display: flex;
                justify-content: flex-end;
            }

            .search-block {
                max-width: 392px;

                input {
                    border: 1px solid #9DCCFF;
                }
            }
        }
    }

</style>