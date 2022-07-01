<template>
    <div class="passwords">
        <v-row>
            <v-col cols="12">
                <v-row>
                    <v-col cols="12">
                        <div class="field-wrapper">
                            <div class="form-actions-password">
                                <div class="form-field form-field-password">
                                    <div class="label-title">Старый пароль</div>
                                    <input 
                                        class="field"
                                        :type="typesPassword.typeOldPass" 
                                        placeholder="Старый пароль" 
                                        v-model="passwordOld"
                                        @input="onValidPassword"
                                        @change="changeField"
                                    >
                                    <button 
                                        type="button" 
                                        class="btn btn-toggle" 
                                        @click="showPassword($event, 'old_password')" 
                                        v-if="typesPassword.stateOldPass === false"
                                        :disabled="passwordOld.length > 0 ? false : true"
                                    >
                                        <svg class="icon" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9 0C7.07143 0 5.4 0.533333 3.98571 1.6L5.01429 2.53333C6.17143 1.73333 7.45714 1.33333 9 1.33333C13.2429 1.33333 16.7143 4.93333 16.7143 9.33333H18C18 4.13333 14.0143 0 9 0ZM0 1.33333L2.05714 3.33333C0.771428 4.93333 0 7.06667 0 9.33333H1.28571C1.28571 7.33333 1.92857 5.6 3.08571 4.26667L5.91429 6.93333C5.4 7.6 5.14286 8.4 5.14286 9.33333C5.14286 11.6 6.81429 13.3333 9 13.3333C10.0286 13.3333 10.9286 12.9333 11.5714 12.2667L15.4286 16L16.3286 15.0667L0.9 0.4L0 1.33333ZM6.81429 7.86667L10.5429 11.4667C10.1571 11.7333 9.64286 12 9 12C7.58571 12 6.42857 10.8 6.42857 9.33333C6.42857 8.8 6.55714 8.26667 6.81429 7.86667ZM12.8571 10L11.5714 8.8C11.3143 7.73333 10.4143 6.8 9.25714 6.66667L7.97143 5.46667C8.35714 5.33333 8.61429 5.33333 9 5.33333C11.1857 5.33333 12.8571 7.06667 12.8571 9.33333V10Z" fill="#9DCCFF"/>
                                        </svg>
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn btn-toggle" 
                                        @click="showPassword($event, 'old_password')" 
                                        v-if="typesPassword.stateOldPass === true"
                                        :disabled="passwordOld.length > 0 ? false : true"
                                    >
                                        <svg class="icon" width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0 9.1C0 4.03 3.98571 0 9 0C14.0143 0 18 4.03 18 9.1H16.7143C16.7143 4.81 13.2429 1.3 9 1.3C4.75714 1.3 1.28571 4.81 1.28571 9.1H0ZM5.14286 9.1C5.14286 6.89 6.81429 5.2 9 5.2C11.1857 5.2 12.8571 6.89 12.8571 9.1C12.8571 11.31 11.1857 13 9 13C6.81429 13 5.14286 11.31 5.14286 9.1ZM6.42857 9.1C6.42857 10.53 7.58571 11.7 9 11.7C10.4143 11.7 11.5714 10.53 11.5714 9.1C11.5714 7.67 10.4143 6.5 9 6.5C7.58571 6.5 6.42857 7.67 6.42857 9.1Z" fill="#5893D4"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="error-icon" v-show="!getAuthCheckPassword && passwordOld.length">
                                    <svg class="icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0C3.58214 0 0 3.58214 0 8C0 12.4179 3.58214 16 8 16C12.4179 16 16 12.4179 16 8C16 3.58214 12.4179 0 8 0ZM8 14.6429C4.33214 14.6429 1.35714 11.6679 1.35714 8C1.35714 6.41071 1.91607 4.95 2.84821 3.80714L12.1929 13.1518C11.05 14.0839 9.58929 14.6429 8 14.6429ZM13.1518 12.1929L3.80714 2.84821C4.95 1.91607 6.41071 1.35714 8 1.35714C11.6679 1.35714 14.6429 4.33214 14.6429 8C14.6429 9.58929 14.0839 11.05 13.1518 12.1929Z" fill="#FF9F2F"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <div class="field-wrapper">
                            <div class="form-field form-field-password">
                                <div class="label-title">Новый пароль</div>
                                <input 
                                    class="field"
                                    :type="typesPassword.typeNewPass" 
                                    placeholder="Новый пароль" 
                                    v-model="passwordNew"
                                    :disabled="!getAuthCheckPassword"
                                    @keyup="checkNumAndWord"
                                    @change="changeField"
                                >
                                <button 
                                    type="button" 
                                    class="btn btn-toggle" 
                                    @click="showPassword($event, 'new_password')" 
                                    v-if="typesPassword.stateNewPass === false"
                                    :disabled="passwordNew.length > 0 ? false : true"
                                >
                                    <svg class="icon" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9 0C7.07143 0 5.4 0.533333 3.98571 1.6L5.01429 2.53333C6.17143 1.73333 7.45714 1.33333 9 1.33333C13.2429 1.33333 16.7143 4.93333 16.7143 9.33333H18C18 4.13333 14.0143 0 9 0ZM0 1.33333L2.05714 3.33333C0.771428 4.93333 0 7.06667 0 9.33333H1.28571C1.28571 7.33333 1.92857 5.6 3.08571 4.26667L5.91429 6.93333C5.4 7.6 5.14286 8.4 5.14286 9.33333C5.14286 11.6 6.81429 13.3333 9 13.3333C10.0286 13.3333 10.9286 12.9333 11.5714 12.2667L15.4286 16L16.3286 15.0667L0.9 0.4L0 1.33333ZM6.81429 7.86667L10.5429 11.4667C10.1571 11.7333 9.64286 12 9 12C7.58571 12 6.42857 10.8 6.42857 9.33333C6.42857 8.8 6.55714 8.26667 6.81429 7.86667ZM12.8571 10L11.5714 8.8C11.3143 7.73333 10.4143 6.8 9.25714 6.66667L7.97143 5.46667C8.35714 5.33333 8.61429 5.33333 9 5.33333C11.1857 5.33333 12.8571 7.06667 12.8571 9.33333V10Z" fill="#9DCCFF"/>
                                    </svg>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-toggle" 
                                    @click="showPassword($event, 'new_password')" 
                                    v-if="typesPassword.stateNewPass === true"
                                    :disabled="passwordNew.length > 0 ? false : true"
                                    >
                                    <svg class="icon" width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 9.1C0 4.03 3.98571 0 9 0C14.0143 0 18 4.03 18 9.1H16.7143C16.7143 4.81 13.2429 1.3 9 1.3C4.75714 1.3 1.28571 4.81 1.28571 9.1H0ZM5.14286 9.1C5.14286 6.89 6.81429 5.2 9 5.2C11.1857 5.2 12.8571 6.89 12.8571 9.1C12.8571 11.31 11.1857 13 9 13C6.81429 13 5.14286 11.31 5.14286 9.1ZM6.42857 9.1C6.42857 10.53 7.58571 11.7 9 11.7C10.4143 11.7 11.5714 10.53 11.5714 9.1C11.5714 7.67 10.4143 6.5 9 6.5C7.58571 6.5 6.42857 7.67 6.42857 9.1Z" fill="#5893D4"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="popup-rules" v-show="passwordNew.length && !passwordRepeat.length">
                                <div class="popup-rules-caption">Создайте пароль, который:</div>
                                <div class="popup-rules-list">
                                    <div class="popup-rules-list__item" :class="{'popup-rules-list__item--complete': $v.passwordNew.minLength}">
                                        Содержит минимум 8 символов
                                    </div>
                                    <div class="popup-rules-list__item" :class="{'popup-rules-list__item--complete': checkPassValidate}">
                                        Содержит хотя бы одну цифру и букву
                                    </div>
                                    <div class="popup-rules-list__item" :class="{'popup-rules-list__item--complete': checkLatinPass}">
                                        Содержит только латинские буквы
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <div class="field-wrapper">
                            <div class="form-field form-field-password">
                                <div class="label-title">Повторите пароль</div>
                                <input 
                                    class="field"
                                    :type="typesPassword.typeRepeatPass" 
                                    placeholder="Повторите пароль" 
                                    v-model="passwordRepeat"
                                    :disabled="!getAuthCheckPassword"
                                    @input="changePassword"
                                    @change="changeField"
                                >
                                <button 
                                    type="button" 
                                    class="btn btn-toggle" 
                                    @click="showPassword($event, 'repeat_password')" 
                                    v-if="typesPassword.stateRepeatPass === false"
                                    :disabled="passwordRepeat.length > 0 ? false : true"
                                >
                                    <svg class="icon" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9 0C7.07143 0 5.4 0.533333 3.98571 1.6L5.01429 2.53333C6.17143 1.73333 7.45714 1.33333 9 1.33333C13.2429 1.33333 16.7143 4.93333 16.7143 9.33333H18C18 4.13333 14.0143 0 9 0ZM0 1.33333L2.05714 3.33333C0.771428 4.93333 0 7.06667 0 9.33333H1.28571C1.28571 7.33333 1.92857 5.6 3.08571 4.26667L5.91429 6.93333C5.4 7.6 5.14286 8.4 5.14286 9.33333C5.14286 11.6 6.81429 13.3333 9 13.3333C10.0286 13.3333 10.9286 12.9333 11.5714 12.2667L15.4286 16L16.3286 15.0667L0.9 0.4L0 1.33333ZM6.81429 7.86667L10.5429 11.4667C10.1571 11.7333 9.64286 12 9 12C7.58571 12 6.42857 10.8 6.42857 9.33333C6.42857 8.8 6.55714 8.26667 6.81429 7.86667ZM12.8571 10L11.5714 8.8C11.3143 7.73333 10.4143 6.8 9.25714 6.66667L7.97143 5.46667C8.35714 5.33333 8.61429 5.33333 9 5.33333C11.1857 5.33333 12.8571 7.06667 12.8571 9.33333V10Z" fill="#9DCCFF"/>
                                    </svg>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-toggle" 
                                    @click="showPassword($event, 'repeat_password')" 
                                    v-if="typesPassword.stateRepeatPass === true"
                                    :disabled="passwordRepeat.length > 0 ? false : true"
                                    >
                                    <svg class="icon" width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 9.1C0 4.03 3.98571 0 9 0C14.0143 0 18 4.03 18 9.1H16.7143C16.7143 4.81 13.2429 1.3 9 1.3C4.75714 1.3 1.28571 4.81 1.28571 9.1H0ZM5.14286 9.1C5.14286 6.89 6.81429 5.2 9 5.2C11.1857 5.2 12.8571 6.89 12.8571 9.1C12.8571 11.31 11.1857 13 9 13C6.81429 13 5.14286 11.31 5.14286 9.1ZM6.42857 9.1C6.42857 10.53 7.58571 11.7 9 11.7C10.4143 11.7 11.5714 10.53 11.5714 9.1C11.5714 7.67 10.4143 6.5 9 6.5C7.58571 6.5 6.42857 7.67 6.42857 9.1Z" fill="#5893D4"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="popup-rules popup-rules--alone" v-show="passwordRepeat.length">
                                <div class="popup-rules-list">
                                    <div class="popup-rules-list__item" :class="{'popup-rules-list__item--complete': $v.passwordRepeat.sameAsPassword}">
                                        <template v-if="$v.passwordRepeat.sameAsPassword">
                                            Пароли совпадают :)
                                        </template>
                                        <template v-if="!$v.passwordRepeat.sameAsPassword">
                                            Пароли не совпадают :(
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
import { validationMixin } from 'vuelidate'
import { minLength, sameAs } from 'vuelidate/lib/validators'
import debounce from 'lodash.debounce'

export default {
    name: "ChangePassword",
    mixins: [validationMixin],
    validations: {
        passwordNew: {
            minLength: minLength(8)
        },
        passwordRepeat: { 
            sameAsPassword: sameAs('passwordNew') 
        },
    },
    data: () => ({
        checkPassValidate: false,
        checkLatinPass: false,
        typePass: 'password',
        passwordOld: "",
        passwordNew: "",
        passwordRepeat: "",
        typesPassword: {
            typeOldPass: 'password',
            typeNewPass: 'password',
            typeRepeatPass: 'password',
            stateOldPass: false,
            stateNewPass: false,
            stateRepeatPass: false,
        },
    }),
    computed: { ...mapGetters(['getAuthCheckPassword', 'getAuthChangePassword']) },
    methods: {
        ...mapActions(['checkOldPassword']),
        showPassword({ target }, type) {
            console.log(true)
            const currentInputType = target.closest('.btn').previousElementSibling.type
            if(type === 'old_password') {
                if(currentInputType === 'password') {
                    this.typesPassword.typeOldPass = 'text'
                    this.typesPassword.stateOldPass = true
                } else {
                    this.typesPassword.typeOldPass = 'password'
                    this.typesPassword.stateOldPass = false
                }
            }

            if(type === 'new_password') {
                if(currentInputType === 'password') {
                    this.typesPassword.typeNewPass = 'text'
                    this.typesPassword.stateNewPass = true
                } else {
                    this.typesPassword.typeNewPass = 'password'
                    this.typesPassword.stateNewPass = false
                }
            }

            if(type === 'repeat_password') {
                if(currentInputType === 'password') {
                    this.typesPassword.typeRepeatPass = 'text'
                    this.typesPassword.stateRepeatPass = true
                } else {
                    this.typesPassword.typeRepeatPass = 'password'
                    this.typesPassword.stateRepeatPass = false
                }
            }

        },
        checkNumAndWord() {
            if( this.passwordNew.match(/[a-zA-Z]/g) && this.passwordNew.match(/[0-9]/g) ) {
                this.checkPassValidate = true
            } else {
                this.checkPassValidate = false
            }
            if ( !this.passwordNew.match(/[\u0400-\u04FF]/gi) ) {
                this.checkLatinPass = true
            } else {
                this.checkLatinPass = false
            }
        },
        onValidPassword: debounce(function(e) {
            this.typesPassword.typeNewPass = 'password'
            this.typesPassword.typeRepeatPass = 'password'
            this.passwordNew = ""
            this.passwordRepeat = ""
            this.checkOldPassword({ 
               data: { password: e.target.value },
               type: 'checkPassword' 
            })
        }, 500),
        changePassword() {
            console.log('sameAsPassword', this.$v.passwordRepeat.sameAsPassword)
            this.$v.passwordRepeat.sameAsPassword && this.$emit('updatePassword', { 
                data: { 
                    password: this.passwordRepeat
                },
                type: 'checkChangePassword'
            })
        },
        changeField() { this.$emit('changeField') }
    },
}
</script>
<style lang="sass">

.popup-rules
    position: absolute
    top: 0
    right: 0
    background: #F4F9FF
    border-radius: 10px
    padding: 15px 20px 15px 35px
    width: 55%
    z-index: 1
    &::before
        content: ""
        position: absolute
        left: -15px
        top: 22px
        width: 0
        height: 0
        border-top: 7px solid transparent
        border-bottom: 7px solid transparent
        border-right: 15px solid #F4F9FF
    &--alone
        &::before
            top: 50%
            transform: translateY(-50%)
    &-caption
        font-size: 14px
        line-height: 1
        font-weight: 700
        margin-bottom: 10px
        color: #5893D4
    &-list
        &__item
            font-size: 13px
            font-weight: normal
            line-height: 17px
            color: #7E7E7E
            &:not(:last-child)
                margin-bottom: 10px
            &--complete
                color: #01A744

</style>