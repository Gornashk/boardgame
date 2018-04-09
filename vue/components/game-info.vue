<template>
	<div class="container gameInfoContainer">
		<div class="row">
			<div class="col-md-4 gameImage">
				<img :src="game.image" :alt="game.title">
			</div>
			<div class="col-md-8 gameInfoTop">
				<div class="gameTitle"><h1 v-html="game.title"></h1></div>
				<hr/>
				<game-pricing></game-pricing>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-3">
						<ul class="gameStats">
							<li class="rating">
								<h6>Rating</h6>
								<p><span></span>{{ game.acf.rating }}</p>
							</li>
							<li class="players">
								<h6># Players</h6>
								<p><span><i class="fas fa-users"></i></span>{{game.acf.min_players}} - {{game.acf.max_players}}</p>
							</li>
							<li class="playtime">
								<h6>Playtime</h6>
								<p><span><i class="far fa-clock"></i></span>{{ game.acf.min_playtime }} Minutes</p>
							</li>
							<li class="age">
								<h6>Age</h6>
								<p><span><i class="far fa-calendar-alt"></i></span>{{ game.acf.min_age }}</p>
							</li>
							<li class="published">
								<h6>Year Published</h6>
								<p><span></span>{{ game.acf.year_published }}</p>
							</li>
						</ul>
					</div>
					<div class="col-md-9">
						<h3>Game Description</h3>
						<div class="gameDescription" v-html="game.acf.description"></div>
					</div>
				</div>
				
			</div>
			<div class="col-md-4">
				<!-- Board Game Responsive -->
				<ins class="adsbygoogle"
						style="display:block"
						data-ad-client="ca-pub-0438075078271065"
						data-ad-slot="4591237891"
						data-ad-format="auto"></ins>
			</div>
		</div>
	</div>
</template>

<script>
// require('dotenv').config()
import gamePricing from './game-pricing.vue';
import axios from 'axios';
import xmltojson from 'xmltojson';
import NaturalLanguage from 'nlg';

module.exports = {
	components: { gamePricing },
  data () { 
    return {
      game: singleGame,
    }
  },
  mounted () {
		this.nlgDescription()
  },
  methods: {
		nlgDescription () {

		}
  }
}

</script>