<template>
    <simplebar class="item-form create-new-item">
        <v-row class="custom-row">
            <v-col cols="6">
                <v-row>
                    <v-col cols="12">
                        <div class="item-name">

                            <div class="label-title">
                                {{$t(`nomenclature.short_title`)}} *
                                <span class="label-text"
                                      v-if="isValidFields.result && isValidFields.key === 'short_title'">
                                        {{ isValidFields.massage }}
              </span>
                            </div>
                            <input
                                    type="text"
                                    @keyup="onDouble"
                                    :class="{'error-on-input': $v.form.short_title.$error }"
                                    @blur="$v.form.short_title.$touch()"
                                    @change="onValid('short_title', 'short_title')"
                                    @input="emitFormData" v-model="form.short_title"
                                    :placeholder="$t('nomenclature.name_placeholder')">
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="item-name">
                            <div class="label-title">{{$t(`nomenclature.full_title`)}} <span class="label-subtitle">({{$t('nomenclature.in_document')}}) *</span>
                            </div>
                            <input type="text"
                                   v-model="form.dock_title"
                                   @change="onFullTitleBlur(form.dock_title)"
                                   @blur="$v.form.dock_title.$touch()"
                                   :class="{'error-on-input': $v.form.dock_title.$error }"
                                   :placeholder="$t('nomenclature.name_placeholder')">
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="select-wrap" v-click-outside="onClickOutsideHead">
                            <div class="item select-category">
                                <div class="label-title">
                                    {{$t(`nomenclature.category`)}}
                                </div>
                                <div class="input-wrap">
                                    <input
                                            readonly
                                            v-model="form.category_id"
                                            @click="showDropHeadDown = !showDropHeadDown"
                                            class="dropdown-trigger"
                                            :class="{'error-on-input':  $v.form.category_id.$error }"
                                            @focus="$v.form.category_id.$touch()"
                                            @change="getCategoryTree(category)"
                                            type="text" :placeholder="$t('nomenclature.category_placeholder')">
                                    <div class="category-tree-wrapper" @click="showDropHeadDown = !showDropHeadDown">
                                        <category-tree :editable="true" :categories="categoryTree"
                                                       @changeCategory="selectCategory"></category-tree>

                                    </div>
                                    <v-btn class="open-drop-down" icon @click="showDropHeadDown = !showDropHeadDown">
                                        <svg width="11" height="6" viewBox="0 0 11 6" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55708e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z"
                                                    fill="#9DCCFF"/>
                                        </svg>
                                    </v-btn>
                                    <simplebar class="drop-down drop-down-categories" v-if="showDropHeadDown">
                                        <v-treeview
                                                open-all
                                                activatable
                                                item-text="title"
                                                selection-type="independent"
                                                :items="categories"
                                        >
                                            <template slot="label" slot-scope="{item}">
                                                <div @click="selectCategory(item)">{{ item.title }}</div>
                                            </template>
                                        </v-treeview>
                                    </simplebar>
                                </div>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="item nested-category" v-click-outside=" onClickOutsideAdditional">
                            <div class="label-title">
                                <span>{{$t('nomenclature.additional_category')}} <span class="label-subtitle">({{$t('nomenclature.on_site')}})</span></span>
                            </div>
                            <div class="input-wrap">
                                <input
                                        @focus="showDropDown = !showDropDown"
                                        v-model="childGroupsTitle"
                                        type="text"
                                        :placeholder="$t('nomenclature.category_placeholder')">
                                <v-btn class="open-drop-down" icon @click="showDropDown = !showDropDown">
                                    <svg width="11" height="6" viewBox="0 0 11 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                                d="M5.92922 5.83709L10.8243 1.29221C10.9376 1.1871 11 1.04677 11 0.897152C11 0.74753 10.9376 0.607207 10.8243 0.50209L10.4639 0.167391C10.229 -0.0503999 9.84733 -0.0503999 9.61285 0.167391L5.50228 3.98384L1.38715 0.163156C1.27384 0.0580381 1.1228 -7.44275e-07 0.961732 -7.56412e-07C0.800489 -7.68562e-07 0.649442 0.058038 0.536044 0.163155L0.17573 0.497854C0.0624218 0.603055 -3.24905e-08 0.743294 -3.90307e-08 0.892917C-4.55708e-08 1.04254 0.0624217 1.18286 0.17573 1.28798L5.07525 5.83709C5.18892 5.94246 5.34068 6.00033 5.50201 6C5.66397 6.00033 5.81564 5.94246 5.92922 5.83709Z"
                                                fill="#9DCCFF"/>
                                    </svg>
                                </v-btn>
                                <v-btn @click="createChildCategory()" icon class="create-nested-category">
                                    <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                                d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z"
                                                stroke="#9DCCFF"/>
                                        <path
                                                d="M16.1027 13.8732L20 13.8732L20 16.0765L16.1027 16.0765L16.1027 20L13.8973 20L13.8973 16.0765L10 16.0765L10 13.8732L13.8973 13.8732L13.8973 10L16.1027 10L16.1027 13.8732Z"
                                                fill="#9DCCFF"/>
                                    </svg>
                                </v-btn>
                                <simplebar class="drop-down drop-down-categories"
                                           v-if="showDropDown && !this.childGroupsTitle.length">
                                    <v-treeview
                                            open-all
                                            activatable
                                            item-text="title"
                                            selection-type="independent"
                                            :items="categoriesItems"
                                            item-disabled="disabled"
                                    >
                                        <template slot="label" slot-scope="{item}">
                                            <div item-disabled="{item.id == 1}" @click="selectChildCategory(item)">{{
                                                item.title }}
                                            </div>
                                        </template>
                                    </v-treeview>
                                </simplebar>
                            </div>
                        </div>
                        <div class="values-list">
                            <div class="value-item" v-for="(item, i) in children" :key="item.id">
                                <span>{{ item.title }}</span>
                                <svg @click="deleteChildCategory(i)" data-v-51d92aa0="" width="12" height="13"
                                     viewBox="0 0 12 13"
                                     fill="none" xmlns="http://www.w3.org/2000/svg" class="close">
                                    <path data-v-51d92aa0=""
                                          d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z"
                                          fill="#BBDBFE"></path>
                                </svg>
                            </div>
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <div class="item-name">

                            <div class="label-title">
                                {{$t('nomenclature.sku')}}
                                <span class="label-text" v-if="isValidFields.result && isValidFields.key === 'sku'">
                                      {{ isValidFields.massage }}
                                </span>
                            </div>
                            <input type="text" @input="emitFormData" @change="onValid('sku', 'sku')"
                                   v-model="form.sku" :placeholder="$t('nomenclature.sku_placeholder')">
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <div class="item">
                            <div class="label-title">{{$t('nomenclature.unit')}}</div>
                        </div>
                        <div class="select-wrap">
                            <v-select class="select-switcher"
                                      :items="units"
                                      item-text="title"
                                      item-value="id"
                                      v-model="form.unit_id"
                                      solo
                                      dense
                                      menu-props="units"
                            >
                            </v-select>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <v-row>
                            <v-col cols="6">
                                <div class="select-wrap">
                                    <div class="label-title">{{$t('nomenclature.price')}}</div>
                                    <v-select class="select-switcher"
                                              :items="pricesTypes"
                                              item-text="title"
                                              item-value="id"
                                              v-model="selectedPrice"
                                              solo
                                              dense
                                              menu-props="p"
                                    >
                                    </v-select>
                                </div>
                            </v-col>
                            <v-col cols="6">
                                <div class="input-number-wrapper">
                                    <input type="number" v-model="selectedPriceValue" @input="changePrice">
                                    <button @click="createChildPrice" :disabled="!selectedPriceValue && !selectedPrice">
                                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.500003 23.0081 0.500003 15C0.500004 6.99187 6.99188 0.499998 15 0.499999C23.0081 0.499999 29.5 6.99187 29.5 15Z"
                                                    stroke="#9DCCFF"/>
                                            <path
                                                    d="M16.1027 13.8732L20 13.8732L20 16.0765L16.1027 16.0765L16.1027 20L13.8973 20L13.8973 16.0765L10 16.0765L10 13.8732L13.8973 13.8732L13.8973 10L16.1027 10L16.1027 13.8732Z"
                                                    fill="#9DCCFF"/>
                                        </svg>
                                    </button>
                                </div>
                            </v-col>
                            <div class="values-list">
                                <div class="value-item" v-for="(item, i) in selectedPrices" :key="item.id">
                                    <span>{{ item.title }}</span>
                                    <span>{{ item.value }}</span>
                                    <svg @click="deleteChildPrice(i)" width="12" height="13" viewBox="0 0 12 13"
                                         fill="none"
                                         xmlns="http://www.w3.org/2000/svg" class="close">
                                        <path data-v-51d92aa0=""
                                              d="M5.98676 4.7921L10.2789 0.5L11.9711 2.19222L7.67898 6.48432L12 10.8053L10.3053 12.5L5.98432 8.17898L1.69222 12.4711L0 10.7789L4.2921 6.48676L0.0264792 2.22114L1.72114 0.526477L5.98676 4.7921Z"
                                              fill="#BBDBFE"></path>
                                    </svg>
                                </div>
                            </div>
                        </v-row>
                    </v-col>
                    <v-col cols="6">
                        <div class="select-wrap">
                            <div class="label-title">{{$t('nomenclature.supplier')}}</div>
                            <v-select class="select-switcher"
                                      :items="suppliers"
                                      item-text="title"
                                      v-model="form.supplier_id"
                                      item-value="id"
                                      solo
                                      dense
                                      menu-props="units"
                                      :placeholder="$t('nomenclature.supplier_placeholder')"
                            >
                            </v-select>
                        </div>
                    </v-col>
                    <v-col cols="6">
                        <div class="item-name input-number-wrapper">
                            <div class="label-title">{{$t('nomenclature.min_price')}}</div>
                            <input @change="changePrice" @input="emitFormData" type="number"
                                   v-model="form.min_price"
                                   :placeholder="$t('nomenclature.min_price_placeholder')">
                        </div>
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="6">
                <v-row>
                    <v-col cols="12">
                        <div class="item-name">
                            <div class="label-title">{{$t('nomenclature.desc')}}</div>
                            <textarea @input="emitFormData" v-model="form.desc"></textarea>
                        </div>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </simplebar>
