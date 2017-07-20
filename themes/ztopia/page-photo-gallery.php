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
				<span class="all-btn active" data-filter="*">ALL</span>
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
	<footer class="site-footer photo-gallery-footer">
		<ul>
			<?php
			$user = get_user_by( 'email', 'zicodeng@gmail.com' );
			$userID = $user->ID;
			$userLinkedIn = get_user_meta( $userID, 'linkedin', true);
			if ( strlen( $userLinkedIn ) ) {
			?>
				<li><a href="<?php echo $userLinkedIn; ?>" target="_blank"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
			<?php
			}
			?>
			<?php
			$userGitHub = get_user_meta( $userID, 'github', true);
			if ( strlen( $userGitHub ) ) {
			?>
				<li><a href="<?php echo $userGitHub; ?>" target="_blank"><i class="fa fa-github-square" aria-hidden="true"></i></a></li>
			<?php
			}
			?>
		</ul>
		<p class="total-page-view">Total page view:
			<span>
			<?php
			function total_page_view_count( $post_id ) {
				// Verify we're running Jetpack
				if ( function_exists( 'stats_get_csv' ) ) {
					// -1 indicates infinity, so get can get total views for all days
					$args = array(
					    'days'    => -1,
					    'limit'   => -1,
					    'post_id' => $post_id
					);
					// Do API call
					$response = stats_get_csv( 'postviews', $args );
					// Set total count
					$count = absint( $response[0]['views'] );
					return $count;
				} else {
					return 0;
				}
			}
			$front_page_id = get_option( 'page_on_front' );
			echo total_page_view_count( $front_page_id );
			?>
			</span>
		</p>
	    <p class="created-by">Created with <span></span> by Zico Deng</p>
	</footer>
</main>

<?php
// Get global footer
get_footer();
?>
