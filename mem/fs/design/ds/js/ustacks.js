var component_uid = 0;
var user_list = [];
jQuery(document).ready(function() {

	if($('#tbl_ustack').length > 0) {
		$('#tbl_ustack').dataTable({
			"sPaginationType": "simple_numbers",
			"bProcessing": app.bProcessing,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": '/ajax.php?action=list_ustacks&nocache=' + new Date().getTime(),
			"oLanguage": {
				"sInfo": app.lang.data_table_showing_footer_info,
				"sInfoEmpty": app.lang.data_table_no_enteries,
				"sZeroRecords": app.lang.data_table_empty_records,
				"sSearch": app.lang.data_table_search,
				"sProcessing": app.lang.data_table_processing,
				"sInfoThousands": app.lang.thousand_separator,
				"oPaginate": {
					"sNext": app.lang.data_table_next,
					"sPrevious": app.lang.data_table_previous,
					"sFirst": app.lang.data_table_first,
					"sLast": app.lang.data_table_last
				}
			},
			"fnServerParams": function(aoData) {
				aoData.push({
					"name": "sSearch",
					"value": $('#tbl_ustack_filter input[type="search"]').val()
				});
			},
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bFilter": true,
			"stateSave": true,
			"aaSorting": [0, "desc"],
			"aoColumnDefs": [{
				'bSortable': false, "aTargets": [ 3 ]
			}],
			"aoColumns": [{
				"sName": "name",
				"data": "name"
			}, {
				"sName": "git_repository",
				"data": "git_repository"
			}, {
				"sName": "related_gits",
				"data": "related_gits"
			}, {
				"sName": "actions",
				"data": "actions"
			}],
			"fnRowCallback": function(nRow, aData, iDisplayIndex) {
				// parse logo on 0 cell
				if (iDisplayIndex % 2 == 0)
					nRow.className = "gradeA odd";
				else
					nRow.className = "gradeA even";

				return nRow;
			},
			"fnDrawCallback": function() {
				$('.tooltips').tooltip();
			}
		});
	}

	$('#edit_ustack_btn').on('click', function() {
		add_new_related_git_record();
		loadMask({
			flag: false
		});
		$("#edit_ustack").modal('hide');
		grid_filter();
	})

	$('#edit_ustack').on('hidden.bs.modal', function () {
		grid_filter();
	});

	$('#edit_ustack').on('shown.bs.modal', function (e) {
		$('#tbl_related_gits').dataTable().fnDestroy();
		if($('#tbl_related_gits').length > 0) {
			$('#tbl_related_gits').dataTable({
				"sPaginationType": "simple_numbers",
				"bProcessing": app.bProcessing,
				"bServerSide": true,
				"iDisplayLength": 10,
				"sAjaxSource": '/ajax.php?action=get_ustack&nocache=' + new Date().getTime(),
				"fnServerData" : function(sSource, aoData, fnCallback ) {
					$.ajax({
						'dataType': 'json',
						'type': 'POST',
						'url': sSource,
						'data': aoData,
						'success': function(data){                      
							if (data.success) {
								loadMask({
									flag: false
								});
								$("#prim_uid").val(data.ustack.prim_uid);
								$("#component_name").val(data.ustack.name);

								if (component_uid == 0) {
									if (!$('#div_related_gits').hasClass('hidden')) {
										$('#div_related_gits').addClass('hidden');
									}									
								} else {
									if ($('#div_related_gits').hasClass('hidden')) {
										$('#div_related_gits').removeClass('hidden');
									}
								}
								fnCallback(data);
							} else {
								toastr['error'](data.error_message);
							}
						},
						'error': function(jqXHR, textStatus, errorThrown) {
								onDataTableAjaxError(jqXHR, textStatus, errorThrown, fnCallback);
						}
					});
				},
				"oLanguage": {
					"sInfo": app.lang.data_table_showing_footer_info,
					"sInfoEmpty": app.lang.data_table_no_enteries,
					"sZeroRecords": app.lang.data_table_empty_records,
					"sSearch": app.lang.data_table_search,
					"sProcessing": app.lang.data_table_processing,
					"sInfoThousands": app.lang.thousand_separator,
					"oPaginate": {
						"sNext": app.lang.data_table_next,
						"sPrevious": app.lang.data_table_previous,
						"sFirst": app.lang.data_table_first,
						"sLast": app.lang.data_table_last
					}
				},
				"fnServerParams": function(aoData) {
					aoData.push({
						"name": "prim_uid",
						"value": component_uid
					});
				},
				"bJQueryUI": false,
				"bAutoWidth": false,
				"bLengthChange": false,
				"bFilter": false,
				"pageLength": 10,
				"aoColumnDefs": [{
					'bSortable': false, "aTargets": [ 4 ]
				}],
				"stateSave": true,
				"aaSorting": [0, "desc"],
				"aoColumns": [{
					"sName": "git_repository",
					"data": "git_repository"
				}, {
					"sName": "git_user",
					"data": "git_user"
				}, {
					"sName": "pm_project",
					"data": "pm_project"
				}, {
					"sName": "pm_user",
					"data": "pm_user"
				}, {
					"sName": "action",
					"data": "action"
				}],
				"fnRowCallback": function(nRow, aData, iDisplayIndex) {
					var rowClass = aData.red_row == 1 ? 'red_row' : (aData.gray_row == 1 ? 'gray_row' : '');

					if (iDisplayIndex % 2 == 0)
						nRow.className = "gradeA odd " + rowClass;
					else
						nRow.className = "gradeA even " + rowClass;

					return nRow;
				},
				"fnDrawCallback": function () {
					$('#tbl_related_gits .tooltips').tooltip();

					$('#tbl_related_gits td > a.git-repository').editable({
						type: 'text',
						pk: $(this).data('pk'),
						url: '/ajax.php?action=save_ustack_related_git&column=git_repository&nocache=' + new Date().getTime(),
						title: 'Enter Git Repository',
						onblur: 'ignore'
					});
					$('#tbl_related_gits td > a.git-user').editable({
						type: 'text',
						pk: $(this).data('pk'),
						url: '/ajax.php?action=save_ustack_related_git&column=git_user&nocache=' + new Date().getTime(),
						title: 'Enter Git User',
						onblur: 'ignore'
					});
					$('#tbl_related_gits td > a.pm-project').editable({
						type: 'text',
						pk: $(this).data('pk'),
						url: '/ajax.php?action=save_ustack_related_git&column=pm_project&nocache=' + new Date().getTime(),
						title: 'Enter PM Project',
						onblur: 'ignore'
					});
					$('#tbl_related_gits td > a.pm-user').editable({
						type: 'text',
						pk: $(this).data('pk'),
						url: '/ajax.php?action=save_ustack_related_git&column=pm_user&nocache=' + new Date().getTime(),
						title: 'Enter PM User',
						onblur: 'ignore'
					});
				}
			});
		}
	});

	$(document).on('click', '.publish_btn', function() {
		
		var prim_uid = $(this).data('item-uid');
		var git_count = parseInt($(this).data('git-count'));

		if (git_count > 0) {
			var publish_confirm_message = app.lang.publish_ustack_confirm;
			publish_confirm_message = publish_confirm_message.replace("%count_number%", git_count);

			bootbox.confirm({
				message: publish_confirm_message,
				callback: function(result) {
					if (result) {
						loadMask({
							flag: true,
							message: app.lang.publishing_data
						});
						$.post('/ajax.php?action=publish_ustack&nocache=' + new Date().getTime(), {
							'prim_uid': prim_uid
						}, function(response) {
							var obj = jQuery.parseJSON(response);
							loadMask({
								flag: false
							});
							if (obj.Error) {
								toastr['error'](obj.error_message);
							} else {
								toastr['success'](app.lang.success_published);
								grid_filter();
							}
						});
					}
				},
				buttons: {
					confirm: {
						label: app.lang.yes
					},
					cancel: {
						label: app.lang.no
					}
				}
			});
		} else {
			toastr['error'](app.lang.no_gits);
		}
	});
});

