<template>
  <div id="heroSearchBar" :style="{ backgroundImage: 'url(' + options.search_background.sizes.large + ')' }">>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Search CTA goes here</h2>
          <input type="text" v-model="query" placeholder="Search by game name..." />
          <div class="resultContainer">
            <ais-index
            :searchStore="searchStore"
            index-name="boardgame_local_posts_post"
            :query="query"
            v-show="query.length > 1">
              <ais-results inline-template>
                <ul class="searchResults">
                  <li v-for="result in results" :key="result.id">
                    <a :href="result.permalink" v-html="result.post_title"></a>
                  </li>
                </ul>
              </ais-results>
              <ais-no-results>
                
              </ais-no-results>
            </ais-index>
            <ul class="bggResults">
              <li 
              v-for="post in posts"
              :key="post.id"
              v-html="post.name[0]._attr.value._value"
              @click="createPost(post.name[0]._attr.value._value, post._attr.id._value)"></li>
            </ul>
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
  
  h2 {
    text-align: center;
  }
  input {
    display: block;
    width: 85%;
    margin: 0 auto;
    color: $gray;
    font-size: 1.5em;
    padding: .5em;
  }

  .resultContainer {
    position: absolute;
    background: $white;
    padding: .5em;
    border: 1px solid $gray;
    width: calc(85% - 30px);
    left: calc(7.5% + 15px);
  }
}
</style>

<script>
import axios from 'axios';
import xmltojson from 'xmltojson';
import InstantSearch from 'vue-instantsearch';
Vue.use(InstantSearch);
import { createFromAlgoliaCredentials } from 'vue-instantsearch';

module.exports = {
  props: ["options"],
  data () {
    return {
      posts: [],
      filterdBGG: [],
      createThing: [],
      query: '',
      searchStore: createFromAlgoliaCredentials('LXQBY5Z3HD', 'c9b85b2d6f1cc197402589cd615b3cd5'),
    }
  },
  mounted () {
    //this.getPosts()
    //this.ajaxTest()
    //console.log(adminAjax)
  },
  watch: {
    
    query: _.debounce(function () {
      axios({
        method: 'get',
        url: 'https://www.boardgamegeek.com/xmlapi2/search?type=boardgame&query=' + this.query,
        responseType: 'text'
      })
      .then((response) => {
        var bggresponse = xmltojson.parseString(response.data)
        this.posts = bggresponse.items[0].item
      })
      .then( () => {
        this.filterBGG(this.posts);
      })
      .catch(function (error) {
        this.posts = 'Error! Could not reach the API. ' + error
      })
    }, 500)
    
  },
  computed: {
    bggFiltered () {

    }
  },
  methods: {
    filterBGG (bggPosts) {

      // filterBGG = [];
      // filterBGG = bggPosts.filter(bggPost => bggPost._attr.id._value != )
      // for (var i = 0; i < bggPosts.length; i++) { 
      //   if(bggPosts._attr.id._value ===
    },
    sortBGG () {

    },
    createPost (name, id) {
      axios({
        method: 'get',
        url: 'https://www.boardgamegeek.com/xmlapi2/thing?id=' + id,
        responseType: 'text'
      })
      .then((response) => {
        // console.log(response)
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
            yearPublished: yearPublished
           },
          success: function(data){
            //alert('success');
            //console.log('success')
              console.log(data);
              var postID = data.toString().substr(0, data.toString().length - 1);
              window.location.replace( siteUrl + '/?p=' + postID );
          },
          error: function(data) {
            console.log('error')
            console.log(data);
          }
        })


      })
      
    }
  }
}

</script>

