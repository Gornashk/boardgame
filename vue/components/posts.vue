<template>
  <div>
    <ul>
      <li v-for="post in posts">
        <a href="#">{{post.title}}</a>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

module.exports = {
  data() {
    return {
      // posts: posts
      posts: []
    }
  }
}
</script>