function add_ustack() {
	edit_ustack(0);
}

function edit_ustack(prim_uid) {

	$("#edit_ustack .form-group").removeClass('has-error');

	loadMask({
		flag: true,
		message: app.lang.loading
	});

	if (prim_uid) {
		$("#edit_ustack div.modal-content div.modal-header h4.modal-title").html(app.lang.edit_ustack);
	} else {
		$("#edit_ustack div.modal-content div.modal-header h4.modal-title").html(app.lang.add_ustack);
	}
	component_uid = prim_uid;

	get_user_list();
	$('#edit_ustack').modal('show');

}

function delete_ustack(prim_uid) {
	bootbox.confirm({
		message: app.lang.error_message.delete_conformation,
		callback: function(result) {
			if (result) {
				loadMask({
					flag: true,
					message: app.lang.deleting_data
				});
				$.post('/ajax.php?action=delete_ustack&nocache=' + new Date().getTime(), {
					'prim_uid': prim_uid
				}, function(response) {
					var obj = jQuery.parseJSON(response);
					loadMask({
						flag: false
					});
					if (obj.Error) {
						toastr['error'](obj.error_message);
					} else {
						toastr['success'](app.lang.success_delete);
						grid_filter();
					}
				});
			}
		},
		buttons: {
			confirm: {
				label: app.lang.yes
			},
			cancel: {
				label: app.lang.no
			}
		}
	});
}

function grid_filter() {
	$('#tbl_ustack').dataTable().fnFilter('', null, false, true, false);
}

