<?php 
/*

	Template Name: symposia home
	
*/

?>

<?php get_header(); ?>

<div class="b_content clearfix" id="main">
	<div class="b_page">
		
		<!-- Page title -->
		<div class="content-title">
			<div class="title">
				<h1 class = "entry-title symposia-title"><?php echo get_the_title(); ?></h1>
			</div>
		</div>
		
		<!-- CONTENT BODY -->
		<div id="primary" class="fl">
			<div id="content" role="main">
				<div class="single ">
				
				  <!-- BEGIN POST-->	
					<article class="page type-page status-publish hentry post">
						<h3>What is symposia</h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
									</p>
										
					</article>
					<!-- END POST -->
					<p class ="delimiter blank"></p>
					
					<!-- BEGIN POST-->	
					<article class="page type-page status-publish hentry post">
						<h3>Symposia 2012 </h3>
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
									</p>
									<!--Learn more -->
										<div class ="fr"><?php echo do_shortcode( '[button size="medium" color="blue" style="none" new_window="false" link=""]Learn more[/button]' ); ?></div>
									<!--End Learn more -->
					</article>
					<!-- END POST -->
					
					<!--Past Symposia list-->
					<article class="post past-sym-container">       
						<h3>Past Symposia</h3>                                         
						<ul class ="past-sym-list">
							
								<li>
									<div class = "past-sym-year">
										2010
									</div>
									<span>
										image caption	
									</span>
								</li>

								<li>
									<div class = "past-sym-year">
										2010
									</div>
									<span>
										image caption	
									</span>
								</li>

								<li>
									<div class = "past-sym-year">
										2010
									</div>
									<span>
										image caption	
									</span>
								</li>

								<li>
									<div class = "past-sym-year">
										2010
									</div>
									<span>
										image caption	
									</span>
								</li>
					
						</ul>	
					</article>
					<!--End Past Symposia list-->

				</div>
			</div>
			<!-- END CONTENT BODY -->

		</div>
</div>

<?php get_footer(); ?>


