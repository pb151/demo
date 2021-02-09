var user_list = [];
jQuery(document).ready(function() {

	if($('#tbl_merge_back').length > 0) {
		$('#tbl_merge_back').dataTable({
			"sPaginationType": "simple_numbers",
			"bProcessing": app.bProcessing,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": '/ajax.php?action=list_merge_backs&nocache=' + new Date().getTime(),
			"oLanguage": {
				"sInfo": app.lang.data_table_showing_footer_info,
				"sInfoEmpty": app.lang.data_table_no_enteries,
				"sZeroRecords": app.lang.data_table_empty_records,
				"filter_for_repository": app.lang.data_table_filter_repository,
				"sProcessing": app.lang.data_table_processing,
				"sInfoThousands": app.lang.thousand_separator,
				"oPaginate": {
					"sNext": app.lang.data_table_next,
					"sPrevious": app.lang.data_table_previous,
					"sFirst": app.lang.data_table_first,
					"sLast": app.lang.data_table_last
				}
			},
			"fnServerData" : function(sSource, aoData, fnCallback ) {
				$.ajax({
					'dataType': 'json',
					'type': 'POST',
					'url': sSource,
					'data': aoData,
					'success': function(data){
	                    fnCallback(data);

						// Update Title item total count
						filtered_count = parseInt(data.iTotalRecords);
						$('#merge_back_count').text(filtered_count);
	                },
					'error': function(jqXHR, textStatus, errorThrown) {
						onDataTableAjaxError(jqXHR, textStatus, errorThrown, fnCallback);
					}
				});
			},
			"fnServerParams": function(aoData) {
				aoData.push({
                    "name": "repository_filter",
                    "value": $("#repository_filter").val()
                });
			},
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bFilter": false,
			"stateSave": true,
			"aaSorting": [3, "desc"],
			"aoColumnDefs": [
			{
				'bSortable': false, "aTargets": [ 4 ]
			},
			{ className: "text-center", "aTargets": [ 2 ] }
			],
			"aoColumns": [{
				"sName": "repository",
				"data": "repository"
			}, {
				"sName": "message",
				"data": "message"
			}, {
				"sName": "commit_url",
				"data": "commit_url"
			}, {
				"sName": "timestamp",
				"data": "timestamp"
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

	$('#repository_filter').change(function () {
        grid_filter();
    });

	$(document).on('click', '.merge-back-review-set-all', function(e) {
		if ($(this).prop('checked')) {
			$('input.merge-back-review-set').prop('checked', true);
		} else {
			$('input.merge-back-review-set').prop('checked', false);
		}
	});

	$(document).on('click', '.publish_btn', function() {
		
		var prim_uid = $(this).data('item-uid');
		var commit_message = $(this).data('item-message');
		var commit_author = $(this).data('item-author');
		var commit_url = $(this).data('item-url');

		if (prim_uid > 0) {
			var publish_confirm_message = app.lang.publish_git_confirm;

			bootbox.confirm({
				message: publish_confirm_message,
				callback: function(result) {
					if (result) {
						loadMask({
							flag: true,
							message: app.lang.loading
						});
						$.post("ajax.php?action=get_all_users", {}, function(data){
							var obj = jQuery.parseJSON(data);	
							loadMask({
								flag: false
							});
								
							if(obj.length > 0) {
								user_list = obj;

								var first_item = {id: "", text: ""};
								user_list.unshift(first_item);

								if($("#git_username").hasClass('applied')) {
									$("#git_username").select2('destroy');
								}
								$("#git_username").select2({
									width: "100%",
									multiple: false,
									data: user_list,
									minimumInputLength: 2,
									tags: true
								});
								$("#git_username").addClass('applied');
								$("#git_username").val('').trigger('change');

								var git_title = app.lang.git_publish_title.replace('%commit_msg%', commit_message);
								var git_desc = app.lang.git_publish_desc.replace('%commit_url%', commit_url);
								git_desc = git_desc.replace('%author%', commit_author);

								$('#merge_back_publish_modal').find('#git_uid').val(prim_uid);
								$('#merge_back_publish_modal').find('#git_title').val(git_title);
								$('#merge_back_publish_modal').find('#git_desc').val(git_desc);

								setTimeout(function(){ 
									$('#merge_back_publish_modal').modal('show');
								}, 500);

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
	});
});

function set_review(prim_uid) {
	loadMask({
		flag: true,
		message: app.lang.reviewing_data
	});
	$.post('/ajax.php?action=set_review&nocache=' + new Date().getTime(), {
		'prim_uid': prim_uid
	}, function(response) {
		var obj = jQuery.parseJSON(response);
		loadMask({
			flag: false
		});
		if (obj.Error) {
			toastr['error'](obj.error_message);
		} else {
			toastr['success'](app.lang.success_reviewed);
			grid_filter();
		}
	});
}

function grid_filter() {
	$('#tbl_merge_back').dataTable().fnFilter('', null, false, true, false);
}

function merge_back_setting_save() {
	var checked_component = [];
	$('input.merge-back-review-set').each(function() {
		var $component_tr = $(this).closest('.component-tr');
		var project_name = $component_tr.find('.project-name').text();
		var component_name = $component_tr.find('.component-name').text();

		var item = project_name.trim() + '/' + component_name.trim();

		if ($(this).prop('checked')) {
			checked_component.push(item);
		}
	});
	var checked_component_str = checked_component.join(',');

	$.post('/ajax.php?action=save_as_json&nocache=' + new Date().getTime(), {
		'checked_component_str': checked_component_str
	}, function(response) {
		var obj = jQuery.parseJSON(response);
		if (obj.success) {
			$("#merge_back_setting_modal").modal('hide');
		}
	});
}

function merge_back_publish() {
	var prim_uid = $('#merge_back_publish_modal').find('#git_uid').val();
	var username = $('#merge_back_publish_modal').find('#git_username').val();
	var git_title = $('#merge_back_publish_modal').find('#git_title').val();
	var git_desc = $('#merge_back_publish_modal').find('#git_desc').val();
	loadMask({
		flag: true,
		message: app.lang.publishing_data
	});
	$.post('/ajax.php?action=publish_merge_back&nocache=' + new Date().getTime(), {
		'prim_uid': prim_uid,
		'username': username,
		'git_title': git_title,
		'git_desc': git_desc
	}, function(response) {
		var obj = jQuery.parseJSON(response);
		loadMask({
			flag: false
		});
		$("#merge_back_publish_modal").modal('hide');
		if (obj.Error) {
			toastr['error'](obj.error_message);
		} else {
			toastr['success'](app.lang.success_published);
			grid_filter();
		}
	});
}