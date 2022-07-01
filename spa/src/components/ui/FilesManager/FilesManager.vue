<template>
   <modal-container
    v-if="isOpen" 
    :dialog="isOpen" 
    :modalWidth="1234" 
    @clickOutside="onCloseModal"
    :customDialogClass="['modal-container-files']"
   >
    <template v-slot:header>
        <div class="dialog-header">
            <div class="header-text">
                <span>{{ $t('page.addImage') }}</span>
            </div>
            <div class="dialog-header-actions">
                <button 
                    type="button" 
                    class="base-button pin" 
                >
                    <SvgSprite
                        :width="15"
                        :height="20"
                        iconId="pin"
                    />
                </button>
                <button 
                    type="button" 
                    class="base-button close" 
                    @click="onCloseModal"
                >
                    <SvgSprite
                        style="color: rgb(187,219,254)"
                        :width="14"
                        :height="14"
                        iconId="closeWhite"
                    />
                </button>
            </div>
        </div>
    </template>
    <template v-slot:main>
        <div class="dialog-body">
            <div class="wrapper">
                <div class="wrapper-header">
                    <v-row>
                        <v-col cols="6">
                            <div class="wrapper-header-left">
                                <button class="button button-prev">
                                    <svg width="6" height="12" viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon"><path d="M0.162909 6.42922L4.70779 11.3243C4.8129 11.4376 4.95323 11.5 5.10285 11.5C5.25247 11.5 5.39279 11.4376 5.49791 11.3243L5.83261 10.9639C6.0504 10.729 6.0504 10.3473 5.83261 10.1128L2.01616 6.00228L5.83684 1.88715C5.94196 1.77384 6 1.6228 6 1.46173C6 1.30049 5.94196 1.14944 5.83684 1.03604L5.50215 0.67573C5.39695 0.562422 5.25671 0.5 5.10708 0.5C4.95746 0.5 4.81714 0.562422 4.71202 0.67573L0.162909 5.57525C0.0575425 5.68892 -0.000329774 5.84068 2.08512e-06 6.00201C-0.000329813 6.16397 0.0575425 6.31564 0.162909 6.42922Z"></path></svg>
                                    <span class="btn-text">Назад</span>
                                </button>
                                <button 
                                    v-if="isChange"
                                    type="button" 
                                    class="base-button base-button--lighten--transparent" 
                                    @click="isChange = false"
                                >
                                    {{ $t('page.selectFileTitle') }}
                                </button>
                            </div>
                        </v-col> 
                        <v-col cols="6">
                            <div class="wrapper-header-right">
                                <div class="list-title">{{ $t('page.availableImages') }}</div>
                                <div class="list-search">
                                    <AsyncSimpleInput 
                                        @updateInput="updateInput"
                                    >
                                    </AsyncSimpleInput>
                                </div>
                                <div class="list-actions">
                                    <button type="button" class="sort" @click="sort">
                                        <SvgSprite
                                            :width="13"
                                            :height="12"
                                            iconId="sort"
                                        />
                                    </button>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <div class="wrapper-main">
                    <v-row>
                        <v-col cols="6">
                            <UploadFiles
                                v-if="!isChange"
                                @upload="upload"
                                :key="componentKey"
                            >
                            </UploadFiles>
                            <CropFiles 
                                v-if="isChange"
                                :file="fileUrl"
                                :isLoader="isLoader"
                                @cropped="cropped"
                            >
                            </CropFiles>
                        </v-col>    
                        <v-col cols="6" :style="{'opacity': isEditable ? '.5' : '1'}">
                            <ListFiles 
                                v-if="isOpenComponent"
                                :key="fileKey"
                                :typeField="typeField"
                                :limit="limit"
                                @selectItem="selectItem"
                                @selectItems="selectItems"
                                @removeItem="removeItem"
                            >
                            </ListFiles>
                            <CropFiles 
                                v-else-if="!isOpenComponent"
                                :file="fileUrl"
                                :isLoader="isLoader"
                                @cropped="cropped"
                            >
                            </CropFiles>
                        </v-col>
                    </v-row>
                </div>
            </div>
        </div>
    </template>
    <template v-slot:footer>
        <div class="dialog-footer">
            <div class="form-actions">
                <button 
                    type="button"   
                    class="base-button base-button--transparent" 
                    @click="onCloseModal"
                >
                    {{ $t('page.cancelButton') }}
                </button>
                <button 
                    v-if="!isChange && !multiplyFlag"
                    type="button" 
                    class="base-button base-button--blue" 
                    :disabled="isOpenComponent || isLoader"
                    @click="addFileHandler"
                >
                    <div class="btn-loader" v-if="isLoader"></div>
                    <template v-else>
                        {{ $t('page.addButton') }}
                    </template>
                </button>
                <button 
                    v-if="isChange && !multiplyFlag"
                    type="button" 
                    class="base-button base-button--blue" 
                    @click="editFileHandler"
                    :disabled="isLoader"
                >
                    <div class="btn-loader" v-if="isLoader"></div>
                    <template v-else>
                        {{ $t('page.addButton') }}
                    </template>
                </button>
                <button 
                    v-if="!isChange && multiplyFlag"
                    type="button" 
                    class="base-button base-button--blue" 
                    @click="addMultiplyHandler"
                    :disabled="!files.length"
                >
                    <div class="btn-loader" v-if="isLoader"></div>
                    <template v-else>
                        {{ $t('page.addButton') }}
                    </template>
                </button>
            </div>
        </div>
    </template>
   </modal-container>
