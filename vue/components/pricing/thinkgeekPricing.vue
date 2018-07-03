<template>
  <div>
    <div class="priceRow" v-if="thinkgeekData.thinkgeekPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="thinkgeekData.thinkgeekLink" @click="linkClick" itemprop="name">ThinkGeek</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + thinkgeekData.thinkgeekPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="thinkgeekData.thinkgeekStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="thinkgeekData.thinkgeekLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      thinkgeekResponse: [],
      thinkgeekData: {
        thinkgeekPrice: '',
        thinkgeekLink: '',
        thinkgeekDate: '',
        thinkgeekStock: '',
        thinkgeekError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'ThinkGeek',
        eventAction: this.thinkgeekData.thinkgeekLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.thinkgeekPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    thinkgeekPrices () {
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
        this.$emit('noPrice', {noPrice: true, retailer: 'thinkgeek'});
        return;
      }
      

        // Axios function to get signed Amazon URL
        axios.get(adminAjax, {
          responseType: 'text',
          params: {
            action: "ks_getCjPrice",
            upc: upcCode,
            websiteId: '8512196', // BoardGamerDeal's ID through CJ
            advertiserId: '1382555' // Think Geek's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var thinkgeekResponse = str.substring(0, str.length - 1);

          that.thinkgeekResponse = xmltojson.parseString(thinkgeekResponse);
        }) 
        .catch(function (error) {
          that.$emit('noPrice', {noPrice: true, retailer: 'thinkgeek'});
          that.thinkgeekData.thinkgeekError = 'Error! Could not get Think Geek prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      
    },
    savePrice () {
      if(this.thinkgeekResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'thinkgeek'});
        return;
      }
      if(this.thinkgeekResponse) {
        if(this.thinkgeekResponse['cj-api'][0].products[0].product) {
          // If a Sale Price exists, use that, otherwise use just the price field
          if(this.thinkgeekResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text.length > 0) {
            this.thinkgeekData.thinkgeekPrice = Number.parseFloat(this.thinkgeekResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          } else {
            this.thinkgeekData.thinkgeekPrice = Number.parseFloat(this.thinkgeekResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text).toFixed(2);
          }
          this.thinkgeekData.thinkgeekLink = this.thinkgeekResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.thinkgeekData.thinkgeekStock = this.thinkgeekResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateThinkgeek(this.thinkgeekData.thinkgeekPrice, this.thinkgeekData.thinkgeekStock, this.thinkgeekData.thinkgeekLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'thinkgeek'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'thinkgeek'});
    },
    updateThinkgeek (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'Thinkgeek',
          price: price,
          stock: stock,
          link: link,
          rowNum: 9
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price thinkgeek')
          console.log(data);
        }
      })
    }
  }
};
</script>
