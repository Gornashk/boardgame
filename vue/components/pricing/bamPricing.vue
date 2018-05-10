<template>
  <div>
    <div class="priceRow" v-if="bamData.bamPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="bamData.bamLink" @click="linkClick" itemprop="name">Books a Million</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + bamData.bamPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="bamData.bamStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="bamData.bamLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      bamResponse: [],
      bamData: {
        bamPrice: '',
        bamLink: '',
        bamDate: '',
        bamStock: '',
        bamError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Books A Million',
        eventAction: this.bamData.bamLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.bamPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    bamPrices () {
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
            advertiserId: '129899' // Books A Million's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var bamResponse = str.substring(0, str.length - 1);

          that.bamResponse = xmltojson.parseString(bamResponse);
        }) 
        .catch(function (error) {
          that.bamData.bamError = 'Error! Could not get Books A Million prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      }
    },
    savePrice () {
      if(this.bamResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'bam'});
        return;
      }
      if(this.bamResponse) {
        if(this.bamResponse['cj-api'][0].products[0].product) {
          // If a Sale Price exists, use that, otherwise use just the price field
          if(this.bamResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text.length > 0) {
            this.bamData.bamPrice = Number.parseFloat(this.bamResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          } else {
            this.bamData.bamPrice = Number.parseFloat(this.bamResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text).toFixed(2);
          }
          this.bamData.bamLink = this.bamResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.bamData.bamStock = this.bamResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateBam(this.bamData.bamPrice, this.bamData.bamStock, this.bamData.bamLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'bam'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'bam'});
    },
    updateBam (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'bam',
          price: price,
          stock: stock,
          link: link
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price bam')
          console.log(data);
        }
      })
    }
  }
};
</script>
