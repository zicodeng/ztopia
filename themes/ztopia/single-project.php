<?php
// Get global header
get_header();
?>

<main class="single-project">
	<?php
	if ( have_posts() ) {
	    while ( have_posts() ) {
			the_post();
			?>

			<h4 class="project-title"><?php the_title(); ?></h4>

			<div id="fullpage">
				<?php
				$project_slide_one_title = get_post_meta( $post->ID, 'project_slide_one_title', true );
				if ( strlen( $project_slide_one_title ) > 0 ) {
					?>
					<div class="section">
						<div class="container">
							<div class="slide-content">
								<h4><?php echo get_post_meta( $post->ID, 'project_slide_one_title', true ); ?></h4>
								<p><?php echo apply_filters( 'the_content', get_post_meta( $post->ID, 'project_slide_one_description', true ) ); ?></p>
							</div>
						</div>

						<div class="image-group">
							<img src="<?php echo get_post_meta( $post->ID, 'project_slide_one_image_one', true ); ?>" alt="">
							<img src="<?php echo get_post_meta( $post->ID, 'project_slide_one_image_two', true ); ?>" alt="">
							<img src="<?php echo get_post_meta( $post->ID, 'project_slide_one_image_three', true ); ?>" alt="">
						</div>
				    </div>
				<?php
				}

				$project_slide_two_title = get_post_meta( $post->ID, 'project_slide_two_title', true );
				if ( strlen( $project_slide_two_title ) > 0 ) {
					?>
				    <div class="section">
						<div class="container">
							<div class="row">
								<div class="col-sm-12 col-md-12 col-lg-6">
									<div class="slide-content">
										<h4><?php echo get_post_meta( $post->ID, 'project_slide_two_title', true ); ?></h4>
										<p><?php echo apply_filters( 'the_content', get_post_meta( $post->ID, 'project_slide_two_description', true ) ); ?></p>
									</div>
								</div>
								<div class="col-sm-12 col-md-12 col-lg-6">
									<div class="image-group">
										<img src="<?php echo get_post_meta( $post->ID, 'project_slide_two_image_one', true ); ?>" alt="">
										<img src="<?php echo get_post_meta( $post->ID, 'project_slide_two_image_two', true ); ?>" alt="">
										<img src="<?php echo get_post_meta( $post->ID, 'project_slide_two_image_three', true ); ?>" alt="">
									</div>
								</div>
							</div>
						</div>
				    </div>
				<?php
				}

				$project_slide_three_title = get_post_meta( $post->ID, 'project_slide_three_title', true );
				if ( strlen( $project_slide_three_title ) > 0 ) {
				?>
				    <div class="section">
						<div class="container">
							<div class="row">
								<div class="col-lg-12 col-xl-6">
									<div class="image-group">
										<img src="<?php echo get_post_meta( $post->ID, 'project_slide_three_image_one', true ); ?>" alt="">
										<img src="<?php echo get_post_meta( $post->ID, 'project_slide_three_image_two', true ); ?>" alt="">
										<img src="<?php echo get_post_meta( $post->ID, 'project_slide_three_image_three', true ); ?>" alt="">
										<img src="<?php echo get_post_meta( $post->ID, 'project_slide_three_image_four', true ); ?>" alt="">
									</div>
								</div>
								<div class="col-lg-12 col-xl-6">
									<div class="slide-content">
										<h4><?php echo get_post_meta( $post->ID, 'project_slide_three_title', true ); ?></h4>
										<p><?php echo apply_filters( 'the_content', get_post_meta( $post->ID, 'project_slide_three_description', true ) ); ?></p>
									</div>
								</div>
							</div>
						</div>
				    </div>
				<?php
				}
				?>
			</div>
			<?php
	    }
	} else {
	    echo "No posts found";
	}
	?>
</main>
<?php
// Get global footer
get_footer();
?>
