<template>
  <section class="changelog">
    <div v-for="post in posts" :key="post.id">
      <h2>
        <a :href="post.link" v-html="post.title.rendered"></a>
      </h2>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

module.exports = {
  data() {
    return {
      // posts: posts
      posts: []
    }
  },
  mounted () {
    this.getChangeLog();
  },
  methods: {
    getChangeLog () {
      let that = this
      axios.get('/wp-json/wp/v2/changelog')
      .then(function(response) {
        that.posts = response.data
      })
    }
  }
}
</script>