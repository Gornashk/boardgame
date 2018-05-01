<template>
  <div class="container">
    <div class="row recentSearch">
      <div class="col-md-12">
        <h4>Recently Searched Board Games</h4>
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
      posts: added
      // posts: []
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";



</style>