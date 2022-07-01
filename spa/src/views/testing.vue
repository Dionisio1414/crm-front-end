<template>
    <div>
        <!-- 'without-select' for phone default, etc. -->
        <vue-phone
            :defaultCountry="defaultCountry"    
            :preferredCountries="preferredCountries"
            :isRepeater="isRepeater"
            :isFlags="true"
            :customPhoneClass="['phone-default', 'without-select']"
            :disabled="disabledField"
            valueField="380500511217"
            @input="inputHandler"
        >
        </vue-phone>
        <button @click="toggleDefaultCountry">Toggle default country</button>
        <button style="display: block;" @click="onOpenFileManager">File manager</button>
        <button @click="onOpenModal">open</button>
        <modal-container v-if="isModalOpen" @clickOutside="toConfirm" :dialog="isModalOpen" :modalWidth="1200">
            <template v-slot:header>
                <div class="dialog-header">
                    <div class="header-text">
                        <span>Title</span>
                    </div>

                    <div class="dialog-header-actions">
                        <v-btn icon color="#5893D4">
                            <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                                      fill="#BBDBFE"/>
                            </svg>
                        </v-btn>
                        <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="onCloseModal">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                                        fill="#BBDBFE"/>
                            </svg>
                        </v-btn>
                    </div>
                </div>
            </template>
            <template v-slot:main>
                <div class="dialog-main">
                    <h1>
                        main
                    </h1>
                    <input type="text">
                </div>
            </template>
            <template v-slot:footer>
                <div class="dialog-footer">
                    <h1>
                        footer
                    </h1>
                </div>
            </template>
        </modal-container>
        <modal-container @closeModal="onCloseModal" :dialog="isConfirm" :modalWidth="600">
            <template v-slot:main>
                <div class="dialog-main">
                    <h1>confirm?</h1>
                </div>
            </template>
            <template v-slot:footer>
                <div class="dialog-footer">
                    <button @click="onCancel">cancel</button>
                    <button @click="onConfirm">confirm</button>
                </div>
            </template>
        </modal-container>

        <files-manager
            v-if="isOpen"
            :isOpen="isOpen"
            typeField="radio"
            :limit="10"
            @closeFilesManager="closeFilesManager"
            @image="getImages"
            @selectItems="selectItems"
        >
        </files-manager>

        <!-- <div class="grid-table__header-column">
            <div class="header-title">Наименование</div>
            <div class="header-icon">
                <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
            </div>
        </div> -->

        <div class="grid-table">
            <div class="grid-table__header">
                <div class="header-column">
                    <div class="header-column__item"></div>
                    <div class="header-column__item">
                        <div class="checkbox">
                            <label class="checkbox-label">
                                <input type="checkbox">
                                <div class="checkbox-text"></div>
                            </label>
                        </div>
                    </div>
                    <div class="header-column__item">
                        <div class="header-title">ID</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                    <div class="header-column__item">
                        <div class="header-title">Артикул</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                    <div class="header-column__item">
                        <div class="header-title">Наименование</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                </div>
                <div class="header-column">
                    <div class="header-column__item">
                        <div class="header-title">Ед. изм.</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                    <div class="header-column__item">
                        <div class="header-title">Количество</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                </div>
                <div class="header-column">
                    <div class="header-column__item">
                        <div class="header-title">Цена</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                    <div class="header-column__item">
                        <div class="header-title">Скидка</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                    <div class="header-column__item">
                        <div class="header-title">Сумма</div>
                        <div class="header-icon">
                            <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g opacity="0.5"><path d="M12.3 8.3L10.5 10.1V0H9.5V10.1L7.7 8.3L7 9L10 12L13 9L12.3 8.3Z" fill="#5893D4"></path><path d="M3 0L0 3L0.7 3.7L2.5 1.9V12H3.5V1.9L5.3 3.7L6 3L3 0Z" fill="#5893D4"></path></g></svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid-table__body">
                <div class="grid-table__body-row">
                    <label class="body-radio">
                        <input type="radio" name="radio-1">
                        <div class="body-radio-columns">
                            <div class="body-column">
                                <div class="body-column__item">1.</div>
                                <div class="body-column__item">
                                    <div class="checkbox">
                                        <label class="checkbox-label">
                                            <input type="checkbox">
                                            <div class="checkbox-text"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">HG-000001</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                            <div class="body-column">
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                            <div class="body-column">
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL!!!</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL!!</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="grid-table__body-row">
                    <label class="body-radio">
                        <input type="radio" name="radio-1">
                        <div class="body-radio-columns">
                            <div class="body-column">
                                <div class="body-column__item">2.</div>
                                <div class="body-column__item">
                                    <div class="checkbox">
                                        <label class="checkbox-label">
                                            <input type="checkbox">
                                            <div class="checkbox-text"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">HG-000001</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                            <div class="body-column">
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                            <div class="body-column">
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="grid-table__body-row">
                    <label class="body-radio">
                        <input type="radio" name="radio-1">
                        <div class="body-radio-columns">
                            <div class="body-column">
                                <div class="body-column__item">3.</div>
                                <div class="body-column__item">
                                    <div class="checkbox">
                                        <label class="checkbox-label">
                                            <input type="checkbox">
                                            <div class="checkbox-text"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">HG-000001</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                            <div class="body-column">
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                            <div class="body-column">
                                <div class="body-column__item">
                                    <div class="text">456455550</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                                <div class="body-column__item">
                                    <div class="text">Куртка красная XL</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import ModalContainer from "../components/ui/ModalContainer/ModalContainer";
    import FilesManager from '@/components/ui/FilesManager/FilesManager';
    import VuePhone from '@/components/ui/VuePhone/VuePhone'

    export default {
        name: "testing",
        components: { ModalContainer, FilesManager, VuePhone },
        data() {
            return {
                isModalOpen: false,
                isConfirm: false,
                isOpen: false,

                defaultCountry: 'UA',
                preferredCountries: ['UA', 'RU'],
                isRepeater: false,
                disabledField: false
            }
        },
        methods: {
            toConfirm() {
                this.isConfirm = true
            },
            onCloseModal() {
                this.toConfirm()
            },
            onCancel() {
                this.isConfirm = false
            },
            onConfirm() {
                this.isConfirm = false
                this.isModalOpen = false
                console.log('confirm')
            },
            onOpenModal() {
                this.isModalOpen = true
            },
            onOpenFileManager() {
                this.isOpen = true
            },
            closeFilesManager() { this.isOpen = false },
            getImages(val) { console.log(val) },
            selectItems(files, ids) {
                console.log(files, ids)
            },
            toggleDefaultCountry() {
                this.defaultCountry = 'RU'
            },
            inputHandler(val) {
                this.defaultCountry = val
                this.loader = true
            }
            
        }
    }
