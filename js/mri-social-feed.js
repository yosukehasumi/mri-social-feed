
var MRISocialFeed;(MRISocialFeed=function(){var t;return t=jQuery,t(document).on("click",".mri-tab-titles .mri-tab-title:not(.active)",function(e){var r,a,i;return e.preventDefault(),i=t(".mri-tab-titles .mri-tab-title"),a=t(".mri-tab-contents .mri-tab-content"),r=t(".mri-tab-contents .mri-tab-content[data-id="+t(this).data("id")+"]"),i.not(t(this)).removeClass("active"),a.not(r).removeClass("active"),t(this).addClass("active"),r.addClass("active")}),t(document).on("click",".mri-repeater-add-row-trigger",function(e){var r,a,i;return e.preventDefault(),r=t(this).closest(".mri-repeater"),a=t(r.find(".template-row")[0].outerHTML),a.removeClass("template-row"),i=(new Date).getTime(),a.find("input").each(function(){var e,r;return r=t(this).attr("data-scope"),e=t(this).attr("data-name"),t(this).attr("name",r+"["+i+"]["+e+"]")}),r.append(a)}),t(document).on("click",".mri-repeater-remove-row-trigger",function(e){return e.preventDefault(),t(this).closest("tr").remove()})})();