<?php
// Get global header
get_header();

if(have_posts()) {
    while (have_posts()) { ?>
        <article>
            <?php
            // Get one post at a time
            the_post(); ?>

            <!-- Get post title -->
            <!-- Link to a page with only that post -->
            <h2>
                <a href="<?php the_permalink(); ?>">Single This is my title: <?php the_title(); ?></a>
            </h2>

            <p class="post-info">
                <?php the_time("m/d/y g:i a"); ?> | by <a href="<?php echo get_author_posts_url(get_the_author_meta("ID")); ?>"><?php the_author(); ?></a> | Posted in
                <?php
                    $categories = get_the_category();
                    $separator = ", ";
                    $output = "";

                    if($categories) {
                        foreach($categories as $category) {
                            $output .= "<a href='" . get_category_link($category->term_id) . "'>" . $category->cat_name . "</a>" . $separator;
                        }
                        echo trim($output, $separator);
                    }
                ?>
            </p>

            <!-- Get post content based on its format -->
            <?php
                // If returns null, it means it is a standard post
                // We just need to output its content with the most basic format
                if (get_post_format($post->ID) == null) {
                    the_post_thumbnail("banner-image"); ?>
                    <p><?php the_content(); ?></p>
            <?php } else {

                    // If not null, it means it is a post with special format such as gallery
                    // We need to output its content based on its special format
                    get_template_part("content", get_post_format());
                }
            ?>

        </article>
    <?php
    }
}
else {
    echo "No posts found";
}

// Get global footer
get_footer();
?>
