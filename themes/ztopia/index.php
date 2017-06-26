<?php
// Get global header
get_header();
?>

    <div class="site-content clearfix">
        <div class="main-col">
            <h1>INDEX.PHP</h1>
            <?php
            if(have_posts()) {
                while (have_posts()) {
                    // Get one post at a time
                    the_post();

                    // Get post based on its format
                    // Different post format can have different post content, layout, and style
                    get_template_part("content", get_post_format());
                }

                echo paginate_links();
            }
            else {
                echo "No posts found";
            }
            ?>
        </div>

        <?php get_sidebar(); ?>

    </div>

<?php
// Get global footer
get_footer();
?>
