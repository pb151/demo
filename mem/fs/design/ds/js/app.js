var app_mobile_view_w = 968;
var Metronic;
var alert_sound;
jQuery(document).ready(function() {
	Metronic = App; // set App object of Material Design
	Metronic.setAssetsPath(theme_path + 'global/');
	Metronic.setGlobalImgPath('img/');
	Metronic.setGlobalPluginsPath('plugins/');
	alert_sound = new Audio(design_path + 'images/alarm.mp3');

	// initialize Observer
	app_observer();

	// Check if user has access of QF
	$.post('/ajax.php?action=check_qf_access&nocache=' + new Date().getTime(), {
	}, function(response) {
		var obj = jQuery.parseJSON(response);
		if(obj.success) {
			ask_notification_permission();
		}
	});

	toastr.options = {
		"closeButton": true,
		"debug": false,
		"positionClass": "toast-top-center",
		"onclick": null,
		"showDuration": "1000",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}

	// apply ripple effect on links
	$("li.ripple a").click(function(e){
		var parent = $(this).parent();
		//create .ink element if it doesn't exist
		if(parent.find(".ink").length == 0)
			parent.prepend("<span class='ink'></span>");

		var ink = parent.find(".ink");
		//incase of quick double clicks stop the previous animation
		ink.removeClass("animate");

		//set size of .ink
		if(!ink.height() && !ink.width()) {
			//use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
			var d = Math.max(parent.outerWidth(), parent.outerHeight());
			ink.css({height: d, width: d});
		}

		//get click coordinates
		//logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
		var x = e.pageX - parent.offset().left - ink.width()/2;
		var y = e.pageY - parent.offset().top - ink.height()/2;

		//set the position and add class .animate
		ink.css({top: y+'px', left: x+'px'}).addClass("animate");
	})

	if ($.fn.datetimepicker && $.fn.datetimepicker.defaults) {
		$.fn.datetimepicker.defaults.weekStart = 1;
		$.fn.datetimepicker.defaults.format = app.format.datepicker.datetimeFormat;
		$.fn.datetimepicker.defaults.linkFormat = app.format.datepicker.datetimeFormat;
		$.fn.datetimepicker.defaults.todayHighlight = true;
		if (language_code == 'de_de' && $.fn.datetimepicker.dates['de']) {
			$.fn.datetimepicker.defaults.language = 'de';
		}
	}
	if ($.fn.datepicker && $.fn.datepicker.defaults) {
		$.fn.datepicker.defaults.weekStart = 1;
		$.fn.datepicker.defaults.format = app.format.datepicker.dateFormat;
		$.fn.datepicker.defaults.todayHighlight = true;
		if (language_code == 'de_de' && $.fn.datepicker.dates['de']) {
			$.fn.datepicker.defaults.language = 'de';
		}
	}

	// make all modal draggable
	if ($(".modal").length > 0) {
		$(".modal").draggable({
			handle: ".modal-header"
		});
	}

	// set focus on first element in all dialogs
	$('.modal').on('shown.bs.modal', function () {
		$(this).find('input, textarea, select')
			.not('input[type=hidden],input[type=button],input[type=submit],input[type=reset],input[type=image],button')
			.filter(':enabled:visible:first')
			.focus();
	});

	$(".sidebar_close").click(function() {
		if($('body').hasClass('page-quick-sidebar-open')) {
			$('body').toggleClass('page-quick-sidebar-open');
		}
	});

	// restrict numeric
	if($("input.digits").length > 0) {
		$("input.digits").numeric({
			decimal: false
		});
	}

	// extend data table redraw
	if($.fn.dataTableExt) {
		$.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
			if (oSettings.oFeatures.bServerSide === true) {
				var before = oSettings.oFeatures.bServerSide;

				oSettings.oFeatures.bServerSide = false;
				oSettings.oApi._fnReDraw(oSettings);

				oSettings.oFeatures.bServerSide = before;
			} else {
				oSettings.oApi._fnDraw(oSettings);
			}
		};
	}

	// extend data table redraw
	if($.fn.dataTableExt) {
		$.fn.dataTableExt.oApi.fnKeepPageRedraw = function(oSettings) {
			if (oSettings.oFeatures.bServerSide === true) {
				var beforeDispstart = oSettings._iDisplayStart;

				oSettings.oApi._fnReDraw(oSettings);

				oSettings._iDisplayStart = beforeDispstart;
				oSettings.oApi._fnCalculateEnd(oSettings);
				oSettings.oApi._fnDraw(oSettings);
			} else {
				oSettings.oApi._fnDraw(oSettings);
			}
		};
	}

	// never let browser update password field
	setTimeout(function() {
		$('input[type=password]').attr('value', '');
	}, 100);

	$('.sysops_menu').hover(function() {
        var dropdown_offset_top = $(this).closest("li.portal-dropdown-list").offset().top + $(this).closest("li.portal-dropdown-list").outerHeight() + $(this).closest("li.portal-dropdown-list").find("div.dropdown-content").outerHeight();

        var dropdown_height = $(this).closest("li.portal-dropdown-list").find("div.dropdown-content").outerHeight();

        if (dropdown_offset_top > $("body").outerHeight()) {
        	$(this).closest("li.portal-dropdown-list").find("div.dropdown-content").css('top', -dropdown_height);
        }

    }, function() {
    });
});

