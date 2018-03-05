<template>
  <div>
    <div class="priceRow" v-if="walmartData.walmartPrice">
      <div class="rowName">
        <a :href="walmartData.walmartLink">Walmart.com</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + walmartData.walmartPrice"></span>
      </div>
      <div class="rowStock">
        <span v-html="walmartData.walmartStock"></span>
      </div>
      <div class="rowLink">
        <a :href="walmartData.walmartLink" class="storeBtn">Visit Store</a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import xmltojson from 'xmltojson';

module.exports = {
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
        upcCode = this.game.acf.upcs[0].upc
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
        that.walmartData.walmartError = 'Error! Could not get Walmart prices. ' + error
      })
      .then( () => {
        this.savePrice()
      })
    },
    savePrice () {
      if(this.walmartResponse.errors) {
        return;
      }
      if(this.walmartResponse) {
        this.walmartData.walmartPrice = Number.parseFloat(this.walmartResponse.items[0].salePrice).toFixed(2);
        this.walmartData.walmartLink = this.walmartResponse.items[0].productTrackingUrl;
        this.walmartData.walmartStock = this.walmartResponse.items[0].stock;
        this.$emit('pricing', true)
      }
    },
  }
};
</script>
