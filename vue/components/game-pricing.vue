<template>
  <div>
    <ul>
      <li><a href="#">{{game.title}}</a></li>
    </ul>
    <span v-html="amazonResponse"></span>
  </div>
</template>

<script>
// require('dotenv').config()
import axios from 'axios';
import xmltojson from 'xmltojson';

module.exports = {
  data () { 
    return {
      game: singleGame,
      upcResponse: [],
      amazonError: '',
      amazonResponse: []
    }
  },
  mounted () {
    console.log('mounted 2')
    console.log(process.env.AWS_ID);
    this.getGameIDs()
    // this.amazonPrices()
  },
  watch: {
    upcResponse (newResponse, oldResponse) {
      this.saveGameIDs()
    }
  },
  methods: {
    amazonPrices () {
      var that = this;
      // create blank id code vars
      var asinCode
      var eanCode
      var upcCode
      var elidCode
      if( this.game.acf.asins ) {
        asinCode = this.game.acf.asins[0].asin
      }
      if( this.game.acf.eans ) {
        eanCode = this.game.acf.eans[0].ean
      }
      if( this.game.acf.upcs ) {
        upcCode = this.game.acf.upcs[0].upc
      }
      if( this.game.acf.elids ) {
        elidCode = this.game.acf.elids[0].elid
      }

      // Axios function to get signed Amazon URL
      axios.get(adminAjax, {
        responseType: 'text',
        params: {
          action: "ks_getAmazonPrice",
          asin: asinCode,
          ean: eanCode,
          upc: upcCode,
          elid: elidCode
        }
      })
      .then((response) => {
        var str = response.data
        var amazonResponse = str.substring(0, str.length - 1);
        this.amazonResponse = xmltojson.parseString(amazonResponse)
      }) 
      .catch(function (error) {
        that.amazonError = 'Error! Could not get Amazon prices. ' + error
      })
    },
    getGameIDs () {
      if( this.game.acf.upcs ) {

        if( this.game.acf.upcs.length > 0 ) {
          this.amazonPrices()
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
      if( this.game.acf.upcs ) {
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
        upc.push(item.upc)
        asin.push(item.asin)
        ean.push(item.ean)
        elid.push(item.elid)
        mpn.push(item.model)
      })
      upc = _.compact(upc)
      asin = _.compact(asin)
      ean = _.compact(ean)
      elid = _.compact(elid)
      mpn = _.compact(mpn)

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