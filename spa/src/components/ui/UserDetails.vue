<template>
    <!-- personal info edit right widget -->
    <!-- v-click-outside="onClickOutside" -->
    <div class="user-details" v-click-outside="hide">
        <files-manager
            v-if="isOpenManager"
            :isOpen="isOpenManager"
            typeField="radio"
            @closeFilesManager="closeFilesManager"
            @image="getImages"
        >
        </files-manager>
        <!-- <button class="personalInfoButton" @click="popupState = !popupState">
            <img src="@/assets/icons/user-win.svg" alt="">
        </button> -->

        <!-- personalInfoWin--open -->
        <div :class="{'personalInfoWin': true, 'personalInfoWin--open': isOpen}">

            <div class="personalInfoWin__title">
                <button type="button" class="personalInfoWin__close personalInfoWin__close--arrow" @click="closePopup">
                    <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.83709 5.92922L1.29221 10.8243C1.1871 10.9376 1.04677 11 0.897152 11C0.74753 11 0.607207 10.9376 0.50209 10.8243L0.167391 10.4639C-0.0503997 10.229 -0.0503997 9.84733 0.167391 9.61285L3.98384 5.50228L0.163158 1.38715C0.0580402 1.27384 1.41446e-06 1.1228 1.43752e-06 0.961732C1.46061e-06 0.800489 0.0580403 0.649442 0.163158 0.536044L0.497856 0.17573C0.603057 0.0624219 0.743297 1.29962e-07 0.892919 1.56123e-07C1.04254 1.82283e-07 1.18286 0.062422 1.28798 0.17573L5.83709 5.07525C5.94246 5.18892 6.00033 5.34068 6 5.50201C6.00033 5.66397 5.94246 5.81564 5.83709 5.92922Z" fill="#FF9F2F"/>
                    </svg>
                </button>
                <button type="button" class="personalInfoWin__close" @click="closePopup">
                    <img class="close-icon" src="@/assets/icons/user-win--orange.svg" alt="">
                </button>
                <div class="personalInfoWin__heading">Информация профиля</div>			
            </div>

            <div class="personalInfoWin__header">
                <div class="personalInfoWin__photo">
                    <!-- @/assets/img/ava.png -->
                    <!-- <label class="personalInfoWin__label">
                        <input class="personalInfoWin__input" type="file" accept="image/png, image/jpeg, image/bmp" @change="previewImage">
                        <img :src="selectImage" alt=""> 
                        <div class="personalInfoWin__photoAdd">Загрузить фото</div>
                    </label> -->
                    <div class="personalInfoWin__label" @click="openFilesManager">
                        <img :src="selectImage" alt=""> <!-- @/assets/img/ava.png -->
                        <div class="personalInfoWin__photoAdd">Загрузить фото</div>
                    </div>
                </div>
                <div class="personalInfoWin__pos">
                    <div class="personalInfoWin__posName">{{ defaultPositionTitle }}</div>
                    <div class="personalInfoWin__posCategory">Продажи</div>
                </div>
                <div class="personalInfoWin__status personalInfoWin__status--online">В сети</div>
            </div>

            <form class="personalInfoWin__fields">

                <div class="personalInfoWin__field">
                    <label for="personalInfo_name">Имя</label>
                    <input placeholder="Иван" id="personalInfo_name" type="text" name="first_name" v-model="userInfo.first_name" @blur="changeField('first_name')">
                </div>
                <div class="personalInfoWin__field">
                    <label for="personalInfo_lastName">Фамилия</label>
                    <input placeholder="Иванов" id="personalInfo_lastName" type="text" name="last_name" v-model="userInfo.last_name" @blur="changeField('last_name')">
                </div>
                <div class="personalInfoWin__field">
                    <label for="personalInfo_subName">Отчество</label>
                    <input placeholder="Иванович" id="personalInfo_subName" type="text" name="middle_name" v-model="userInfo.middle_name" @blur="changeField('middle_name')">
                </div>
                <div class="personalInfoWin__field personalInfoWin__field--readonly">
                    <label for="personalInfo_email">E-mail</label>
                    <input placeholder="emailexample@gmail.com" id="personalInfo_email" type="email" name="email" v-model="userInfo.email" readonly>
                </div>
                <div class="personalInfoWin__field personalInfoWin__field--readonly">
                    <label for="personalInfo_mobilePhone">мобильный телефон</label>
                    <!-- <input 
                        id="personalInfo_mobilePhone" 
                        type="text" 
                        placeholder="+38 (0__) ___ __ __" 
                        v-mask="'+38 (###) ### ## ##'" 
                        v-model="userInfo.phone" 
                        readonly
                    > -->
                    <vue-phone
                        :defaultCountry="defaultCountry"    
                        :preferredCountries="preferredCountries"
                        :isRepeater="isRepeater"
                        :isFlags="true"
                        :customPhoneClass="['phone-default', 'without-select']"
                        :disabled="disabledField"
                        :valueField="userPhone"
                        @input="inputHandler"
                    >
                    </vue-phone>
                    <!-- <masked-input mask="\+\38 (111) 111 11 11" id="personalInfo_mobilePhone" name="phone" placeholder="+38 (095) 033 66 55" @input="rawVal = arguments[1]" v-model="userInfo.phone" /> -->
                </div>
                <!-- <div class="personalInfoWin__field">
                    <label for="personalInfo_workPhone">рабочий телефон</label>
                    <masked-input mask="\+\38 (111) 111 11 11" id="personalInfo_workPhone" name="work_phone" placeholder="+38 (095) 033 66 55" @input="rawVal = arguments[1]" />
                </div>
                <div class="personalInfoWin__field">
                    <label for="personalInfo_innerPhone">внутренний телефон</label>
                    <masked-input mask="\+\38 (111) 111 11 11" id="personalInfo_innerPhone" name="inner_phone" placeholder="+38 (095) 033 66 55" @input="rawVal = arguments[1]" />
                </div>

                <div class="personalInfoWin__field personalInfoWin__field--short">
                    <label for="personalInfo_pol">пол</label>
                    <input placeholder="Мужской" id="personalInfo_pol" type="text" name="sex">
                </div>
                <div class="personalInfoWin__field personalInfoWin__field--short personalInfoWin__field--date">
                    <label for="personalInfo_birth">День рождения</label>
                    <input placeholder="12.06.1992" id="personalInfo_birth" type="date" name="birth">
                </div>
                <div class="personalInfoWin__field personalInfoWin__field--short">
                    <label for="personalInfo_city">город</label>
                    <input placeholder="Харьков" id="personalInfo_city" type="text" name="city">
                </div>
                <div class="personalInfoWin__field personalInfoWin__field--short personalInfoWin__field--date">
                    <label for="personalInfo_date">дата принятия на работу</label>
                    <input placeholder="22.03.2017" id="personalInfo_date" type="date" name="work_date">
                </div>
                <div class="personalInfoWin__field personalInfoWin__field--short personalInfoWin__field--lasted">
                    <label for="personalInfo_skype">логин skype</label>
                    <input placeholder="Ivan333" id="personalInfo_skype" type="text" name="login_skype">
                </div>
                <div class="personalInfoWin__field personalInfoWin__field--short personalInfoWin__field--lasted">
                    <label for="personalInfo_zoom">zoom</label>
                    <input placeholder="Ivan333" id="personalInfo_zoom" type="text" name="login_zoom">
                </div> -->

            </form>

        </div>
    </div>

