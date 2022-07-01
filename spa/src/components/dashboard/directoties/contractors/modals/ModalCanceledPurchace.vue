<template>
  <modal-container
    v-if="isModalOpen" 
    @clickOutside="onCloseModal"
    :dialog="isModalOpen" 
    :modalWidth="1230"
    :customDialogClass="['modal-container-default']"
  >
    <template v-slot:header>
      <div class="dialog-header">
        <div class="header-text">
          <span>{{ $t('directories.reasonCanceledPurchase') }}</span>
        </div>
        <div class="dialog-header-actions">
          <v-btn icon color="#5893D4" @click="addTab('reason-canceled-purchase', 'directories.reasonCanceledPurchase')" :disabled="checkTabs" v-if="mode === 'create'">
            <svg class="attach" width="15" height="20" viewBox="0 0 15 20" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M7.19206 0L15 4.76753L14.0841 6.44527L12.0646 6.33063L9.77483 10.525L10.7434 14.4718L9.36953 16.9884L5.46556 14.6047L3.63377 17.9601L1.47914 20L2.07218 17.0066L3.90397 13.6512L0 11.2674L1.37384 8.7508L5.09007 7.66445L7.3798 3.47011L6.27616 1.67774L7.19206 0Z"
                    fill="#BBDBFE"/>
            </svg>
          </v-btn>
          <v-btn icon color="#5893D4" class="action-btn sortable-btn" @click="cancel">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z"
                  fill="#BBDBFE"/>
            </svg>
          </v-btn>
        </div>
      </div>
    </template>
    <template v-slot:main>
      <div class="dialog-body">
        <div class="dialog-body-content">
          <form class="form">
            <div class="form-content form-content--singular">
              <v-row>
                <v-col cols="6">
                    <div class="label-title label-title--required label-title--singular">
                      <div class="label-title-text">
                        Причина отмены
                      </div>
                      <span class="default-icon" @click="changeDefaultState">
                        <svg v-if="localFormData.is_default" width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                          <path
                              d="M3.61065 15.9392C3.22465 16.1504 2.78665 15.7803 2.86465 15.3078L3.69465 10.2626L0.171653 6.68298C-0.157347 6.34806 0.0136534 5.73581 0.454653 5.66968L5.35265 4.9273L7.53665 0.31199C7.73365 -0.103997 8.26665 -0.103997 8.46365 0.31199L10.6477 4.9273L15.5457 5.66968C15.9867 5.73581 16.1577 6.34806 15.8287 6.68298L12.3057 10.2626L13.1357 15.3078C13.2137 15.7803 12.7757 16.1504 12.3897 15.9392L7.99865 13.5329L3.60965 15.9392H3.61065Z"
                              fill="#FFE923"/>
                        </svg>
                        <svg v-else width="16" height="16" viewBox="0 0 16 16" fill="none"
                              xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M2.86465 15.3067C2.78665 15.7803 3.22465 16.1504 3.61065 15.9392L8.00065 13.533L12.3897 15.9392C12.7757 16.1504 13.2137 15.7803 13.1357 15.3078L12.3057 10.2628L15.8287 6.68338C16.1577 6.34847 15.9867 5.73625 15.5457 5.67012L10.6477 4.92778L8.46365 0.312663C8.42205 0.219111 8.35641 0.140071 8.27436 0.0847254C8.19232 0.0293796 8.09723 0 8.00015 0C7.90308 0 7.80799 0.0293796 7.72594 0.0847254C7.6439 0.140071 7.57826 0.219111 7.53665 0.312663L5.35265 4.92884L0.454653 5.67119C0.0136534 5.73732 -0.157347 6.34954 0.171653 6.68444L3.69465 10.2639L2.86465 15.3089V15.3067ZM7.76965 12.3555L4.08365 14.3756L4.77765 10.1551C4.79391 10.058 4.78755 9.95804 4.75913 9.86412C4.73071 9.7702 4.6811 9.68517 4.61465 9.61649L1.70865 6.66205L5.76065 6.04769C5.84456 6.03418 5.92414 5.99917 5.99259 5.94568C6.06103 5.89218 6.1163 5.82178 6.15365 5.74052L8.00065 1.83895L9.84665 5.74052C9.88401 5.82178 9.93928 5.89218 10.0077 5.94568C10.0762 5.99917 10.1557 6.03418 10.2397 6.04769L14.2917 6.66098L11.3857 9.61542C11.319 9.68416 11.2692 9.76936 11.2408 9.8635C11.2124 9.95763 11.2061 10.0578 11.2227 10.1551L11.9167 14.3756L8.23065 12.3555C8.15938 12.3163 8.08035 12.2958 8.00015 12.2958C7.91996 12.2958 7.84093 12.3163 7.76965 12.3555Z"
                                fill="#E3F0FF"/>
                          </svg>
                      </span>
                    </div>
                    <div class="form-field">
                      <input 
                        class="field" 
                        :class="{'field-error': mode === 'copy'}"
                        type="text" 
                        :readonly=" mode === 'view'" 
                        v-model="tableCells.title" 
                        placeholder="Введите причину"
                      >
                      <div class="form-field-helper" v-if="mode === 'copy'">Измените название</div>
                    </div>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <div class="validation-rules">{{ $t('directories.validationRules') }}</div>
                </v-col>
              </v-row>
            </div>
          </form>
        </div>
      </div>
    </template>
    <template v-slot:footer>
      <div class="dialog-footer">
        <div class="form-actions">
          <button 
            v-if="mode !== 'view'"
            type="button" 
            class="base-button base-button--transparent" 
            :disabled="loader"
            @click="cancel"
          >
            Назад
          </button>
          <button 
            v-if="mode === 'view'"
            type="button" 
            class="base-button base-button--transparent"
            :disabled="loader" 
            @click="cancel"
          >
            {{ $t('page.close') }}
          </button>
          <button 
            v-if="mode !== 'view'"
            type="button" 
            class="base-button base-button--blue" 
            :disabled="$v.$invalid || loader"
            @click="onSave"
          >
            {{ $t('page.saveButton') }}
          </button>
        </div>
      </div>
    </template>
  </modal-container>
