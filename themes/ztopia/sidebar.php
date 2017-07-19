<aside class="sidebar active">
	<div class="blog-info-container active">
		<h1>
			<span class="first-name">Zico</span>
			<span class="last-name">Deng</span>
		</h1>
	</div>

    <nav class="site-nav">
        <?php
		if(has_nav_menu("sidebar-nav")) {
			echo "<ul>";

			$menuLocation = get_nav_menu_locations();
			$menuId = $menuLocation["sidebar-nav"];

			// Return an array of menu item objects
			$sidebarMenuItemList = wp_get_nav_menu_items($menuId);

			// Construct a <li> tag for each menu item
			foreach ($sidebarMenuItemList as $menuItem) {
				$originalUrl = $menuItem -> url;
				$title = $menuItem -> title;
				$urlSplit = explode("/", $originalUrl);
				$newUrl = $urlSplit[count($urlSplit) - 2];

				if ( $title == 'Photo Gallery' )  {
					echo "<li><a href='$newUrl' target='_blank'>$title</a></li>";
				} else {
					echo "<li><a href='#$newUrl'>$title</a></li>";
				}
			}
			echo "</ul>";
		}
        ?>
    </nav>
</aside>
