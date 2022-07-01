<template>
    <v-dialog 
        content-class="home-dialog"
        :value="isOpen"
        :hide-overlay="true"
        transition="fade-transition"
        max-width="100%"
    >
        <div class="home-preloader">
            <div class="home-preloader-icon">
                <lottie-animation
                    ref="anim"
                    :animationData="require('@/preloader_new.json')"
                    :loop="true"
                    :autoPlay="false"
                />
            </div>
        </div>
    </v-dialog>
</template>
<script>
import LottieAnimation from 'lottie-web-vue'

export default {
    name: "HomePreloader",
    props: {
        isOpen: Boolean
    },
    components: {
        LottieAnimation
    },
    data: () => ({}),
    methods: {},
    beforeDestroy() { this.$refs.anim.stop() },
    mounted() { this.$refs.anim.play() }
}
</script>
<style lang="sass">
    .home
        &-dialog
            background: rgba(0, 0, 0, .25)
            margin: 0
            height: 100%
            max-height: 100%!important
            backdrop-filter: blur(25px)
            box-shadow: none!important
        &-preloader
            display: flex
            align-items: center
            justify-content: center
            position: absolute
            top: 0
            left: 0
            right: 0
            bottom: 0
</style>