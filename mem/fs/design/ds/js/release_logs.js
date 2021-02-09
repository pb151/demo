var current_project = '';
var current_component_name = '';
jQuery(document).ready(function() {
	if($('#tbl_release_logs').length > 0) {
		$('#tbl_release_logs').dataTable({
			"sPaginationType": "simple_numbers",
			"bProcessing": app.bProcessing,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": '/ajax.php?action=list_release_logs&nocache=' + new Date().getTime(),
			"oLanguage": {
				"sInfo": app.lang.data_table_showing_footer_info,
				"sInfoEmpty": app.lang.data_table_no_enteries,
				"sZeroRecords": app.lang.data_table_empty_records,
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
	                },
					'error': function(jqXHR, textStatus, errorThrown) {
						onDataTableAjaxError(jqXHR, textStatus, errorThrown, fnCallback);
					}
				});
			},
			"fnServerParams": function(aoData) {
				aoData.push({
                    "name": "project_filter",
                    "value": $("#project_filter").val()
                });
                aoData.push({
                    "name": "component_filter",
                    "value": $("#component_filter").val()
                });
                aoData.push({
                    "name": "component_name_filter",
                    "value": $("#component_name_filter").val()
                });
			},
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bFilter": false,
			"stateSave": true,
			"aaSorting": [0, "desc"],
			"aoColumnDefs": [
			{
				'bSortable': false, "aTargets": [ 3 ]
			},
			{ className: "text-center", "aTargets": [ 0 ] }
			],
			"aoColumns": [{
				"sName": "project",
				"data": "project"
			}, {
				"sName": "component",
				"data": "component"
			}, {
				"sName": "component_name",
				"data": "component_name"
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

    $("#project_filter, #component_filter, #component_name_filter").on('change', function () {
        grid_filter();
    });

    $('#release_log_details').on('shown.bs.modal', function (e) {
		$('#tbl_release_log_details').dataTable().fnDestroy();
		if($('#tbl_release_log_details').length > 0) {
			$('#tbl_release_log_details').dataTable({
				"sPaginationType": "simple_numbers",
				"bProcessing": app.bProcessing,
				"bServerSide": true,
				"iDisplayLength": 10,
				"sAjaxSource": '/ajax.php?action=get_release_log_details&nocache=' + new Date().getTime(),
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
						"name": "current_project",
						"value": current_project
					});
					aoData.push({
						"name": "current_component_name",
						"value": current_component_name
					});
					aoData.push({
	                    "name": "log_content_filter",
	                    "value": $("#log_content_filter").val()
	                });
				},
				"bJQueryUI": false,
				"bAutoWidth": false,
				"bLengthChange": false,
				"bFilter": false,
				"pageLength": 10,
				"aoColumnDefs": [{
					'bSortable': false, "aTargets": [ 4 ]
				},
				{ className: "text-center", "aTargets": [ 1, 2 ] }],
				"stateSave": true,
				"aaSorting": [4, "desc"],
				"aoColumns": [{
					"sName": "project",
					"data": "project"
				}, {
					"sName": "component",
					"data": "component"
				}, {
					"sName": "component_name",
					"data": "component_name"
				}, {
					"sName": "log_text",
					"data": "log_text"
				}, {
					"sName": "timestamp",
					"data": "timestamp"
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
					$('#tbl_release_log_details .tooltips').tooltip();
				}
			});
		}
	});

    $(document).on('click', '.view_btn', function() {
		var project = $(this).data('item-project');
		var component_name = $(this).data('item-component_name');

		if (project != '' && component_name != '') {
			current_project = project;
			current_component_name = component_name;
			loadMask({
				flag: true,
				message: app.lang.loading
			});
			$('#release_log_details #log_content_filter').val('');
			$('#release_log_details').modal('show');
		}
	});

	$(document).on('keypress', '#log_content_filter', function(event) {
		var keycode = (event.keyCode ? event.keyCode : event.which);
		if(keycode == '13') {
			grid_modal_filter();
		}
	});

});

function grid_filter() {
	$('#tbl_release_logs').dataTable().fnFilter('', null, false, true, false);
}

function grid_modal_filter() {
	$('#tbl_release_log_details').dataTable().fnFilter('', null, false, true, false);
}