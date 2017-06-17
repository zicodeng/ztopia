import React from "react";
import ReactDOM from "react-dom";

// Import React components
import Project from "./project.jsx";
import Life from "./life.jsx";

// Render them on screen
ReactDOM.render(<Project />, document.getElementById("project"));
ReactDOM.render(<Life />, document.getElementById("map-ui"));
