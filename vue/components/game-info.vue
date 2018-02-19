<template>
  <div>
    <div class="col-md-4">
			<img :src="game.thumb">
		</div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-3">
					<ul class="gameStats">
						<li>
							<span>Rating</span>
							<p v-text="game.acf.rating"></p>
						</li>
						<li>
							<span># Players</span>
							<p>{{game.acf.min_players}} - {{game.acf.max_players}}</p>
						</li>
						<li>
							<span><i class="far fa-clock"></i>Playtime</span>
							<p v-text="game.acf.min_playtime"></p>
						</li>
						<li>
							<span>Age</span>
							<p v-text="game.acf.min_age"></p>
						</li>
						<li>
							<span>Year Published</span>
							<p v-text="game.acf.year_published"></p>
						</li>
					</ul>
				</div>
				<div class="col-md-9">
					<div id="content">
						<div class="gameTitle"><h1 v-text="game.title"></h1></div>
						<hr/>
						<h3>Game Description</h3>
						<span v-html="game.acf.description"></span>
						</div><!-- End Blog Content -->
					</div>
				</div>
			</div>
    </div>
  </div>
</template>

<script>
// require('dotenv').config()
import axios from 'axios';
import xmltojson from 'xmltojson';

module.exports = {
  data () { 
    return {
      game: singleGame,
    }
  },
  mounted () {

  },
  methods: {
  
  }
}

</script>