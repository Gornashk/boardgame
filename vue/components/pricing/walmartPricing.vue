<template>
  <div>
    <div class="priceRow" v-if="walmartData.walmartPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="walmartData.walmartLink" @click="linkClick" itemprop="name">Walmart</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + walmartData.walmartPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span v-html="walmartData.walmartStock"></span>
      </div>
      <div class="rowLink">
        <a :href="walmartData.walmartLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      walmartResponse: [],
      walmartData: {
        walmartPrice: '',
        walmartLink: '',
        walmartDate: '',
        walmartStock: '',
        walmartError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Walmart',
        eventAction: this.walmartData.walmartLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          this.walmartPrices()
          return;
        }
        // Cancel function if UPCs already exist
        return;
      }
    },
    walmartPrices () {
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
        this.$emit('noPrice', {noPrice: true, retailer: 'walmart'});
        return;
      }

      // Axios function to get signed Amazon URL
      axios.get(adminAjax, {
        responseType: 'text',
        params: {
          action: "ks_getWalmartPrice",
          apiKey: 'em5trawayxythnku8ap3dmyw',
          upc: upcCode,
          lsPublisherId: 'mW/x7g5aNEg'
        }
      })
      .then((response) => {
        
        var str = response.data
        var walmartResponse = str.substring(0, str.length - 1);

        that.walmartResponse = JSON.parse(walmartResponse);
      }) 
      .catch(function (error) {
        that.$emit('noPrice', {noPrice: true, retailer: 'walmart'});
        that.walmartData.walmartError = 'Error! Could not get Walmart prices. ' + error
      })
      .then( () => {
        this.savePrice()
      })
    },
    savePrice () {
      if(this.walmartResponse.errors) {
        this.$emit('noPrice', {noPrice: true, retailer: 'walmart'});
        return;
      }
      if(this.walmartResponse.items) {
        if(this.walmartResponse.items.length > 0) {
          this.walmartData.walmartPrice = Number.parseFloat(this.walmartResponse.items[0].salePrice).toFixed(2);
          this.walmartData.walmartLink = this.walmartResponse.items[0].productTrackingUrl;
          this.walmartData.walmartStock = this.walmartResponse.items[0].stock;
          this.updateWalmart(this.walmartData.walmartPrice, this.walmartData.walmartStock, this.walmartData.walmartLink);
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'walmart'});
          return;
        }
      }
      this.$emit('noPrice', {noPrice: true, retailer: 'walmart'});
    },
    updateWalmart (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'Walmart',
          price: price,
          stock: stock,
          link: link,
          rowNum: 11
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price walmart')
          console.log(data);
        }
      })
    }
  }
};
</script>
