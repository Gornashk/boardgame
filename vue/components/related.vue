<template>
  <ais-index
  app-id="K2PX6XUYZD"
  api-key="ab6ff564e0cedf34e0716ad88fa36701"
  index-name="isutoday_posts_announcements"
  :query="query"
  :query-parameters="{
    removeWordsIfNoResults: 'allOptional',
    optionalWords: query,
    filters: filter,
    hitsPerPage: '4',
    minProximity: '2'
  }"
  >
    <h2>Related Board Games</h2>
    <ais-results>
      <template scope="{ result }">
        <div class="col-sm-6 col-md-3 newsPost" v-if="result.images.thumbnail">\
          <a :href="result.permalink" :style="{ backgroundImage: 'url(' + result.images.thumbnail.url + ')' }">
            <div class="overlay"></div>
            <h4>{{result.post_title}}</h4>
            <div class="postBtn">Read More</div>
          </a>
        </div>
        <div class="col-sm-6 col-md-3 newsPost" v-else>\
          <a :href="result.permalink">
            <div class="overlay"></div>
            <h4>{{result.post_title}}</h4>
            <div class="postBtn">Read More</div>
          </a>
        </div>
      </template>
    </ais-results>
  </ais-index>
</template>

<script>
import InstantSearch from 'vue-instantsearch';

module.exports = {
  data () {
    return {
      query: '',
      title: title,
      content: content,
      tags: tags,
      filter: 'post_id != ' + postID,
      ranking: ['words', 'exact']
    }
  },
  mounted () {
    this.createQuery()
  },
  methods: {
    createQuery: function() {
      var queryLong = this.title +' '+ this.tags +' '+ this.content
      if (queryLong.length < 512) {
        this.query = queryLong
      } else {
        this.query = queryLong.substring(0, 512)
      }
    }
  }
}

</script>