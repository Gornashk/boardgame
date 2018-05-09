<template>
  <!-- <div class="social-icons">
    <a data-pin-do="buttonBookmark" href="https://www.pinterest.com/pin/create/button/"><i class="fa fa-pinterest"></i></a>
    <a href=""><i class="fa fa-facebook-f"></i></a>
    <a href=""><i class="fa fa-twitter"></i></a>
    <a href=""><i class="fa fa-envelope"></i></a>
  </div> -->
  
  <social-sharing :url="url"
  :title="title"
  :media="media"
  inline-template>
    <div class="social-icons">
      <network network="pinterest">
        <i class="fab fa-pinterest"></i>
      </network>
      <network network="facebook">
        <i class="fab fa-facebook-square"></i>
      </network>
      <network network="twitter">
        <i class="fab fa-twitter-square"></i>
      </network>
      <network network="email">
        <i class="fas fa-envelope"></i>
      </network>
    </div>
  </social-sharing>
</template>

<script>
import SocialSharing from 'vue-social-sharing';
Vue.use(SocialSharing);

module.exports = {
  props: ["url", "title", "media"],
  data() {
    return {
      
    };
  }
};
</script>

<style lang="scss">
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";

.social-icons {
  margin-bottom: 1.875em;
  display: flex;
  justify-content: space-between;
  span {
    border: 2px solid $grayLight;
    text-align: center;
    padding: 15px;
    flex: 0 0 23%;
    cursor: pointer;
    @include border-radius(10px);
    @include transition(all 0.15s ease-in-out);


    &:hover {
      background:$blue;

      i {
        color:$white;
      }
    }

    i {
      color: #6d6e71;
      font-size: 1.55em;
      @include transition(all 0.15s ease-in-out);
    }

    @include breakpoint(tablet) {
      padding: 30px;

      i {
        font-size: 2.25em;
      }
    }
  }
}
</style>
