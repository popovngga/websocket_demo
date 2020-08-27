<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Okcoin</div>
                    <div class="card-body">
                        <form @submit="send">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Api key</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="apiKey" required autofocus v-model="apiKey">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Api secret</label>
                                <div class="col-md-6">
                                    <input id="email" type="text" class="form-control" name="apiSecret" required v-model="apiSecret">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Passphrase</label>
                                <div class="col-md-6">
                                    <input id="password" type="text" class="form-control" name="passphrase" required v-model="passphrase">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="type" class="col-md-4 col-form-label text-md-right">Type</label>
                                <div class="col-md-6">
                                    <select id="type" class="form-control" v-model="side">
                                        <option disabled value="">Choose something</option>
                                        <option value="sell">Sell</option>
                                        <option value="buy">Buy</option>
                                        <option value="something">Something else</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" v-if="side === 'sell' || side === 'buy'">
                                <label for="instrument" class="col-md-4 col-form-label text-md-right">Instrument</label>
                                <div class="col-md-6">
                                    <select id="instrument" class="form-control" v-model="instrument" @change="onChange">
                                        <option disabled value="">Choose something</option>
                                        <option v-for="coin in coins" :value=JSON.stringify(coin)>{{coin.instrument_id}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row" v-if="side">
                                <label for="size" class="col-md-4 col-form-label text-md-right">Quantity to buy or sell</label>
                                <div class="col-md-6">
                                    <input id="size" type="number" :min="this.input_size.min_size" :step="this.input_size.size_increment" class="form-control" name="size" required v-model="size">
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Send credentials
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-header" v-if="history">Activity: <p v-for="activity in history">{{activity}}</p></div>
                </div>
                <span :style="color" role="alert">
                    <strong>{{p}}</strong>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                apiKey: '302f7866-c52b-4cf5-a4f8-c190c3bf9f64',
                apiSecret: '7E448CD61A3D7D4EF70D09A7DFB93FED',
                passphrase: '64646290b',
                side:'',
                error_message:'',
                instrument:'',
                color: '',
                size:'',
                input_size:{},
                p: '',
                users: '',
                history: [],
                coins: {}
            }
        },
        methods: {
            send(event){
                let instrument_id ='';
                if (this.side === 'something') {
                    instrument_id = 'something';
                } else {
                    instrument_id = JSON.parse(this.instrument).instrument_id;
                }
                event.preventDefault();
                axios.post(`/api/index?apiKey=${this.apiKey}&apiSecret=${this.apiSecret}&passphrase=${this.passphrase}`, {
                    'instrument_id': instrument_id,
                    'side': this.side,
                    'size': this.input_size.min_size,
                    'price': this.size,
                }).then((res) => {

                    });
            },
            onChange(event) {
                this.input_size = JSON.parse(event.target.value);
            }
        },
        async mounted(){
            await axios.get(`/api/coin_info?apiKey=${this.apiKey}&apiSecret=${this.apiSecret}&passphrase=${this.passphrase}`)
                .then((res) => {
                    this.coins = res.data;
                });
            Echo.private('response-channel')
                .listen('ResponseEvent', (e) => {
                    this.color = 'color:#'+((1<<24)*Math.random()|0).toString(16);
                    this.p = e.response;
                });
            Echo.join('response-channel')
                .here((users) => {
                    for(let user of users) {
                        this.users += `${user.name}(${user.email}), `;
                    }
                    this.history.push(this.users + ' HERE');
                    console.log(this.users + ' HERE')
                })
                .joining((user) => {
                    this.history.push(user.name+' JOINING');
                    console.log(user.name+' JOINING')
                })
                .leaving((user) => {
                    this.history.push(user.name+' LEAVING');
                    console.log(user.name+' LEAVING')
                });
        },
    }
</script>
