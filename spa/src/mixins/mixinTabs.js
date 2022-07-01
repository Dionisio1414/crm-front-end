import {mapActions, mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters(['tabsLength']),
        checkTabs() {
            return this.tabsLength >= 8
        }
    },
    methods: {
        ...mapActions(['updateTabs']),
        getUrl(id) {
            const currentPath = this.$route.path;
            const concatPath = `${currentPath}/${id}`;

            const isNumber = concatPath.match(/\d+/);
            const isDuplicate = concatPath.includes('//');

            if (isDuplicate) {
                return concatPath.replace('//','/')
            }

            if (isNumber) {
                return concatPath.replace(`/${isNumber[0]}`,'')
            }

            return concatPath;
        },
        addTab(id, title) {
            console.log(title)
            this.updateTabs({ title, url: this.getUrl(id) })
            this.dialog = false
            this.$emit('onCloseModal')
        }
    }
}
