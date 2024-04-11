($=>{$.loadScript=function(e,t,i){2===arguments.length&&"function"==typeof arguments[1]&&(i=arguments[1],t={}),t=t||{};var n=$.Deferred();return"function"==typeof i&&n.done((()=>{i()})),((e,t,i)=>{var n=document.createElement("script");n.type="text/javascript",n.readyState?n.onreadystatechange=()=>{"loaded"!=n.readyState&&"complete"!=n.readyState||(n.onreadystatechange=null,i())}:n.onload=()=>{i()};var o=["type","src","htmlFor","event","charset","async","defer","crossOrigin","text","onerror"];if("object"==typeof t&&!$.isEmptyObject(t))for(var a in t)t.hasOwnProperty(a)&&$.inArray(a,o)&&(n[a]=t[a]);n.src=e,document.getElementsByTagName(t.lazyLoad?"body":"head")[0].appendChild(n)})(e,t,(()=>{n.resolve()})),n.promise()}})(jQuery),window.Themify_Metabox=function($){"use strict";var e={};function t(e){let t="";$('input[type="checkbox"]',e).each((function(){t+=$(this).attr("id")+"="+$(this).val()+"&"})),$('input[type="text"]',e).val(t.slice(0,-1))}return e.loadScript=(e,t,i)=>{i?t():$.loadScript(e).done((()=>{t()}))},e.init=()=>{window.addEventListener("load",e.document_ready,{passive:!0,once:!0})},e.document_ready=function(){e.init_metabox_tabs(),e.gallery_shortcode(),e.repeater(),e.enable_toggle(),e.query_category(),e.post_meta_checkbox(),e.init_fields($("body")),e.pagination($("body")),e.page_layout_field(),$("body").on("click",".themify_clear_field",(function(e){e.preventDefault(),$(this).addClass("hide").closest(".themify_field_row").find(".themify_upload_field").val("").end().find(".themify_upload_preview").fadeOut()}))},e.init_metabox_tabs=()=>{const e=document.getElementsByClassName("themify-meta-box-tabs");e.length&&[...e].forEach((e=>{if(e.querySelectorAll(".ilc-htabs li").length>1){ThemifyTabs({ilctabs:"#"+e.id});let t=e.getElementsByClassName("default_active")[0];t&&t.click()}else e.querySelector(".ilc-tab").style.display="block"}))},e.init_fields=t=>{e.layout(t),e.color_picker(t),t.find(".themifyDatePicker").length&&e.loadScript(TF_Metabox.includes_url+"js/jquery/ui/datepicker.min.js",(()=>{e.loadScript(TF_Metabox.includes_url+"js/jquery/ui/slider.min.js",(()=>{e.loadScript(TF_Metabox.url+"js/jquery-ui-timepicker.min.js",(()=>{e.date_picker(t)}),void 0!==$.ui.timepicker)}),void 0!==$.fn.slider)}),void 0!==$.fn.datepicker),e.assignments(t),e.dropdownbutton(t),e.togglegroup(t),$(document).triggerHandler("themify_metabox_init_fields",[e])},e.repeater=function(){$("body").on("click",".themify-repeater-add",(function(t){t.preventDefault();var i=$(this).closest(".themify_field_row"),n=i.find(".themify-repeater-rows"),o=i.find(".themify-repeater-template").html(),a=1;n.find("> div").length&&(n.find("> div").each((function(){a=Math.max(a,$(this).data("id"))})),++a);var s=$(o.replace(/__i__/g,a));s.find(".ajaxnonceplu").attr("id",""),n.append(s),s.has(".plupload-upload-uic").length&&s.find(".plupload-upload-uic").each((function(){themify_create_pluploader($(this))})),e.init_fields(n.find(".themify-repeater-row:last-child"))})).on("click",".themify-repeater-remove-row",(function(e){e.preventDefault(),$(this).parent().remove()}))},e.color_picker=function(e){"function"==typeof $.fn.tfminicolors&&(e.find(".colorSelectInput").each((function(){var e={},t=$(this).data("format");"rgba"==t?(e.format="rgb",e.opacity=!0):"rgb"==t&&(e.format="rgb"),$(this).tfminicolors(e)})),e.find(".clearColor").on("click",(function(){$(this).parent().find(".colorSelectInput").tfminicolors("value",""),$(this).parent().find(".colorSelectInput").val("")})))},e.date_picker=function(e){e.find(".themifyDatePicker").each((function(){var e=$(this),t=e.data("label"),i=e.data("close"),n=e.data("dateformat"),o=e.data("timeformat"),a=e.data("timeseparator");$.fn.datetimepicker.call(e,{showOn:"both",showButtonPanel:!0,closeButton:i,buttonText:t,dateFormat:n,timeFormat:o,stepMinute:5,firstDay:e.data("first-day"),separator:a,onClose(t){""!=t&&$("#"+e.data("clear")).addClass("themifyFadeIn")},beforeShow(){$("#ui-datepicker-div").addClass("themifyDateTimePickerPanel")}}),e.next().addClass("button")})),e.find(".themifyClearDate").on("click",(function(e){e.preventDefault();var t=$(this);$("#"+t.data("picker")).val("").trigger("change"),t.removeClass("themifyFadeIn")}))},e.assignments=function(e){e.find(".themify-assignments, .themify-assignment-inner-tabs").tabs(),e.on("change",'.themify-assignments input[type="checkbox"]',(function(){var e=$(this);e.is(":checked")?e.closest(".themify-assignments").find(".values").append('<input type="hidden" name="'+e.attr("data-name")+'" value="on" />'):e.closest(".themify-assignments").find('.values input[type="hidden"][name="'+e.attr("data-name")+'"]').remove()}))},e.layout=function(e){e.find(".preview-icon").each((function(){var e,t=$(this),i=t.parent(),n=i.find(".val"),o="",a="";if(t.closest(".group-hide").length>0?(a="theme-settings",e=t.closest(".group-hide"),o=e.data("hide")):t.closest(".themify_field_row").length>0&&(a="custom-panel",void 0!==(e=t.closest(".themify_field_row")).data("hide")&&(o=e.data("hide"))),t.on("click",(s=>{if(s.preventDefault(),i.find(".selected").removeClass("selected"),t.addClass("selected"),n.val(t.find("img").attr("alt")).trigger("change"),""!==o)if("custom-panel"==a){var l=e.nextUntil("[data-hide]");l.add(l.find(".themify_field .hide-if")).not(".toggled-off").filter("."+o.replace(/\s/g,",.")).show().filter("."+n.val()).hide()}else"theme-settings"==a&&e.find(".hide-if").filter("."+o.replace(/\s/g,",.")).show().filter("."+n.val()).hide()})),""!==o)if("custom-panel"==a){var s=e.nextUntil("[data-hide]");s.add(s.find(".themify_field .hide-if")).not(".toggled-off").filter("."+o.replace(/\s/g,",.")).filter("."+n.val()).hide()}else"theme-settings"==a&&e.find(".hide-if").filter("."+o.replace(/\s/g,",.")).show().filter("."+n.val()).hide()})),e.find(".themify_field .preview-icon").on("click",(function(e){e.preventDefault(),$(this).parent().find(".selected").removeClass("selected"),$(this).addClass("selected"),$(this).parent().find(".val").val($(this).find("img").attr("alt")).trigger("change")})),e.find(".themify_field_row[data-hide]").each((function(){var e,t,i=$(this).data("hide");"string"==typeof i&&(i=i.split(" ")).length>1&&(e=(e=i.shift()).split("|"),t=$("."+i.join(", .")),$("select, input",this).on("change",(function(){var i=$(this).val();!e.includes(i)&&t.is(":visible")||t.toggle(!e.includes(i))})).trigger("change"))}))},e.dropdownbutton=function(e){e.find(".dropdownbutton-group").each((function(){var e=$(this);e.on("mouseenter mouseleave",".dropdownbutton-list",(function(e){e.preventDefault();var t=$(this);if(t.hasClass("disabled"))return!1;"mouseenter"===e.type?t.children(".dropdownbutton").is(":visible")||t.children(".dropdownbutton").show():"mouseleave"===e.type&&t.children(".dropdownbutton").is(":visible")&&t.children(".dropdownbutton").hide()})).on("click",".first-ddbtn a",(e=>{e.preventDefault()})).on("click",".ddbtn a",(function(t){t.preventDefault();var i=$(this).find("img").attr("src"),n=$(this).data("val"),o=$(this).closest(".dropdownbutton-list"),a=o.attr("id");if($(this).closest(".dropdownbutton-list").find(".first-ddbtn img").attr("src",i),$(this).closest(".dropdownbutton").hide(),$("input#"+a).val(n),o.next().hasClass("ddbtn-all")){var s,l;if(e.hasClass("multi-ddbtn"))s=$(".multi-ddbtn-sub",e.parent().parent()),l=$(".multi-ddbtn-sub + input",e.parent().parent());else{var c=o.next();s=c.prev().siblings(".dropdownbutton-list"),l=c.siblings("input")}"yes"==o.next().val()?(s.addClass("disabled opacity-5"),s.each((function(){var e=$(this).data("def-icon");$(this).find(".first-ddbtn img").attr("src",e)})),l.val("")):s.removeClass("disabled opacity-5")}}));var t=e.find("input.ddbtn-all");"yes"===t.val()&&(e.hasClass("multi-ddbtn")?$(".multi-ddbtn-sub",e.parent().parent()).addClass("disabled opacity-5"):t.prev().siblings(".dropdownbutton-list").addClass("disabled opacity-5"))}))},e.togglegroup=function(e){e.find(".themify_toggle_group_wrapper").each((function(){var e=$(this);e.find(".themify_toggle_group_inner").hide(),e.on("click",".themify_toggle_group_label",(function(e){var t=$(this),i=t.find("i"),n=t.next();n.hasClass("is-activated")?(i.removeClass("ti-minus").addClass("tf_plus_icon"),n.hide().removeClass("is-activated")):(n.show().addClass("is-activated"),i.removeClass("tf_plus_icon").addClass("ti-minus")),e.preventDefault()}))}))},e.gallery_shortcode=function(){var e,t=wp.media.gallery.shortcode,i=wp.media.gallery;$("body").on("click",".themify-gallery-shortcode-btn",(function(n){var o=$(this).closest(".themify_field").find(".themify-gallery-shortcode-input");o.html()&&(o.val(o.html()),o.html(""),o.text("")),e?e.open():e=$.trim(o.val()).length>0?i.edit($.trim(o.val())):wp.media.frames.file_frame=wp.media({frame:"post",state:"gallery-edit",title:wp.media.view.l10n.editGalleryTitle,editing:!0,multiple:!0,selection:!1}),wp.media.gallery.shortcode=e=>{var i=e.props.toJSON(),n=_.pick(i,"orderby","order");e.gallery&&_.extend(n,e.gallery.toJSON()),n.ids=e.pluck("id"),i.uploadedTo&&(n.id=i.uploadedTo),n.t&&(n.orderby="rand"),delete n.t,n.ids&&"post__in"===n.orderby&&delete n.orderby,_.each(wp.media.gallery.defaults,((e,t)=>{e===n[t]&&delete n[t]}));var a=new wp.shortcode({tag:"gallery",attrs:n,type:"single"});return o.val(a.string()),wp.media.gallery.shortcode=t,a},e.on("update",(e=>{var t=wp.media.gallery.shortcode(e).string().slice(1,-1);o.val("["+t+"]")})),o.val().trim()&&$(".media-menu").find(".media-menu-item").last().trigger("click"),n.preventDefault()}))},e.enable_toggle=function(){var e=$(".enable_toggle");e.length>0&&e.each((function(){var e=$(this).closest(".themify_write_panel");e.length||(e=$(this).closest("form")),$(".themify-toggle",e).hide().addClass("toggled-off")})),$(".enable_toggle .preview-icon").on("click",(function(e){var t=$(this).find("img").attr("alt"),i=""!=$.trim(t)?"."+t+"-toggle":".default-toggle";$(this).closest(".inside").find(".themify-toggle").hide().addClass("toggled-off"),$(this).closest(".inside").find(i).show().removeClass("toggled-off"),e.preventDefault()})),$(".enable_toggle .preview-icon.selected").each((function(){var e=$(this).find("img").attr("alt");$(""!=e&&"default"!=e?"."+e+"-toggle":".default-toggle").show().removeClass("toggled-off")})),$(".enable_toggle input[type=radio]").on("click",(function(){var e=$(this).val(),t=0!=e&&""!=e?"."+e+"-toggle":".default-toggle";$(this).siblings("input[type=radio]").each((function(){var e=$(this).val();0!=e&&""!==e&&$("."+e+"-toggle").hide().addClass("toggled-off")})),$(t).each((function(){($(this).show().removeClass("toggled-off"),$(this).hasClass("enable_toggle_child"))&&$(this).find("input[type=radio]:checked").siblings("input[type=radio]").each((function(){var e=$(this).val();setTimeout((()=>{0!=e&&""!==e&&$("."+e+"-toggle").hide().addClass("toggled-off")}),500)}))}))})),e.each((function(){var e=$(this).find('input[type="radio"]:checked').val();$(0!=e&&""!==e?"."+e+"-toggle":".default-toggle").each((function(){($(this).show().removeClass("toggled-off"),$(this).hasClass("enable_toggle_child"))&&$(this).find("input[type=radio]:checked").siblings("input[type=radio]").each((function(){var e=$(this).val();setTimeout((()=>{0!=e&&""!==e&&$("."+e+"-toggle").hide().addClass("toggled-off")}),500)}))}))})),$('.enable_toggle input[type="checkbox"]').on("click",(function(){var e=$(this).data("val"),t=0!=e&&""!=e?"."+e+"-toggle":".default-toggle";$(this).closest(".inside").find(".themify-toggle").hide().addClass("toggled-off"),$(this).prop("checked")&&$(this).closest(".inside").find(t).show().removeClass("toggled-off")})),$('.enable_toggle input[type="checkbox"]:checked').each((function(){var e=$(this).data("val");$(0!=e&&""!==e?"."+e+"-toggle":".default-toggle").show().removeClass("toggled-off")}))},e.query_category=function(){var e=$(".themify_field"),t=$(".themify-info-link");function i(e,t){""!=t?e.closest(".inside").find(".themify_field_row").removeClass("query-field-hide"):e.closest(".inside").find(".themify_field_row").not(e.closest(".themify_field_row")).not(".query-field-visible").addClass("query-field-hide")}e.find(".query_category").on("blur",(function(){var e=$(this),t=e.val();$(this).parent().find(".val").val(t),i(e,t)})).on("keyup",(function(){var e=$(this),t=e.val();$(this).parent().find(".val").val(t),i(e,t)})),e.find(".query_category_single").on("change",(function(){var e=$(this),t=e.val();e.parent().find(".query_category, .val").val(t),i(e,t)})).closest(".themify_field_row").addClass("query-field-visible"),t.closest(".themify_field_row").addClass("query-field-visible"),$(".query_category_single, .query_category").each((function(){var e=$(this),t=e.val();i(e,t)})),t.closest(".themify_field_row").removeClass("query-field-hide")},e.post_meta_checkbox=function(){$(".post-meta-group").each((function(){var e=$(this);$(".meta-all",e).prop("checked")&&$(".meta-sub",e).prop("disabled",!0).parent().addClass("opacity-7"),e.on("click",".meta-all",(function(){$(this).prop("checked")?$(".meta-sub",e).prop("disabled",!0).prop("checked",!1).parent().addClass("opacity-7"):$(".meta-sub",e).prop("disabled",!1).parent().removeClass("opacity-7")}))})),$(".custom-post-meta-group").each((function(){var e=$(this),i=$('input[type="text"]',e).val(),n={},o=[],a=[];if("yes"===i)$(".meta-all",e).val("yes").prop("checked",!0),$(".meta-sub",e).val("yes").prop("disabled",!0).parent().addClass("opacity-7");else{a=i.split("&");for(var s=0;s<a.length;s++)n[(o=a[s].split("="))[0]]=o[1];for(var l in n)"yes"===n[l]&&$("#"+l,e).val("yes").prop("checked",!0);$(".meta-all",e).prop("checked")&&$(".meta-sub",e).prop("disabled",!0).prop("checked",!1).parent().addClass("opacity-7")}e.on("click",".meta-all",(function(){var i=$(this);i.prop("checked")?($(".meta-sub",e).val("yes").prop("disabled",!0).prop("checked",!1).parent().addClass("opacity-7"),i.val("yes")):($(".meta-sub",e).val("no").prop("disabled",!1).parent().removeClass("opacity-7"),i.val("no")),t(e)})).on("click",".meta-sub",(function(){var i=$(this);i.prop("checked")?i.val("yes"):i.val("no"),t(e)}))}))},e.pagination=function(e){e.on("click","#themify_assignments_popup_show .themify-popup-visibility-tab",(function(e){e.preventDefault();var t=$(this);if(!t.data("active")){var i=t.data("type"),n=t.parents("#themify_assignments_popup_show").find(".themify-assignment-type-options[data-type="+i+"]"),o=$("#popup_show-assignment-tab-pages").data("post-id");$.ajax({url:ajaxurl,type:"post",data:{action:"themify_create_inner_popup_page",type:i,post_id:o},beforeSend(){},success(e){n.html(e),t.data("active","on")}})}})),e.on("click",".themify-assignment-pagination .page-numbers",(function(e){e.preventDefault();var t=$(this),i=t.parents(".themify-assignment-options"),n=t.parents(".themify-assignment-items-inner"),o=$(".themify-assignment-pagination",n),a=parseFloat($(".themify-assignment-pagination .current",n).text()),s=$(".themify-assignment-items-inner",i),l=1;t.hasClass("next")?l=a+1:t.hasClass("prev")?l=a-1:t.hasClass("page-numbers")&&(l=parseFloat(t.text())),$.ajax({url:ajaxurl,type:"post",data:{action:"themify_create_popup_page_pagination",current_page:l,num_of_pages:n.data("pages")},beforeSend(){$(".tb_slider_loader",i).remove(),$(".themify-assignment-items-page",n).addClass("is-hidden"),o.hide(),s.append('<div class="tb_slider_loader"></div>')},success(e){$(".tb_slider_loader",i).remove(),$(".themify-assignment-items-page-"+l,n).removeClass("is-hidden"),o.html(e).show()}})}))},e.page_layout_field=()=>{const e=document.getElementsByClassName("themify_field-page_layout");if(e[0]){const i=e[0].querySelector('input[type="hidden"]'),n=document.getElementById("content_width"),o=document.getElementById("section_full_scrolling"),a=document.getElementById("hide_page_title"),s=document.getElementsByClassName("tf_section_scroll_setting"),l=e=>{const t="section_scroll"===e?"block":"none";for(let e=s.length-1;e>-1;--e)s[e].style.display=t;o&&(o.value="section_scroll"===e?"yes":"no"),n&&(n.value="section_scroll"===e||"full_width"===e?"full_width":""),"section_scroll"!==e&&"full_width"!==e||(i.value="sidebar-none",a&&(a.value="yes"))};var t=new MutationObserver(((e,t)=>{"value"==e[0].attributeName&&(t.disconnect(),l(i.value),t.observe(i,{attributes:!0}))}));l(i.dataset.selected),t.observe(i,{attributes:!0})}},e.init(),e}(jQuery);