function process_form(options) {
	var defaultOptions = {
		formName: '',
		ajaxSource: '',
		redirectPage: '',
		reloadTable: '',
		applybtn: '',
		rules: {},
		messages: {},
		beforePost: '',
		beforeProcessing: '',
		callback: '',
		loadingMessage: '',
		showValidateMessage: true,
		iframe: false,
	}
	var opts;
	if (typeof options != "object") {
		opts = $.extend({}, defaultOptions);
	} else {
		opts = $.extend({}, defaultOptions, options);
	}

	// check if apply button is in form
	if (opts.applybtn != '') {
		var input = $("<input>").attr("type", "hidden").attr("name", "is_apply").attr("id", "is_apply").val("0");
		$("#" + opts.formName).append($(input));

		$("#" + opts.applybtn).click(function() {
			$("#is_apply").val(1);
		});
	}

	// attach form processing
	if (opts.formName != '' && $("#" + opts.formName) && opts.ajaxSource != '') {
		$("#" + opts.formName).validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: null,
			ignore: 'input[type="hidden"]',
			ignore: ':hidden',
			ignore: ":hidden:not(.bs-select)",
			rules: opts.rules,
			iframe: true,
			messages: opts.messages,
			invalidHandler: function(event, validator) { //display error alert on form submit
				if($('.show-error-message', $("#" + opts.formName)).length > 0) $('.show-error-message', $("#" + opts.formName)).show();
				$('.alert-danger', $("#" + opts.formName)).show();
			},
			highlight: function(element) { // hightlight error inputs
				$(element).closest('.form-group').addClass('has-error'); // set error class to the control group

				// set different highlight stuff for login
				if($('body').hasClass('login')) {
					$(element).closest('div').addClass('has-error');
				}
			},
			success: function(label) {
				label.closest('.form-group').removeClass('has-error');

				// set different highlight stuff for login
				if($('body').hasClass('login')) {
					label.closest('div').removeClass('has-error');
				}

				label.remove();
			},
			errorPlacement: function(error, element) {
				if(opts.showValidateMessage) {
					if (element.hasClass('bs-select')) { // fix for bootstrap select
						if(element.closest('div').hasClass('input-group')) {
							error.insertAfter(element.closest('div'));
						} else {
							error.insertAfter(element.next());
						}
					} else {
						if(element.closest('div').hasClass('input-group')) {
							error.insertAfter(element.closest('div'));
						} else {
							error.insertAfter(element);
						}
					}
				}
			},
			submitHandler: function(form) {
				if (typeof opts.beforePost == 'function') {
					opts.beforePost();
				}
				$(form).ajaxSubmit({
					url: opts.ajaxSource + '&nocache=' + new Date().getTime(),
					beforeSubmit: function() {
						var ret = true;
						if (typeof opts.beforeProcessing == 'function') {
							ret = opts.beforeProcessing();
						}
						if (ret) {
							loadMask({
								flag: true,
								message: opts.loadingMessage
							});
						} else
							return false;
					},
					success: function(responseText) {
						try {
							var obj = jQuery.parseJSON(responseText);
							if (obj.Error) {
								loadMask({
									flag: false
								});
								toastr['error'](obj.error_message)
							} else {
								win_unload_warning = false;
								if (opts.applybtn != '' && $("#is_apply").val() == 1) {
									if (obj.new_window_page) {
										window.open(obj.new_window_page);
									}
									if (obj.redirect_page) {
										location.href = obj.redirect_page;
									} else {
										location.reload();
									}
								} else {
									if (opts.redirectPage != '') {
										location.href = opts.redirectPage;
										return;
									} else if (opts.reloadTable != '') {
										loadMask({
											flag: false
										});

										var oTable = $("#" + opts.reloadTable);
										oTable.dataTable().fnDraw();
									} else if (typeof opts.callback == 'function') {
										opts.callback(responseText);
									} else {
										loadMask({
											flag: false
										});
										toastr['success'](app.lang.success_message)
									}
								}
							}
						} catch (ex) {
							loadMask({
								flag: false
							});
							toastr['error'](app.lang.error_message.server_not_responding)
						}
					}
				});
			}
		});
	}
}

