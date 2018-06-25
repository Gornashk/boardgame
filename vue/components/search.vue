<template>
  <div id="heroSearchBar" :style="{ backgroundImage: 'url(' + options.search_background.sizes.large + ')' }">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2>Find the lowest prices for your favorite games.</h2>
          <single-search :options="options"></single-search>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import singleSearch from './singleSearch.vue';

module.exports = {
  props: ["options"],
  components: { singleSearch }
}

</script>