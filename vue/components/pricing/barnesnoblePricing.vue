<template>
  <div>
    <div class="priceRow" v-if="barnesData.barnesPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="barnesData.barnesLink" @click="linkClick" itemprop="name">Barnes and Noble</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + barnesData.barnesPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="barnesData.barnesStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="barnesData.barnesLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      barnesResponse: [],
      barnesData: {
        barnesPrice: '',
        barnesLink: '',
        barnesDate: '',
        barnesStock: '',
        barnesError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Barnes and Noble',
        eventAction: this.barnesData.barnesLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.barnesPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    barnesPrices () {
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
        this.$emit('noPrice', {noPrice: true, retailer: 'barnes'});
        return;
      }
      

        // Axios function to get signed Amazon URL
        axios.get(adminAjax, {
          responseType: 'text',
          params: {
            action: "ks_getCjPrice",
            upc: upcCode,
            websiteId: '8512196', // BoardGamerDeal's ID through CJ
            advertiserId: '4258829' // B&N's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var barnesResponse = str.substring(0, str.length - 1);

          that.barnesResponse = xmltojson.parseString(barnesResponse);
        }) 
        .catch(function (error) {
          that.barnesData.barnesError = 'Error! Could not get Barnes and Noble prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      
    },
    savePrice () {
      if(this.barnesResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'barnes'});
        return;
      }
      if(this.barnesResponse) {
        if(this.barnesResponse['cj-api'][0].products[0].product) {
          // If a Sale Price exists, use that, otherwise use just the price field
          if(this.barnesResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text.length > 0) {
            this.barnesData.barnesPrice = Number.parseFloat(this.barnesResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          } else {
            this.barnesData.barnesPrice = Number.parseFloat(this.barnesResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text).toFixed(2);
          }
          this.barnesData.barnesLink = this.barnesResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.barnesData.barnesStock = this.barnesResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateBarnes(this.barnesData.barnesPrice, this.barnesData.barnesStock, this.barnesData.barnesLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'barnes'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'barnes'});
    },
    updateBarnes (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'barnes',
          price: price,
          stock: stock,
          link: link
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price barnes')
          console.log(data);
        }
      })
    }
  }
};
</script>
