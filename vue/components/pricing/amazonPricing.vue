<template>
  <div>
    <div class="priceRow" v-if="amazonData.amazonPrice">
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
  </div>
</template>

<script>
import axios from 'axios';
import xmltojson from 'xmltojson';

module.exports = {
  props: ["game"],
  data () {
    return {
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
    this.checkCodes();
  },
  methods: {
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          this.amazonPrices()
          return;
        }
        // Cancel function if UPCs already exist
        return;
      }
    },
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
        if( !this.amazonResponse.ItemLookupResponse[0].Items[0].Item ) {
          // If no item found on Amazon at all
          this.amazonData = false;
          return
        }
        if( !this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer ) {
          // If no amazon offer found, check for private seller offers
          this.amazonData.amazonPrice = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].OfferSummary[0].LowestNewPrice[0].FormattedPrice[0]._text;
          this.amazonData.amazonStock = '';
          this.amazonData.amazonLink = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].DetailPageURL[0]._text;  
          this.$emit('pricing', true)
          return
        }
        this.amazonData.amazonPrice = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Price[0].FormattedPrice[0]._text;
        this.amazonData.amazonStock = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Availability[0]._text;
        this.amazonData.amazonASIN = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].ASIN[0]._text;
        this.amazonData.amazonLink = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].DetailPageURL[0]._text;
        this.$emit('pricing', true)
      }
    },
  }
};
</script>