</template>
<script>
    import ModalContainer from "../ModalContainer/ModalContainer"
    import SvgSprite from "@/components/ui/SvgSprite/SvgSprite"
    import UploadFiles from "@/components/ui/FilesManager/components/UploadFiles"
    import ListFiles from "@/components/ui/FilesManager/components/ListFiles"
    import CropFiles from "@/components/ui/FilesManager/components/CropFiles"
    import AsyncSimpleInput from "@/components/ui/AsyncSimpleInput/AsyncSimpleInput"
    import { mapActions } from "vuex"
 
    export default {
        name: "FilesManager",
        props: {
            isOpen: Boolean,
            typeField: String,
            limit: Number
        },
        components: {
            ModalContainer,
            SvgSprite,
            UploadFiles,
            ListFiles,
            CropFiles,
            AsyncSimpleInput
        },
        data: () => ({
            isOpenComponent: true,
            fileUrl: null,
            base64_file: null,
            type: null,
            title: null,
            componentKey: 0, 
            fileKey: 0,
            isLoader: false,
            isChange: false,
            isEditable: false,
            successUrl: null,
            files: [],
            filesId: [],
            multiplyFlag: false
        }),
        computed: {},
        methods: {
            ...mapActions(['fetchFiles', 'addFile', 'paginationFilesCounter']),
            onCloseModal() { 
                this.paginationFilesCounter('clear')
                this.$emit('closeFilesManager') 
            },
            upload({ resultFile, fileName, type }) {
                this.isOpenComponent = false
                this.fileUrl = resultFile
                this.title = fileName
                this.type = type
            },
            async addFileHandler() {
                console.log('addFileHandler')
                this.isLoader = true
                await this.addFile({ 
                    payload: {
                        base64_file: this.base64_file,
                        type: "png",
                        title: `${this.title}.png`,
                        is_image: true
                    },
                    mode: false
                }).then(({ data }) => {
                    this.isOpenComponent = true
                    this.isLoader = false
                    this.forceRerender('componentKey')
                    this.paginationFilesCounter('clear')
                    this.$emit('image', { data: data.id, successUrl: data.url })
                    this.onCloseModal()
                })
            },
            async editFileHandler() {
                console.log('editFileHandler')
                this.isLoader = true
                this.isEditable = true
                await this.addFile({ 
                    payload: {
                        base64_file: this.base64_file,
                        type: "png",
                        title: `${this.title}.png`,
                        is_image: true
                    },
                    mode: true
                }).then(({ data }) => { 
                    this.isChange = false
                    this.isLoader = false
                    this.isEditable = false
                    this.forceRerender('fileKey') 
                    this.paginationFilesCounter('clear')
                    this.$emit('image', { data: data.id, successUrl: this.successUrl })
                    this.onCloseModal()
                })
            },
            addMultiplyHandler() {
                console.log('addMultiplyHandler')
                this.$emit('selectItems', this.files, this.filesId)
                this.onCloseModal()
            },
            cropped(value) { this.base64_file = value },
            forceRerender(key) { this[key] += 1 },
            selectItem({ response:fileUrl, title, url }) {
                this.fileUrl = fileUrl
                this.title = title
                this.isChange = true
                this.successUrl = url
            },
            selectItems(arrayFiles, arrayIdFiles) {
                console.log('selectItems', arrayFiles, arrayIdFiles)
                this.multiplyFlag = true
                this.files = arrayFiles
                this.filesId = arrayIdFiles
            },
            removeItem() { this.isChange = false },
            async updateInput(value) { 
                await this.paginationFilesCounter('clear')
                await this.fetchFiles({ isSearch: true, searchValue: value }) 
            },
            sort() { console.log('sort') },
        },
        mounted() { this.fetchFiles() }
    }
</script>
<style lang="sass">
    @import 'sass/main'
</style>