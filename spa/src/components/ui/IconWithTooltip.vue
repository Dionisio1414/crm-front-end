<template>
    <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
            <v-icon
                    class="icon-tooltip"
                    :class="customClass"
                    :color="color"
                    dark
                    :disabled="loading"
                    :bind="attrs"
                    @click="onHandler"
                    v-on="on"
            >
                {{icon}}
            </v-icon>
        </template>
        <span>{{ $t(tooltipText) }}</span>
    </v-tooltip>
</template>

<script>
    export default {
        name: "IconWithTooltip",
        props: {
            icon: String,
            color: String,
            tooltipText: String,
            customClass: String,
            loading: Boolean
        },
        methods: {
            onHandler() {
                this.$emit('push-btn')
            }
        }
    }
</script>

<style scoped lang="scss">
    .icon-tooltip {
        border-radius: 50%;
        min-width: 36px;
        min-height: 36px;
        background: #5893D4;
        margin-left: 15px;
        cursor: pointer;

        &:hover {
            background: #FF951A;
        }

        &.delete {
            background: #9DCCFF;

            &:hover {
                background: #5893D4;
            }
        }

        &:before {
            font-size: 20px;
        }
    }
</style>