function loadMask(options) {
	var defaultOptions = {
		target: '',
		message: app.lang.loading,
		flag: false,
		animate: false
	}
	var opts;
	if (typeof options != "object") {
		opts = $.extend({}, defaultOptions);
	} else {
		opts = $.extend({}, defaultOptions, options);
	}
	if (opts.flag) {
		if (opts.target) { // element blocking
			Metronic.blockUI({
				target: opts.target,
				message: opts.message,
				zIndex: 9999999,
				overlayColor: '#000',
				boxed: opts.animate ? false : true,
				animate: opts.animate
			});
		} else { // page blocking
			Metronic.blockUI({
				message: opts.message,
				zIndex: 9999999,
				overlayColor: '#000',
				boxed: true
			});
		}
	} else {
		if (opts.target) {
			Metronic.unblockUI(opts.target);
		} else {
			Metronic.unblockUI();
		}
	}
}


/**
 * scrollType 0: Horizontal only; 1: Vertical only; 2: Both
 */
function applyTableScroller(table_id, scrollType) {
	var wrapper = $("#" + table_id + '_wrapper div.table-scrollable');
	if(typeof(scrollType) == 'undefined') scrollType = 0;

	if(wrapper.length > 0) {
		if(!wrapper.hasClass('scroller')) {
			wrapper.addClass('scroller');
			if(scrollType == 0 || scrollType == 2) {
				$("#" + table_id + '_wrapper').addClass('scrollerH');
			}
			if(scrollType == 1 || scrollType == 2) {
				$("#" + table_id + '_wrapper').addClass('scrollerV');
			}
		} else {
			if(scrollType == 0 || scrollType == 2) { // either horizontal only or both
				wrapper.slimScrollH({
					destroy: true
				});
			}
			if(scrollType == 0 || scrollType == 1) { // either vertical only or both
				wrapper.slimScroll({
					destroy: true
				});
			}
		}

		if(Metronic.getViewPort().width <= app_mobile_view_w) {
			wrapper.removeClass('scroller');
			wrapper.css('height', '');
			wrapper.css('width', '');
			return;
		}

		var width = $("#" + table_id).closest('.table-container').actual('width');

		var height = $("#" + table_id).actual('height');
		if(height == 0) {
			height = 87; // min height of 2 rows
		}
		if(width == 0) {
			width = $("#" + table_id).closest('.portlet-body').actual('width');
		}

		if(scrollType == 0 || scrollType == 2) { // either horizontal only or both
			if(scrollType == 2) {
				if($("#" + table_id).attr('init-height')) {
					height = parseInt($("#" + table_id).attr('init-height'));
					wrapper.css('height', height + 20);
				} else {
					var height = $(".page-footer").offset().top - wrapper.offset().top;
					height = height - $("#" + table_id + "_info").parent().parent().outerHeight(); // reduce height of info bar
					height = height - 40; // reduce padding of outer panel
				}
				if(wrapper.height() > height || wrapper.hasClass('vscroller')) {
					wrapper.addClass('vscroller');
					wrapper.slimScroll({
						height: height,
						alwaysVisible: 1
					});
				}
			}
			if(wrapper.hasClass('vscroller')) {
				wrapper.slimScrollH({
					height: height,
					width: width,
					alwaysVisible: 1,
					position: 'bottom'
				});
			} else {
				wrapper.slimScrollH({
					height: height+20,
					width: width,
					alwaysVisible: 1,
					position: 'bottom'
				});
			}
			wrapper.css('height', wrapper.height()-20);
			wrapper.css('width', wrapper.width()-10);
		}

		if(scrollType == 1) { // vertical only
			if($("#" + table_id).attr('init-height')) {
				height = parseInt($("#" + table_id).attr('init-height'));
				wrapper.css('height', height + 20);
			} else {
				var height = $(".page-footer").offset().top - wrapper.offset().top;
				height = height - $("#" + table_id + "_info").parent().parent().outerHeight(); // reduce height of info bar
				height = height - 40; // reduce padding of outer panel
			}

			if(wrapper.height() > height || wrapper.hasClass('vscroller')) {
				wrapper.addClass('vscroller');
				wrapper.slimScroll({
					height: height,
					alwaysVisible: 1
				});
				wrapper.css('height', height-20);
				wrapper.css('width', wrapper.width()-10);
				if(typeof($.fn.dataTable.FixedHeader) != 'undefined') {
					new $.fn.dataTable.FixedHeader($("#" + table_id).dataTable());
				}
			}
		}
	}
}






