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
    <h3>Related Board Games</h3>
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
      let content = this.game.acf.description
      let catsGroup = this.game.cats;
      let groupsGroup = this.game.groups;
      let mechanicsGroup = this.game.mechanics;
      if( content.includes('<p>') ) {
        content = content.replace(/<p>/gm, '');
      }
      if( content.includes('</p>') ) {
        content = content.replace(/<\/p>/gm, '');
      }
      if( content.includes('Game description from the publisher:') ) {
        content = content.replace('Game description from the publisher:', '');
      }
      if( content.includes('From the publisher:') ) {
        content = content.replace('From the publisher:', '');
      }
      if( content.includes('Description from the publisher:') ) {
        content = content.replace('Description from the publisher:', '');
      }
      var catsArr = [];
      var groupsArr = [];
      var mechanicsArr = [];
      for(var cat of catsGroup) {
        catsArr.push(cat.name);
      }
      for(var group of groupsGroup) {
        groupsArr.push(group.name);
      }
      for(var mec of mechanicsGroup) {
        mechanicsArr.push(mec.name);
      }
      var cats = catsArr.join(', ');
      var groups = groupsArr.join(', ');
      var mechanics = mechanicsArr.join(', ');

      if( cats.includes('Uncategorized') ) {
        cats = cats.replace('Uncategorized', '');
      }

      console.log(cats)

      var queryLong = this.game.post_title +' '+ groups +' '+ cats +' '+ mechanics +' '+ content

      // this.query = queryLong.substr(0, 511)
      if (queryLong.length < 512) {
        this.query = queryLong
      } else {
        this.query = queryLong.substr(0, 512)
      }
    }
  }
}

</script>