</script>

<style lang="sass">
    .grid 
        &-table
            &__header
                display: grid
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr))
                grid-gap: 6px   
                .header-column
                    display: flex
                    align-items: center
                    border: 1px solid #E3F0FF
                    border-bottom: 1px solid #9DCCFF
                    border-radius: 10px 10px 0 0
                    padding: 17px 15px
                    &__item
                        display: flex
                        align-items: center
                        line-height: 1
                        cursor: pointer
                        .checkbox
                            &-text
                                &::before
                                    border-color: #7CE1A4
                                &::after
                                    background: #7CE1A4
                        .header
                            &-title
                                font-size: 15px
                                text-transform: capitalize
                                color: #5893D4
                            &-icon
                                margin-left: 7px
            &__body
                &-row
                    .body-radio
                        &-columns
                            display: grid
                            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr))
                            grid-gap: 6px   
                        input
                            display: none
                            &:checked
                                + .body-radio-columns
                                    opacity: .5
                    .body-column
                        display: flex
                        align-items: center
                        padding: 17px 15px
                        border-bottom: 1px solid #E3F0FF
                        transition: background .25s ease-in-out
                        &__item
                            .text
                                font-size: 15px
                                line-height: 1
                                color: #7E7E7E
                            .checkbox
                                &-text
                                    &::before
                                        border-color: #7CE1A4
                                    &::after
                                        background: #7CE1A4
                    &:hover
                        .body-radio
                            &-columns
                                .body-column
                                    background: #F4F9FF
                    

</style>