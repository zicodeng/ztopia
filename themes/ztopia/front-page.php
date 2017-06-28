<?php
// Get global header
get_header();

// Get global sidebar
get_sidebar();
?>

<main class="front-page">
	<section class="home">
		<div class="logo">
			<div class="logo-frame"></div>
			<div class="logo-frame"></div>
			<h1>Z</h1>
		</div>
	</section>
    <section id="about" class="about">
        <?php
        // "About" post type
        $about_posts = new WP_Query( array(
            "post_type"      => "about",
			"posts_per_page" => 1
        ) );
        if ( $about_posts->have_posts() ) {
            while ( $about_posts->have_posts() ) {
                $about_posts->the_post(); ?>
                <article>
					<h1>Hi, <?php the_title(); ?>!</h1>
					<div class="row">
						<div class="col-md-12 col-lg-8">
							<div class="about-content">
								<?php $about_subtitle = get_post_meta( $post->ID, "about_subtitle", true ); ?>
								<p class="about-subtitle"><?php echo $about_subtitle; ?></p>

								<?php $about_description = get_post_meta( $post->ID, "about_description", true ); ?>
								<p><?php echo $about_description; ?></p>
							</div>
						</div>
						<div class="col-md-12 col-lg-4">
							<div class="profile-picture">
								<div class="picture-frame"></div>
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail();
								}
								?>
							</div>
						</div>
					</div>
                </article>
            <?php
            }
        } else {
            echo "No Posts Found";
        }
        // Prevents our custom WP_Query query from ever disrupting the main natural url-based WordPress query runs on its own
        wp_reset_postdata();
        ?>
    </section>
	<section id="education" class="education">
        <?php
        // "Education" post type
        $education_posts = new WP_Query( array(
            "post_type"      => "education",
			"posts_per_page" => -1
        ) );
        if ( $education_posts->have_posts() ) {

			$education_post_count = 0;

            while ( $education_posts->have_posts() ) {

				$education_post_count ++;

                $education_posts->the_post();

				// Odd number case
				if ( $education_post_count % 2 == 1 ) {
		?>
					<article>
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="institute-picture">
									<?php $graduation_date = get_post_meta( $post->ID, "graduation_date", true ); ?>
									<p><?php echo $graduation_date; ?></p>

									<div class="institute-thumbnail">
										<?php
										if ( has_post_thumbnail() ) {
											the_post_thumbnail();
										}
										?>
									</div>

									<div class="thumbnail-stripe"></div>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div class="education-content">
									<h4><?php the_title(); ?></h4>

									<?php $institute_location = get_post_meta( $post->ID, "institute_location", true ); ?>
									<p><?php echo $institute_location; ?></p>

									<?php $degree_type = get_post_meta( $post->ID, "degree_type", true ); ?>
									<p><?php echo $degree_type; ?></p>

									<?php $education_description = get_post_meta( $post->ID, "education_description", true ); ?>
									<p><?php echo $education_description; ?></p>
								</div>
							</div>
						</div>
					</article>
				<?php
				} else {
				// Even number case
				?>
				<article>
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="education-content">
								<h4><?php the_title(); ?></h4>

								<?php $institute_location = get_post_meta( $post->ID, "institute_location", true ); ?>
								<p><?php echo $institute_location; ?></p>

								<?php $degree_type = get_post_meta( $post->ID, "degree_type", true ); ?>
								<p><?php echo $degree_type; ?></p>

								<?php $education_description = get_post_meta( $post->ID, "education_description", true ); ?>
								<p><?php echo $education_description; ?></p>
							</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<div class="institute-picture">
								<?php $graduation_date = get_post_meta( $post->ID, "graduation_date", true ); ?>
								<p><?php echo $graduation_date; ?></p>

								<div class="institute-thumbnail">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail();
									}
									?>
								</div>

								<div class="thumbnail-stripe"></div>
							</div>
						</div>
					</div>
				</article>
				<article class="education-mobile">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="institute-picture">
								<?php $graduation_date = get_post_meta( $post->ID, "graduation_date", true ); ?>
								<p><?php echo $graduation_date; ?></p>

								<div class="institute-thumbnail">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail();
									}
									?>
								</div>

								<div class="thumbnail-stripe"></div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<div class="education-content">
								<h4><?php the_title(); ?></h4>

								<?php $institute_location = get_post_meta( $post->ID, "institute_location", true ); ?>
								<p><?php echo $institute_location; ?></p>

								<?php $degree_type = get_post_meta( $post->ID, "degree_type", true ); ?>
								<p><?php echo $degree_type; ?></p>

								<?php $education_description = get_post_meta( $post->ID, "education_description", true ); ?>
								<p><?php echo $education_description; ?></p>
							</div>
						</div>
					</div>
				</article>
				<?php
				}
				?>
            <?php
            }
        } else {
            echo "No Posts Found";
        }
        // Prevents our custom WP_Query query from ever disrupting the main natural url-based WordPress query runs on its own
        wp_reset_postdata();
        ?>
    </section>
	<section id="experience" class="experience">
		<?php
		// "Experience" post type
		$experience_posts = new WP_Query( array(
			"post_type" => "experience",
			"posts_per_page" => -1
		) );
		if ( $experience_posts->have_posts() ) {
			while ( $experience_posts->have_posts() ) {
				$experience_posts->the_post(); ?>
				<?php
				if ( has_post_thumbnail() ) {
				?>
					<article style="background-image: url('<?php the_post_thumbnail_url(); ?>')">
						<div class="experience-content">
							<h4><?php the_title(); ?></h4>

							<?php $experience_location = get_post_meta($post->ID, "experience_location", true); ?>
							<p>Location: <?php echo $experience_location ?></p>

							<?php $experience_time_period = get_post_meta($post->ID, "experience_time_period", true); ?>
							<p>Time Period: <?php echo $experience_time_period ?></p>

							<?php $job_title = get_post_meta($post->ID, "job_title", true); ?>
							<p>Job Title: <?php echo $job_title ?></p>

							<?php $experience_description = get_post_meta($post->ID, "experience_description", true); ?>
							<p>Description: <?php echo $experience_description ?></p>
						</div>
					</article>
		<?php
				}
			}
		} else {
			echo "No Posts Found";
		}
		// Prevents our custom WP_Query query from ever disrupting the main natural url-based WordPress query runs on its own
		wp_reset_postdata();
		?>
	</section>
	<section id="project" class="project"></section>
	<section id="life" class="life">
		<div id="map" class="map"></div>
		<div id="map-ui"></div>
	</section>
	<section class="life-motto">
		<div class="wrapper">
			<div class="content">
				<h4>Life Motto</h4>
				<p>"I aim to save people from the overwhelming tsunami of information." - Zico Deng</p>
			</div>
			<div class="featured-image"></div>
		</div>
	</section>
</main>
<footer class="site-footer">
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
	<p>Total page view:
		<span>
		<?php
		function total_page_view_count( $post_id ) {
			// Check for transient
			if ( ! ( $count = get_transient( 'wds_post_pageview_count' . $post_id ) ) ) {
				// Verify we're running Jetpack
				if ( function_exists( 'stats_get_csv' ) ) {
					// Do API call
					$response = stats_get_csv( 'postviews', 'post_id='. absint( $post_id ) .'&period=month&limit=1' );
					// Set total count
					$count = absint( $response[0]['views'] );
					// If not, stop and don't set transient
				} else {
					return 'Jetpack stats not active';
				}
				// Set transient to expire every 30 minutes
				set_transient( 'wds_post_pageview_count' . absint( $post_id ), absint( $count ), 30 * MINUTE_IN_SECONDS );
			}
			return absint( $count );
		}
		echo total_page_view_count( $post_id );
		?>
		</span>
	</p>
    <p class="created-by">Created with <span></span> by Zico Deng</p>
</footer>

<?php
// Get global footer
get_footer();
?>
