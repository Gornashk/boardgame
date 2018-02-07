/*
 * Load Vue & Vue-Resource.
 *
 * Vue is the JavaScript framework used by Base.
 */
if (window.Vue === undefined) {
  window.Vue = require("vue");

  window.Bus = new Vue();
}

// Require SASS
// require("../scss/bootstrap/_bootstrap.scss");
require("../scss/main.scss");

// Require Vue
require("../vue/app.js");

// Require JavaScript
require("./script");
