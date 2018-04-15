<template>
  <div id="heroSearchBar" :style="{ backgroundImage: 'url(' + options.search_background.sizes.large + ')' }">
    <div class="creatingOver" v-show="creatingBGG === true">
      <div>
        <img :src="options.dir + '/img/searchLoader.gif'">
        <p>Getting the game page ready for it's first visit!</p>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Find the lowest prices for your favorite games.</h2>
          <input type="text" v-model="query" placeholder="Search by game name..." />
          <div class="resultContainer" v-show="query">
            <ais-index
            :searchStore="searchStore"
            :index-name="algoliaPrefix + 'posts_post'"
            :query="query"
            v-show="query.length > 1">
              <ais-results inline-template>
                <ul class="searchResults">
                  <li v-for="result in results" :key="result.id">
                    <a :href="result.permalink">
                      <img v-if="result.images.thumbnail" :src="result.images.thumbnail.url">
                      <span v-html="result.post_title"></span>
                    </a>
                  </li>
                </ul>
              </ais-results>
              <ais-no-results>
                <template slot-scope="props">
                 
                </template>
              </ais-no-results>
            </ais-index>
            <div class="bggResults" :class="{ searching: searchingBGG, creating: creatingBGG }">
              <div class="loadingOver" v-show="searchingBGG === true">
                <img :src="options.dir + '/img/searchLoader.gif'">
                <p>Finding ALL the games!</p>
              </div>
              <ul>
                <li 
                v-for="post in filteredBGG"
                :key="post.id"
                v-html="post.name[0]._attr.value._value"
                @click="createPost(post.name[0]._attr.value._value, post._attr.id._value)">
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";

#heroSearchBar {
  padding: 6.925em 0;
  background-size: cover;
  background-position: center;
  margin-bottom: 3em;
  
  h2 {
    text-align: center;
    font-weight:600;
    color: $blue;
  }
  input {
    display: block;
    width: 85%;
    margin: 0 auto;
    color: $gray;
    font-size: 1.5em;
    padding: .5em;
  }
}
</style>

<script>
import axios from 'axios';
import axiosCancel from 'axios-cancel';
import xmltojson from 'xmltojson';
import InstantSearch from 'vue-instantsearch';
import _debounce from 'lodash/debounce';
import _uniq from 'lodash/uniq';
import _compact from 'lodash/compact';
import _flatten from 'lodash/flatten';
Vue.use(InstantSearch);
import { createFromAlgoliaCredentials } from 'vue-instantsearch';

