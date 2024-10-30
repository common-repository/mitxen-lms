"use strict";

jQuery(window).on( "ready" , function () {
  compatibleGridding();
});

jQuery(window).on( "resize" , function () {
  var viewportWidth = jQuery(".mxlms-wrapper").width();
  compatibleGridding();
});

// COMPATIBLE GRIDDING FOR DIFFERENT THEME
function compatibleGridding() {
  var viewportWidth = jQuery(".mxlms-wrapper").width();
  var wrap = jQuery(".mxlms-wrap");
  if (viewportWidth > 1050) {
    // ADD MXLMS-COL-XL-*
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-lg-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-md-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-sm-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-xs-\S+/g) || []).join(" ");
    });
  } else if (viewportWidth <= 1050 && viewportWidth > 850) {
    // ADD MXLMS-COL-LG-*
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-xl-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-md-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-sm-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-xs-\S+/g) || []).join(" ");
    });
  } else if (viewportWidth <= 850 && viewportWidth > 650) {
    // ADD MXLMS-COL-MD-*
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-xl-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-lg-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-sm-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-xs-\S+/g) || []).join(" ");
    });
  } else if (viewportWidth <= 650 && viewportWidth > 500) {
    // ADD MXLMS-COL-SM-*
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-xl-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-lg-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-md-\S+/g) || []).join(" ");
    });
    wrap.removeClass(function (index, css) {
      return (css.match(/(^|\s)mxlms-col-xs-\S+/g) || []).join(" ");
    });
  }

  var restClasses = wrap.attr("class");
  if (typeof restClasses !== "undefined") {
    var splittedRestClasses = restClasses.split("mxlms-wrap");
    if (splittedRestClasses[1].indexOf("mxlms-col-") == 0) {
      wrap.addClass("mxlms-col");
    }
  }
}

// MXLMS ACCORDION JS
jQuery(".mxlms-accordion-toggle").on( "click" , function (e) {
  e.preventDefault();

  jQuery(".mxlms-accordion")
    .find(".fa-chevron-up")
    .removeClass("fa-chevron-up");
  jQuery(".mxlms-accordion").find(".fas").addClass("fa-chevron-down");

  let $this = jQuery(this);
  if ($this.next().hasClass("show")) {
    $this.next().removeClass("show");
    $this.next().slideUp(0);
    $this.find(".fa-chevron-up").removeClass("fa-chevron-up");
    $this.find(".fas").addClass("fa-chevron-down");
  } else {
    $this.parent().parent().find("li .inner").removeClass("show");
    $this.parent().parent().find("li .inner").slideUp(150);
    $this.next().toggleClass("show");
    $this.next().slideToggle(0);
    $this.find(".fa-chevron-down").removeClass("fa-chevron-down");
    $this.find(".fas").addClass("fa-chevron-up");
  }
});

// MXLMS MODAL JS

jQuery(".mxlms-open-modal").on( "click" , function (e) {
  e.preventDefault();
  jQuery(".mxlms-modal").addClass("mxlms-opened");
});
jQuery(".mxlms-close-modal").on( "click" , function (e) {
  e.preventDefault();
  jQuery(".mxlms-modal").removeClass("mxlms-opened");
});

// COURSE PREVIEW MODAL JS
jQuery(".mxlms-open-course-preview-modal").on( "click" , function (e) {
  e.preventDefault();
  jQuery(".mxlms-course-preview-modal").addClass("mxlms-opened");
});
jQuery(".mxlms-close-course-preview-modal").on( "click" , function (e) {
  e.preventDefault();
  jQuery(".mxlms-course-preview-modal iframe").attr(
    "src",
    jQuery(".mxlms-course-preview-modal iframe").attr("src")
  );
  jQuery(".mxlms-course-preview-modal").removeClass("mxlms-opened");
});

// CUSTOM FORM ELEMENT
// MXLMS-DROPDOWN-FOR-FRONTEND
//Mouseup textarea false
jQuery(".mxlms-submenu").mouseup(function () {
  return false;
});
jQuery(".mxlms-account").mouseup(function () {
  return false;
});
//Textarea without editing.
jQuery(document).mouseup(function () {
  jQuery(".mxlms-submenu").hide();
  jQuery(".mxlms-account").attr("id", "");
});

// INIT NICE SELECT
function initNiceSelect(ids) {
  if (ids && ids.length) {
    let i;
    for (i = 0; i < ids.length; i++) {
      jQuery(ids[i]).niceSelect();
    }
  } else {
    jQuery("select").niceSelect();
  }
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
      task: "load_frontend_response",
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
        else {
          jQuery("#" + in_div).html(response);
          compatibleGridding();
        }
      }, 500);
    }
  );
}

// CUSTOM JS FUNCTION FOR SAVING THE COURSE PROGRESS
function mxlmsSaveCourseProgress(ajax_url, lessonId, courseId, progress) {
  jQuery(".mxlms-addon-tab-view-placeholder").removeClass("mxlms-hidden");
	jQuery(".mxlms-addon-tab-view").addClass("mxlms-hidden");

  jQuery("#mxlms-lesson-accordion").css("opacity", 0);
  jQuery("#mxlms-lesson-list-area").addClass(
    "mxlms-custom-modal-body-placeholder"
  );
  var ajaxurl = ajax_url;
  jQuery.post(
    ajaxurl,
    {
      action: "mxlms",
      task: "save_course_progress",
      param1: lessonId,
      param2: progress,
    },
    function (response) {
      jQuery.post(
				ajaxurl,
				{
					action: "mxlms",
					task: "load_frontend_response",
					page: "mxlms-lesson-tabview",
					param1: courseId,
				},
				function (response) {
					jQuery("#mxlms-lesson-addon-tabview").html(response);
					// initCertificateLoader();
					jQuery(".mxlms-addon-tab-view-placeholder").addClass("mxlms-hidden");
					jQuery(".mxlms-addon-tab-view").removeClass("mxlms-hidden");
				}
			);
      jQuery("#mxlms-lesson-accordion").css("opacity", 1);
      jQuery("#mxlms-lesson-list-area").removeClass(
        "mxlms-custom-modal-body-placeholder"
      );
    }
  );
}

function initCertificateLoader() {
	var max = -219.99078369140625;
	forEach(
		document.querySelectorAll(".circular-progress"),
		function (index, value) {
			var percent = value.getAttribute("data-progress");
			value
				.querySelector(".fill")
				.setAttribute(
					"style",
					"stroke-dashoffset: " + ((100 - percent) / 100) * max
				);
			value.querySelector(".progress-value").innerHTML = percent + "%";
		}
	);
}
