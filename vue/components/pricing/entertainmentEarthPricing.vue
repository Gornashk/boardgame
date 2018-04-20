<template>
  <div>
    <div class="priceRow" v-if="entertainmentData.entertainmentPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="entertainmentData.entertainmentLink" @click="linkClick" itemprop="name">Entertainment Earth</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + entertainmentData.entertainmentPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span
        v-if="entertainmentData.entertainmentStock">In Stock</span>
        <span v-else>Out of Stock</span>
      </div>
      <div class="rowLink">
        <a :href="entertainmentData.entertainmentLink" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      entertainmentResponse: [],
      entertainmentData: {
        entertainmentPrice: '',
        entertainmentLink: '',
        entertainmentDate: '',
        entertainmentStock: '',
        entertainmentError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Entertainment Earth',
        eventAction: this.entertainmentData.entertainmentLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.entertainmentPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    entertainmentPrices () {
      var that = this;
      // create blank id code vars
      var upcCode
      if( this.game.acf.upcs ) {
        upcCode = this.game.acf.upcs[0].upc
      }

      // axios.get('https://product-search.api.cj.com/v2/product-search', {
      //   headers: {
      //     authorization: '00a6dd1cf06e33479ee017af3d7de3e1e8f77407ab66777c2ca9d7e2c8b2a099e6c22a7e6d01a27277707247503f435ce0dfc18d442771cb347afd4e2306ffb967/292905c8710689363184e61f3efc856f580af11160973cb42fb92721e69e231818776f8b727a9c9a2b18e3ef66840874e395c7d47efce8f5fa7c435936b73b31'
      //   },
      //   params: {
      //     upc: upcCode,
      //     websiteId: '8512196',
      //     advertiserId: '4258829'
      //   }
      // })
      // .then((response) => {
      //   console.log(response)
      // })

      // Axios function to get signed Amazon URL
      axios.get(adminAjax, {
        responseType: 'text',
        params: {
          action: "ks_getCjPrice",
          upc: upcCode,
          websiteId: '8512196', // BoardGamerDeal's ID through CJ
          advertiserId: '1413722' // Entertainment Earth's ID through CJ
        }
      })
      .then((response) => {
        var str = response.data
        var entertainmentResponse = str.substring(0, str.length - 1);

        that.entertainmentResponse = xmltojson.parseString(entertainmentResponse);
      }) 
      .catch(function (error) {
        that.entertainmentData.entertainmentError = 'Error! Could not get Entertainment Earth prices. ' + error
      })
      .then( () => {
        this.savePrice()
      })
    },
    savePrice () {
      if(this.entertainmentResponse.errors) {
        return;
      }
      if(this.entertainmentResponse) {
        if(this.entertainmentResponse['cj-api'][0].products[0].product) {
          this.entertainmentData.entertainmentPrice = this.entertainmentResponse['cj-api'][0].products[0].product[0].price[0]._text;
          this.entertainmentData.entertainmentLink = this.entertainmentResponse['cj-api'][0].products[0].product[0]['buy-url'][0]._text;
          this.entertainmentData.entertainmentStock = this.entertainmentResponse['cj-api'][0].products[0].product[0]['in-stock'][0]._text;
          this.updateEntertainment(this.entertainmentData.entertainmentPrice, this.entertainmentData.entertainmentStock, this.entertainmentData.entertainmentLink);
          this.$emit('pricing', true)
        }
      }
    },
    updateEntertainment (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'entertainment',
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