</template>
<script>
	import { validationMixin } from 'vuelidate'
	import { email } from 'vuelidate/lib/validators'
    import ClickOutside from 'vue-click-outside'
    // import MaskedInput from 'vue-masked-input'
    import FilesManager from '@/components/ui/FilesManager/FilesManager'
    import { mapGetters, mapActions } from 'vuex'
    import VuePhone from '@/components/ui/VuePhone/VuePhone'

    export default {
        name: "UserDetails",
		mixins: [validationMixin],
        validations: {
            userInfo: {
                email: { email }
            }
        },
        components: {
            // MaskedInput,
            FilesManager,
            VuePhone  
        },
        data: () => ({
            popupState: true,
            selectImage: null,
            rawVal: null,
            isOpenManager: false,
            isOpen: false,
            thumbnail_id: null,
            userInfo: {
                email: null,
                first_name: null,
                last_name: null,
                middle_name: null,
                phone: null,
            },
            defaultCountry: 'UA',
            preferredCountries: ['UA', 'RU'],
            disabledField: true,
            isRepeater: false
        }),
        computed: {
            ...mapGetters([
                'user',
                'getLists',
                'getCurrentFile'
            ]),
            userPhone() {
                return this.user.phone ? this.user.phone.slice(1).replace(/[()]/g, '') : ''
            },
            positionsList() {
                return this.getLists('core_lists')['positions']
            },
            defaultPositionTitle() {
                const idx = +this.user.position_id
                return this.positionsList.find(({ directory_id }) => directory_id === idx).title
            },
            getImageUser() {
                return this.getCurrentFile !== null ? this.getCurrentFile.file : null
            }
        },
        methods: {
            ...mapActions(['fetchLists', 'fetchCurrentFile', 'changeAuth']),
            onClickOutside() {
                this.$emit('close')
            },
            previewImage({ target }) {
                let input = target
                console.log(target)
                if (input.files && input.files[0]) {
                    let reader = new FileReader()
                    reader.onload = e => { 
                        console.log(e.target.result)
                        this.selectImage = e.target.result
                    }
                    reader.readAsDataURL(input.files[0])
                }
            },
            closePopup() {
                this.isOpen = false
                setTimeout(() => this.$emit('close'), 250)
            },
            closeFilesManager() { this.isOpenManager = false },
            openFilesManager() { this.isOpenManager = true },
            getImages({ data, successUrl }) { 
                console.log('get image', data, successUrl)
                this.selectImage = successUrl
                this.thumbnail_id = data
                localStorage.setItem('user_image_id', data)
            },
            hide() { console.log('close') },
            changeField(field) { 
                console.log(this.userInfo[field])
                this.changeAuth({ [field]: this.userInfo[field] }) 
            },
            inputHandler(val) {
                this.defaultCountry = val
                this.loader = true
            }
        },
        async created() {
            console.log('created')
            const isUserImage = localStorage.getItem('user_image_id') !== null ? true : false
            if(isUserImage) { 
                await this.fetchCurrentFile(localStorage.getItem('user_image_id')).then(data => {
                    console.log('datarsr', data.url)
                    this.selectImage = data.url
                })
            }
            await this.fetchLists({ type: 'core', resources: 'positions' })
        },
        mounted() {
            console.log('mounted', this.getCurrentFile)
            const { 
                email, 
                first_name, 
                last_name, 
                middle_name, 
                phone 
            } = this.user

            this.userInfo = { email, first_name, last_name, middle_name, phone }
            setTimeout(() => this.isOpen = true, 250)
        },

        directives: { ClickOutside }
    }
</script>
<style lang="sass" scoped>
    
</style>