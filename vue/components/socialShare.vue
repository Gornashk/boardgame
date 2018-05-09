<template>
  <social-sharing :url="url"
  :title="title"
  :media="media"
  inline-template>
    <div class="wrap">
      <div class="social-icons">
        <network network="pinterest" id="pinterest">
          <i class="fab fa-pinterest"></i>
        </network>
        <network network="facebook" id="facebook">
          <i class="fab fa-facebook-square"></i>
        </network>
        <network network="twitter" id="twitter">
          <i class="fab fa-twitter-square"></i>
        </network>
        <network network="reddit" id="reddit">
          <i class="fab fa-reddit"></i>
        </network>
      </div>
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

h6 {
  font-size: .925em;
}

.social-icons {
  display: flex;
  justify-content: space-between;
  span {
    border: 2px solid $grayLight;
    text-align: center;
    padding: 5px 7%;
    flex: 0 0 23%;
    cursor: pointer;
    @include border-radius(10px);
    @include transition(all 0.15s ease-in-out);


    &:hover {
      &#reddit {
        background: #ff4500;
      }
      &#twitter {
        background: #1DA1F2;
      }
      &#facebook {
        background: #3B5998;
      }
      &#pinterest {
        background: #BD081C;
      }

      svg.svg-inline--fa {
        color: $white!important;
      }
    }

    svg.svg-inline--fa {
      width: 100%;
      height: 100%;
      max-width: 24px;
      max-height: 24px;
      vertical-align: middle;
      @include transition(all 0.15s ease-in-out);
    }

    &#reddit {
      svg.svg-inline--fa {
        color: #ff4500;
      }
    }
    &#twitter {
      svg.svg-inline--fa {
        color: #1DA1F2;
      }
    }
    &#facebook {
      svg.svg-inline--fa {
        color: #3B5998;
      }
    }
    &#pinterest {
      svg.svg-inline--fa {
        color: #BD081C;
      }
    }

    @include breakpoint(tablet) {
      padding: 5px 7%;
    }
  }
}
</style>
