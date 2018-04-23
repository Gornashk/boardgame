<template>
  <div>
    <h4>Current Offers for <span v-html="game.title"></span></h4>
    <div class="priceTable" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
      <div class="priceRow" v-if="!pricingExists">
        <div>
          <span>We could not find this game for sale at this time.</span>
        </div>
      </div>
      <amazon-pricing :game="game" v-on:pricing="pricingCheck"></amazon-pricing>
      <walmart-pricing :game="game" v-on:pricing="pricingCheck"></walmart-pricing>
      <barnes-pricing :game="game" v-on:pricing="pricingCheck"></barnes-pricing>
      <entertainment-pricing :game="game" v-on:pricing="pricingCheck"></entertainment-pricing>
      <newegg-pricing :game="game" v-on:pricing="pricingCheck"></newegg-pricing>
      <star-trek-pricing :game="game" v-on:pricing="pricingCheck"></star-trek-pricing>
      <bam-pricing :game="game" v-on:pricing="pricingCheck"></bam-pricing>
      <unbeatable-pricing :game="game" v-on:pricing="pricingCheck"></unbeatable-pricing>
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
import barnesPricing from './pricing/barnesnoblePricing.vue';
import entertainmentPricing from './pricing/entertainmentEarthPricing.vue';
import neweggPricing from './pricing/neweggPricing.vue';
import starTrekPricing from './pricing/startrekPricing.vue';
import bamPricing from './pricing/bamPricing.vue';
import unbeatablePricing from './pricing/unbeatablePricing.vue';

module.exports = {
  components: { amazonPricing, walmartPricing, barnesPricing, entertainmentPricing, neweggPricing, starTrekPricing, bamPricing, unbeatablePricing },
  // props: ['acfs','upcs','eans','elids','codes','gameTitle'],
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
  // watch: {
  //   upcResponse (newResponse, oldResponse) {
  //     this.saveGameIDs()
  //   }
  // },
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
      var title = this.game.title;
      var titleEncode = encodeURIComponent(title);
      // Search Amazon API, then search UPC Item DB
      axios.get(adminAjax, {
        responseType: 'text',
        params: {
          action: "ks_getAmazonSearch",
          gameTitle: title
        }
      })
      .then((response) => {
        console.log(response)
        var str = response.data
        var amazonSearch = str.substring(0, str.length - 1);
        that.amazonSearch = xmltojson.parseString(amazonSearch);
        
      })
      .catch(function (error) {
        that.amazonSearch = 'Error! Could not reach the API. ' + error
      })
      .then(() => {

        // Search UPC Item DB after searching Amazon API
        axios.get(adminAjax, {
          responseType: 'json',
          params: {
            action: "ks_getUpcIds",
            gameTitle: titleEncode
          }
        })
        .then((response) => {
          console.log(response)
          that.upcResponse = response.data
        })
        .catch(function (error) {
          that.upcResponse = 'Error! Could not reach the API. ' + error
        })
        .then(() => {
          this.saveGameIDs()
        })

      })
    },
    saveGameIDs () {
      if( this.game.acf.codes ) {
        // Cancel function if UPCs already exist
        return;
      }
      if(this.amazonSearch.ItemSearchResponse[0].Items[0].Item) {
        var amazonItem = this.amazonSearch.ItemSearchResponse[0].Items[0].Item[0];
        var amzTitle = amazonItem.ItemAttributes[0].Title[0]._text;
        var amzASIN = amazonItem.ASIN[0]._text;
        if(amazonItem.ItemAttributes[0].EAN) {
          var amzEAN = amazonItem.ItemAttributes[0].EAN[0]._text;
        }
        if(amazonItem.ItemAttributes[0].MPN) {
          var amzMPN = amazonItem.ItemAttributes[0].MPN[0]._text;
        }
        if(amazonItem.ItemAttributes[0].UPC) {
          var amzUPC = amazonItem.ItemAttributes[0].UPC[0]._text;
        }
        if(amazonItem.ItemAttributes[0].ELID) {
          var amzELID = amazonItem.ItemAttributes[0].ELID[0]._text;
        }
      }
      if(this.upcResponse) {
        var upcItems = this.upcResponse.items
      }
      var upc = [];
      var asin = [];
      var ean = [];
      var elid = [];
      var mpn = [];
      if(upcItems) {
        upcItems.forEach(function(item){
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
      }

      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_saveGameIds",
          nonce: nonce,
          postID: this.game.id,
          amzTitle: amzTitle,
          amzAsin: amzASIN,
          amzEan: amzEAN,
          amzMpn: amzMPN,
          amzUpc: amzUPC,
          amzElid: amzELID,
          upc: upc,
          asin: asin,
          ean: ean,
          elid: elid,
          mpn: mpn
         },
        success: function(data){
          // console.log(data);
          // Send user to newly created game post
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