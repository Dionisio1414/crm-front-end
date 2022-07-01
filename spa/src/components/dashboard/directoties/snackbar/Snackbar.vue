<template>
    <v-snackbar
        class="alertBlock"
        timeout="5000"
        v-model="snackbar"
        @input="closeSnackBar"
    >
        {{ getCorrectTitle }}
        <template v-slot:action="{ attrs }">
            <v-btn
                text
                v-bind="attrs"
                @click="onHandler"
            >
                <svg-sprite style="color: rgb(88,147,212)" width="12" height="12" icon-id="closeWhite"></svg-sprite>
            </v-btn>
        </template>
        <v-overlay opacity="0.2" z-index="-1" :value="snackbar"></v-overlay>
    </v-snackbar>
</template>

<script>
import { MODE_TYPES } from "@/constants/constants"

export default {
    name: "Snackbar",
    props: {
        modeType: String,
        typeTitle: String
    },
    data: () => ({
        snackbar: false
    }),
    computed: {
        getCorrectTitle() {
            switch (this.modeType) {
            case MODE_TYPES.ADD:
                return `${this.$t('directories.alert.createDirectoryItem')} ${this.$t(`${this.typeTitle}`)}`
            case MODE_TYPES.CREATE:
                return `${this.$t('directories.alert.createDirectoryItem')} ${this.$t(`${this.typeTitle}`)}`
            case MODE_TYPES.EDIT:
                return this.$t('directories.alert.changeDirectoryItem')
            case MODE_TYPES.CHANGE:
                return this.$t('directories.alert.changeDirectoryItem')
            case MODE_TYPES.REMOVE:
                return this.$t('directories.alert.deleteDirectoryText')
            default:
                return ''
        }
    },
    },
    methods: {
        onHandler() { this.closeSnackBar() },
        closeSnackBar() { this.$emit('closeSnackbar') }
    },
    mounted() { this.snackbar = true }
}
</script>

<style lang="sass">
  .alertBlock
    .v-snack__wrapper
      background: #E3F0FF !important
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05) !important
      border-radius: 30px !important
      min-height: 35px !important
      bottom: 130px
      .v-snack__content
        font-size: 15px
        line-height: 15px
        text-align: center
        color: #5893D4
      .v-snack__action .v-btn
        min-width: inherit
</style>