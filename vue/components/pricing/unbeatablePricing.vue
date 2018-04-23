<template>
  <div>
    <div class="priceRow" v-if="unbeatableData.unbeatablePrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="unbeatableData.unbeatableLink" @click="linkClick" itemprop="name">UnbeatableSale.com</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + unbeatableData.unbeatablePrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="unbeatableData.unbeatableStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="unbeatableData.unbeatableLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      unbeatableResponse: [],
      unbeatableData: {
        unbeatablePrice: '',
        unbeatableLink: '',
        unbeatableDate: '',
        unbeatableStock: '',
        unbeatableError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Unbeatable Sale',
        eventAction: this.unbeatableData.unbeatableLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.unbeatablePrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    unbeatablePrices () {
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
            advertiserId: '1807847' // Unbeatable Sale's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var unbeatableResponse = str.substring(0, str.length - 1);

          that.unbeatableResponse = xmltojson.parseString(unbeatableResponse);
        }) 
        .catch(function (error) {
          that.unbeatableData.unbeatableError = 'Error! Could not get Unbeatable Sale prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      }
    },
    savePrice () {
      if(this.unbeatableResponse.errors) {
        return;
      }
      if(this.unbeatableResponse) {
        if(this.unbeatableResponse['cj-api'][0].products[0].product) {
          this.unbeatableData.unbeatablePrice = Number.parseFloat(this.unbeatableResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          this.unbeatableData.unbeatableLink = this.unbeatableResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.unbeatableData.unbeatableStock = this.unbeatableResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateUnbeatable(this.unbeatableData.unbeatablePrice, this.unbeatableData.unbeatableStock, this.unbeatableData.unbeatableLink);
          this.$emit('pricing', true)
        }
      }
    },
    updateUnbeatable (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'unbeatable sale',
          price: price,
          stock: stock,
          link: link
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error')
          console.log(data);
        }
      })
    }
  }
};
</script>
