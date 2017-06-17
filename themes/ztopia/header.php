<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset');?>" />
    <meta name="viewport" content="width=device-width" />

    <title><?php bloginfo("name"); ?></title>

    <!-- WordPress hook function -->
    <?php wp_head();

	if ( is_singular('project') ) {
		// Only load these files in single-project.php
		?>
		<script type="text/javascript">
			(function($) {
				$(document).ready(function() {
					$('#fullpage').fullpage({
						//Navigation
						menu: '#menu',
						lockAnchors: false,
						anchors:['slide-1', 'slide-2', 'slide-3', 'slide-4'],
						navigation: true,
						navigationPosition: 'right',
						navigationTooltips: [],
						showActiveTooltip: false,
						slidesNavigation: false,
						slidesNavPosition: 'bottom',

						//Design
						controlArrows: true,
						verticalCentered: true,
						sectionsColor : ['#000', '#000', '#000', '#000'],
						paddingTop: '100px',
						paddingBottom: '0px',
						fixedElements: '#header, .footer',
						responsiveWidth: 0,
						responsiveHeight: 0,
						responsiveSlides: false,
						parallax: false,
						parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},

						lazyLoading: true,
					});
				});
			}(jQuery));
		</script>
		<?php
	}
	?>
</head>
<body <?php body_class(); ?>>
