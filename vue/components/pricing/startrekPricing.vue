<template>
  <div>
    <div class="priceRow" v-if="startrekData.startrekPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="startrekData.startrekLink" @click="linkClick" itemprop="name">StarTrek.com</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + startrekData.startrekPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="startrekData.startrekStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="startrekData.startrekLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      startrekResponse: [],
      startrekData: {
        startrekPrice: '',
        startrekLink: '',
        startrekDate: '',
        startrekStock: '',
        startrekError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Star Trek',
        eventAction: this.startrekData.startrekLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.startrekPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    startrekPrices () {
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
            advertiserId: '4338270' // Star Trek's ID through CJ
          }
        })
        .then((response) => {
          var str = response.data
          var startrekResponse = str.substring(0, str.length - 1);

          that.startrekResponse = xmltojson.parseString(startrekResponse);
        }) 
        .catch(function (error) {
          that.startrekData.startrekError = 'Error! Could not get star trek prices. ' + error
        })
        .then( () => {
          this.savePrice()
        })
      }
    },
    savePrice () {
      if(this.startrekResponse.errors) {
        return;
      }
      if(this.startrekResponse) {
        if(this.startrekResponse['cj-api'][0].products[0].product) {
          this.startrekData.startrekPrice = Number.parseFloat(this.startrekResponse['cj-api'][0].products[0].product[0].price[0]._text).toFixed(2);
          this.startrekData.startrekLink = this.startrekResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.startrekData.startrekStock = this.startrekResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateStartrek(this.startrekData.startrekPrice, this.startrekData.startrekStock, this.startrekData.startrekLink);
          this.$emit('pricing', true)
        }
      }
    },
    updateStartrek (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'starTrek',
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
