<?php
/*Template Name: Test - Carousel*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
	<style>
		html,body{ font-size: 12px; font-family: Consolas, "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", "Monaco", "Courier New", "monospace"}
		/*
		all carousels
		*/
		div[carousel]{
			display: block;
			text-align: center;
			padding: 10px;
			margin: 10px 0;
			background: #cccccc;
		}
		/*
		all carousels -> all items
		*/
		div[carousel] [carousel_item_number]{
			display: block;
			margin: 0 auto;
			overflow: hidden;
		}
		/*
		all carousels -> carousel nav
		*/
		div[carousel] .carousel_nav{
			display: inline-block;
			margin: 0 auto;
		}
		/*
		all carousels -> carousel nav -> nav items
		*/
		div[carousel] .carousel_nav [carousel_nav_item]{
			cursor: pointer;
			display: inline-block;
			padding: 5px;
		}
		div[carousel] .carousel_nav [carousel_nav_item][selected]{
			color: red;
		}
		/*
		specific carousels
		*/
		.carousel1{
			width: calc(100% - 20px); height: 224px
		}
		.carousel1 [carousel_item_number]{
			width: 680px; height: 200px;
		}
		.carousel2, .carousel3, .carousel4{
			width: calc(100% - 20px); min-height: 300px
		}
		.carousel2 [carousel_item_number], .carousel3 [carousel_item_number], .carousel4 [carousel_item_number]{
			width: 400px; height: 300px;
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script>
	$(document).ready(function(){
		/*
		CAROUSEL
		*/
		var carousel_time_default = 4;
		$('[carousel]').each(function(index){
			//give each carousel a unique identifing attribute
			$(this).attr('carousel',index);
			//count items and add as attribute
			$(this).attr('carousel_count',$(this).children().length);
			//add current item display attribute
			$(this).attr('carousel_item_display',1);
			//give each item a unique identifing attribute
			$(this).children().each(function(index){
				$(this).attr('carousel_item_number',index + 1);
			});
			//use carousel default carousel time unless stated (or stated is less than default) and add as attribute
			if( typeof $(this).attr('carousel_time') === "undefined" || $(this).attr('carousel_time') < carousel_time_default ){
				$(this).attr('carousel_time',carousel_time_default);
			}
			//if user has spcified the carousel nav then create it
			if( typeof $(this).attr('carousel_nav') !== "undefined" ){
				var this_nav = '<div class="carousel_nav">';
				$(this).append();
				for(i=1;i<=$(this).attr('carousel_count'); i++ ){
					this_nav += '<div carousel_nav_item="' + i + '" ' + ((i == 1) ? 'selected="selected"' : '' ) + '>' + i + '</div>';
				}
				this_nav += '</div>';
				$(this).append(this_nav);
			}
		});
		//rotate
		var carousel_rotate_interval = [];
		function carousel_rotate(){
			$('[carousel]').each(function(index){
				clearInterval(carousel_rotate_interval[index]);
				carousel_rotate_interval[index] = '';
				carousel_rotate_interval[index] = setInterval( function(){ carousel_interval(index); }, 1000 * $(this).attr('carousel_time') );
			});
		}
		function carousel_interval(i){
			var this_carousel = $('[carousel="' + i + '"]');
			if( $(this_carousel).attr('carousel_count') > 1 ){
				$(this_carousel).find('[carousel_item_number="' + $(this_carousel).attr('carousel_item_display') + '"]').fadeOut(300);
				$('[carousel="' + i + '"] .carousel_nav [carousel_nav_item="' + $(this_carousel).attr('carousel_item_display') + '"]').removeAttr('selected');

				if( $(this_carousel).attr('carousel_item_display') < $(this_carousel).attr('carousel_count') ){
					$(this_carousel).attr('carousel_item_display', parseInt($(this_carousel).attr('carousel_item_display')) + 1 );
				}else{
					$(this_carousel).attr('carousel_item_display', 1 );
				}

				$(this_carousel).find('[carousel_item_number="' + $(this_carousel).attr('carousel_item_display') + '"]').delay(300).fadeIn();
				$('[carousel="' + i + '"] .carousel_nav [carousel_nav_item="' + $(this_carousel).attr('carousel_item_display') + '"]').attr('selected', 'selected');
			}
		}
		carousel_rotate();
		//carousel nav functions
		$('[carousel]').each(function(index){
			$(this).find('.carousel_nav').children().on('click',function(event){
				event.preventDefault();
				var this_carousel = $(this).parent().parent();
				var this_carousel_number = $(this).parent().parent().attr('carousel');
				var this_carousel_count = $(this).parent().parent().attr('carousel_count');
				var this_carousel_item_current = $(this_carousel).attr('carousel_item_display');
				var this_carousel_item = $(this).attr('carousel_nav_item');


				//alert( 'number: ' + this_carousel_number + ' | count: ' + this_carousel_count + ' | selected item: ' + this_carousel_item + ' | current item: ' + this_carousel_item_current );
				//stop interval
				clearInterval(carousel_rotate_interval[ this_carousel_number ]);
				//restart interval
				//setTimeout( function(){ carousel_rotate_interval[ this_carousel_number ] = setInterval( function(){ carousel_interval(this_carousel_number); }, 1000 *  $(this_carousel).attr('carousel_time') ) }, 600);
				carousel_rotate_interval[ this_carousel_number ] = setInterval( function(){ carousel_interval(this_carousel_number); }, 1000 *  $(this_carousel).attr('carousel_time') );
				//update nav
				$(this_carousel).find('.carousel_nav').children().removeAttr('selected');
				$(this_carousel).find('.carousel_nav').children('[carousel_nav_item="' + this_carousel_item + '"]').attr('selected', 'selected');
				//update carousel
				$(this_carousel).attr('carousel_item_display', this_carousel_item );
				$(this_carousel).find('[carousel_item_number]').fadeOut(300);
				$(this_carousel).find('[carousel_item_number="' + this_carousel_item + '"]').delay(300).fadeIn();
			});
		});
	});
	</script>
</head>
<body>
	<h1>Test Carousel</h1>
	<p>A simple jQuery carousel, that counts items and auto scrolls in a loop.</p>
	<p>The items may be a hyperlink or a div as in example 1.</p>
	<p>Carousel navigation is optional, set by the attribute: "carousel_nav".</p>
	<p>The default time is set within jQuery, but longer times maybe set by the attribute: "carousel_time=<i>int</i>".</p>
	<p>CSS:</p>
	<ul>
		<li>Every carousel item apart from the first must include ' style="display: none;" ' to prevent them from displaying initially.</li>
		<li>Carousel and carousel items must have css defined width and heights</li>
		<li>Style nav with .carousel_nav{}</li>
		<li>Style current nav item with [carousel_nav_item][selected]{}</li>
	</ul>
	
	<h2>Example 1: Banner</h2>
	<ul>
		<li>carousel_time: default (4 seconds)</li>
		<li>carousel_nav: true</li>
	</ul>
	<div class="carousel1" carousel carousel_nav>
		<a href="" target="_blank">
			<img src="https://via.placeholder.com/680x200/DDDDDD/FFFFFF.png?text=1%3A a href %2B png" alt="1">
		</a>
		<div style="display: none;">
			<img src="https://via.placeholder.com/680x200/555555/CCCCCC.jpg?text=2%3A div %2B jpg" alt="2">
		</div>
		<div style="display: none;">
			<img src="https://via.placeholder.com/680x200/000000/777777.gif?text=3%3A div %2B gif" alt="3">
		</div>
		<div style="display: none;">
			<p>HTML TEXT: parent dimensions must match other images</p>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis suscipit auctor tellus in gravida. Nulla suscipit semper neque in ultrices. Nullam nec hendrerit odio. Nam viverra efficitur rutrum. Duis ut odio eu massa semper mollis eget ut enim. Sed et est est. Maecenas eget venenatis ex. Mauris rutrum, ipsum eget tempor ornare, risus erat egestas lacus, nec viverra risus sem in justo. Maecenas facilisis nisi ut gravida sagittis. Aenean mauris quam, suscipit vitae malesuada in, auctor iaculis sem. Pellentesque feugiat egestas risus, eu malesuada urna pharetra quis. Suspendisse potenti. Curabitur porta facilisis leo quis fermentum. Proin id sapien cursus, vestibulum metus vitae, placerat ligula. Donec sollicitudin tellus sit amet dictum consectetur.</p>
		</div>
	</div>
	<h2>Example 2: Ad Rotator</h2>
	<ul>
		<li>carousel_time: 7 seconds</li>
		<li>carousel_nav: false</li>
	</ul>
	<div class="carousel2" carousel carousel_time="7">
		<a href="" target="_blank">
			<img src="https://via.placeholder.com/400x300/0000FF/FFFFFF.png?text=one" alt="1">
		</a>
		<a href="" target="_blank" style="display: none;">
			<img src="https://via.placeholder.com/400x300/00FF00/FFFFFF.png?text=two" alt="2">
		</a>
		<a href="" target="_blank" style="display: none;">
			<img src="https://via.placeholder.com/400x300/FF0000/FFFFFF.png?text=three" alt="3">
		</a>
	</div>
	<?php
	class carousel{
		public $items; //array
		public $timer; //optional time in seconds
		public $nav; //display nav if true
		public $attr; //additional attributes eg class and styles
		public $item_error = false;
		function __construct($items, $timer = null, $nav = null, $attr){
        	$this->items = $items;
            $this->timer = $timer;
            $this->nav = $nav;
            $this->attr = $attr;
			if(!empty($this->items) && is_array($this->items)){
				foreach($this->items as $k => $item){
					if(
						!array_key_exists('content',$item) || 
						empty($item['content'])
					){
						$this->item_error[] = 'There is an error in your carousel content item array at: ' . $k;
					}
				}
			}else{
				$this->item_error[] = 'An array must be included: item array, time in seconds (optional), true (nav optional), additional attributes';
			}
		}
		function build(){
			if($this->item_error !== false){
				//throw new Exception('carousel Class error in item content');
				foreach($this->item_error as $k => $v){
					echo '<p>' . $v . '</p>';
				}
			}else{
				//return $this->items;
				echo '<div' . (!empty($this->attr) ? ' ' . $this->attr : '') .  ' carousel' . (!empty($this->timer) ? ' carousel_time="' . $this->timer . '"' : '') . (!empty($this->nav) ? ' carousel_nav' : '') . '>';
				for($i=0;$i<count($this->items);$i++){
					$this_element = (isset($this->items[$i]['href']) && !empty($this->items[$i]['href']) ? 'a' : 'div');
					$this_href = (isset($this->items[$i]['href']) && !empty($this->items[$i]['href']) ? ' href="' . $this->items[$i]['href'] . '"' : '');
					echo '<' . $this_element . $this_href . ($i != 0 ? ' style="display: none;"' : '') . '>';
						echo $this->items[$i]['content'];
					echo '</' . $this_element . '>';
				}
				echo '</div>';
			}
		}
	}
	?>
	<?php
	$item_array = array(
		0 => array(
			'href' => 'https://www.example.com',
			'content' => '<img src="https://via.placeholder.com/400x300/000000/FFFFFF.png?text=PHP" alt="0">',
		),
		1 => array(
			'href' => '',
			'content' => '<img src="https://via.placeholder.com/400x300/0000FF/FFFFFF.png?text=PHP" alt="1">',
		),
		2 => array(
			'content' => '<img src="https://via.placeholder.com/400x300/FF0000/FFFFFF.png?text=PHP" alt="2">',
		),
		3 => array(
			'href' => 'https://www.example.com',
			'content' => '<img src="https://via.placeholder.com/400x300/00FF00/FFFFFF.png?text=PHP" alt="3">',
		),
	);
	//	
	?>
	<h2>Example 3: Bonus PHP Class</h2>
	<?php 
	$carousel3 = new carousel($item_array, 7, true, 'class="carousel3"');
	$carousel4 = new carousel($item_array, null, null, 'class="carousel4"');
	?>
	<?php
	$carousel3->build();
	$carousel4->build();
	?>
	<p>The generated images are from <a href="https://placeholder.com/" target="_blank">placeholder.com</a>.</p>
	
<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>

<p>The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She packed her seven versalia, put her initial into the belt and made herself on the way. When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy.</p>

<p>The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country. But nothing the copy said could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their projects again and again. And if she hasn’t been rewritten, then they are still using her. Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>

<p>Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar. The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen.</p>

<p>She packed her seven versalia, put her initial into the belt and made herself on the way. When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country.</p>

<p>But nothing the copy said could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their projects again and again. And if she hasn’t been rewritten, then they are still using her. Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar. The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She packed her seven versalia, put her initial into the belt and made herself on the way. When she reached the first hills of the Italic Mountains, she had a last view</p>
</body>
</html>