</template>

<script>
import { required } from "vuelidate/lib/validators"
import { mapActions, mapGetters } from 'vuex'
import mixin from '@/mixins/mixinTabs'
import ModalContainer from "@/components/ui/ModalContainer/ModalContainer"

export default {
  name: "ModalCanceledPurchace",
  mixins: [mixin],
  props: {
    itemData: Object,
    isModalOpen: Boolean,
    modeType: String
  },
  components: {
    ModalContainer
  },
  validations: {
    localFormData: {
      cells: {
        title: { required }
      }
    }
  },
  data() {
    return {
      mode: 'create',
      dialog: false,
      loader: false,
      localFormData: {
        id: Math.floor( Math.random() * ( 1 - 50000 + 1 ) ) + 1,
        is_editable: true,
        is_default: false,
        cells: {
            title: ""
        }
      },
    }
  },
  computed: {
    ...mapGetters([
      'tabsLength'
    ]),
    tableCells() {
      return this.localFormData.cells
    }
  },
  methods: {
    ...mapActions([
      'updateTabs',
    ]),
    onCloseModal() { this.$emit('close') },
    onSave() {
      const { params } = this.$route.params

      if(this.mode == 'copy') this.mode = 'create'

      if(params) {
        this.$emit('save', {
          action: this.mode,
          resources: 'counterparties_cancellations',
          data: {...this.localFormData},
          alertItem: 'directories.reasonCanceledPurchase'
        })
        this.$router.go(-1)
        this.$router.push(this.$route.path.slice(0, -params.length))
        this.loader = true
      } else {
        this.$emit('save', {
          action: this.mode,
          resources: 'counterparties_cancellations',
          data: {...this.localFormData},
          alertItem: 'directories.reasonCanceledPurchase'
        })
        this.loader = true
      }
    },
    changeDefaultState() {
      this.localFormData.is_default = !this.localFormData.is_default
      if (this.mode !== 'create') {
        this.$emit('changeDefault', {id: this.localFormData.id, resources: 'counterparties_cancellations'})
      }
    },
    cancel() {
      if(!this.loader) {
        const { params } = this.$route.params
        if(params) { 
          this.$router.go(-1)
          this.$router.push(this.$route.path.slice(0, -params.length))
        }
        this.$emit('close')
      }
    },
  },
  created () {
    this.defaultData = this.localFormData
  },
  mounted() {
    let item = ''
    this.mode = this.modeType
    if(this.mode === 'create') {
      item = this.defaultData
      this.localFormData = item
    } else if(this.mode === 'view') {
      item = this.itemData
      this.localFormData = item
    } else if(this.mode === 'change') {
      item = this.itemData
      this.localFormData = item
    } else {
      item = this.itemData
      this.localFormData = item
      this.localFormData.cells.title += ' (Копия)' 
    }
  }
}
</script>

<style scoped lang="sass">
</style>