var products = [
  {id: 1, name: 'Angular', description: 'Superheroic JavaScript MVW Framework.', price: 100},
  {id: 2, name: 'Ember', description: 'A framework for creating ambitious web applications.', price: 100},
  {id: 3, name: 'React', description: 'A JavaScript Library for building user interfaces.', price: 100},
  {id: 4, name: 'Vue', description: 'The Progressive JavaScript Framework using MVVM structure', price: 100}
];

var personels = [];
$.getJSON("personel/getPersonelJson",function(res){
  var i=0;
  var obj = new Object();
  $.each(res.rows,function(i,v){
      obj.id = v.personel_id;
      obj.name = v.name;
      obj.nrp = v.nrp;
      personels[i] = obj;
  });
  console.log(personels);
});  


function findProduct (productId) {
  return products[findProductKey(productId)];
};

function findProductKey (productId) {
  for (var key = 0; key < products.length; key++) {
    if (products[key].id == productId) {
      return key;
    }
  }
};

// component --------------------------------------------------------
const List = {
    template: '#personel-list',
    data: function () {
      return {personels: personels, searchKey: ''};
    },
    computed: {
      filteredProducts: function () {
        var self = this;
        return self.personels.filter(function (product) {
          return personel.name.toLowerCase().indexOf(self.searchKey.toLowerCase()) !== -1
        })
      }
    }
};

const AddProduct = {
    template: '#add-product',
    data: function () {
      return {product: {name: '', description: '', price: ''}
      }
    },
    methods: {
      createProduct: function() {
        var product = this.product;
        products.push({
          id: Math.random().toString().split('.')[1],
          name: product.name,
          description: product.description,
          price: product.price
        });
        router.push('/');
      }
    }
};

const Product = {
    template: '#personel',
    data: function(){
        return {personel: findProduct(this.$route.params.personel_id)};
    }
};

const ProductEdit = {
    template: '#product-edit',
    data: function(){
        return {product: findProduct(this.$route.params.product_id)};
    },
    methods: {
        updateProduct: function(){
            var product = this.product;
            products[findProductKey(product.id)] = {
                id: product.id,
                name: product.name,
                description: product.description,
                price: product.price
            }
            router.push('/');
        }
    }
};

const ProductDelete = {
    template: '#product-delete',
    data: function(){
        return {product: findProduct(this.$route.params.product_id)};
    },
    methods: {
        deleteProduct: function () {
          products.splice(findProductKey(this.$route.params.product_id), 1);
          router.push('/');
        }
    }
}

// router ------------------------------------------------------------
var router = new VueRouter({
    routes: [
        { path: '/', component: List },
        { path: '/product/:product_id', component: Product, name: 'product' },
        { path: '/add-product', component: AddProduct },
        { path: '/product/:product_id/edit', component: ProductEdit, name: 'product-edit' },
        { path: '/product/:product_id/delete', component: ProductDelete, name: 'product-delete' }
    ]
});

// Vue app -----------------------------------------------------------
var app = new Vue({
  el: '#app',
  router: router,
  template: '<router-view></router-view>'
});