<template>
  <div class="dialog-wrap">
    <v-dialog
        v-model="open"
        max-width="920px"
        class="dialog-large dialog-category"
    >
      <div class="dialog">
        <div class="dialog-header dialog-header-delete">
          <div class="header-text">
            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6 7.14258H7.5V15.714H6V7.14258Z" fill="white"/>
              <path d="M10.5 7.14258H12V15.714H10.5V7.14258Z" fill="white"/>
              <path d="M0 2.85742V4.28598H1.5V18.5716C1.5 18.9505 1.65804 19.3139 1.93934 19.5818C2.22064 19.8497 2.60218 20.0002 3 20.0002H15C15.3978 20.0002 15.7794 19.8497 16.0607 19.5818C16.342 19.3139 16.5 18.9505 16.5 18.5716V4.28598H18V2.85742H0ZM3 18.5716V4.28598H15V18.5716H3Z" fill="white"/>
              <path d="M6 0H12V1.42856H6V0Z" fill="white"/>
            </svg>
            <span v-if="confirm === false">{{ $t('page.contextMenu.remove') }}</span>
            <span v-else>{{ $t('page.done') }}</span>
          </div>
          <div class="dialog-header-actions">
            <button class="close" type="button" @click="close">
                <svg class="icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.98235 5.7228L13.7052 0L15.9614 2.25629L10.2386 7.97909L16 13.7405L13.7405 16L7.97909 10.2386L2.25629 15.9614L0 13.7052L5.7228 7.98235L0.0353056 2.29485L2.29485 0.0353032L7.98235 5.7228Z" fill="#BBDBFE"/>
                </svg>
            </button>
          </div>
        </div>
        <div class="dialog-body dialog-body-delete">
          <div class="form">
            <div v-if="!isDocument" class="form-content">
                <transition name="fade">
                    <div class="form-content-text" v-if="confirm === false">
                        {{ $t('page.isConfirm') }} <strong>{{ $t('page.suppliers.modal.createSupplier.mainTitle.remove') }}</strong> 
                        <template v-if="typeof options.directory === 'object'">
                          <template v-for="val in options.directory">
                            {{ $t(val) }}
                          </template>
                        </template>
                        <template v-else>
                          {{ $t(options.directory) }}
                        </template>
                        <strong>"{{ options.items }}"</strong> ?
                    </div>
                    <div class="form-content-text" v-else>
                        <template v-if="typeof options.directory === 'object'">
                          <template v-for="val in options.directory">
                            {{ $t(val) | capitalize }}
                          </template>
                        </template>
                        <template v-else>
                          {{ $t(options.directory) | capitalize }}
                        </template>
                        <strong v-if="isNumberItems">({{ options.items }})</strong><strong v-else>"{{ options.items }}"</strong> в архиве.
                        <span class="subtext">Автоматическое удаление через 30 дней</span>
                    </div>
                </transition>
            </div>
            <div v-else class="form-content">
              <transition name="fade">
                <div class="form-content-text" v-if="confirm === false">
                  Вы действительно хотите удалить “{{ options.items }}” ?
                </div>
                <div class="form-content-text" v-else>
                  Документ(ы) “Оприходование запасов” в архиве.
                  <span class="subtext">Автоматическое удаление через 30 дней</span>
                </div>
              </transition>
            </div>
            <div class="form-actions">
                <transition name="fade" mode="out-in">
                    <router-link
                        v-show="confirm" 
                        to="/system_management/archive" 
                        tag="button" 
                        class="button base-button base-button--transparent"
                    >
                        {{ $t('page.toArchive') }}
                    </router-link>
                </transition>
                <button 
                    v-show="confirm === false"
                    type="button" 
                    class="button base-button base-button--transparent" 
                    @click="close">
                    {{ $t('page.cancelButton') }}
                </button>
                <button 
                    v-show="confirm === false"
                    type="button" 
                    class="button base-button base-button--blue"
                    @click="toArchive"
                    >
                    {{ $t('page.suppliers.modal.createSupplier.firstForm.yes') }}
                </button>
                <transition name="fade" mode="out-in">
                    <button 
                        v-show="confirm"
                        type="button" 
                        class="button base-button base-button--blue"
                        @click="close"
                        >
                         Ок
                    </button>
                </transition>
            </div>
          </div>
        </div>
      </div>
    </v-dialog>
  </div>
</template>
<script>
export default {
    name: "ModalDelete",
    props: {
      isDocument: Boolean
    },
    data: () => ({
        open: false,
        confirm: false,
        data: null,
        options: {
            directory: '',
            items: ''
        }
    }),
    computed: {
        isNumberItems() { return Number.isInteger(this.options.items) }
    },
    methods: {
        close() {
            this.open = false
            setTimeout(() => {
                this.confirm = false
                this.options = {}
            }, 500)
        },
        toArchive() { 
            this.confirm = !this.confirm
            this.$emit('successDelete', this.data) 
        }
    }
}
</script>
<style lang="sass" scoped>
    .fade-enter, 
    .fade-leave-to
        opacity: 0
    .fade-enter-active, 
    .fade-leave-active
        transition: opacity .5s
</style>