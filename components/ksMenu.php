<?php
function ks_menu() { ?>

<div id="mobile-nav-wrap" class="mobile-nav-wrap" :class="{ opened : mobileMenu }" v-show="mobileMenu">
	<nav id="mobile-navigation-top" class="navigation-mobile" role="navigation">
    <ul>
			<li><a @click="subActiveToggle('categories')">Categories</a>
				<ul class="sub-menu" :class="{ active : subActive == 'categories' }">
					<?php 
					$args = array(
					'taxonomy' => 'category',
					'title_li' => '',
					'depth' => 2,
					'hide_empty' => 1
							);
					wp_list_categories($args); ?>
				</ul>
			</li>
			<li><a @click="subActiveToggle('mechanics')">Mechanics</a>
				<ul class="sub-menu" :class="{ active : subActive == 'mechanics' }">
					<?php 
					$args = array(
					'taxonomy' => 'mechanic',
					'title_li' => '',
					'depth' => 2,
					'hide_empty' => 1
							);
					wp_list_categories($args); ?>
				</ul>
			</li>
			<li><a @click="subActiveToggle('groups')">Groups</a>
				<ul class="sub-menu" :class="{ active : subActive == 'groups' }">
					<?php 
					$args = array(
					'taxonomy' => 'family',
					'title_li' => '',
					'depth' => 2,
					'hide_empty' => 1
							);
					wp_list_categories($args); ?>
				</ul>
			</li>
			<li><a @click="subActiveToggle('publishers')">Publishers</a>
				<ul class="sub-menu" :class="{ active : subActive == 'publishers' }">
					<?php 
					$args = array(
					'taxonomy' => 'publisher',
					'title_li' => '',
					'depth' => 2,
					'hide_empty' => 1
							);
					wp_list_categories($args); ?>
				</ul>
			</li>
			<li><a @click="subActiveToggle('artists')">Artists</a>
				<ul class="sub-menu" :class="{ active : subActive == 'artists' }">
					<?php 
					$args = array(
					'taxonomy' => 'artists',
					'title_li' => '',
					'depth' => 2,
					'hide_empty' => 1
							);
					wp_list_categories($args); ?>
				</ul>
			</li>
			<li><a @click="subActiveToggle('designers')">Designers</a>
				<ul class="sub-menu" :class="{ active : subActive == 'designers' }">
					<?php 
					$args = array(
					'taxonomy' => 'designers',
					'title_li' => '',
					'depth' => 2,
					'hide_empty' => 1
							);
					wp_list_categories($args); ?>
				</ul>
			</li>
			<li><a @click="subActiveToggle('awards')">Awards</a>
				<ul class="sub-menu" :class="{ active : subActive == 'awards' }">
					<?php 
					$args = array(
					'taxonomy' => 'awards',
					'title_li' => '',
					'depth' => 2,
					'hide_empty' => 1
							);
					wp_list_categories($args); ?>
				</ul>
			</li>
		</ul>
	</nav><!-- #mobile-navigation -->
</div> <!-- #mobile-nav-wrap -->

<?php
} ?>