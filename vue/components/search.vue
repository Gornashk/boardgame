<template>
  <div id="heroSearchBar" :style="{ backgroundImage: 'url(' + options.search_background.sizes.large + ')' }">
    <single-search :options="options"></single-search>
  </div>
</template>

<script>
import singleSearch from './singleSearch.vue';

module.exports = {
  props: ["options"],
  components: { singleSearch }
}

</script>

<style lang="scss" scoped>
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";

#heroSearchBar {
  padding: 6.925em 0;
  background-size: cover;
  background-position: center;
  margin-bottom: 3em;
  
  h2 {
    text-align: center;
    font-weight:600;
    color: $blue;
  }
  input {
    display: block;
    width: 85%;
    margin: 0 auto;
    color: $gray;
    font-size: 1.5em;
    padding: .5em;
  }
}
</style>