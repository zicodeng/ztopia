// Attach the image uploader to the input field
jQuery(document).ready(function($){

	var metaBoxImageFrame
	var imgEditBtn = $(".meta-box-image-edit-btn");

	imgEditBtn.click(function(e){
		e.preventDefault();

		// Sets up the media library frame
		metaBoxImageFrame = wp.media.frames.meta_box_image_frame = wp.media({
			title: "Choose or Upload an Image",
			button: {text: "Select"},
			library: {type: "image"}
		});

		if($(this).hasClass("one")) {
			setImage("one");
		}

		if($(this).hasClass("two")) {
			setImage("two");
		}

		if($(this).hasClass("three")) {
			setImage("three");
		}

		if($(this).hasClass("four")) {
			setImage("four");
		}

		if($(this).hasClass("five")) {
			setImage("five");
		}

		if($(this).hasClass("six")) {
			setImage("six");
		}

		if($(this).hasClass("seven")) {
			setImage("seven");
		}

		if($(this).hasClass("eight")) {
			setImage("eight");
		}

		if($(this).hasClass("nine")) {
			setImage("nine");
		}

		if($(this).hasClass("ten")) {
			setImage("ten");
		}

		if($(this).hasClass("eleven")) {
			setImage("eleven");
		}

		if($(this).hasClass("twelve")) {
			setImage("twelve");
		}

		if($(this).hasClass("thirteen")) {
			setImage("thirteen");
		}

		if($(this).hasClass("fourteen")) {
			setImage("fourteen");
		}

		if($(this).hasClass("fifteen")) {
			setImage("fifteen");
		}

		if($(this).hasClass("sixteen")) {
			setImage("sixteen");
		}

		// Open the media library frame.
		metaBoxImageFrame.open();
	});

	function setImage(imgNum) {

		// Get image url when an image is selected
		metaBoxImageFrame.on("select", function(){

			// Grab the attachment selection and creates a JSON representation of the model
			var mediaAttachment = metaBoxImageFrame.state().get("selection").first().toJSON();

			// Send the attachment URL to our custom image input field
			$("#meta-box-image-" + imgNum).val(mediaAttachment.url);

			// Update image preview
			$("#meta-box-image-" + imgNum + "-preview").css("background-image", 'url("' + mediaAttachment.url + '")');
		});
	}

	var imgRemoveBtn = $(".meta-box-image-remove-btn");

	imgRemoveBtn.click(function() {
		if($(this).hasClass("one")) {
			removeImage("one");
		}

		if($(this).hasClass("two")) {
			removeImage("two");
		}

		if($(this).hasClass("three")) {
			removeImage("three");
		}

		if($(this).hasClass("four")) {
			removeImage("four");
		}

		if($(this).hasClass("five")) {
			removeImage("five");
		}

		if($(this).hasClass("six")) {
			removeImage("six");
		}

		if($(this).hasClass("seven")) {
			removeImage("seven");
		}

		if($(this).hasClass("eight")) {
			removeImage("eight");
		}

		if($(this).hasClass("nine")) {
			removeImage("nine");
		}

		if($(this).hasClass("ten")) {
			removeImage("ten");
		}

		if($(this).hasClass("eleven")) {
			removeImage("eleven");
		}

		if($(this).hasClass("twelve")) {
			removeImage("twelve");
		}

		if($(this).hasClass("thirteen")) {
			removeImage("thirteen");
		}

		if($(this).hasClass("fourteen")) {
			removeImage("fourteen");
		}

		if($(this).hasClass("fifteen")) {
			removeImage("fifteen");
		}

		if($(this).hasClass("sixteen")) {
			removeImage("sixteen");
		}
	});

	function removeImage(imgNum) {
		// Send the attachment URL to our custom image input field
		$("#meta-box-image-" + imgNum).val("");

		// Update image preview
		$("#meta-box-image-" + imgNum + "-preview").css("background-image", 'url("./../wp-content/themes/ztopia/assets/images/default-image.jpg")');
	}

	// Toggle indicator
	$(".slide-info-container h2 span.toggle-indicator").click(function() {
		$(this).toggleClass("active");
		$(this).parent("h2").next().slideToggle(0);
	});
});
