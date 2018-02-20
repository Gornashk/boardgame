<template>
  <div>
    <h4>Current Offers for {{game.title}}</h4>
    <div class="priceTable">
      <div class="priceRow" v-if="amazonResponse.ItemLookupResponse">
        <div class="rowName">
          <a :href="amazonData.amazonLink">Amazon.com</a>
        </div>
        <div class="rowPrice">
          <span v-html="amazonData.amazonPrice"></span>
        </div>
        <div class="rowStock">
          <span v-html="amazonData.amazonStock"></span>
        </div>
        <div class="rowLink">
          <a :href="amazonData.amazonLink" class="storeBtn">Visit Store</a>
        </div>
      </div>
      <div class="priceRow">
        <div class="rowName">
          <a href="">Walmart.com</a>
        </div>
        <div class="rowPrice">
          <span>$55.66</span>
        </div>
        <div class="rowStock">
          <span>In Stock</span>
        </div>
        <div class="rowLink">
          <a href="" class="storeBtn">Visit Store</a>
        </div>
      </div>
      <div class="priceRow">
        <div class="rowName">
          <a href="">Booksamillion.com</a>
        </div>
        <div class="rowPrice">
          <span>$48.66</span>
        </div>
        <div class="rowStock">
          <span>In Stock</span>
        </div>
        <div class="rowLink">
          <a href="" class="storeBtn">Visit Store</a>
        </div>
      </div>
      <div class="priceRow">
        <div class="rowName">
          <a href="">Walmart.com</a>
        </div>
        <div class="rowPrice">
          <span>$55.66</span>
        </div>
        <div class="rowStock">
          <span>In Stock</span>
        </div>
        <div class="rowLink">
          <a href="" class="storeBtn">Visit Store</a>
        </div>
      </div>
      <div class="priceRow">
        <div class="rowName">
          <a href="">Barnesandnoble.com</a>
        </div>
        <div class="rowPrice">
          <span>$45.66</span>
        </div>
        <div class="rowStock">
          <span>Out of Stock</span>
        </div>
        <div class="rowLink">
          <a href="" class="storeBtn">Visit Store</a>
        </div>
      </div>
    </div>
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
      amazonResponse: [],
      amazonData: {
        amazonPrice: '',
        amazonLink: '',
        amazonDate: '',
        amazonASIN: '',
        amazonStock: '',
        amazonError: ''
      }
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
      .then( () => {
        this.saveAmazon()  
      })
    },
    saveAmazon () {
      if(this.amazonResponse) {
        this.amazonData.amazonPrice = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Price[0].FormattedPrice[0]._text;
        this.amazonData.amazonStock = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Availability[0]._text;
        this.amazonData.amazonASIN = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].ASIN[0]._text;
        this.amazonData.amazonLink = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].DetailPageURL[0]._text;
      }
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