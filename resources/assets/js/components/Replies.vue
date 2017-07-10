<template>
    <div>
        <div v-for="(reply,index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <the-reply @created="add"></the-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue'
    import TheReply from './TheReply.vue'
    import collection from '../mixins/collection.js'

    export default{
        components: {
            Reply,
            TheReply
        },
        created(){
            this.fetch()
        },
        mixins:[collection],
        data(){
            return {
                dataSet:false
            }
        },
        methods: {
            fetch(page){
                axios.get(this.url(page))
                    .then(this.refresh)
            },
            refresh({data}){
                this.dataSet = data
                this.items = data.data

                window.scrollTo(0,0)
            },
            url(page){
                if(!page){
                    let query = location.search.match(/page=(\d+)/)

                    page = query ? query[1] : 1
                }
                return `${location.pathname}/replies?page=${page}`
            }
        }
    }
</script>