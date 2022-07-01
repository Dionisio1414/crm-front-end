<template>
    <v-autocomplete
        :class="customClass"
        :item-text="itemText"
        :item-value="itemValue"
        v-model="value"
        :items="list"
        hide-selected
        :placeholder="placeholder"
        :menu-props="copyMenuProps"
        :no-data-text="$t('page.selectNoData')"
        :loading="loading"
        :disabled="disabled"
        :readonly="readonly"
        return-object
        dense
        @change="onChange"
        @keydown="onInput"
        @click="infiniteScroll"
    >
        <template
            #prepend-item
            v-if="isAddValue"
        >
            <button
                class="v-list-button"
                type="button"
                @click="toggleListPopup"
            >
                <SvgSprite
                    class="icon"
                    :width="12"
                    :height="12"
                    iconId="add_icon"
                />
                <span class="text">
                    <template v-if="!isListPopup">
                        {{ $t('page.addingTitle') }}
                    </template>
                    <template v-else>
                        {{ $t('page.hidedTitle') }}
                    </template>
                </span>
            </button>
            <div class="v-list-popup" v-if="isListPopup">
                <div class="form">
                    <div class="form-content">
                        <div class="form-field">
                            <input
                                type="text"
                                class="field"
                                placeholder="Введите название"
                                v-model="listTitle"
                            >
                        </div>
                        <div class="form-actions">
                            <button
                                class="base-button base-button--lighten--transparent"
                                type="button"
                                :disabled="$v.$invalid"
                                @click="addListItem"
                            >
                                {{ $t('page.addingTitle') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </v-autocomplete>
</template>

<script>
import debounce from 'lodash.debounce'
import { REGIONS, COUNTRIES } from './constants'
import { MODE_TYPES } from '@/constants/constants'
import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
import { required } from "vuelidate/lib/validators"

export default {
    name: 'Autocomplete',
    props: {
        list: Array,
        id: Number,
        placeholder: String,
        itemText: String,
        itemValue: String,
        menuProps: Object,
        loading: Boolean,
        type: String,
        uniq: Object,
        isAddValue: Boolean,
        customClass: String,
        readonly: Boolean,
        modeTypes: String
    },
    components: {
        SvgSprite
    },
    validations: { listTitle: { required } },
    data: () => ({
        disabled: false,
        value: null,
        isListPopup: false,
        listTitle: null,
        paginateCounter: 1,
        copyMenuProps: {},
        disableScroll: true
    }),
    watch: {
        list(val, oldVal) {
            if(val.length === oldVal.length) {
                this.disableScroll = false
            }
        },
        id(value) {
            this.paginateCounter = 1
            this.disableScroll = true

            if(this.modeTypes === MODE_TYPES.VIEW || this.modeTypes === MODE_TYPES.EDIT || this.modeTypes === MODE_TYPES.CHANGE) {
                this.value = value
                this.disabled = false
            }
        },
        uniq(value, oldVal) {
            if(value.id !== oldVal?.id) {
                if(value.key === COUNTRIES) {
                    this.value = null
                    this.disabled = !(this.type === REGIONS)
                }

                if(value.key === REGIONS && this.type !== REGIONS) {
                    this.disabled = false
                    this.value = null
                }
            }
        }
    },
    methods: {
        onChange(val) {
            this.value = val
            this.$emit('updateValue', val)
        },
        onInput: debounce(function(val) {
            console.log('debounce', val)
        }, 300),
        infiniteScroll: debounce(function() {
            const dropdown = document.querySelector(`.${this.type}.autocomplete-dropdown`)
            dropdown.addEventListener('scroll', () => {
                if(dropdown.scrollTop + dropdown.clientHeight >= dropdown.scrollHeight) {
                    this.paginateCounter += 1
                    this.$emit('updatePagePaginate', this.paginateCounter)
                }
            })
        }, 300),
        toggleListPopup() {
            const { contentClass } = this.copyMenuProps
            this.isListPopup = !this.isListPopup
            if(this.isListPopup) {
                let result = contentClass.split(' ')
                result = [ ...result, 'active-popup' ]
                this.copyMenuProps.contentClass = result.join(' ')
            } else {
                let result = contentClass.split(' ')
                result.pop()
                this.copyMenuProps.contentClass = result.join(' ')
            }
        },
        addListItem() {
            this.toggleListPopup()
            this.$emit('addListItem', this.listTitle)
        },
    },
    created() { this.copyMenuProps = { ...this.menuProps } },
    mounted() {
        if(this.type !== COUNTRIES) this.disabled = true
        if(this.modeTypes === MODE_TYPES.VIEW || this.modeTypes === MODE_TYPES.EDIT || this.modeTypes === MODE_TYPES.CHANGE) {
            this.value = this.id
            this.disabled = false
        }
    }
}
</script>

<style lang="sass">
  @import "./autocomplite"
</style>
