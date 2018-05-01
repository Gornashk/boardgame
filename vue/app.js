// import Posts from "./components/posts";
import gamePricing from "./components/game-pricing";
// import gameInfo from "./components/game-info";
import frontSearch from "./components/search";
import categories from "./components/categories";
import newlyAdded from "./components/newlyAdded";
import homeCats from "./components/homeCats";
import changePosts from "./components/changePosts";

var app = new Vue({
  el: "#app",
  components: {
    frontSearch,
    gamePricing,
    categories,
    newlyAdded,
    homeCats,
    changePosts
  },
  data() {
    return {
      mobileMenu: "",
      options: options
    };
  }
});
