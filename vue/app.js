import Posts from "./components/posts";
import gamePricing from "./components/game-pricing";
import gameInfo from "./components/game-info";
import frontSearch from "./components/search";
import categories from "./components/categories";

var app = new Vue({
  el: "#app",
  components: {
    Posts,
    frontSearch,
    gamePricing,
    gameInfo,
    categories
  },
  data() {
    return {
      mobileMenu: "",
      options: options
    };
  }
});
