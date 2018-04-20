<template>
  <div>
    <div class="priceRow" v-if="barnesData.barnesPrice"
    itemprop="seller" itemscope itemtype="http://schema.org/Organization">
      <div class="rowName">
        <a :href="barnesData.barnesLink" @click="linkClick" itemprop="name">barnesandnoble.com</a>
      </div>
      <div class="rowPrice">
        <span v-html="'$' + barnesData.barnesPrice" itemprop="price"></span>
      </div>
      <div class="rowStock">
        <span v-html="barnesData.barnesStock"></span>
      </div>
      <div class="rowLink">
        <a :href="barnesData.barnesLink" itemprop="url" class="storeBtn" @click="linkClick">Visit Store</a>
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
      barnesResponse: [],
      barnesData: {
        barnesPrice: '',
        barnesLink: '',
        barnesDate: '',
        barnesStock: '',
        barnesError: ''
      }
    }
  },
  mounted () {
    this.checkCodes();
  },
  methods: {
    linkClick() {
      __gaTracker('send', 'event', {
        eventCategory: 'Barnes and Noble',
        eventAction: this.barnesData.barnesLink,
        eventLabel: this.game.title
      });
    },
    checkCodes() {
      if( this.game.acf.codes ) {

        if( this.game.acf.codes = true ) {
          // If I have ID codes, look for product prices
          this.barnesPrices()
          return;
        }
        // Don't get prices if ID codes don't exist
        return;
      }
    },
    barnesPrices () {
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
          action: "ks_getBarnesPrice",
          apiKey: '00a6dd1cf06e33479ee017af3d7de3e1e8f77407ab66777c2ca9d7e2c8b2a099e6c22a7e6d01a27277707247503f435ce0dfc18d442771cb347afd4e2306ffb967/292905c8710689363184e61f3efc856f580af11160973cb42fb92721e69e231818776f8b727a9c9a2b18e3ef66840874e395c7d47efce8f5fa7c435936b73b31',
          upc: upcCode,
          websiteId: '8512196',
          advertiserId: '4258829'
        }
      })
      .then((response) => {
        
        var str = response.data
        var barnesResponse = str.substring(0, str.length - 1);

        that.barnesResponse = JSON.parse(barnesResponse);
      }) 
      .catch(function (error) {
        that.barnesData.barnesError = 'Error! Could not get Barnes and Noble prices. ' + error
      })
      .then( () => {
        this.savePrice()
      })
    },
    savePrice () {
      if(this.barnesResponse.errors) {
        return;
      }
      if(this.barnesResponse) {
        if(this.barnesResponse.items.length > 0) {
          this.barnesData.barnesPrice = Number.parseFloat('response here').toFixed(2);
          this.barnesData.barnesLink = '';
          this.barnesData.barnesStock = '';
          this.updateBarnes(this.barnesData.barnesPrice, this.barnesData.barnesStock, this.barnesData.barnesLink);
          this.$emit('pricing', true)
        }
      }
    },
    updateBarnes (price, stock, link) {
      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_updateGamePrice",
          nonce: nonce,
          postID: this.game.id,
          retailer: 'barnes',
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
