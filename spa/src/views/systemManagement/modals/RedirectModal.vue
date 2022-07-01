<template>
    <modal-container v-if="dialogConfirm"
                     :dialog="dialogConfirm" :modalWidth="600">
        <template v-slot:header>
            <div class="dialog-header">
                <div class="header-text">
                    {{$t('page.saveButton')}}
                </div>
                <div class="dialog-header-actions">
                    <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="cancel">
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
            <div class="dialog-content">
                {{ $t('programSettings.redirect_text')}}
            </div>
        </template>

        <template v-slot:footer>
            <div class="dialog-content">
                <slot name="dialog-text"></slot>
                <div class="dialog-actions popup-buttons">
                    <v-btn
                            class="popup-btn transparent-btn"
                            color="#5893D4"
                            text
                            @click.native="cancel"
                    >
                        {{$t('page.cancelButton')}}
                    </v-btn>
                    <v-btn
                            class="popup-btn"
                            color="#5893D4"
                            dark

                            @click.native="agree"
                    >
                        {{$t('page.saveButton')}}
                    </v-btn>
                </div>
            </div>
        </template>

    </modal-container>
</template>

<script>
    import ModalContainer from "../../../components/ui/ModalContainer/ModalContainer";

    export default {
        name: "redirectModal",
        components: {ModalContainer},
        data() {
            return {
                dialogConfirm: false
            }
        },
        methods: {
            open(title, message) {
                this.dialogConfirm = true
                this.title = title
                this.message = message
                return new Promise((resolve, reject) => {
                    this.resolve = resolve
                    this.reject = reject
                })
            },
            agree() {
                this.resolve(true)
                this.dialogConfirm = false
            },

            cancel() {
                this.resolve(false)
                this.dialogConfirm = false
            }
        }
    }
</script>

<style scoped>

</style>