<template>
  <div>
    <div class="priceRow" v-if="indigoData.indigoPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="indigoData.indigoLink" @click="linkClick" itemprop="name">Indigo Books & Music</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + indigoData.indigoPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="indigoData.indigoStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="indigoData.indigoLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      indigoResponse: [],
      indigoData: {
        indigoPrice: '',
        indigoLink: '',
        indigoDate: '',
        indigoStock: '',
        indigoError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Indigo',
        eventAction: this.indigoData.indigoLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.indigoPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    indigoPrices () {
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
        this.$emit('noPrice', {noPrice: true, retailer: 'indigo'});
        return;
      }
      

        // Axios function to get signed Amazon URL
        axios.get(adminAjax, {
          responseType: 'text',
          params: {
            action: "ks_getCjPrice",
            upc: upcCode,
            websiteId: '8512196', // BoardGamerDeal's ID through CJ
            advertiserId: '1666237' // Indigo's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var indigoResponse = str.substring(0, str.length - 1);

          that.indigoResponse = xmltojson.parseString(indigoResponse);
        }) 
        .catch(function (error) {
          that.$emit('noPrice', {noPrice: true, retailer: 'indigo'});
          that.indigoData.indigoError = 'Error! Could not get Indigo prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      
    },
    savePrice () {
      if(this.indigoResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'indigo'});
        return;
      }
      if(this.indigoResponse) {
        if(this.indigoResponse['cj-api'][0].products[0].product) {
          // If a Sale Price exists, use that, otherwise use just the price field
          if(this.indigoResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text.length > 0) {
            this.indigoData.indigoPrice = Number.parseFloat(this.indigoResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          } else {
            this.indigoData.indigoPrice = Number.parseFloat(this.indigoResponse['cj-api'][0].products[0].product[0]['sale-price'][0]._text).toFixed(2);
          }
          this.indigoData.indigoLink = this.indigoResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.indigoData.indigoStock = this.indigoResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateIndigo(this.indigoData.indigoPrice, this.indigoData.indigoStock, this.indigoData.indigoLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'indigo'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'indigo'});
    },
    updateIndigo (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'Indigo Books & Music',
          price: price,
          stock: stock,
          link: link,
          rowNum: 6
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price indigo')
          console.log(data);
        }
      })
    }
  }
};
</script>
