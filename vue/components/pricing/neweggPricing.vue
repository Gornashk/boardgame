<template>
  <div>
    <div class="priceRow" v-if="neweggData.neweggPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="neweggData.neweggLink" @click="linkClick" itemprop="name">Newegg.com</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + neweggData.neweggPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="neweggData.neweggStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="neweggData.neweggLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      neweggResponse: [],
      neweggData: {
        neweggPrice: '',
        neweggLink: '',
        neweggDate: '',
        neweggStock: '',
        neweggError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Newegg',
        eventAction: this.neweggData.neweggLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.neweggPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    neweggPrices () {
      var that = this;
      // create blank id code vars
      var upcCode
      if( this.game.acf.upcs ) {
        upcCode = this.game.acf.upcs[0].upc
      

        // Axios function to get signed Amazon URL
        axios.get(adminAjax, {
          responseType: 'text',
          params: {
            action: "ks_getCjPrice",
            upc: upcCode,
            websiteId: '8512196', // BoardGamerDeal's ID through CJ
            advertiserId: '1807847' // NewEgg's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var neweggResponse = str.substring(0, str.length - 1);

          that.neweggResponse = xmltojson.parseString(neweggResponse);
        }) 
        .catch(function (error) {
          that.neweggData.neweggError = 'Error! Could not get Newegg prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      }
    },
    savePrice () {
      if(this.neweggResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'newegg'});
        return;
      }
      if(this.neweggResponse) {
        if(this.neweggResponse['cj-api'][0].products[0].product) {
          // If a Sale Price exists, use that, otherwise use just the price field
          if(this.neweggResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text.length > 0) {
            this.neweggData.neweggPrice = Number.parseFloat(this.neweggResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          } else {
            this.neweggData.neweggPrice = Number.parseFloat(this.neweggResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text).toFixed(2);
          }
          this.neweggData.neweggLink = this.neweggResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.neweggData.neweggStock = this.neweggResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateNewegg(this.neweggData.neweggPrice, this.neweggData.neweggStock, this.neweggData.neweggLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'newegg'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'newegg'});
    },
    updateNewegg (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'newegg',
          price: price,
          stock: stock,
          link: link
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price newegg')
          console.log(data);
        }
      })
    }
  }
};
</script>
