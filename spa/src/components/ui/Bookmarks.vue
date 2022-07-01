<template>
    <div class="bookmarks-menu-wrap">
        <div class="menu-title">
            <div class="title">Добавить закладку</div>
            <div class="bookmarks-count">{{ getAllBookmarks.length }} / 6</div>
        </div>
        <ul class="bookmarks-menu">
            <li :class="{'active': currentTab === 1}">
                <a href="#" @click.prevent="changeTab(1)">Товары ({{ bookmarks.products.length }})</a>
                <svg class="icon" width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83709 5.07078L1.29221 0.17573C1.1871 0.0624223 1.04677 0 0.897152 0C0.74753 0 0.607207 0.0624223 0.50209 0.17573L0.167391 0.536134C-0.0503999 0.770977 -0.0503999 1.15267 0.167391 1.38715L3.98384 5.49772L0.163156 9.61285C0.0580385 9.72616 -3.12529e-07 9.8772 -3.17625e-07 10.0383C-3.22727e-07 10.1995 0.0580385 10.3506 0.163156 10.464L0.497855 10.8243C0.603055 10.9376 0.743295 11 0.892917 11C1.04254 11 1.18286 10.9376 1.28798 10.8243L5.83709 5.92475C5.94246 5.81108 6.00033 5.65932 6 5.49799C6.00033 5.33603 5.94246 5.18436 5.83709 5.07078Z" fill="#5893D4"/>
                </svg>
                <div class="sub-bookmarks-menu" v-show="currentTab === 1">
                    <div v-for="(link, index) in menu.products" :key="index">
                        <div class="checkbox">
                            <label class="checkbox-label">
                                <input
                                    :id="link.url" 
                                    type="checkbox"
                                    @change="updateSelectedBookmarks($event, link, 'products')"
                                    :disabled="getAllBookmarks.length >= 6 && bookmarks.products.indexOf(link) === -1"
                                    :checked="bookmarks.products.indexOf(link) !== -1"
                                >
                                <div class="checkbox-text">{{link.title}}</div>
                            </label>
                        </div>
                    </div>
                </div>
            </li>
            <li :class="{'active': currentTab === 2}">
                <a href="#" @click.prevent="changeTab(2)">Справочники ({{ bookmarks.directories.length }})</a>
                <svg class="icon" width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83709 5.07078L1.29221 0.17573C1.1871 0.0624223 1.04677 0 0.897152 0C0.74753 0 0.607207 0.0624223 0.50209 0.17573L0.167391 0.536134C-0.0503999 0.770977 -0.0503999 1.15267 0.167391 1.38715L3.98384 5.49772L0.163156 9.61285C0.0580385 9.72616 -3.12529e-07 9.8772 -3.17625e-07 10.0383C-3.22727e-07 10.1995 0.0580385 10.3506 0.163156 10.464L0.497855 10.8243C0.603055 10.9376 0.743295 11 0.892917 11C1.04254 11 1.18286 10.9376 1.28798 10.8243L5.83709 5.92475C5.94246 5.81108 6.00033 5.65932 6 5.49799C6.00033 5.33603 5.94246 5.18436 5.83709 5.07078Z" fill="#5893D4"/>
                </svg>
                <div class="sub-bookmarks-menu" v-show="currentTab === 2">
                    <div v-for="(link, index) in menu.directories" :key="index">
                        <div class="checkbox">
                            <label class="checkbox-label">
                                <input
                                    :id="link.url" 
                                    type="checkbox"
                                    @change="updateSelectedBookmarks($event, link, 'directories')"
                                    :disabled="getAllBookmarks.length >= 6 && bookmarks.directories.indexOf(link) === -1"
                                    :checked="bookmarks.directories.indexOf(link) !== -1"
                                >
                                <div class="checkbox-text">{{link.title}}</div>
                            </label>
                        </div>
                    </div>
                </div>
            </li>
            <li :class="{'active': currentTab === 3}">
            <a href="#" @click.prevent="changeTab(3)">Управление системой ({{ bookmarks.systemManagement.length }})</a>
            <svg class="icon" width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.83709 5.07078L1.29221 0.17573C1.1871 0.0624223 1.04677 0 0.897152 0C0.74753 0 0.607207 0.0624223 0.50209 0.17573L0.167391 0.536134C-0.0503999 0.770977 -0.0503999 1.15267 0.167391 1.38715L3.98384 5.49772L0.163156 9.61285C0.0580385 9.72616 -3.12529e-07 9.8772 -3.17625e-07 10.0383C-3.22727e-07 10.1995 0.0580385 10.3506 0.163156 10.464L0.497855 10.8243C0.603055 10.9376 0.743295 11 0.892917 11C1.04254 11 1.18286 10.9376 1.28798 10.8243L5.83709 5.92475C5.94246 5.81108 6.00033 5.65932 6 5.49799C6.00033 5.33603 5.94246 5.18436 5.83709 5.07078Z" fill="#5893D4"/>
            </svg>
            <div class="sub-bookmarks-menu" v-show="currentTab === 3">
                <div v-for="(link, index) in menu.systemManagement" :key="index">
                    <div class="checkbox">
                        <label class="checkbox-label">
                            <input
                                :id="link.url" 
                                type="checkbox"
                                @change="updateSelectedBookmarks($event, link, 'systemManagement')"
                                :disabled="getAllBookmarks.length >= 6 && bookmarks.systemManagement.indexOf(link) === -1"
                                :checked="bookmarks.systemManagement.indexOf(link) !== -1"
                            >
                            <div class="checkbox-text">{{link.title}}</div>
                        </label>
                    </div>
                </div>
            </div>
            </li>
        </ul>
    </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    name: "Bookmarks",
    data: () => ({
        currentTab: null,
        bookmarks: {
            products: [],
            directories: [],
            systemManagement: []
        },
    }),
    computed: {
        ...mapGetters([
            'selectedBookmarks',
            'menu'
        ]),
        getAllBookmarks() {
            let bookmarks = this.bookmarks
            let arr = []
            for(let key in bookmarks) {
                const items = bookmarks[key] 
                items.forEach(item => arr.push(item))
            } 
            return arr
        }
    },
    methods: {
        ...mapActions(['updateBookmarks']),
        updateSelectedBookmarks(e, link, type) {
            link.bookmark = e.target.checked
            this.updateBookmarks({ link, type, flag: e.target.checked })
        },
        changeTab(index) { this.currentTab = index },
    },
    created() {
        this.bookmarks = this.selectedBookmarks
    }
}
</script>
<style scoped>

</style>