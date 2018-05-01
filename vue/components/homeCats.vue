<template>
  <div class="container">
    <div class="row homeCats" v-for="catRow in catRows" :key="catRow.id">
      <div class="col-md-12">
        <div class="flex">
          <h4 v-html="catRow.name + ' Games'"></h4>
          <a :href="catRow.taxPage" class="catBtn">View All</a>
        </div>
        <slick class="gameBoxes" ref="slick" :options="slickOptions">
          <game-box v-for="post in catRow.games" :key="post.id" :post="post"></game-box>
        </slick>
      </div>
    </div>
  </div>
</template>

<script>
import Slick from 'vue-slick';
import gameBox from './gameBox.vue';

module.exports = {
  components: { Slick, gameBox },
  data() {
    return {
      slickOptions: {
        slidesToShow: 5,
        slidesToScroll: 5,
        prevArrow: '<button type="button" class="slidePrev"><i class="fa fa-chevron-left " aria-hidden="true"></i></button>',
        nextArrow: '<button type="button" class="slideNext"><i class="fa fa-chevron-right " aria-hidden="true"></i></button>',
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 4,
              slidesToScroll: 4
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3
            }
          },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2
            }
          }
        ]
      },
      catRows: homecats,
    }
  },
  mounted () {
    console.log(this.slickOptions)
  },
  watch: {
    catRows: function (newItems) {
      let currIndex = this.$refs.slick.currentSlide()

      this.$refs.slick.destroy()
      this.$nextTick(() => {
        this.$refs.slick.create()
        this.$refs.slick.goTo(currIndex, true)
      })
    }
  },
  methods: {
    next() {
      this.$refs.slick.next();
    },
    prev() {
      this.$refs.slick.prev();
    },
    reInit() {
      // Helpful if you have to deal with v-for to update dynamic lists
      this.$nextTick(() => {
        this.$refs.slick.reSlick();
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../scss/variables.scss";
@import "../../scss/mixins.scss";

.flex {
  display:flex;
  justify-content: space-between;
  padding: 0 1em;
  align-items: center;

  .catBtn {
    font-size: .825em;
    background: $orange;
    color: #fff;
    padding: .25em .75em;
    @include border-radius(5px);
    @include transition(all .3s ease-in-out);

    &:hover {
      background: $darkOrange;
    }
  }
}

</style>