module.exports = {
  props: ["options"],
  data () {
    return {
      posts: [],
      filteredBGG: [],
      createThing: [],
      query: '',
      searchStore: createFromAlgoliaCredentials('LXQBY5Z3HD', 'c9b85b2d6f1cc197402589cd615b3cd5'),
      resultCount: '',
      upcResponse: [],
      amazonSearch: [],
      searchingBGG: false,
      creatingBGG: false,
      algoliaPrefix: algoliaPrefix
    }
  },
  mounted () {
    //this.getPosts()
    //this.ajaxTest()
    //console.log(adminAjax)
  },
  watch: {
    query: _debounce(function() {
      const requestId = 'my_sample_request';
      this.bggQuery(requestId)
    }, 500),
    creatingBGG (oldVal, newVal) {
      if(this.creatingBGG == true) {
        document.body.style.position = 'fixed';
        document.body.style.overflowY = 'scroll';
        document.body.style.width = '100%';
      }
    }
    
  },
  computed: {
    
  },
  methods: {
    bggQuery (requestId) {

      this.searchingBGG = true;
      
      // var CancelToken = axios.CancelToken;
      // var source = CancelToken.source();
      // console.log(source)
      var bggUrl = 'https://www.boardgamegeek.com/xmlapi2/search?type=boardgame&query=' + this.query

      axiosCancel(axios, {
        debug: false
      });

      axios.cancel(requestId)

      const promise = axios.get(bggUrl, {
        requestId: requestId,
        responseType: 'text'
      })
        .then((res) => {
          // console.log('resolved promise');
          var bggresponse = xmltojson.parseString(res.data)
          this.posts = bggresponse.items[0].item
          this.searchingBGG = false;
        }).catch((thrown) => {
          if (axios.isCancel(thrown)) {
            // console.log('request cancelled');
            // this.searchingBGG = false;
          } else {
            // this.searchingBGG = false;
            // console.log('some other reason');
          }
        }).then( (res) => {
          this.filterBGG(this.posts);
        })
    },

    filterBGG (bggPosts) {
      var that = this;
      var bggMatch = []
      // Filter to compare algolia and Board Game Geeks results
      if(bggPosts) {
        bggPosts.filter(function (el) {
          // loop through Algolia results
          for (var i = 0; i < that.searchStore._results.length; i++) {
            // push to match array if algolia result matches BGG result
            if( el._attr.id._value == that.searchStore._results[i].bgg_id ) {
              bggMatch.push(el)
            }
          }
        })

        // filter out matches from BGG results
        var bggFiltered = bggPosts.filter(function(e){
          return this.indexOf(e) < 0;
        }, bggMatch)

        this.filteredBGG = bggFiltered
      }
    },
    sortBGG () {

    },
    createPost (name, id) {
      this.creatingBGG = true;
      axios({
        method: 'get',
        url: 'https://www.boardgamegeek.com/xmlapi2/thing?id=' + id + '&ratingcomments=1&stats=1',
        responseType: 'text'
      })
      .then((response) => {
        // console.log(response)
        var that = this;
        var thingResponse = xmltojson.parseString(response.data)
        var thing = thingResponse.items[0].item

        this.createThing = thing[0];

        var description = '';
        var image = '';
        var maxPlayers = '';
        var minPlayers = '';
        var maxPlayTime = '';
        var minPlayTime = '';
        var minAge = '';
        var yearPublished = '';
        var rating = '';
        var categories = [];

        if(this.createThing.description) {
          description = this.createThing.description[0]._text;
        }
        if(this.createThing.image) {
          image = this.createThing.image[0]._text;
        }
        if(this.createThing.maxplayers) {
          maxPlayers = this.createThing.maxplayers[0]._attr.value._value;
        }
        if(this.createThing.minplayers) {
          minPlayers = this.createThing.minplayers[0]._attr.value._value;
        }
        if(this.createThing.maxplaytime) {
          maxPlayTime = this.createThing.maxplaytime[0]._attr.value._value;
        }
        if(this.createThing.minplaytime) {
          minPlayTime = this.createThing.minplaytime[0]._attr.value._value;
        }
        if(this.createThing.minage) {
          minAge = this.createThing.minage[0]._attr.value._value;
        }
        if(this.createThing.yearpublished) {
          yearPublished = this.createThing.yearpublished[0]._attr.value._value;
        }
        if(this.createThing.statistics) {
          rating = this.createThing.statistics[0].ratings[0].average[0]._attr.value._value;
        }

        if( this.createThing.link.length > 0 ) {
          for (var i = 0; i < this.createThing.link.length; i++) { 
            var catToPush = {};
            catToPush.id = this.createThing.link[i]._attr.id._value;
            catToPush.type = this.createThing.link[i]._attr.type._value;
            catToPush.value = this.createThing.link[i]._attr.value._value;
            
            categories.push(catToPush)
          }
        }

        
        jQuery.ajax({
          type: "post",
          url: adminAjax,
          //contentType: 'application/json; charset=utf-8',
          dataType: "json",
          data: { 
            action: "ks_postNewGame",
            nonce: nonce,
            title: name,
            bggID: id,
            description: description,
            image: image,
            categories: categories,
            maxPlayers: maxPlayers,
            minPlayers: minPlayers,
            maxPlayTime: maxPlayTime,
            minPlayTime: minPlayTime,
            minAge: minAge,
            yearPublished: yearPublished,
            rating: rating
           },
          success: function(data){
            //alert('success');
            //console.log('success')
              // console.log(data);
              var postID = data.toString().substr(0, data.toString().length - 1);
              that.getGameIDs(name, postID)
          },
          error: function(data) {
            console.log('error')
            console.log(data);
          }
        })


      })
      
    },
    getGameIDs (name, postID) {
      var that = this;
      var title = name;
      var titleEncode = encodeURIComponent(name);

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
          this.saveGameIDs(postID)
        })

      })

      
    },
    saveGameIDs (postID) {
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
      var upcItems = this.upcResponse.items
      var upc = [];
      var asin = [];
      var ean = [];
      var elid = [];
      var mpn = [];
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

      jQuery.ajax({
        type: "post",
        url: adminAjax,
        //contentType: 'application/json; charset=utf-8',
        dataType: "json",
        data: { 
          action: "ks_saveGameIds",
          nonce: nonce,
          postID: postID,
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
          console.log(data);
          // Send user to newly created game post
          window.location.replace( siteUrl + '/?p=' + postID );
        },
        error: function(data) {
          console.log('error')
          console.log(data);
        }
      })
    },
  }
}

</script>

