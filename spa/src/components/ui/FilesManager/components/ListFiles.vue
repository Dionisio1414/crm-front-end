<template>
    <div 
        class="list-main" 
        :class="{'list-main-error': !getFiles.length}" 
    >
        <template v-if="getFiles.length">
            <div class="lists" ref="list">
                <ListItem 
                    v-for="(val, idx) in getFiles" 
                    :type="typeField" 
                    :index="idx" 
                    :key="idx"
                    :url="val.url"
                    :title="val.title"
                    :id="val.id"
                    :limit="limit"
                    :filesLength="getFiles.length"
                    @selectItem="selectItem"
                    @selectItems="selectItems"
                    @deleteItem="deleteItem"
                    @removeItem="removeItem"
                >
                </ListItem>
            </div>
        </template>
        <template v-else>
            <div class="lists-error">
                <div class="picture">
                    <img src="@/assets/icons/area-bg.svg" alt="">
                </div>
                <span class="text">Загрузите файл</span>
            </div>
        </template>
    </div>
</template>
<script>
import ListItem from "@/components/ui/FilesManager/components/ListItem"
import { mapGetters, mapActions } from "vuex"
import { pick } from "@/helper/pick"

export default {
    name: "ListFiles",
    props: {
        typeField: String,
        limit: Number
    },
    data: () => ({
        files: []
    }),
    components: { ListItem },
    computed: { ...mapGetters(['getFiles', 'getPaginationFilesCounter', 'getFilesTotal']) },
    methods: {
        ...mapActions(['paginationFilesCounter', 'updateFetchFiles']),
        selectItem(val) {
            this.$emit('selectItem', val) 
        },
        selectItems(val) {
            this.files.push(val)
            const transformFiles = pick(this.files)('id')
            this.$emit('selectItems', this.files, transformFiles) 
        },
        deleteItem(id) {
            const currentItem = this.files.findIndex(item => item.id === id)
            this.files.splice(currentItem, 1)
            const transformFiles = pick(this.files)('id')
            this.$emit('selectItems', this.files, transformFiles) 
        },
        removeItem() { this.$emit('removeItem') },
        scrollPagination() {
            const ref = this.$refs.list
            if(ref) {
                ref.addEventListener('mousewheel', () => {
                    console.log('scroll')
                    if(ref.scrollTop + ref.clientHeight >= ref.scrollHeight) {
                        this.pagination()
                        console.log('scrolledToEnd')
                    }
                })
            }
        }, 
        async pagination() {
            const isAmountPage = this.getFilesTotal > (this.getPaginationFilesCounter * 12)
            console.log('isAmountPage', isAmountPage)
            if(isAmountPage) {
                await this.paginationFilesCounter()
                await this.updateFetchFiles(this.getPaginationFilesCounter)
            }
        }
    },
    mounted() { this.scrollPagination() }   
}
</script>
<style scoped>

</style>