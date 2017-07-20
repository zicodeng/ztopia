(function($) {

	$(document).ready(function() {
		// Init Isotope
		var grid = $(".grid").isotope({
			// options
		});

		// Init jsPDF
		var doc = new jsPDF();

		// Handle click filter
		var allBtn = $(".all-btn");
		var savedBtn = $(".saved-btn");
		var downloadBtn = $(".download-btn");

		allBtn.click(function() {
			downloadBtn.css("display", "none");

			var self = $(this);
			handleClickFilter(grid, self);
		});

		savedBtn.click(function() {
			downloadBtn.css("display", "block");

			var self = $(this);
			handleClickFilter(grid, self);
		});

		// Save/unsave favorite photograph
		var elementItem = $(".element-item");
		var savedList = [];

		elementItem.click(function() {
			$(this).toggleClass("saved");

			// Get image and title of saved item
			var savedImgURL = $(this).children(".photo-preview").css("background-image").replace('url(','').replace(')','').replace(/\"/gi, "");

			var savedImgTitle = $(this).find(".photo-title").html();

			var savedItem = {
				savedImgURL: savedImgURL,
				savedImgTitle: savedImgTitle
			}

			// Check if this click action is for saving or removing
			if ($(this).hasClass("saved")) {
				// Push it to saved list
				savedList.push(savedItem);
			} else {
				// Remove it from saved list
				savedList = savedList.filter(function(item) {
					return savedImgTitle !== item.savedImgTitle;
				});
			}

			// If the user remove a saved item from saved list,
			// filter the list again to re-render
			if (savedBtn.hasClass("active")) {
				grid.isotope({
					filter: ".saved"
				});
			}

			// Update saved button view to indicate
			// the number of saved item in the saved list
			savedBtn.html("SAVED(" + savedList.length + ")");
		});

		// Handle download as PDF
		downloadBtn.click(function() {
			var doc = new jsPDF();
			doc.setFontSize(20);
			savedList.map(function(item) {
				doc.text(10, 20, item.savedImgTitle);
				generateImagePDF(doc, item.savedImgURL);
				doc.addPage();
			});

			// Last page
			doc.setFontSize(35);
			doc.text(28, 130, "CREATED BY ZICO DENG");

			doc.setFontSize(15);
			doc.text(28, 140, "For more information, please contact me at zicodeng@gmail.com");

			doc.save("photo-gallery.pdf");
		});
	});

	// When a filter button is clicked,
	// corresponding isotope filter function will be applied
	function handleClickFilter(grid, self) {
		$(".btn-group > span").removeClass("active");
		self.addClass("active");
		var filterValue = self.attr("data-filter");
		grid.isotope({
			filter: filterValue
		});
	}

	function generateImagePDF(doc, savedImgURL) {
		// Create an image object
	    var img = new Image();
		img.src = savedImgURL;
	    img.setAttribute('crossOrigin', 'anonymous');

		// Create a canvas object
        var canvas = document.createElement("canvas");
        canvas.width = img.width;
        canvas.height = img.height;
		var ctx = canvas.getContext("2d");

		// Draw image on the canvas
        ctx.drawImage(img, 0, 0);

		// Convert to base64 data url
        var dataURL = canvas.toDataURL("image/jpg");

		// Set image width and height in PDF
		var pageWidth = doc.internal.pageSize.width;
		var pageHeight = doc.internal.pageSize.height;

		// Dynamically calculate image height to retain ratio
		var imgNewWidth = pageWidth - 20;
		var imgNewHeight = imgNewWidth * img.height / img.width;

		doc.addImage(dataURL, "JPG", 10, 25, imgNewWidth, imgNewHeight);
	}

})(jQuery);
