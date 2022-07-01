<template>
    <v-dialog
            :value="dialog"
            :max-width="modalWidth"
            persistent
            @click:outside="onClickOutside"
    >
        <div class="dialog modal-container" :class="customDialogClass">
            <slot name="header"/>
            <slot name="main"/>
            <slot name="footer"/>
        </div>
    </v-dialog>
</template>

<script>
    export default {
        name: "ModalContainer",
        data() {
            return {
                confirm: false
            }
        },
        props: {
            dialog: Boolean,
            modalWidth: Number,
            customDialogClass: [Array, String],
        },
        methods: {
            onClickOutside() {
                this.$emit('clickOutside')
            },
        }
    }
</script>

<style lang="scss">
    @import "scss/modalContainer";

    .dialog {
        &.modal-container {
            background-color: white;
            .dialog-main, .dialog-footer {
                background: rgb(255, 255, 255);
                background: linear-gradient(90deg, rgba(255, 255, 255, 0.6194852941176471) 0%, rgba(255, 255, 255, 0.8071603641456583) 21%, rgba(255, 255, 255, 0.9556197478991597) 49%, rgba(255, 255, 255, 1) 100%);
                padding: 30px;
            }
        }
    }

</style>