<template>
    <modal-container v-if="isModalOpen"
                     @clickOutside="close" :dialog="isModalOpen" :custom-dialog-class="['non-categories-modal']"
                     :modalWidth="920">
        <template slot="header">
            <div class="dialog-header">
                <div class="header-text">
                    {{$t('nomenclature.non_categories_title')}}
                </div>
                <div class="progress progress-category">

                </div>
                <div class="dialog-header-actions">
                    <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="close">
                      <svg-sprite width="16" height="16" icon-id="cross"></svg-sprite>
                    </v-btn>
                </div>
            </div>
        </template>
        <template slot="main">
            <p class="main-text"> {{$t('nomenclature.non_categories_text')}}</p>
            <div class="input-wrapper">
                <input type="text" v-model="categoryTitle">

            </div>
        </template>
        <template slot="footer">
            <div class="dialog-footer dialog-actions popup-buttons">
                <v-btn class="popup-btn transparent-btn"
                       color="#5893D4"
                       text
                       @click="close"
                >
                    {{$t('page.cancelButton')}}
                </v-btn>
                <v-btn class="popup-btn"
                       color="#5893D4"
                       dark
                       @click="save"
                >
                    {{$t('page.save_and_continue')}}
                </v-btn>
            </div>

        </template>
    </modal-container>
</template>

<script>
    import {mapActions} from 'vuex';
    import ModalContainer from "../../../../ui/ModalContainer/ModalContainer";

    export default {
        name: "NoneCategoriesModal",
        components: {ModalContainer},
        data() {
            return {
                categoryTitle: ''
            }
        },
        props: {
            isModalOpen: Boolean
        },
        methods: {
            ...mapActions ({
                addNewCategory: 'addNewCategory',
            }),
            close() {
                this.$emit('close-modal')
            },
            save() {
                const newCategory = {
                    title: this.categoryTitle
                }
                this.addNewCategory(newCategory).then(() => {
                    this.$emit('category-create')
                })
            }
        }
    }
</script>
