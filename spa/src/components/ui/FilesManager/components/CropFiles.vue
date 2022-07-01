<template>
    <div class="cropper-container" :style="{'opacity': isLoader ? '.5' : '1'}">
        <div class="cropper-wrapper">
            <cropper
                class="cropper"
                :src="file"
                imageClass="cropper-image"
                ref="cropper"
                @change="change"
                @ready="ready"
                @error="error"
            />
        </div>
        <!-- <div class="cropper-settings">
            <div class="cropper-settings-range">
                <div class="range-label">{{ $t('page.brightness') }}</div>
                <v-slider
                    :color="sliderParams.color"
                    :trackColor="'#E3F0FF'"
                    v-model="imgOptions.brightness"
                    :thumb-color="sliderParams.color"
                    thumb-label="always"
                    :max="sliderParams.max"
                    :min="sliderParams.min"
                >
                </v-slider>
            </div>
            <div class="cropper-settings-range">
                <div class="range-label">{{ $t('page.contrast') }}</div>
                <v-slider
                    :color="sliderParams.color"
                    :trackColor="'#E3F0FF'"
                    v-model="imgOptions.contrast"
                    :thumb-color="sliderParams.color"
                    thumb-label="always"
                    :max="sliderParams.max"
                    :min="sliderParams.min"
                >
                </v-slider>
            </div>
            <div class="cropper-settings-range">
                <div class="range-label">{{ $t('page.saturation') }}</div>
                <v-slider
                    :color="sliderParams.color"
                    :trackColor="'#E3F0FF'"
                    v-model="imgOptions.saturation"
                    :thumb-color="sliderParams.color"
                    thumb-label="always"
                    :max="sliderParams.max"
                    :min="sliderParams.min"
                >
                </v-slider>
            </div>
        </div> -->
    </div>
</template>
<script>
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css'
import 'vue-advanced-cropper/dist/theme.classic.css'

export default {
    name: "CropFiles",
    props: ['file', 'isLoader'],
    components: {
        Cropper
    },
    data: () => ({
        img: 'https://images.unsplash.com/photo-1485178575877-1a13bf489dfe?ixlib=rb-1.2.1&auto=format&fit=crop&w=991&q=80',
        sliderParams: {
            color: '#4ECA80',
            min: 0,
            max: 100
        },
        imgOptions: {
            brightness: 23,
            contrast: 54,
            saturation: 73
        }
    }),
    methods: {
        change({ coordinates, canvas }) { 
            console.log(canvas.toDataURL(), coordinates)
            this.$emit('cropped', canvas.toDataURL()) 
        },
        ready() {
            console.log("Loaded", window.Caman)
        },
        error(val) {
            console.log("Error loaded", val)
        }
    }
}
</script>
<style lang="sass">
    /* 532x288 */
    .vue-simple-handler
        background: #9DCCFF!important
</style>

