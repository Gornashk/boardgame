<template>
  <div class="gameBoxes">
    <game-box v-for="result in searchStore._results" :key="result.key" fromRelated="true" :post="result" ></game-box>
  </div>
</template>

<script>
import { Component } from 'vue-instantsearch';
import gameBox from './gameBox.vue';

module.exports = {
  components: { gameBox },
  mixins: [Component],
  props: ["siteUrl"],
  computed: {
    query() {
      return this.searchStore.query;
    },
  }
}

</script>

<style lang="scss" scoped>
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";

.gameBoxes {
  margin: 0 -15px;
}
</style>