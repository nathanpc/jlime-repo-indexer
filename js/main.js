// main.js
// Do *ALL* the things!!!!

// TODO: Put the package title into a header (in the body) with the version (RubyGems style)
// TODO: Color the Priority
// TODO: Fix the maintainer field display (<> from email is getting parsed)

function show_details(pack) {
	// Clear modal body.
	$("#details > .modal-body").html("");

	// Set title.
	$("#details > .modal-header > h3").html(pack.Package);

	// Set body.
	for (var key in pack) {
		var html = "<b>" + key + ":</b> ";
		if (key === "Depends" || key === "Provides" || key === "Source") {
			html += build_list(pack[key]);
		} else {
			html += pack[key] + "<br />";
		}

		$("#details > .modal-body").append(html);
	}
	
	// Show.
	$("#details").modal("show");
}

function build_list(str) {
	var html = "";
	var arr = str.split(", ");
	
	html += "<ul>";
	for (var i = 0; i < arr.length; i++) {
		html += "<li>" + arr[i] + "</li>";
	}
	html += "</ul>";
	
	return html;
}