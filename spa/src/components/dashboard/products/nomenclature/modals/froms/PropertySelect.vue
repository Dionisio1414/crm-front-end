<template>
    <div class="select-wrap" v-click-outside="onClickOutside">
        <div class="label-title"> {{ property.title }}</div>
        <v-select
            :placeholder="placeholder"
            class="select-switcher"
            :items="property.property_value"
            item-text="title"
            item-value="id"
            v-model="selectedProperty"
            @change="changeProperty"
            no-data-text="Нет значений"
            solo
            dense
            menu-props="units"
        >
        </v-select>
        <div class="add-property-value">
            <div @click="openProperty">
              <svg-sprite width="12" height="12" icon-id="plusBlue"></svg-sprite>
                Добавить
            </div>
            <add-value @add-value="addValue" v-if="isShowModal"></add-value>
        </div>

    </div>
</template>

<script>
    import AddValue from "@/components/dashboard/products/nomenclature/modals/froms/addValue";

    export default {
        name: "PropertySelect",
        components: {AddValue},
        props: ['property', 'value', 'placeholder'],
        data() {
            return {
                selectedProperty: '',
                isShowModal: false
            }
        },
        created() {
            this.selectedProperty = this.value
        },
        watch: {
            value: function (val) {
                console.log(val);
                this.selectedProperty = this.value
            }
        },
        methods: {
            openProperty() {
                console.log('open');
                if (!this.isShowModal) {
                    this.isShowModal = true
                }
            },
            closeProperty() {
                console.log('close');
                if (this.isShowModal) {
                    this.isShowModal = false
                }
            },
            addValue(title) {
                this.closeProperty()
                const isInclude = this.property.property_value.find(value => value.title === title)
                console.log(!isInclude, this.property.property_value, title);
                if (!isInclude) {
                    this.$emit('add-value', {title, id: this.property.id})
                }

            },
            onClickOutside() {
                if (this.isShowModal) {
                    this.isShowModal = false
                }
            },
            changeProperty() {
                const property_value = this.property.property_value.find(item => item.id === this.selectedProperty)
                this.$emit('change-property', property_value)
            }
        }
    }
</script>

<style scoped lang="scss">
    .select-wrap {
        position: relative;
    }

    .add-property-value {
        font-size: 15px;
        line-height: 1;
        color: #5893D4;
        margin-top: 15px;
        cursor: pointer;

        &:hover {
            text-decoration: underline;
        }
    }

</style>
