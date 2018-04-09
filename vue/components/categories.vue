<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="gameBoxes">
            <game-box v-for="post in posts" :key="post.id" :post="post"></game-box>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import gameBox from './gameBox.vue';

module.exports = {
  components: { gameBox },
  data() {
    return {
      posts: posts
      // posts: []
    }
  }
}
</script>
