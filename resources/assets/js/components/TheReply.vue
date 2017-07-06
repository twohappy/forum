<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group">
                <textarea name="body"
                    id="body"
                    class="form-control"
                    placeholder="Have something to say?"
                    rows="5" v-model="body"></textarea>
                </div>
                <button class="btn btn-default"
                    type="submit"
                    @click="addReply">Post
                </button>
        </div>
        <!--@if (auth()->check() )-->
        <!--<form method="POST" action="{{ $thread->path() . '/replies' }}">-->
        <!--{{csrf_field()}}-->

        <!--</form>-->
        <!--@else-->
        <p class="text-center" v-else>
            Please <a href="/login">Sign In</a> to participate in this discussion.
        </p>
        <!--@endif-->
    </div>
</template>

<script>
    export default {
        props:['endpoint'],
        data() {
            return {
                body: '',
            }
        },
        computed:{
            signedIn(){
                return Window.App.signedIn
            }
        },
        methods: {
            addReply() {
                axios.post(this.endpoint, {body: this.body})
                    .then(({data}) => {
                        this.body = ''

                        flash('Your reply has been posted')

                        this.$emit('created', data)
                    })
            }
        }
    }
</script>