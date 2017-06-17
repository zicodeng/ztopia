<!-- Customize search form -->
<form role="search" method="get" id="searchform" action="<?php echo home_url("/"); ?>">
    <div>
        <label class="screen-reader-text" for="s">Search for:</label>
        <input type="text" name="s" value="" id="s" placeholder="<?php the_search_query(); ?>" />
        <input type="submit" name="" value="Search" id="searchsubmit" />
    </div>
</form>
