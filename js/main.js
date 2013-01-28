// main.js
// Do *ALL* the things!!!!

function search_package(repo) {
	window.location = "search.php?repo=" + repo + "&term=" + encodeURIComponent($("#search").val());
}

function search_keypress(evt) {
	if (typeof evt === undefined && window.event) {
		evt = window.event;
	}

	if (evt.keyCode == 13) {
		alert("Select a repository first.");
		evt.preventDefault();
	}
}

function show_details(pack) {
	// Clear modal body.
	$("#details > .modal-body").html("<p id=\"description\"></p>");

	// Set title.
	$("#details > .modal-header > h3").html(pack.Package);
	$("#details > .modal-header > h4").html(pack.Version);

	// Set body.
	for (var key in pack) {
		var html = "<b>" + key + ":</b> ";
		
		if (key === "Package" || key === "Version") {
			html = "";
		} else if (key === "Description") {
			html = "";
			$("#details > .modal-body > #description").html(pack[key]);
		} else if (key === "Depends" || key === "Provides") {
			html += build_list(pack[key], ", ");
		} else if (key === "Source") {
			html += build_list(pack[key], " ", true);
		} else if (key === "Homepage") {
			html += "<a href=\"" + pack[key] + "\">" + pack[key] + "</a><br />";
		} else if (key === "Maintainer") {
			html += safe_tags(pack[key]) + "<br />";
		} else {
			html += pack[key] + "<br />";
		}

		$("#details > .modal-body").append(html);
	}
	
	// Show.
	$("#details").modal("show");
}

function build_list(str, splt, link) {
	var html = "";
	var arr = str.split(splt);
	
	html += "<ul>";
	for (var i = 0; i < arr.length; i++) {
		if (!link) {
			html += "<li>" + arr[i] + "</li>";
		} else {
			html += "<li><a href=\"" + arr[i] + "\">" + arr[i] + "</a></li>";
		}
	}
	html += "</ul>";
	
	return html;
}

function safe_tags(str) {
	return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
}