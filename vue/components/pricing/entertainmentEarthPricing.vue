<template>
  <div>
    <div class="priceRow" v-if="entertainmentData.entertainmentPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="entertainmentData.entertainmentLink" @click="linkClick" itemprop="name">Entertainment Earth</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + entertainmentData.entertainmentPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="entertainmentData.entertainmentStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="entertainmentData.entertainmentLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      entertainmentResponse: [],
      entertainmentData: {
        entertainmentPrice: '',
        entertainmentLink: '',
        entertainmentDate: '',
        entertainmentStock: '',
        entertainmentError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Entertainment Earth',
        eventAction: this.entertainmentData.entertainmentLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.entertainmentPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    entertainmentPrices () {
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
        this.$emit('noPrice', {noPrice: true, retailer: 'entertainment'});
        return;
      }
      

        // Axios function to get signed Amazon URL
        axios.get(adminAjax, {
          responseType: 'text',
          params: {
            action: "ks_getCjPrice",
            upc: upcCode,
            websiteId: '8512196', // BoardGamerDeal's ID through CJ
            advertiserId: '1413722' // Entertainment Earth's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var entertainmentResponse = str.substring(0, str.length - 1);

          that.entertainmentResponse = xmltojson.parseString(entertainmentResponse);
        }) 
        .catch(function (error) {
          that.$emit('noPrice', {noPrice: true, retailer: 'entertainment'});
          that.entertainmentData.entertainmentError = 'Error! Could not get Entertainment Earth prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      
    },
    savePrice () {
      if(this.entertainmentResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'entertainment'});
        return;
      }
      if(this.entertainmentResponse) {
        if(this.entertainmentResponse['cj-api'][0].products[0].product) {
          // If a Sale Price exists, use that, otherwise use just the price field
          if(this.entertainmentResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text.length > 0) {
            this.entertainmentData.entertainmentPrice = Number.parseFloat(this.entertainmentResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          } else {
            this.entertainmentData.entertainmentPrice = Number.parseFloat(this.entertainmentResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text).toFixed(2);
          }
          this.entertainmentData.entertainmentLink = this.entertainmentResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.entertainmentData.entertainmentStock = this.entertainmentResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateEntertainment(this.entertainmentData.entertainmentPrice, this.entertainmentData.entertainmentStock, this.entertainmentData.entertainmentLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'entertainment'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'entertainment'});
    },
    updateEntertainment (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'entertainment',
          price: price,
          stock: stock,
          link: link
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price entertainment earth')
          console.log(data);
        }
      })
    }
  }
};
</script>
