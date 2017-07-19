<?php
// Get global header
get_header();
?>

<main class="photo-gallery">
	<section class="landing">
		<div>
			<h1>Photo Gallery</h1>
			<p>"The world is not a lack of beauty, but the lack of discovery of beautiful eyes."</p>
		</div>
	</section>
	<section class="gallery">
		<div class="container">
			<h4 class="btn-group">
				<span class="all-btn" data-filter="*">ALL</span>
				<span class="saved-btn" data-filter=".saved">SAVED</span>
			</h4>
			<div class="row grid">
				<?php
				// "Photograph" post type
		        $photograph_posts = new WP_Query( array(
		            "post_type"      => "photograph",
					"posts_per_page" => -1
		        ) );

				if ( $photograph_posts->have_posts() ) {
					while ( $photograph_posts->have_posts() ) {
						$photograph_posts->the_post();
						?>
						<article class="col-sm-12 col-md-6 col-lg-3 element-item">
							<div class="photo-preview" style="background-image: url('<?php the_post_thumbnail_url(); ?>')">
								<div class="banner">
									<span class="photo-title"><?php the_title(); ?></span>
									<span class="love-btn"><i class="fa fa-heart" aria-hidden="true"></i></span>
								</div>
							</div>
						</article>
				<?php
					}
				}
				?>
			</div>
			<button class="download-btn" type="button" name="download_btn">DOWNLOAD</button>
		</div>
	</section>
</main>

<?php
// Get global footer
get_footer();
?>
