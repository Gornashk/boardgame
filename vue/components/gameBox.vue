<template>
  <div class="gameBox">
    <a :href="post.link">
      <div class="gameThumb">
        <img v-if="post.thumb"
        :src="post.thumb" :alt="post.title">
      </div>
      <h5 v-html="post.title"></h5>
      <p v-if="lowestPrice" class="lastPrice">
        Last Seen Lowest Price
        <span v-text="'$' + lowestPrice"></span>
      </p>
    </a>
  </div>
</template>

<script>
module.exports = {
  props: ['post'],
  data() {
    return {
      // posts: added
      // posts: []
    }
  },
  computed: {
    lowestPrice() {
      if( this.post.acf.prices ) {
        var prices = [];
        for ( var price of this.post.acf.prices ) {
          prices.push( Number.parseFloat(price.price) );
        }
        return Math.min(...prices)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";

.gameBox {
  .lastPrice {
    border-top: 2px solid $lightBlue;
    font-size: .625em;
    padding: .5em .5em 0;
    text-align: center;

    span {
      font-size: 1.75em;
      display: block;
      font-weight: 700;
    }
  }
}


</style>