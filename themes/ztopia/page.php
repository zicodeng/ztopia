<?php
// Get global header
get_header();

if(have_posts()) {
    while (have_posts()) {
        // Get one post at a time
        the_post();

        // Display sub-menu
        if (count(get_children($post->ID)) > 0 || $post->post_parent > 0) { ?>
            <nav class="site-nav children-links">

                <!-- Show the highest ancestor title -->
                <span class="parent-link">
                    <a href="<?php echo get_the_permalink(get_top_ancestor_id()); ?>">
                        <?php echo get_the_title(get_top_ancestor_id()); ?>
                    </a>
                </span>
                <ul>
                    <!-- Show children of only currently viewed page -->
                    <?php
                    $args = array(
                        "child_of" => get_top_ancestor_id(),
                        "title_li" => ""
                    );
                    wp_list_pages($args);
                    ?>
                </ul>

            </nav>

        <?php } ?>

        <article class="page">
            <!-- Get post title -->
            <!-- Link to a page with only that post -->
            <h2>page title: <?php the_title() ?></h2>

            <!-- Get post content -->
            <p><?php the_content()?></p>
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