</template>

<script>
    import {required} from 'vuelidate/lib/validators'
    import {TABLE_ACTIONS} from '../../../../../../constants/constants'
    import {eventBus} from "@/main";
    import {mapGetters, mapActions} from 'vuex'
    import CategoryTree from "@/components/dashboard/products/nomenclature/modals/froms/CategoryTree";

    export default {
        name: "ServiceForm",
        components: {CategoryTree},
        data() {
            return {
                packing: false,
                categoryTree: [],
                children: [],
                childGroupsTitle: '',
                showDropDown: false,
                showDropHeadDown: false,
                selectedPrice: '',
                parents: [],
                selectedPriceValue: null,
                double: true,
                prices: [],
                form: {
                    desc: null,
                    category_id: null,
                    categories: [],
                    min_price: null,
                    short_title: null,
                    dock_title: null,
                    sku: null,
                    lower_limit: null,
                    unit_id: 1,
                    supplier_id: null,
                    prices: [],
                    packing: null
                },
            }
        },
        props: {
            categories: Array,
            category: Object,
            data: Object,
            mode: String,
            units: Array,
            pricesTypes: Array,
            countries: Array,
            suppliers: Array
        },
        validations: {
            form: {
                category_id: {required},
                short_title: {required},
                dock_title: {required},
            },
        },
        created() {
            if (this.mode === TABLE_ACTIONS.UPDATE || this.mode === TABLE_ACTIONS.COPY) {
                Object.keys(this.form).forEach((item => {
                    console.log(item);
                    this.form[item] = this.data[item]
                }))
                this.form.category_id = this.data.category.id
                this.$emit('changeCategory', this.form.category_id)
            }
        },
        computed: {
            ...mapGetters({
                isValidFields: 'nomenclatureIsValidFields'
            }),
            categoriesItems() {
                return this.getNodes(this.freeCategories, this.children)
            },
            freeCategories() {
                return [...this.categories].filter(category => category.id !== this.form.category_id)
            },
            selectedPrices() {
                if (this.form.prices) {
                    if (this.mode === 'create') {
                        return [...this.form.prices].map(item => {
                            return {
                                title: this.pricesTypes.find(price => price.id === item.id).title,
                                value: item.value
                            }
                        })
                    } else {
                        return this.form.prices
                    }

                }
                return false
            }
        },
        mounted() {
            eventBus.$on('validate-product-form', () => {
                this.$v.form.$touch()
            });
        },
        watch: {
            category: function () {
                if (this.category) {
                    this.categoryTree = []
                    this.getCategoryTree(this.category)
                    this.form.unit_id = this.category.unit_id || 1
                }

            }
        },
        methods: {
            ...mapActions({
                addNewCategory: 'addNewCategory',
                onValidAction: 'nomenclatureValidation',

            }),
            emitFormData() {
                this.$emit('stepUpdated', {
                    data: this.form,
                    isValid: !this.$v.$invalid && !this.isValidFields.result,
                    resource: 'product',
                })
            },
            onDouble() {
                if (this.double) {
                    this.form.dock_title = this.form.short_title
                }
            },
            onValid(invalidKey, key) {
                let mode
                const resource = 'products'
                if (this.mode === 'create' || this.mode === 'copy') {
                    mode = 'store'
                }
                if (this.mode === 'edit') {
                    mode = 'update'
                }
                if (this.form[key]) {
                    const body = {
                        validate: {[`${invalidKey}`]: this.form[key]}
                    }
                    this.onValidAction({body, mode, resource, key: invalidKey}).then(() => {
                        this.emitFormData()
                    })
                    this.$v.form[key] && this.$v.form[key].$touch()
                }
            },
            onFullTitleBlur(val) {
                this.double = !val
            },
            onClickOutsideHead() {
                this.showDropHeadDown = false
            },
            onClickOutsideAdditional() {
                this.showDropDown = false
            },
            getCategoryTree(category) {
                this.emitFormData()
                this.categoryTree.push({title: category.title, id: category.id})
                if (category.parent !== null) {
                    this.getCategoryTree(category.parent)
                } else
                    return false
            },
            getNodes(items) {
                return items.map(item => {
                    const disabled = this.children.find(({id}) => id === item.id)
                    return {
                        id: item.id,
                        title: item.title,
                        disabled: Boolean(disabled),
                        children: item.children ? this.getNodes(item.children) : []
                    }
                })
            },
            createChildCategory() {
                const newCategory = {
                    title: this.childGroupsTitle
                }
                this.addNewCategory(newCategory).then(val => {
                    if (this.childGroupsTitle.length > 3) {
                        this.children.unshift(val)
                        this.childGroupsTitle = ''
                        this.showDropDown = false
                    }
                })
            },
            createChildPrice() {
                if (this.selectedPrice && this.selectedPriceValue) {
                    const item = {
                        id: this.selectedPrice,
                        value: this.selectedPriceValue,
                    }
                    this.form.prices.push(item)
                    this.selectedPrice = ''
                    this.selectedPriceValue = ''
                    this.emitFormData()
                }
            },
            validateForm() {
                this.$v.from.$touch()
            },
            deleteChildCategory(index) {
                this.children.splice(index, 1)
                this.emitFormData()
            },
            deleteChildPrice(index) {
                this.form.prices.splice(index, 1)
                this.emitFormData()
            },
            selectChildCategory(item) {
                if (item.disabled) {
                    return false
                } else {
                    this.children.unshift(item)
                    this.form.categories.unshift(item)
                }
            },
            selectCategory(item) {
                this.form.category_id = item.id
                this.showDropHeadDown = false
                const index = this.children.findIndex(child => child.id === item.id)
                if (index !== -1) {
                    this.children.splice(index, 1)
                }
                this.$emit('stepUpdated', {data: this.form})
                this.$emit('changeCategory', this.form.category_id)
            },
            changePrice() {
                if (this.selectedPriceValue && this.form.min_price && this.selectedPriceValue < this.form.min_price) {
                    this.selectedPriceValue = this.form.min_price
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .label-subtitle {
        color: #9DCCFF;
    }

    .custom-row {
        align-items: flex-start !important;
    }

    .label-text {
        text-transform: initial;
        font-size: 12px;
        line-height: 12px;
        float: right;
        color: #FF9F2F;
        font-weight: 400;
    }

    .category-tree-wrapper {
        position: absolute;
        bottom: 1px;
        z-index: 2;
        background-color: white;
    }

    .lang-switch {
        display: inline-flex;
        justify-content: flex-start;
        float: right;

        .lang-btn {
            width: 40px;
            height: 20px;
            background-color: transparent;
            border: 1px solid #E6F3FD;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-right: 10px;

            &:last-child {
                margin-right: 0;
            }

            &.active {
                background-color: #BBDBFE;
            }
        }
    }

    .checkbox-wrapper {
        .checkbox {
            display: block;
        }

        .checkbox-text {
            height: 16px;
        }
    }

    .values-list {
        margin-bottom: 0;

        .value-item {
            margin: 10px 10px 0 0;
        }
    }

    .select-category {
        .open-drop-down {
            position: absolute;
            right: 0;
        }

        input {
            color: transparent;
        }

        .dropdown-trigger {
            border-bottom: 1px solid #9DCCFF;

            &.error-on-input {
                border-bottom: 1px solid #FF9F2F;
            }
        }
    }

    .item {
        &.checkbox-wrapper {
            input {
                padding: 0;
            }
        }
    }

    .file {
        .label-title {
            margin-bottom: 18px;
        }

        &-input-trigger {
            width: 160px;
            text-align: center;
            padding: 6px 0;
            border: 1px solid #E3F0FF;
            box-sizing: border-box;
            border-radius: 32.5652px;
            font-size: 13px;
            color: #9DCCFF;
            cursor: pointer;

            &:hover {
                color: #E3F0FF;
                background: #9DCCFF;
            }
        }

    }

    .item-name {
        textarea {
            height: 223px;
        }
    }


</style>

