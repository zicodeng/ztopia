<!-- Customize search result page -->
<?php
// Get global header
get_header();

if(have_posts()) { ?>
    <!-- Output user search query -->
    <h2>Search result for: <?php the_search_query(); ?></h2>

    <?php
    while (have_posts()) {
        the_post();
        get_template_part("content", get_post_format());
    }
}
else {
    echo "No posts found";
}

// Get global footer
get_footer();
?>
