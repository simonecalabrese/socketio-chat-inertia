<template>
<app-layout>
    <div class="bg-gray-800 w-screen h-screen overflow-hidden" title="Chat">
        <div class="bg-gray-700 mx-auto shadow-2xl rounded-xl w-11/12 lg:w-7/12 my-8 flex flex-row overflow-hidden relative" style="height: 75%;" id="app">
            <div class="info w-1/12 lg:w-3/12 py-6 relative overflow-x-hidden overflow-y-auto usersOverlay h-full z-10 lg:z-0" style="background-color: #2e3744;" ref="usersContainer">
                <div class="block lg:hidden m-2 relative z-10" @click="usersListExpands()">
                    <div class="w-4 bg-gray-200 my-1 rounded" style="height:2px"></div>
                    <div class="w-4 bg-gray-200 my-1 rounded" style="height:2px"></div>
                    <div class="w-4 bg-gray-200 my-1 rounded" style="height:2px"></div>
                </div>
                <h3 class="text-xl relative overflow-hidden p-2 lg:px-5 lg:py-3 text-gray-200 cursor-pointer" :class="(publicChat) ? 'selected' : ''" @click="() => {publicChat = !publicChat; selected= 'public'}">Chat pubblica</h3>
                <h2 class="text-gray-200 text-3xl mt-3 px-3 lg:px-8 lg:mt-6">Utenti</h2>
                <ul class="users mt-2 overflow-y-auto">
                    <div v-for="(user, index) in users" :key="index" >
                        <li class="bg-gray-700 px-2 py-3 text-gray-500 lg:py-2 lg:px-5 relative overflow-hidden flex flex-row items-center" 
                        @click="() =>{selected= user.username; publicChat=false}"
                        :class="(selected==user.username) ? 'selected' : ''" 
                        v-if="user.id != currentUser.id">
                            <img :src="'https:\/\/ui-avatars.com\/api\/?name='+user.name+'&color=7F9CF5&background=1f2937'" width="35" height="35" style="border-radius:99px; margin-right:6px" alt="Profile image">
                            <p class="text-lg text-gray-300">{{user.username}}</p>
                            <span class="text-green-400 bg-green-900 p-1 rounded text-xs h-6 m-1 w-11" v-if="user.status == 'online' || (user.id == currentUser.id)">Online</span>
                            <span class="text-red-300 bg-red-900 p-1 rounded text-xs h-6 m-1 w-11" v-if="user.status != 'online' && (user.id != currentUser.id)">Offline</span>
                        </li>                        
                    </div>
                </ul>
            </div>
            <div class="chat w-11/12 lg:w-9/12 relative flex flex-col" ref="messagesContainer">
                <h1 class="text-gray-100 text-xl mt-2 lg:mt-4 text-center" v-if="publicChat">Chat pubblica</h1>
                <h1 class="text-gray-100 text-xl mt-2 lg:mt-4 text-center" v-html="selected" v-if="!publicChat"></h1>
                <p class="text-center text-gray-400 text-base" :class="(typingIndicator) ? 'opacity-1' : 'opacity-0'">Sta scrivendo...</p>
                <ul class="messages p-5 flex flex-col overflow-y-scroll" ref="chatMessages" style="height: calc(100% - 124px)">
                    <div class="flex" v-for="message in messages" :key="message.id">
                        <li class="bg-gray-300 shadow-lg rounded mr-0 my-2"  
                        v-if="messages.length > 0 && publicChat" 
                        :class="(currentUser.id == message.user_id) ? 'ml-auto' : 'ml-0 mr-auto'">
                            <h3 class="text-md text-gray-900 rounded p-2" style="background:rgba(0,0,0,.1)">{{message.author}}</h3>
                            <p class="text-sm text-gray-700 p-2">{{message.message}}</p>
                            <p class="text-xs text-gray-500 pl-2 pb-2">{{message.created_at.split('T')[0].slice(5,10).split('-')[1]}}/{{message.created_at.split('T')[0].slice(5,10).split('-')[0]}} alle {{message.created_at.split('T')[1].slice(0,5)}}</p>
                        </li>
                    </div>
                    <template v-if="privateMessages.length > 0">
                        <div class="flex" v-for="pMessage in privateMessages" :key="pMessage.id">
                            <li class="bg-gray-300 shadow-lg rounded mr-0 my-2"  
                            v-if="!publicChat && (pMessage.receiver == selected || pMessage.sender == selected)" 
                            :class="(currentUser.id == pMessage.user_id) ? 'ml-auto' : 'ml-0 mr-auto'">
                                <h3 class="text-md text-gray-900 rounded p-2" style="background:rgba(0,0,0,.1)">{{pMessage.sender}}</h3>
                                <p class="text-sm text-gray-700 p-2">{{pMessage.message}}</p>
                                <p class="text-xs text-gray-500 pl-2 pb-2">{{pMessage.created_at.split('T')[0].slice(5,10).split('-')[1]}}/{{pMessage.created_at.split('T')[0].slice(5,10).split('-')[0]}} alle {{pMessage.created_at.split('T')[1].slice(0,5)}}</p>
                            </li>
                        </div>
                    </template>
                    <div v-if="selected != 'public'" class="text-center mx-auto">
                        <li v-if="privateMessages.length <= 0">
                            <p class="text-gray-400">Non ci sono messaggi</p>
                        </li>
                    </div>
                    <div v-if="selected == 'public'" class="text-center mx-auto">
                        <li v-if="messages.length <= 0">
                            <p class="text-gray-400">Non ci sono messaggi</p>
                        </li>
                    </div>
                </ul>
                <div class="input-group absolute bottom-0 p-3 w-full flex flex-row" style="background:rgba(31,41,55,.7);" @keyup.enter="sendMessage()">
                    <input type="text" v-model="messageText" class="p-3 bg-gray-800 text-gray-300 w-11/12 text-md focus:outline-none rounded border-none" placeholder="Scrivi il tuo messaggio..." @keyup="typing()">
                    <button class="w-1/12 focus:outline-none" @click="sendMessage()"><i class="fas fa-paper-plane text-2xl text-gray-400 hover:text-gray-300"></i></button>
                </div>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped>

    .messages::-webkit-scrollbar {width: 10px; background: transparent;}
    /* Track */
    .messages::-webkit-scrollbar-track {background: transparent;}
    /* Handle */
    .messages::-webkit-scrollbar-thumb {background: rgba(255,255,255,.1); width: 5px; border-radius: 5px;}
    /* Handle on hover */
    .messages::-webkit-scrollbar-thumb:hover {background: rgba(255,255,255,.2);}
    .users li {background: #2e3744;transition: .02s; cursor: pointer; }
    .users li::after {content: ''; position: absolute; top: 1px; left: 5px; right: 5px; bottom: 1px; background: rgba(255,255,255,.08); border-radius: 4px; opacity: 0;}
    .users li:hover:after {opacity: 1;}
    .userSelected {background:#212831;}
    input:focus {box-shadow: none!important;}

    .messages li {min-width: 120px; max-width: 250px;}
    @media(max-width:767px) {
        .messages li {min-width: 100px!important; max-width: 150px!important;}
        .usersOverlay {overflow: hidden;}
        .usersOverlay::after {content: ''; position: absolute; z-index: 8; top: 0; right: 0;bottom: 0;left: 0; background:rgb(46, 55, 68) }
        .messDisplayContents {position: relative;z-index: 0;display: contents;}
    }



    .selected {}
    .selected::after{content: ''; position: absolute; top: 1px; left: 5px; right: 5px; bottom: 1px; background: rgba(255,255,255,.08); border-radius: 4px; opacity: 1!important;}
</style>
<script>
import AppLayout from '@/Layouts/AppLayout';
export default {
    components: {
        AppLayout
    },
    data() {
        return {
            messageText: '',
            selected: 'public',
            publicChat: true,
            typingIndicator: false,
            tout: {}
        }
    },
    props: {
        currentUser: Object,
        users: Array,
        messages: Array,
        privateMessages: Array
    },
    methods: {
        socketEvents() {
            //Update user status and Emit User connection
            axios.patch('/user/updateStatus', {status:"online"})
                .then(res => {
                    socket.emit('User connected', {id:this.currentUser.id, username:this.currentUser.username});
                })
                .catch(err => console.log(err))

            //User connected
            socket.on('User connected', user => {
                this.users.forEach(u => {
                    if(u.id == user.id) u.status = 'online';
                });
            });

            //User disconnected
            socket.on('User disconnected', userID => {
                axios.patch('/user/updateStatus', {status:"offline", user_id: userID})
                    .then(res => {
                        socket.emit('User disconnected');
                    })
                    .catch(err => console.log(err))
                this.users.forEach(u => {
                    if(u.id == userID) u.status = 'offline';
                });
            });

            //User registered
            socket.on('User registered', () => {
                axios.get('/chat/users/list')
                    .then(res => {
                        this.users.push(res.data[res.data.length-1]) 
                    })
                    .catch(err => console.log(err))
            });


            //Chat message
            socket.on('chat message', msg => {
                this.messages.push(msg);
                setTimeout(() => {this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight+999;}, 10);
            });

            //Private chat message
            socket.on('private message', msg => {
                this.privateMessages.push(msg);
                setTimeout(() => {this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight+999;}, 10);
            });

            //Typing
            socket.on('private typing', receiver => {
                this.typingIndicator = true;
                clearTimeout(this.tout);
                this.tout = setTimeout(() => {
                    this.typingIndicator = false;
                }, 2300); 
            })
            
        },
        typing() {
            if(this.selected != 'public') socket.emit('private typing', {receiver: this.selected})
        },
        sendMessage() {
            if(this.messageText.length<=0) return;
            if(!this.publicChat) {
                //Private Message
                axios.post('/chat/privateMessages', {message:this.messageText, receiver:this.selected})
                    .then(res => {
                        socket.emit('private message', res.data);
                        this.privateMessages.push(res.data);
                        setTimeout(() => {this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight+999;}, 10);
                    })
                    .catch(e => console.log(e));
                this.messageText = '';
            }
            else {
                //Public message
                axios.post('/chat', {message:this.messageText})
                    .then(res => {
                        socket.emit('chat message', res.data);
                        this.messageText = '';
                    })
                    .catch(err => console.log(err.response.data.errors))
            }
        },
        //Design
        usersListExpands() {
            this.$refs.usersContainer.classList.toggle('usersOverlay')
            this.$refs.usersContainer.classList.toggle('w-1/12')
            this.$refs.usersContainer.classList.toggle('w-9/12')
            this.$refs.messagesContainer.classList.toggle('messDisplayContents')
        }
    },
    mounted() {
        this.socketEvents();
        this.$refs.chatMessages.scrollTo(0, this.$refs.chatMessages.scrollHeight);
    }
  }
</script>
