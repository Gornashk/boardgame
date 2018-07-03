<template>
  <div>
    <div class="priceRow" v-if="funData.funPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="funData.funLink" @click="linkClick" itemprop="name">Fun.com</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + funData.funPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="funData.funStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="funData.funLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import xmltojson from 'xmltojson';

module.exports = {
  // props: ['acfs','upcs','eans','elids','codes'],
  props: ["game"],
  data () {
    return {
      funResponse: [],
      funData: {
        funPrice: '',
        funLink: '',
        funDate: '',
        funStock: '',
        funError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Fun.com',
        eventAction: this.funData.funLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.funPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    funPrices () {
      var that = this;
      // create blank id code vars
      var upcCode
      if( this.game.acf.upcs ) {
        // If UPC codes, get code
        upcCode = this.game.acf.upcs[0].upc
      } else if( this.game.acf.eans ) {
        // If no UPC, use EAN code
        upcCode = this.game.acf.eans[0].ean
      } else {
        // If no codes, return and emit no prices
        this.$emit('noPrice', {noPrice: true, retailer: 'fun'});
        return;
      }
      

        // Axios function to get signed Amazon URL
        axios.get(adminAjax, {
          responseType: 'text',
          params: {
            action: "ks_getCjPrice",
            upc: upcCode,
            websiteId: '8512196', // BoardGamerDeal's ID through CJ
            advertiserId: '4449509' // Fun.com's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var funResponse = str.substring(0, str.length - 1);

          that.funResponse = xmltojson.parseString(funResponse);
        }) 
        .catch(function (error) {
          that.$emit('noPrice', {noPrice: true, retailer: 'fun'});
          that.funData.funError = 'Error! Could not get Fun.com prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      
    },
    savePrice () {
      if(this.funResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'fun'});
        return;
      }
      if(this.funResponse) {
        if(this.funResponse['cj-api'][0].products[0].product) {
          // If a Sale Price exists, use that, otherwise use just the price field
          if(this.funResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text.length > 0) {
            this.funData.funPrice = Number.parseFloat(this.funResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          } else {
            this.funData.funPrice = Number.parseFloat(this.funResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text).toFixed(2);
          }
          this.funData.funLink = this.funResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.funData.funStock = this.funResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateFun(this.funData.funPrice, this.funData.funStock, this.funData.funLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'fun'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'fun'});
    },
    updateFun (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'Fun.com',
          price: price,
          stock: stock,
          link: link,
          rowNum: 5
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price fun.com')
          console.log(data);
        }
      })
    }
  }
};
</script>
