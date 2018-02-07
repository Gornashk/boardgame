import Posts from "./components/posts";
import gamePricing from "./components/game-pricing";
import frontSearch from "./components/search";

var app = new Vue({
  el: "#app",
  components: {
    Posts,
    frontSearch,
    gamePricing
  },
  data() {
    return {
      mobileMenu: "",
      options: options
    };
  }
});
