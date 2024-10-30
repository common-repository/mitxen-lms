"use strict";
function getFilename(path) {
	path = path.substring(path.lastIndexOf("/") + 1);
	return (path.match(/[^.]+(\.[^?#]+)?/) || [])[0];
}

function mxlmsGetUpperCaseFirst(str) {
	return str.substr(0, 1).toUpperCase() + str.substr(1);
}
// Method for showing toastr notifications on different events
function mxlmsNotify(message, type) {
	switch (type) {
		case "success":
			toastr.success(message, { timeOut: 60000 });
			break;
		case "warning":
			toastr.warning(message, { timeOut: 60000 });
			break;
		case "error":
			toastr.error(message, { timeOut: 60000 });
			break;
		default:
			toastr.info(message, { timeOut: 60000 });
			break;
	}
}

// Method for performing ajax calls
function mxlmsMakeAjaxCall(
	ajax_url,
	view_to_load,
	in_div,
	param1,
	param2,
	param3,
	param4,
	param5
) {
	// SHOW THE PLACEHOLDER
	jQuery(".mxlms-custom-modal-body").hide();
	jQuery("#mxlms-right-modal .mxlms-custom-modal-content").addClass(
		"mxlms-custom-modal-body-placeholder"
	);

	var ajaxurl = ajax_url;
	jQuery("#" + in_div).block({
		message: null,
		overlayCSS: {
			backgroundColor: "#f3f4f5",
		},
	});
	jQuery.post(
		ajaxurl,
		{
			action: "mxlms",
			page: view_to_load,
			response_div: in_div,
			task: "load_response",
			param1: param1,
			param2: param2,
			param3: param3,
			param4: param4,
			param5: param5,
		},
		function (response) {
			setTimeout(function () {
				jQuery("#" + in_div).unblock();
				if (param2 == "append") jQuery("#" + in_div).append(response);
				else jQuery("#" + in_div).html(response);
			}, 500);

			// HIDE THE PLACEHOLDER
			jQuery("#mxlms-right-modal .mxlms-custom-modal-content").removeClass(
				"mxlms-custom-modal-body-placeholder"
			);
			jQuery(".mxlms-custom-modal-body").show();
		}
	);
}

// Method for performing ajax calls
function mxlmsGetVideoDurationAndValidity(ajax_url, durationFieldId, url) {
	// SHOW THE PLACEHOLDER
	jQuery(".mxlms-custom-modal-body").hide();
	jQuery("#mxlms-right-modal .mxlms-custom-modal-content").addClass(
		"mxlms-custom-modal-body-placeholder"
	);

	var ajaxurl = ajax_url;
	jQuery("#" + durationFieldId).block({
		message: null,
		overlayCSS: {
			backgroundColor: "#f3f4f5",
		},
	});
	jQuery("#video-url-validity-message").show();
	jQuery.post(
		ajaxurl,
		{
			action: "mxlms",
			task: "video_url_validity",
			url: url,
		},
		function (response) {
			setTimeout(function () {
				var json = JSON.parse(response);
				jQuery("#video-url-validity-message").hide();
				if (!json.status) {
					jQuery("#invalid-video-url-message").show();
				} else {
					jQuery("#invalid-video-url-message").hide();
				}
				jQuery("#" + durationFieldId).val(json.duration);
			}, 500);

			// HIDE THE PLACEHOLDER
			jQuery("#mxlms-right-modal .mxlms-custom-modal-content").removeClass(
				"mxlms-custom-modal-body-placeholder"
			);
			jQuery(".mxlms-custom-modal-body").show();
		}
	);
}

// handle the dropdown menu
function mxlmsHandleDropDown(id) {
	jQuery(".mxlms-dd-menu").attr("style", "display: none !important");

	if (jQuery("#mxlms-dd-checkbox-" + id).prop("checked")) {
		jQuery("#mxlms-dd-menu-" + id).attr("style", "display: block !important");
		jQuery(".mxlms-dd-input").prop("checked", true);
	} else {
		jQuery("#mxlms-dd-menu-" + id).attr("style", "display: none !important");
		jQuery(".mxlms-dd-input").prop("checked", false);
	}
}

// HANDLE THE PAGE REDIRECTION
function redirectTo(url) {
	window.location.replace(url);
}
