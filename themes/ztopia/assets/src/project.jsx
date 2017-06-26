import React from "react";

const PROJECT_CATEGORY = {
	ALL: 1,
	WEB: 2,
	ANDROID: 3,
	DESIGN: 4
}

class Project extends React.Component {

	constructor(props) {
		super(props);

		// Initialize state
		this.state = {
			projectAllList: [],
			projectWebList: [],
			projectAndroidList: [],
			projectDesignList: [],
			projectList: [],
			category: PROJECT_CATEGORY.ALL
		};

		// Load all projects on page loads
		this.loadProjects();
	}

	render() {
		return (
			<div>
				<h4 className="project-category">
					<span
						className={this.state.category === PROJECT_CATEGORY.ALL ? "active" : null}
						onClick={(e) => this.handleClickCategory(e, PROJECT_CATEGORY.ALL)}>All</span>
					<span
						className={this.state.category === PROJECT_CATEGORY.WEB ? "active" : null}
						onClick={(e) => this.handleClickCategory(e, PROJECT_CATEGORY.WEB)}>Web</span>
					<span
						className={this.state.category === PROJECT_CATEGORY.ANDROID ? "active" : null}
						onClick={(e) => this.handleClickCategory(e, PROJECT_CATEGORY.ANDROID)}>Android</span>
					<span
						className={this.state.category === PROJECT_CATEGORY.DESIGN ? "active" : null}
						onClick={(e) => this.handleClickCategory(e, PROJECT_CATEGORY.DESIGN)}>Design</span>
				</h4>
				{
					this.state.projectList.map((item, uid) => {
						return (
							<article key={uid}>
								<div className="project-image" style={{backgroundImage: "url(" + item.imgURL + ")"}}></div>
								<div className="project-content">
									<h4>{item.title}</h4>
									<p>Time Period: {item.timePeriod}</p>
									<p>Core Technologies: {item.coreTech}</p>
									<p>Description: {item.description}</p>
								</div>
								<a href={item.projectLink} target="_blank">MORE</a>
							</article>
						)
					})
				}
			</div>
		)
	}

	loadProjects() {

		// This list stores all projects regardless of project category
		var projectAllList = this.state.projectAllList;

		// This list only stores projects if they are categorized as "Web"
		var projectWebList = this.state.projectWebList;

		// This list only stores projects if they are categorized as "Android"
		var projectAndroidList = this.state.projectAndroidList;

		// This list only stores projects if they are categorized as "Design"
		var projectDesignList = this.state.projectDesignList;

		// This list is used to render
		var projectList = this.state.projectList;

		$.ajax({
			type: "GET",
			url: "./wp-json/wp/v2/project-api/?per_page=100",
			dataType: "json"
		})
		.done((response) => {
			response.map((item) => {
				// Retrieve relevant information from API
				var title = item.title.rendered;
				var category = item.project_category;
				var timePeriod = item.project_time_period;
				var coreTech = item.project_core_technologies;
				var description = item.project_description;
				var projectLink = item.project_link;
				var pageLink = item.link;
				var imgURL = item.project_featured_image_url;

				// If project link field is empty,
				// use its single-project.php page link
				if(projectLink.length === 0) {
					projectLink = pageLink;
				}

				// Create a project object
				var project = {
					title: title,
					category: category,
					timePeriod: timePeriod,
					coreTech: coreTech,
					description: description,
					projectLink: projectLink,
					imgURL: imgURL
				}

				switch (category) {
					case "web":
						projectWebList.push(project);
						break;
					case "android":
						projectAndroidList.push(project);
						break;
					case "design":
						projectDesignList.push(project);
						break;
				}

				// Always add to projectAllList
				projectAllList.push(project);
			});

			// After looping through all the items in the API,
			// update the state
			this.setState({
				projectAllList: projectAllList,
				projectWebList: projectWebList,
				projectAndroidList: projectAndroidList,
				projectDesignList: projectDesignList,
				projectList: projectAllList
			});
		})
		.fail((error) => {
			console.log(error);
		});
	}

	handleClickCategory(e, category) {
		switch (category) {
			case PROJECT_CATEGORY.ALL:
				this.setState({
					category: PROJECT_CATEGORY.ALL,
					projectList: this.state.projectAllList
				});
				break;

			case PROJECT_CATEGORY.WEB:
				this.setState({
					category: PROJECT_CATEGORY.WEB,
					projectList: this.state.projectWebList
				});
				break;

			case PROJECT_CATEGORY.ANDROID:
				this.setState({
					category: PROJECT_CATEGORY.ANDROID,
					projectList: this.state.projectAndroidList
				});
				break;

			case PROJECT_CATEGORY.DESIGN:
				this.setState({
					category: PROJECT_CATEGORY.DESIGN,
					projectList: this.state.projectDesignList
				});
				break;
		}
	}
}

export default Project;
