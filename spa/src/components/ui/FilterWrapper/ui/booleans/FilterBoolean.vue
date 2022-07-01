<template>
    <div>
        <div class="label-title">{{ $t(label) }}</div>
        <div class="switch-list">
            <div class="switch-list-item" v-for="(val, index) in list.lists" :key="index">
                <div class="radio">
                    <label class="radio-label">
                        <template v-if="localData[keyName] && index === 0">
                            <input 
                                type="radio" 
                                :name="`radio_${randomNumber}`" 
                                :value="true"
                                :checked="true"
                                @change="onSwitch"
                            >
                        </template>
                        <template v-else>
                            <input 
                                type="radio" 
                                :name="`radio_${randomNumber}`" 
                                :value="false"
                                :checked="false"
                                @change="onSwitch"
                            >
                        </template>
                        <div class="radio-text">
                            <div class="text">{{ $t(val) }}</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from 'vuex'
import getUniqueId from "@/helper/getUniqueId"

export default {
    name: "FilterBoolean",
    props: {
        label: String,
        list: Object
    },
    data() { 
        return {
            localData: { ...this.list.type },
            randomNumber: getUniqueId(),
            keyName: Object.keys(this.list.type)[0]
        }
    },
    methods: {
        ...mapActions([
            'changeDataFilter'
        ]),
        onSwitch(e) {
            const payload = {
                booleans: {
                    [this.keyName]: e.target.value,
                },
                resource: this.list.resource
            }
            this.changeDataFilter(payload)
        }
    }
}
</script>
<style scoped>

</style>