function get_user_list() {
	$.post("ajax.php?action=get_all_users", {}, function(data){
		var obj = jQuery.parseJSON(data);		
		if(obj.length > 0) {
			user_list = obj;
		}
	});
}

function user_auto_complete($obj) {
	if($obj.length > 0) {
		if($obj.hasClass('applied')) {
			$obj.select2('destroy');
		}
		$obj.select2({
			width: "150px",
			multiple: false,
			data: user_list,
			minimumInputLength: 2,
			tags: true
		});
		$obj.addClass('applied');
		$obj.val('').trigger('change');
	}
}

function add_related_git() {
	if ($('#div_related_gits').hasClass('hidden')) {
		$('#div_related_gits').removeClass('hidden');
	}
	var new_related_git_tr_html = '<tr role="row" class="gradeA odd" id="new_related_git_tr"><td><input type="text" class="form-control" name="related_git_repository" id="related_git_repository" value=""></td><td><select name="related_git_user" id="related_git_user" class="form-control" data-live-search="true" data-size="8"></select></td><td><input type="text" class="form-control" name="related_git_pm_project" id="related_git_pm_project" value=""></td><td><select name="related_git_pm_user" id="related_git_pm_user" class="form-control" data-live-search="true" data-size="8"></select></td><td><a class="btn tooltips action_ico" onclick="add_new_related_git_record();" title="Add New Record"><i class="fa fa-plus"></i></a></td></tr>';

	if ($('#tbl_related_gits tbody').find('#new_related_git_tr').length == 0) {
		$('#tbl_related_gits tbody').prepend(new_related_git_tr_html);
		
		user_auto_complete($("#related_git_user"));
		$('#related_git_user').on("select2:close", function (e) { 
			var selected_option_title = $('#select2-related_git_user-container').attr('title');
			var selected_option_text = $('#select2-related_git_user-container').text();
			if (selected_option_title != '' && selected_option_text == '') {
				$("#related_git_user").val(selected_option_title).trigger('change');
			}
		});

		user_auto_complete($("#related_git_pm_user"));
		$('#related_git_pm_user').on("select2:close", function (e) { 
			var selected_option_title = $('#select2-related_git_pm_user-container').attr('title');
			var selected_option_text = $('#select2-related_git_pm_user-container').text();
			if (selected_option_title != '' && selected_option_text == '') {
				$("#related_git_pm_user").val(selected_option_title).trigger('change');
			}
		});
	}	
}

function delete_ustack_related_git(prim_uid) {
	bootbox.confirm({
		message: app.lang.error_message.delete_conformation,
		callback: function(result) {
			if (result) {
				loadMask({
					flag: true,
					message: app.lang.deleting_data
				});
				$.post('/ajax.php?action=delete_ustack_related_git&nocache=' + new Date().getTime(), {
					'prim_uid': prim_uid
				}, function(response) {
					var obj = jQuery.parseJSON(response);
					loadMask({
						flag: false
					});
					if (obj.Error) {
						toastr['error'](obj.error_message);
					} else {
						toastr['success'](app.lang.success_delete);
						git_grid_filter();
					}
				});
			}
		},
		buttons: {
			confirm: {
				label: app.lang.yes
			},
			cancel: {
				label: app.lang.no
			}
		}
	});
}

function add_new_related_git_record() {
	$('#component_name').closest('.form-group').removeClass('has-error');
	$('span.help-block').remove();

	var component_name = $('#component_name').val();
	var component_prim_uid = $('#prim_uid').val();
	var related_git_repository = $('#related_git_repository').val();
	var related_git_user = $('#related_git_user').val();
	var related_git_pm_project = $('#related_git_pm_project').val();
	var related_git_pm_user = $('#related_git_pm_user').val();

	if (component_name != '') {
		if (related_git_repository != '' || related_git_user != '' || related_git_pm_project != '' || related_git_pm_user != '') {
			$.post('/ajax.php?action=save_ustack&nocache=' + new Date().getTime(), {
				'component_name': component_name,
				'prim_uid': component_prim_uid,
				'related_git_repository': related_git_repository,
				'related_git_user': related_git_user,
				'related_git_pm_project': related_git_pm_project,
				'related_git_pm_user': related_git_pm_user
			}, function(response) {
				var obj = jQuery.parseJSON(response);
				if (obj.Error) {
					toastr['error'](obj.error_message);
				} else {					
					if (component_uid == 0) {
						$("#edit_ustack").modal('hide');
						grid_filter();
					} else {
						git_grid_filter();
					}
				}
			});
		}		
	} else {
		$('#component_name').closest('.form-group').addClass('has-error');
		var error_block = '<span class="help-block">This field is required.</span>';
		$(error_block).insertAfter($('#component_name').closest('.input-group'));
	}
}

function git_grid_filter() {
	$('#tbl_related_gits').dataTable().fnFilter('', null, false, true, false);
}
