<template>
    <div class="directories directories-page">
        <div class="directories-grid">

            <div class="directorie-item" v-for="(item, index) in menu.directories" :key="index">
                <div class="icon-wrap">
                    <div class="icon" v-html="item.directory_icon">
                        {{ item.directory_icon }}
                    </div>
                </div>
                <div class="title">
                    <h3>{{ item.title }}</h3>
                </div>
                <div class="navigation">
                    <ul class="navlist">
                        <router-link 
                            tag="li"
                            class="navlist-item" 
                            v-for="(value, idx) in item.children" 
                            :key="idx"
                            :to="value.url"
                        >
                            <a href="/" class="navlist-link">
                                {{ value.title }}
                            </a>
                        </router-link>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'

export default {
    name: "Directories",
    data: () => ({

    }),
    computed: {
        ...mapGetters([
            'menu'
        ]),
    },
    mounted() {
        console.log('menu', this.menu)
    }
}
</script>
<style lang="sass" scoped>

</style>