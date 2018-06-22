// import Posts from "./components/posts";
import gamePricing from "./components/game-pricing";
// import gameInfo from "./components/game-info";
import frontSearch from "./components/search";
// import categories from "./components/categories";
// import newlyAdded from "./components/newlyAdded";
// import homeCats from "./components/homeCats";
import changePosts from "./components/changePosts";
import related from "./components/related";
import socialShare from "./components/socialShare";
import singleSearch from "./components/singleSearch";

var app = new Vue({
  el: "#app",
  components: {
    frontSearch,
    gamePricing,
    // categories,
    // newlyAdded,
    // homeCats,
    changePosts,
    related,
    socialShare,
    singleSearch
  },
  data() {
    return {
      mobileMenu: false,
      options: options,
      showDbPrice: true,
      subActive: ''
    };
  },
  methods: {
    hideDbPrice (payload) {
      if(payload) {
        this.showDbPrice = false
      }
    },
    subActiveToggle (menu) {
      if(this.subActive == menu) {
        this.subActive = '';
        return;
      }
      this.subActive = menu
    }
  }
});
