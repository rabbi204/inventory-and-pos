<template>
    <div>
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">POS Dashboard</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="./">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">POS</li>
                </ol>
            </div>

            <div class="row mb-3">
                <!-- Area Chart -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Expense Insert</h6>
                            <a class="btn btn-sm btn-info text-white">Add Customer</a>
                        </div>
                         <div class="table-responsive" style="font-size:14px">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="cart in carts" :key="cart.id">
                                        <td><a href="#">{{ cart.pro_name }}</a></td>
                                        <td>
                                            <input type="text" style="width:20px" readonly :value="cart.pro_quantity">
                                            <button @click.prevent="increment(cart.id)" class="btn btn-sm btn-success ">++</button>
                                            <button @click.prevent="decrement(cart.id)" class="btn btn-sm btn-danger" v-if="cart.pro_quantity >= 2">--</button>
                                            <button class="btn btn-danger btn-sm" v-else disabled>--</button>
                                        </td>
                                        <td>{{ cart.pro_price }}</td>
                                        <td>{{ cart.sub_total }}</td>
                                        <td><a @click="removeItem(cart.id)" class="btn btn-sm btn-danger text-white">X</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Quantity: <strong>{{ qty }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Sub Total: <strong>{{ subtotal }} tk</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    VAT: <strong>{{ vats.vat }} %</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Amount: <strong>{{ subtotal*vats.vat/100 + subtotal }} tk</strong>
                                </li>
                            </ul>
                            <br>
                            <form action="" @submit.prevent="orderDone">
                                <div class="form-group">
                                    <label for="customer">Customer Name</label>
                                    <select class="form-control" v-model="customer_id" id="customer">
                                    
                                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Pay</label>
                                    <input type="text" class="form-control" v-model="pay" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Due</label>
                                    <input type="text" class="form-control" v-model="due" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Pay By</label>
                                    <select class="form-control" v-model="payby" id="">
                                        <option value="HandCash">Hand Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="GiftCard">Gift Card</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Pie Chart -->
                <div class="col-xl-6 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Products Sold</h6>
                        </div>
                        <!-- category wise product -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">All Product</button>
                            </li>
                            <li class="nav-item" role="presentation" v-for="category in categories" :key="category.id">
                                <button @click="subProduct(category.id)" class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                                    {{ category.category_name }}
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="card-body">
                                    <input type="text" v-model="searchTerm" class="form-control col-md-12 mb-2" placeholder="search product by name">
                                    <div class="row">
                                        <div v-for="product in filterSearch" :key="product.id" class="col-lg-4 col-md-4 col-sm-6 col-6">
                                            <button class="btn btn-sm" @click.prevent="addToCart(product.id)">
                                                <div class="card" style="width: 8.5rem; margin-botom: 5px;">
                                                    <img :src="product.image" class="card-img-top" id="em_photo">
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ product.product_name }}</h6>
                                                        <span class="badge badge-success" v-if="product.product_quantity >= 1">Available {{ product.product_quantity }}</span>
                                                        <span class="badge badge-danger" v-else>Stock Out</span>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- End tabs Home -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="card-body">
                                    <input type="text" v-model="getSearchTerm" class="form-control col-md-12 mb-2" placeholder="search product by name">
                                    <div class="row">
                                        <div v-for="getproduct in getFilterSearch" :key="getproduct.id" class="col-lg-4 col-md-4 col-sm-6 col-6">
                                            <button class="btn btn-sm" @click.prevent="addToCart(getproduct.id)">
                                                <div class="card" style="width: 8.5rem; margin-botom: 5px;">
                                                    <img :src="getproduct.image" class="card-img-top" id="em_photo">
                                                    <div class="card-body">
                                                        <h6 class="card-title">{{ getproduct.product_name }}</h6>
                                                        <span class="badge badge-success" v-if="getproduct.product_quantity >= 1">Available {{ getproduct.product_quantity }}</span>
                                                        <span class="badge badge-danger" v-else>Stock Out</span>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End category wise product -->
                    </div>
                </div>
            </div>
            <!--Row-->
        </div>
        <!---Container Fluid-->

    </div>
</template>

<script>
export default {
    created(){
        if(!User.loggedIn()){
            this.$router.push({name: '/'});
        }
    },
    created(){
        this.allProduct();
        this.allCategory();
        this.allCustomer();
        this.cartProduct();
        this.vat();
        Reload.$on('AfterAdd', () =>{
            this.cartProduct();
        })
    },
    data(){
        return{
            customer_id:'',
            pay: '',
            due: '',
            payby: '',
            products:[],
            categories:'',
            getproducts: [],
            searchTerm: '',
            getSearchTerm: '',
            customers: '',
            errors: '',
            carts:[],
            vats:'',
        }
    },

    computed:{
        filterSearch(){
            return this.products.filter(product => {
                return product.product_name.match(this.searchTerm);
            });
        },
        getFilterSearch(){
            return this.getproducts.filter(getproduct => {
                return getproduct.product_name.match(this.getSearchTerm);
            });
        },
        qty(){
            let sum = 0;
            for (let i = 0; i < this.carts.length; i++) {
                sum += ( parseFloat(this.carts[i].pro_quantity));
            }
             return sum;
        },
        subtotal(){
             let sum = 0;
            for (let i = 0; i < this.carts.length; i++) {
                sum += ( parseFloat(this.carts[i].pro_quantity)) * ( parseFloat(this.carts[i].pro_price) );
            }
             return sum;
        },
    },
    
    methods:{
        // Cart methods here
        addToCart(id){
            axios.get('/api/addToCart/' + id)
            .then( () => { 
                Reload.$emit('AfterAdd');
                Notification.cart_success();
            })
            .catch()
        },
        cartProduct(){
            axios.get('/api/cart/product/')
            .then( ({data}) => (this.carts = data))
            .catch()
        },
        removeItem(id){
            axios.get('/api/remove/cart/' + id)
            .then( () => { 
                Reload.$emit('AfterAdd');
                Notification.cart_delete();
            })
            .catch()
        },
        increment(id){
             axios.get('/api/increment/' + id)
            .then( () => { 
                Reload.$emit('AfterAdd');
                Notification.success();
            })
            .catch()
        },
        decrement(id){
            axios.get('/api/decrement/' + id)
            .then( () => { 
                Reload.$emit('AfterAdd');
                Notification.success();
            })
            .catch()
        },
        vat(){
             axios.get('/api/vats/')
            .then( ({data}) => (this.vats = data))
            .catch()
        },
        orderDone(){
            let total = this.subtotal*this.vats.vat/100 + this.subtotal;
            let data = { qty: this.qty, subtotal:this.subtotal, customer_id: this.customer_id, payby:this.payby, pay:this.pay,due:this.due, vat:this.vats.vat, total:total};
            
            axios.post('/api/orderdone/', data)
            .then(() => {
                Notification.success();
                this.$router.push({name: 'home'})
            })
        },

        // End Cart method
        allProduct(){
            axios.get('/api/product/')
            .then( ({data}) => (this.products = data))
            .catch()
        },

        allCategory(){
            axios.get('/api/category/')
            .then( ({data}) => (this.categories = data))
            .catch()
        },
        
        allCustomer(){
            axios.get('/api/customer/')
            .then( ({data}) => (this.customers = data))
            .catch(console.log('error'))
        },
        subProduct(id){
            axios.get('/api/getting/product/' + id)
            .then( ({data}) => (this.getproducts = data))
            .catch()
        }

    }
    
}
</script>

<style scoped>
    #em_photo{
        height: 90px;
        width: 100%;
        object-fit: cover;
    }
</style>