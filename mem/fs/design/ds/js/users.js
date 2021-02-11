jQuery(document).ready(function() {

	if($('#tbl_users').length > 0) {
		$('#tbl_users').dataTable({
			"sPaginationType": "simple_numbers",
			"bProcessing": app.bProcessing,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": '/ajax.php?action=list_users&nocache=' + new Date().getTime(),
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
					"value": $('#tbl_users_filter input[type="search"]').val()
				});
			},
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bFilter": true,
			"stateSave": true,
			"aaSorting": [0, "desc"],
			"aoColumnDefs": [{
				'bSortable': false, "aTargets": [ 4 ]
			}],
			"aoColumns": [{
				"sName": "username",
				"data": "username"
			}, {
				"sName": "ip_addresses",
				"data": "ip_addresses"
			}, {
				"sName": "status",
				"data": "status"
			}, {
				"sName": "last_login_time",
				"data": "last_login_time"
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

	process_form({
		formName: 'frm_user',
		ajaxSource: '/ajax.php?action=save_user',
		rules: {
			"username": {
				required: true
			}, "password" : {
				required : function() {
					if($('#prim_uid').val() > 0) {
						return false;
					} else {
						return true;
					}
				}
			}, "email" : {
				required : function() {
					if($('#git_username_simplessus').val() == '') {
						return false;
					} else {
						return true;
					}
				}
			}, "first_name" : {
				required : function() {
					if($('#git_username_simplessus').val() != '' && $('#last_name').val() == '') {
						return true;
					} else {
						$('#first_name').closest('div.form-group').removeClass('has-error');
						return false;
					}
				}
			}, "last_name" : {
				required : function() {
					if($('#git_username_simplessus').val() != '' && $('#first_name').val() == '') {
						return true;
					} else {
						$('#last_name').closest('div.form-group').removeClass('has-error');
						return false;
					}
				}
			}
		},
		callback: function(responseText) {
			var obj = jQuery.parseJSON(responseText);
			loadMask({
				flag: false
			});
			$("#edit_user").modal('hide');
			if (obj.success) {
				if(obj.portal_uid) {
					toastr['success'](app.lang.success_add);
				} else {
					toastr['success'](app.lang.success_updated);
				}
				grid_filter();
			} else {
				toastr['error'](obj.error_message);
			}
		}

	});
});

function add_user() {
	$("#frm_user").resetForm();
	var validator = $("#frm_user").validate();
	validator.resetForm();
	$("#frm_user .form-group").removeClass('has-error');
	$('#username').removeProp("readonly");

	$("#prim_uid").val(0);

	if ($('#my-select').length > 0) {
		$('#my-select').multiSelect();
		$('#my-select').multiSelect('deselect_all');
	}

	if ($('#ds_system_select').length > 0) {
		$('#ds_system_select').multiSelect();
		$('#ds_system_select').multiSelect('deselect_all');
	}

	if ($('#watch_system_select').length > 0) {
		$('#watch_system_select').multiSelect();
		$('#watch_system_select').multiSelect('deselect_all');
	}

	$("#edit_user").modal('show');
}

function edit_user(prim_uid) {
	$("#frm_user").resetForm();
	var validator = $("#frm_user").validate();
	validator.resetForm();
	$("#frm_user .form-group").removeClass('has-error');
	$('#username').prop('readonly', true);

	loadMask({
		flag: true,
		message: app.lang.loading
	});

	$("#edit_user div.modal-content div.modal-header h4.modal-title").html(app.lang.edit_user);

	$.post('/ajax.php?action=get_user&nocache=' + new Date().getTime(), {
		'prim_uid': prim_uid
	}, function(response) {
		var obj = jQuery.parseJSON(response);
		loadMask({
			flag: false
		});
		if (obj.Error) {
			toastr['error'](obj.error_message);
		} else {
			$("#prim_uid").val(obj.results.data.prim_uid);
			$("#username").val(obj.results.data.username);
			$("#nice_username").val(obj.results.data.nice_username);
			$("#ip_address").val(obj.results.data.ip_addresses);

			$("#first_name").val(obj.results.data.first_name);
			$("#last_name").val(obj.results.data.last_name);
			$("#email").val(obj.results.data.email);
			$("#pm_username").val(obj.results.data.pm_username);
			$("#git_username_fino").val(obj.results.data.git_username_fino);
			$("#git_username_simplessus").val(obj.results.data.git_username_simplessus);

			if (obj.results.data.status == '0') {
				$("input[name='status'][value='0']").attr('checked', 'checked');
				$("input[name='status'][value='1']").removeAttr('checked');
			} else if (obj.results.data.status == '1') {
				$("input[name='status'][value='1']").attr('checked', 'checked');
				$("input[name='status'][value='0']").removeAttr('checked');
			}

			if ($('#my-select').length > 0) {
				$('#my-select').multiSelect();
				$('#my-select').multiSelect('deselect_all');
				$('#my-select').multiSelect('select', obj.results.data.permission);
			}

			if ($('#ds_system_select').length > 0) {
				$('#ds_system_select').multiSelect();
				$('#ds_system_select').multiSelect('deselect_all');
				$('#ds_system_select').multiSelect('select', obj.results.data.selected_ds_system);
			}

			if ($('#watch_system_select').length > 0) {
				$('#watch_system_select').multiSelect();
				$('#watch_system_select').multiSelect('deselect_all');
				$('#watch_system_select').multiSelect('select', obj.results.data.selected_watch_system);
			}

			$("#edit_user").modal('show');
		}
	});
}

function delete_user(prim_uid) {
	bootbox.confirm({
		message: app.lang.error_message.delete_conformation,
		callback: function(result) {
			if (result) {
				loadMask({
					flag: true,
					message: app.lang.deleting_data
				});
				$.post('/ajax.php?action=delete_user&nocache=' + new Date().getTime(), {
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
	$('#tbl_users').dataTable().fnFilter('', null, false, true, false);
}
