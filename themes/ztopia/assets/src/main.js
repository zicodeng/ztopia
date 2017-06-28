// Define map as a global variable
var map;

(function($) {
	$(document).ready(function() {
		var sidebar = $("aside.sidebar");
		var blogInfoContainer = $(".blog-info-container");
		var mainPanel = $("main.front-page");

		// Activie main panel on scroll
		$(this).on("scroll", activeMainPanelOnScroll);

		// In-page navigation
		$(this).on("scroll", onScroll);

		// Siderbar navigation
		var scrollSpeed = 800;
		var navItem = $("nav.site-nav ul li a[href^='#']");
		var bodyView = $("body, html");

		navItem.on("click", function(e) {
			e.preventDefault();

			$(document).off("scroll");

			// Always remove "active" class on every nav item before add
			$("nav.site-nav ul li a").each(function() {
				$(this).removeClass("active");
			});

			// Add "active" class to clicked nav item
			$(this).addClass("active");

			// this.hash returns hash tag of clicked nav item
			var targetHashTag = this.hash;

			// Get corresponding selector
			var target = $(targetHashTag);

			bodyView.stop().animate({
				scrollTop: target.offset().top - 50
			}, scrollSpeed, "swing", function() {
				window.location.hash = targetHashTag;
				$(document).on("scroll", onScroll);
				$(document).on("scroll", activeMainPanelOnScroll);
			});
		});

		// Contact form
		$(".contact-widget").click(function() {
			$(".contact-box").slideToggle(500);
		});
	}); // document.ready() callback end

	// This function adds indicator on menu item,
	// which allows the user to know which section they are currently in
	function onScroll() {
		var scrollPosition = $(document).scrollTop();
		$("nav.site-nav ul li a").each(function () {
			var currentLink = $(this);
			var refElement = $(currentLink.attr("href"));
			if (refElement.position().top - 200 <= scrollPosition &&
			refElement.position().top + refElement.height() > scrollPosition) {
				$("nav.site-nav ul li a").removeClass("active");
				currentLink.addClass("active");
			} else {
				currentLink.removeClass("active");
			}
		});
	}

	// This function activates the main panel
	function activeMainPanelOnScroll() {
		var scrollPosition = $(document).scrollTop();
		var sidebar = $("aside.sidebar");
		var blogInfoContainer = $(".blog-info-container");
		var mainPanel = $("main.front-page")
		var footer = $("footer.site-footer")

		if(scrollPosition > 0) {
			sidebar.removeClass("active");
			blogInfoContainer.removeClass("active");
			mainPanel.addClass("active");
			footer.addClass("active");
		} else {
			sidebar.addClass("active");
			blogInfoContainer.addClass("active");
			mainPanel.removeClass("active");
			footer.removeClass("active");
		}
	}

	// Google maps
	google.maps.event.addDomListener(window, "load", function() {

		// Create a map object, and include the MapTypeId to add
		// to the map type control.
		map = new google.maps.Map(document.getElementById("map"), {
			center: new google.maps.LatLng(21.289373, -157.917480),
			zoom: 2,
			scrollwheel: false,
			mapTypeControlOptions: {
				mapTypeIds: ["roadmap", "satellite", "hybrid", "terrain"]
			}
		});
	});

})(jQuery);
