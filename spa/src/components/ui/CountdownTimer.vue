<template>
    <div class="countdown-wrapper">
        <div class="countdown-label">
            <slot></slot>
        </div>
        <div class="countdown">
            <div class="countdown-item">
                <span class="digit">{{ getSeconds }}</span>
                <span class="text">минут</span>
            </div>
            <div class="countdown-item">
                <span class="digit">{{ getMinutes }}</span>
                <span class="text">секунд</span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "CountdownTimer",
    data: () => ({
        deadline: new Date(Date.parse(new Date()) + 1 * 60 * 1000),
        timerID: null,
        minutes: null,
        seconds: null
    }),
    computed: {
        getSeconds() {
            return this.minutes
        },
        getMinutes() {
            return this.seconds
        },
    },
    methods: {
        getTimeRemaining(endtime) {
            const total = Date.parse(endtime) - Date.parse(new Date())
            const seconds = Math.floor((total / 1000) % 60)
            const minutes = Math.floor((total / 1000 / 60) % 60)
            
            return { total, minutes, seconds }
        },
        updateClock() {
            const t = this.getTimeRemaining(this.deadline)
            this.minutes = ('0' + t.minutes).slice(-2)
            this.seconds = ('0' + t.seconds).slice(-2)
            if(t.total <= 0) {
                clearInterval(this.timerID)
                this.$emit('end')
            }
        }
    },
    beforeDestroy() {
        if(this.timerID) clearInterval(this.timerID)
    },
    mounted() {
        this.updateClock()
        this.timerID = setInterval(() => {
            this.updateClock()
        }, 1000)
    }
}
</script>
<style lang="sass" scoped>
// .countdown-wrapper
//     .countdown-label
//         font-size: 18px
//         line-height: 1
//         font-weight: 300
//         margin-bottom: 20px
//         color: #fff
//     .countdown
//         display: flex
//         align-items: center
//         justify-content: center
//         &-item
//             color: #fff
//             &:not(:last-child)
//                 margin-right: 10px  
//             .digit
//                 font-size: 24px
//                 font-weight: 500
//                 margin-right: 5px
//             .text
//                 font-size: 18px
//                 opacity: .5
</style>