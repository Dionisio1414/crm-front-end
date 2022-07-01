<template>
    <vue-context class="context-menu" ref="contextMenu" :close-on-scroll="true" :close-on-click="closeOnClick">
        <li class="context-menu-item" :class="{'disabled':disabled}" v-for="{ action, disabled } in actions"
            :key="action">
            <div class="item-icon">
                <img :src="require(`@/assets/icons/context_menu/context-${action}.svg`)" alt="">
            </div>
            <a class="context-btn" @click.prevent="emitEvent(action)">{{$t(`context_menu.${action}`)}}</a>
        </li>
    </vue-context>
</template>

<script>
    import {eventBus} from "@/main";

    export default {
        name: "ContextMenu",
        data() {
            return {
                closeOnClick: false,
                isOpen: false
            }
        },
        props: {
            actions: Array,
            state: Boolean
        },
        mounted() {
            eventBus.$on('close-context-menu', () => {
                if (this.isOpen) {
                    this.close()
                }
            })
        },
        methods: {
            open(event) {
                this.$refs.contextMenu.open(event)
                this.isOpen = true
            },
            close() {
                this.$refs.contextMenu.close()
                this.isOpen = false
            },
            emitEvent(action) {
                this.$emit('context-event',action)
                this.close()
            }
        }
    }
</script>

<style scoped lang="scss">
    .context-menu-item {
        &.disabled {
            .item-icon {
                background-color: #9DCCFF;
            }
            .context-btn, .item-icon{
                cursor: none;
                opacity: .7;
                pointer-events: none;
            }
        }
    }

</style>
