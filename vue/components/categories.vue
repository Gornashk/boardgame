<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="gameBoxes">
            <div class="gameBox" v-for="post in posts" :key="post.id">
              <a :href="post.link">
                <div class="gameThumb">
                  <img v-if="post.thumb"
                  :src="post.thumb" :alt="post.title">
                </div>
                <h5 v-html="post.title"></h5>
              </a>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

module.exports = {
  data() {
    return {
      posts: posts
      // posts: []
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";

.gameBoxes {
  display: flex;
  flex-wrap: wrap;

  .gameBox {
    flex:0 1 20%;
    padding: 0 1em 1.5em;
  }
}

</style>