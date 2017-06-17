<?php
// Get global header
get_header();

if(have_posts()) { ?>

    <h2><?php
        if(is_category()) {
            single_cat_title();
        } else if(is_tag()) {
            single_tag_title();
        } else if(is_author()) {
            the_post();
            echo "Author Archives: " . get_the_author();
            rewind_posts();
        } elseif(is_day()) {
            echo "Daily Archives: " . get_the_date();
        } elseif(is_month()) {
            echo "Monthly Archives: " . get_the_date("F Y");
        } elseif(is_year()) {
            echo "Yealy Archives: " . get_the_date("Y");
        } else {
            echo "Archives";
        }
     ?></h2>

    <?php while (have_posts()) {
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