function app_observer() {
	$.ajax({
		type: "POST",
		url: 'ajax.php?action=observer&nocache=' + new Date().getTime(),
		cache: false,
		success: function(responseText){
			var main_obj = jQuery.parseJSON(responseText);
			if(!main_obj.Error) {
				setTimeout("app_observer()", 10000);

				for (var system_key in main_obj) {
					var obj = main_obj[system_key];

					if(typeof obj.new_portals != 'undefined' && parseInt(obj.new_portals.count) > 0) {
						$('#list-' + system_key + ' .new_portal_badge').removeClass('hidden');
						$('#list-' + system_key + ' .new_portal_badge').html(obj.new_portals.count);
					} else {
						$('#list-' + system_key + ' .new_portal_badge').addClass('hidden');
					}

					if(typeof obj.broken_portal != 'undefined' && parseInt(obj.broken_portal.count) > 0) {
						$('#list-' + system_key + ' .broken_portal_badge').removeClass('hidden');
						$('#list-' + system_key + ' .broken_portal_badge').html(obj.broken_portal.count);
					} else {
						$('#list-' + system_key + ' .broken_portal_badge').addClass('hidden');
					}

					if(typeof obj.portal_init != 'undefined' && parseInt(obj.portal_init.count) > 0) {
						$('#list-' + system_key + ' .portal_init_badge').removeClass('hidden');
						$('#list-' + system_key + ' .portal_init_badge').html(obj.portal_init.count);
					} else {
						$('#list-' + system_key + ' .portal_init_badge').addClass('hidden');
					}

					if(typeof obj.failed_portals != 'undefined' && parseInt(obj.failed_portals.count) > 0) {
						$('#list-' + system_key + ' .new_login_badge').removeClass('hidden');
						$('#list-' + system_key + ' .new_login_badge').html(obj.failed_portals.count);
					} else {
						$('#list-' + system_key + ' .new_login_badge').addClass('hidden');
					}

					if(typeof obj.mail_import_rule != 'undefined' && parseInt(obj.mail_import_rule.count) > 0) {
						$('#list-' + system_key + ' .mail_import_rule_badge').removeClass('hidden');
						$('#list-' + system_key + ' .mail_import_rule_badge').html(obj.mail_import_rule.count);
					} else {
						$('#list-' + system_key + ' .mail_import_rule_badge').addClass('hidden');
					}

					if(typeof obj.test_portal_script != 'undefined' && parseInt(obj.test_portal_script.count) > 0) {
						$('#list-' + system_key + ' .test_portal_script_badge').removeClass('hidden');
						$('#list-' + system_key + ' .test_portal_script_badge').html(obj.test_portal_script.count);
					} else {
						$('#list-' + system_key + ' .test_portal_script_badge').addClass('hidden');
					}

					if(typeof obj.invoice_review != 'undefined' && parseInt(obj.invoice_review.count) > 0) {
						$('#list-' + system_key + ' .inv_review_badge').removeClass('hidden');
						$('#list-' + system_key + ' .inv_review_badge').html(obj.invoice_review.count);
					} else {
						$('#list-' + system_key + ' .inv_review_badge').addClass('hidden');
					}

					if(typeof obj.sysops != 'undefined' && parseInt(obj.sysops.count) > 0) {
						$('#list-' + system_key + ' .sysops_badge').removeClass('hidden');
						$('#list-' + system_key + ' .sysops_badge').html(obj.sysops.count);
					} else {
						$('#list-' + system_key + ' .sysops_badge').addClass('hidden');
					}

					if(typeof obj.aws_servers != 'undefined' && parseInt(obj.aws_servers.count) > 0) {
						$('#list-' + system_key + ' .aws_server_badge').removeClass('hidden');
						$('#list-' + system_key + ' .aws_server_badge').html(obj.aws_servers.count);
					} else {
						$('#list-' + system_key + ' .aws_server_badge').addClass('hidden');
					}
					
					if(typeof obj.aws_users != 'undefined' && parseInt(obj.aws_users.count) > 0) {
						$('#list-' + system_key + ' .aws_users_badge').removeClass('hidden');
						$('#list-' + system_key + ' .aws_users_badge').html(obj.aws_users.count);
					} else {
						$('#list-' + system_key + ' .aws_users_badge').addClass('hidden');
					}

					if(typeof obj.security_groups != 'undefined' && parseInt(obj.security_groups.count) > 0) {
						$('#list-' + system_key + ' .security_groups_badge').removeClass('hidden');
						$('#list-' + system_key + ' .security_groups_badge').html(obj.security_groups.count);
					} else {
						$('#list-' + system_key + ' .security_groups_badge').addClass('hidden');
					}
					
					if(typeof obj.aws_buckets != 'undefined' && parseInt(obj.aws_buckets.count) > 0) {
						$('#list-' + system_key + ' .aws_buckets_badge').removeClass('hidden');
						$('#list-' + system_key + ' .aws_buckets_badge').html(obj.aws_buckets.count);
					} else {
						$('#list-' + system_key + ' .aws_buckets_badge').addClass('hidden');
					}

					if(typeof obj.cron_monitoring != 'undefined' && parseInt(obj.cron_monitoring.count) > 0) {
						$('#list-' + system_key + ' .failed_cron_badge').removeClass('hidden');
						$('#list-' + system_key + ' .failed_cron_badge').html(obj.cron_monitoring.count);
					} else {
						$('#list-' + system_key + ' .failed_cron_badge').addClass('hidden');
					}

					if(typeof obj.disc_monitoring != 'undefined' && parseInt(obj.disc_monitoring.count) > 0) {
						$('#list-' + system_key + ' .failed_dm_badge').removeClass('hidden');
						$('#list-' + system_key + ' .failed_dm_badge').html(obj.disc_monitoring.count);
					} else {
						$('#list-' + system_key + ' .failed_dm_badge').addClass('hidden');
					}

					if(typeof obj.snapshot_monitoring != 'undefined' && parseInt(obj.snapshot_monitoring.count) > 0) {
						$('#list-' + system_key + ' .failed_sm_badge').removeClass('hidden');
						$('#list-' + system_key + ' .failed_sm_badge').html(obj.snapshot_monitoring.count);
					} else {
						$('#list-' + system_key + ' .failed_sm_badge').addClass('hidden');
					}

					if(typeof obj.mysql_backup_monitoring != 'undefined' && parseInt(obj.mysql_backup_monitoring.count) > 0) {
						$('#list-' + system_key + ' .failed_bm_badge').removeClass('hidden');
						$('#list-' + system_key + ' .failed_bm_badge').html(obj.mysql_backup_monitoring.count);
					} else {
						$('#list-' + system_key + ' .failed_bm_badge').addClass('hidden');
					}

					if(typeof obj.script_tickets != 'undefined' && parseInt(obj.script_tickets.count) > 0) {

						$('#list-' + system_key + ' .script_tickets_badge').removeClass('hidden');
						$('#list-' + system_key + ' .script_tickets_badge').html(obj.script_tickets.count);
					} else {
						$('#list-' + system_key + ' .script_tickets_badge').addClass('hidden');
					}

					if(typeof obj.manual_processes != 'undefined' && parseInt(obj.manual_processes.count) > 0) {
						$('#list-' + system_key + ' .manual_processes_badge').removeClass('hidden');
						$('#list-' + system_key + ' .manual_processes_badge').html(obj.manual_processes.count);
					} else {
						$('#list-' + system_key + ' .manual_processes_badge').addClass('hidden');
					}

					if(typeof obj.live_recording != 'undefined' && parseInt(obj.live_recording.count) > 0) {
						$('#list-' + system_key + ' .live_recording_badge').removeClass('hidden');
						$('#list-' + system_key + ' .live_recording_badge').html(obj.live_recording.count);
					} else {
						$('#list-' + system_key + ' .live_recording_badge').addClass('hidden');
					}

					if(typeof obj.corrections_invoices != 'undefined' && parseInt(obj.corrections_invoices.count) > 0) {
						$('#list-' + system_key + ' .correction_badge').removeClass('hidden');
						$('#list-' + system_key + ' .correction_badge').html(obj.corrections_invoices.count);
					} else {
						$('#list-' + system_key + ' .correction_badge').addClass('hidden');
					}

					if(typeof obj.correction_remittances != 'undefined' && parseInt(obj.correction_remittances.count) > 0) {
						$('#list-' + system_key + ' .correction_remittances_badge').removeClass('hidden');
						$('#list-' + system_key + ' .correction_remittances_badge').html(obj.correction_remittances.count);
					} else {
						$('#list-' + system_key + ' .correction_remittances_badge').addClass('hidden');
					}

					if(typeof obj.corrections_invoices_line_items != 'undefined' && parseInt(obj.corrections_invoices_line_items.count) > 0) {
						$('#list-' + system_key + ' .corrections_invoices_line_items_badge').removeClass('hidden');
						$('#list-' + system_key + ' .corrections_invoices_line_items_badge').html(obj.corrections_invoices_line_items.count);
					} else {
						$('#list-' + system_key + ' .corrections_invoices_line_items_badge').addClass('hidden');
					}

					if(typeof obj.corrections_invoices_payment_method != 'undefined' && parseInt(obj.corrections_invoices_payment_method.count) > 0) {
						$('#list-' + system_key + ' .corrections_invoices_payment_method_badge').removeClass('hidden');
						$('#list-' + system_key + ' .corrections_invoices_payment_method_badge').html(obj.corrections_invoices_payment_method.count);
					} else {
						$('#list-' + system_key + ' .corrections_invoices_payment_method_badge').addClass('hidden');
					}

					if(typeof obj.corrections_statement != 'undefined' && parseInt(obj.corrections_statement.count) > 0) {
						$('#list-' + system_key + ' .correction_statement_badge').removeClass('hidden');
						$('#list-' + system_key + ' .correction_statement_badge').html(obj.corrections_statement.count);
					} else {
						$('#list-' + system_key + ' .correction_statement_badge').addClass('hidden');
					}

					if(typeof obj.correction_securities != 'undefined' && parseInt(obj.correction_securities.count) > 0) {
						$('#list-' + system_key + ' .correction_securities_badge').removeClass('hidden');
						$('#list-' + system_key + ' .correction_securities_badge').html(obj.correction_securities.count);
					} else {
						$('#list-' + system_key + ' .correction_securities_badge').addClass('hidden');
					}

					if(typeof obj.corrections_fino_kws != 'undefined' && parseInt(obj.corrections_fino_kws.count) > 0) {
						$('#list-' + system_key + ' .correction_fino_kws_badge').removeClass('hidden');
						$('#list-' + system_key + ' .correction_fino_kws_badge').html(obj.corrections_fino_kws.count);
					} else {
						$('#list-' + system_key + ' .correction_fino_kws_badge').addClass('hidden');
					}

					if(typeof obj.live_validation != 'undefined' && parseInt(obj.live_validation.count) > 0) {
						$('#list-' + system_key + ' .live_view_badge').removeClass('hidden');
						$('#list-' + system_key + ' .live_view_badge').html(obj.live_validation.count);
					} else {
						$('#list-' + system_key + ' .live_view_badge').addClass('hidden');
					}
					
					if(typeof obj.background_queue != 'undefined' && parseInt(obj.background_queue.count) > 0) {
						$('#list-' + system_key + ' .background_queue_badge').removeClass('hidden');
						$('#list-' + system_key + ' .background_queue_badge').html(obj.background_queue.count);
					} else {
						$('#list-' + system_key + ' .background_queue_badge').addClass('hidden');
					}
					
					if(typeof obj.corrections_portal != 'undefined' && parseInt(obj.corrections_portal.count) > 0) {
						$('#list-' + system_key + ' .corrections_portal_badge').removeClass('hidden');
						$('#list-' + system_key + ' .corrections_portal_badge').html(obj.corrections_portal.count);
					} else {
						$('#list-' + system_key + ' .corrections_portal_badge').addClass('hidden');
					}

					if((typeof obj.live_validation != 'undefined' && parseInt(obj.live_validation.count) > 0) || (typeof obj.background_queue != 'undefined' && parseInt(obj.background_queue.count) > 0)) {
						$('#list-' + system_key + ' .live_validation_badge').removeClass('hidden');
						var total = parseInt(obj.live_validation.count);
						total = total + parseInt(obj.background_queue.count);
						$('#list-' + system_key + ' .live_validation_badge').html(total);
					} else {
						$('#list-' + system_key + ' .live_validation_badge').addClass('hidden');
					}

					if(typeof obj.company_validation != 'undefined' && parseInt(obj.company_validation.count) > 0) {
						$('#list-' + system_key + ' .company_validation_badge').removeClass('hidden');
						$('#list-' + system_key + ' .company_validation_badge').html(obj.company_validation.count);
					} else {
						$('#list-' + system_key + ' .company_validation_badge').addClass('hidden');
					}

					if(typeof obj.company_detection != 'undefined' && parseInt(obj.company_detection.count) > 0) {
						$('#list-' + system_key + ' .company_detection_badge').removeClass('hidden');
						$('#list-' + system_key + ' .company_detection_badge').html(obj.company_detection.count);
					} else {
						$('#list-' + system_key + ' .company_detection_badge').addClass('hidden');
					}

					if ($('#list-' + system_key + ' .sysops_menu').length > 0) {
						var $dropdown_content = $('#list-' + system_key + ' .sysops_menu').closest('.dropdown').find('.dropdown-content');
						var $notif_badges = $dropdown_content.find('.notif_badge');
						var badge_count_sum = 0;
						$notif_badges.each(function() {
		                	var $this = $(this);
		                	var badge_count = $this.text().trim();
		                	badge_count_sum += parseInt(badge_count);
		                });

		                if (badge_count_sum) {
		                	$('#list-' + system_key + ' .sysops_menu_badge').removeClass('hidden');
							$('#list-' + system_key + ' .sysops_menu_badge').html(badge_count_sum);
		                } else {
		                	if (!$('#list-' + system_key + ' .sysops_menu_badge').hasClass('hidden')) {
		                		$('#list-' + system_key + ' .sysops_menu_badge').addClass('hidden');
		                	}
		                }
					}

					if(typeof obj.banking_transactions != 'undefined' && parseInt(obj.banking_transactions.count) > 0) {
						$('#list-' + system_key + ' .banking_transactions_badge').removeClass('hidden');
						$('#list-' + system_key + ' .banking_transactions_badge').html(obj.banking_transactions.count);
					} else {
						$('#list-' + system_key + ' .banking_transactions_badge').addClass('hidden');
					}

					if(typeof obj.assigned_transactions != 'undefined' && parseInt(obj.assigned_transactions.count) > 0) {
						$('#list-' + system_key + ' .assigned_transactions_badge').removeClass('hidden');
						$('#list-' + system_key + ' .assigned_transactions_badge').html(obj.assigned_transactions.count);
					} else {
						$('#list-' + system_key + ' .assigned_transactions_badge').addClass('hidden');
					}

					if ($('#list-' + system_key + ' .transactions_menu').length > 0) {
						var $dropdown_content = $('#list-' + system_key + ' .transactions_menu').closest('.dropdown').find('.dropdown-content');
						var $notif_badges = $dropdown_content.find('.notif_badge');
						var badge_count_sum = 0;
						$notif_badges.each(function() {
		                	var $this = $(this);
		                	var badge_count = $this.text().trim();
		                	badge_count_sum += parseInt(badge_count);
		                });

		                if (badge_count_sum) {
		                	$('#list-' + system_key + ' .transactions_menu_badge').removeClass('hidden');
							$('#list-' + system_key + ' .transactions_menu_badge').html(badge_count_sum);
		                } else {
		                	if (!$('#list-' + system_key + ' .transactions_menu_badge').hasClass('hidden')) {
		                		$('#list-' + system_key + ' .transactions_menu_badge').addClass('hidden');
		                	}
		                }
					}
				}
			}
		}
	});
}

