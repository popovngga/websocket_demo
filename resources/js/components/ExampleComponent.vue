<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Okcoin</div>
                    <div class="card-body">
                        <form>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Api key</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="apiKey" required autofocus v-model="apiKey">
                                    <span class="invalid-feedback" role="alert" v-if="">
                                        <strong>msg</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Api secret</label>
                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="apiSecret" required v-model="apiSecret">
                                    <span class="invalid-feedback" role="alert" v-if="">
                                        <strong>msg</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Passphrase</label>
                                <div class="col-md-6">
                                    <input id="password" type="text" class="form-control" name="passphrase" required v-model="passphrase">
                                    <span class="invalid-feedback" role="alert" v-if="">
                                        <strong>msg</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="button" class="btn btn-primary" @click="send">
                                        Send credentials
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <p>{{p}}</p>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                apiKey: '',
                apiSecret: '',
                passphrase: '',
                p: '',
                users: '',
            }
        },
        methods: {
            send(){
                axios.get(`/api/index?apiKey=${this.apiKey}&apiSecret=${this.apiSecret}&passphrase=${this.passphrase}`)
                    .then((res) => {
                        console.log(res);
                    })
            }
        },
        mounted(){
            Echo.private('response-channel')
                .listen('ResponseEvent', (e) => {
                    this.p = e.response;
                });
            Echo.join('response-channel')
                .here((users) => {
                    for(let user of users) {
                        this.users += `${user.name}(${user.email}), `;
                    }
                    console.log(this.users + ' HERE')
                })
                .joining((user) => {
                    console.log(user.name+' JOINING')
                })
                .leaving((user) => {
                    console.log(user.name+' LEAVING')
                });
        },
    }
</script>
