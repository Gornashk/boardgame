<template>
  <ais-index
  app-id="LXQBY5Z3HD"
  api-key="c9b85b2d6f1cc197402589cd615b3cd5"
  :index-name="algoliaPrefix + 'posts_post'"
  :query="query"
  :query-parameters="{
    removeWordsIfNoResults: 'allOptional',
    optionalWords: query,
    filters: filter,
    hitsPerPage: '5',
    minProximity: '2'
  }"
  >
    <h2>Related Board Games</h2>
    <related-results></related-results>
  </ais-index>
</template>

<script>
import InstantSearch from 'vue-instantsearch';
Vue.use(InstantSearch);
import relatedResults from './relatedResults.vue';
// import { createFromAlgoliaCredentials } from 'vue-instantsearch';

module.exports = {
  components: { relatedResults },
  props: [ 'postId' ],
  data () {
    return {
      // searchStore: createFromAlgoliaCredentials('LXQBY5Z3HD', 'c9b85b2d6f1cc197402589cd615b3cd5'),
      algoliaPrefix: algoliaPrefix,
      game: singleGame,
      query: '',
      filter: 'post_id != ' + this.postId,
      ranking: ['words', 'exact']
    }
  },
  mounted () {
    this.createQuery()
  },
  methods: {
    createQuery: function() {
      var queryLong = this.game.title +' '+ this.game.acf.description
      if (queryLong.length < 512) {
        this.query = queryLong
      } else {
        this.query = queryLong.substring(0, 512)
      }
    }
  }
}

</script>