function notify_user(opt) {
	if(!("Notification" in window)) {
		console.log("This browser does not support desktop notification");
	} else if(Notification.permission === "granted") {
		var options = {
			body: opt.body,
			icon: opt.icon,
			dir : "ltr",
			requireInteraction: true,
			data: {
				url: opt.url
			}
		};
		var notification = new Notification(opt.title, options);
		var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
		notification.onclick = function(event) {
			if(!isFirefox) {
				event.preventDefault();
			}
			window.open(event.target.data.url, '_blank');
		};
		if(isFirefox) {
			alert_sound.play();
		}
	}
}

function ask_notification_permission() {
	if(!("Notification" in window)) {
		console.log("This browser does not support desktop notification");
	} else if(Notification.permission !== 'denied') {
		Notification.requestPermission(function (permission) {
			if(!('permission' in Notification)) {
				Notification.permission = permission;
			}
		});
	}

	// Setup websocket
	var ws, url = 'wss://master-ds.cloudspace.work:18001';
	window.onbeforeunload = function() {
		ws.send('quit');
	};
	try {
		ws = new WebSocket(url);
		console.log('Connecting... (readyState ' + ws.readyState + ')');
		ws.onopen = function(msg) {
			console.log('Connection successfully opened (readyState ' + this.readyState+')');
		};
		ws.onmessage = function(msg) {
			notify_user(jQuery.parseJSON(msg.data));
		};
		ws.onclose = function(msg) {
			if(this.readyState == 2) {
				console.log('Closing... The connection is going throught the closing handshake (readyState '+this.readyState+')');
			} else if(this.readyState == 3) {
				console.log('Connection closed... The connection has been closed or could not be opened (readyState '+this.readyState+')');
				ask_notification_permission();
			} else {
				console.log('Connection closed... (unhandled readyState '+this.readyState+')');
				ask_notification_permission();
			}
		};
		ws.onerror = function(event) {
			console.log(event.data);
		};
	} catch(ex){
		console.log(ex);
	}
}
