import React from "react";

// Google Map API Key
const API_KEY = "AIzaSyC_FtTqKXIsU2cykmp5aoVogtQpz_plq1Q";

class Life extends React.Component {
	constructor(props) {
		super(props);

		this.loadMap();
	}

	render() {
		return (
			<div></div>
		)
	}

	loadMap() {
		$.ajax({
			type: "GET",
			url: "./wp-json/wp/v2/life-api/?per_page=100",
			dataType: "json"
		})
		.done((response) => {
			response.map((item) => {
				var title = item.title.rendered;
				var timePeriod = item.life_time_period;
				var description = item.life_description;
				var imgURL = item.life_featured_image_url;
				var location = item.life_location;

				// Validate location
				if (location.length != 0) {
					// Geocoding
					// For each item, turn its location address into coordinates
					$.ajax({
						type: "GET",
						url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + location + "&key=" + API_KEY,
						dataType: "json"
					})
					.done((response) => {
						var lat = response.results[0].geometry.location.lat;
						var lng = response.results[0].geometry.location.lng;

						// Get coordinates
						var coordinates = new google.maps.LatLng(lat, lng);

						// Add marker
						var marker = new google.maps.Marker({
							animation: google.maps.Animation.DROP,
							position: coordinates,
							title: title
						});

						console.log(marker);

						// Display info window on hover
						var contentString =
                            '<div class="life-info-window">' +
                                '<div class="featured-image" style="background-image: url(' + imgURL + ');"></div>' +
								'<div class="content">' +
									'<h4>' + title + '</h4>' +
									'<p>' + timePeriod + '</p>' +
									'<p>' + description + '</p>' +
								'</div>'
                            '</div>'

                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });

						marker.addListener('mouseover', function() {
                            infowindow.open(map, marker);
                        });

                        marker.addListener('mouseout', function() {
                            infowindow.close(map, marker);
                        });

						// Populate map
						marker.setMap(map);

					})
					.fail((error) => {
						console.log(error);
					});
				}
			});
		})
		.fail((error) => {
			console.log(error);
		});
	}
}

export default Life;
