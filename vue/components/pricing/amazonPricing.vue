<template>
  <div>
    <div class="priceRow" v-if="amazonData.amazonPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="amazonData.amazonLink" @click="linkClick"><span itemprop="name">Amazon.com</span></a>
      </div>
      <div class="rowPrice">
        <span v-html="amazonData.amazonPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span v-html="amazonData.amazonStock"></span>
      </div>
      <div class="rowLink">
        <a :href="amazonData.amazonLink" target="_blank" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import xmltojson from 'xmltojson';

module.exports = {
  // props: ['acfs','upcs','eans','elids','codes','gameTitle'],
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
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Amazon',
        eventAction: this.amazonData.amazonLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.amazonPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
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
        that.amazonData.amazonError = 'Error! Could not get Amazon prices. ' + error
      })
      .then( () => {
        this.saveAmazon()  
      })
    },
    saveAmazon () {
      if(this.amazonResponse.ItemLookupResponse) {
        if( !this.amazonResponse.ItemLookupResponse[0].Items[0].Item ) {
          // If no item found on Amazon at all
          this.amazonData = false;
          this.$emit('noPrice', {noPrice: true, retailer: 'amazon'});
          return
        }
        if( !this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer ) {
          // If no amazon offer found, check for private seller offers
          if( this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].OfferSummary[0].LowestNewPrice ) {
            this.amazonData.amazonPrice = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].OfferSummary[0].LowestNewPrice[0].FormattedPrice[0]._text;
          } else {
            this.amazonData.amazonPrice = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].OfferSummary[0].LowestCollectiblePrice[0].FormattedPrice[0]._text;
          }
          this.amazonData.amazonStock = '';
          this.amazonData.amazonLink = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].DetailPageURL[0]._text;  
          this.$emit('pricing', true)
          this.$emit('noPrice', {noPrice: false, retailer: 'amazon'});
          this.updateAmazon(this.amazonData.amazonPrice, this.amazonData.amazonStock, this.amazonData.amazonLink);
          return
        }
        if( this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Price[0].FormattedPrice ) {
          this.amazonData.amazonPrice = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Price[0].FormattedPrice[0]._text;
        }
        if( this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Availability ) {
          this.amazonData.amazonStock = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].Offers[0].Offer[0].OfferListing[0].Availability[0]._text;
        }
        if( this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].ASIN ) {
          this.amazonData.amazonASIN = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].ASIN[0]._text;
        }
        if( this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].DetailPageURL ) {
          this.amazonData.amazonLink = this.amazonResponse.ItemLookupResponse[0].Items[0].Item[0].DetailPageURL[0]._text;
        }
        this.$emit('pricing', true)
        this.$emit('noPrice', {noPrice: false, retailer: 'amazon'});
        this.updateAmazon(this.amazonData.amazonPrice, this.amazonData.amazonStock, this.amazonData.amazonLink);
      }
    },
    updateAmazon (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'amazon',
          price: price,
          stock: stock,
          link: link
          },
        success: function(data){
          // console.log('success')
          // console.log(data);
        },
        error: function(data) {
          console.log('error updating game price amazon')
          console.log(data);
        }
      })
    }
  }
};
</script>
