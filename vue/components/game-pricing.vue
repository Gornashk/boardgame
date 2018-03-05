<template>
  <div>
    <h4>Current Offers for <span v-html="game.title"></span></h4>
    <div class="priceTable">
      <div class="priceRow" v-if="!pricingExists">
        <div>
          <span>We could not find this game for sale at this time.</span>
        </div>
      </div>
      <amazon-pricing :game="game" v-on:pricing="pricingCheck"></amazon-pricing>
      <walmart-pricing :game="game" v-on:pricing="pricingCheck"></walmart-pricing>
    </div>
  </div>
</template>

<script>
// require('dotenv').config()
import axios from 'axios';
import xmltojson from 'xmltojson';
import _compact from 'lodash/compact';

import amazonPricing from './pricing/amazonPricing.vue';
import walmartPricing from './pricing/walmartPricing.vue';

module.exports = {
  components: { amazonPricing, walmartPricing },
  data () { 
    return {
      game: singleGame,
      upcResponse: [],
      pricingExists: false
    }
  },
  mounted () {
    // console.log('mounted 2')
    // console.log(process.env.AWS_ID);
    this.getGameIDs()
    // this.amazonPrices()
  },
  watch: {
    upcResponse (newResponse, oldResponse) {
      this.saveGameIDs()
    }
  },
  methods: {
    pricingCheck (payload) {
      if(payload) {
        this.pricingExists = true
      }
    },
    getGameIDs () {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // this.amazonPrices()
          return;
        }
        // Cancel function if UPCs already exist
        return;
      }
      var that = this;
      var title = encodeURIComponent(this.game.title);
      axios.get(adminAjax, {
        responseType: 'json',
        params: {
          action: "ks_getUpcIds",
          gameTitle: title
        }
      })
      .then((response) => {
        console.log(response)
        this.upcResponse = response.data
      })
      .catch(function (error) {
        this.upcResponse = 'Error! Could not reach the API. ' + error
      })
    },
    saveGameIDs () {
      if( this.game.acf.codes ) {
        // Cancel function if UPCs already exist
        return;
      }
      var responseItems = this.upcResponse.items
      var upc = [];
      var asin = [];
      var ean = [];
      var elid = [];
      var mpn = [];
      responseItems.forEach(function(item){
        if(item.upc) {
          var upcObj = {
            upc: item.upc,
            title: item.title
          }
        }
        if(item.asin) {
          var asinObj = {
            asin: item.asin,
            title: item.title
          }
        }
        if(item.ean) {
          var eanObj = {
            ean: item.ean,
            title: item.title
          }
        }
        if(item.elid) {
          var elidObj = {
            elid: item.elid,
            title: item.title
          }
        }
        if(item.model) {
          var mpnObj = {
            mpn: item.model,
            title: item.title
          }
        }
        upc.push(upcObj)
        asin.push(asinObj)
        ean.push(eanObj)
        elid.push(elidObj)
        mpn.push(mpnObj)
      })
      upc = _compact(upc)
      asin = _compact(asin)
      ean = _compact(ean)
      elid = _compact(elid)
      mpn = _compact(mpn)

      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_saveGameIds",
          nonce: nonce,
          postID: this.game.id,
          upc: upc,
          asin: asin,
          ean: ean,
          elid: elid,
          mpn: mpn
         },
        success: function(data){
          console.log(data);
        },
        error: function(data) {
          console.log('error')
          console.log(data);
        }
      })
    }
  }
}

</script>