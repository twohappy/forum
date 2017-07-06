<template>
    <div>
        <div v-for="(reply,index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <the-reply :endpoint="endpoint" @created="add"></the-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue'
    import TheReply from './TheReply.vue'
    export default{
        props: ['data'],
        components: {
            Reply,
            TheReply
        },
        data(){
            return {
                items: this.data,
                endpoint: location.pathname + '/replies'
            }
        },
        methods: {
            add(reply){
                this.items.push(reply)
                this.$emit('added')
            },
            remove(index){
                this.items.splice(index, 1)

                this.$emit('removed')

                flash('reply was deleted.')
            }
        }
    }
</script>