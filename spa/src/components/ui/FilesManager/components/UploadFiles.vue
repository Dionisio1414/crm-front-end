<template>
    <div class="upload-main">
        <label class="upload-label">
            <div class="upload-wrapper">
                <input 
                    type="file" 
                    name="files" 
                    class="upload-field" 
                    ref="field"
                    accept="image/*"
                    @change="uploadFile"
                >
                <div class="upload-title-error" v-if="isError">{{ errorMessage }}</div>
                <div class="upload-title" v-if="!isLoad">
                    Перетащите файл .jpg / .png сюда
                    <span class="gray-text">
                        (не более 5 Mb)
                    </span>
                    <span class="divider">или</span>  
                </div>
                <div class="upload-title-success" v-else>
                    <strong>Фото {{ fileName }} загружено.</strong>
                    Сохраните изменения или выбирете другой файл.
                </div>
                <div class="upload-actions">
                    <button 
                        type="button" 
                        class="base-button base-button--lighten--transparent" 
                        @click="changeField"
                    >
                        {{ $t('page.selectFileTitle') }}
                    </button>
                </div>
            </div>
        </label>
    </div>
</template>
<script>
import { mapGetters } from "vuex"

export default {
    name: "UploadFiles",
    data: () => ({
        isLoad: false,
        fileName: null,
        isError: false,
        errorMessage: ''
    }),
    computed: {
        ...mapGetters(['getFiles'])
    },
    methods: {
        changeField() { this.$refs.field.click() },
        uploadFile({ target }) {
            const input = target
            const { name, size, type } = input.files[0]
            const validateResult = this.validateFile(name, size) 
            // const checkTitles = this.checkTitle(name.split('.').shift())
            const modifyType = type.split('/').pop()

            if(typeof validateResult === 'string') {
                this.errorMessage = validateResult
                this.isError = true
            } else {
                this.isError = false
                this.errorMessage = ''
                this.successUploadFile(name, input, modifyType)
            }

            // } else if(checkTitles.length) {
            // this.errorMessage = "Файл с таким наименованием уже существует."
            // this.isError = true

        },
        successUploadFile(name, input, type) {
            this.fileName = name.split('.').shift()
            this.isLoad = true
            if(input.files && input.files[0]) {
                const reader = new FileReader()
                reader.onload = e => { 
                    this.resultFile = e.target.result
                    this.$emit('upload', { resultFile: e.target.result, fileName: this.fileName, type })
                }
                reader.readAsDataURL(input.files[0])
            }
        },
        validateFile(name, size) {
            const allowedExtensions = ['jpg', 'jpeg', 'png']
            const sizeLimit = 5000000 // 5MB
            const fileExtension = name.split(".").pop()

            if(!allowedExtensions.includes(fileExtension))
                return "Файл содержит недопустимый формат. Пожалуйста, загрузите изображение."
            else if(size > sizeLimit) 
                return "Файл превышает допустимый размер 5MB."
            else if(size > sizeLimit && !allowedExtensions.includes(fileExtension))
                return "Файл превышает допустимый размер 5MB и содержит недопустимый формат."
            else
                return true

        },
        checkTitle(title) {
            return this.getFiles.slice().filter(item => item.title.includes(title))
        }
    },
    mounted() {}    
}
</script>
<style scoped>

</style>