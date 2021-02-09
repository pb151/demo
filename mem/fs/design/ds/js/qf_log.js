jQuery(document).ready(function() {
	if($.fn.selectpicker) {
		$('.bs-select').selectpicker({
			iconBase: 'fa',
			tickIcon: 'fa-check'
		});
	}

	$('.date-picker').datetimepicker({
		pickDate: true,
		format : 'dd.mm.yyyy',
		minView: 2,
		autoclose: true
	});

	$('#filter_text').keypress(function(e) {
		if(e.which == 13) {
			grid_filter();
			return false;
		}
	});

	if($('#tbl_qf_log').length > 0) {
	    $('#tbl_qf_log').dataTable({
	    	"sPaginationType": "simple_numbers",
			"bProcessing": app.bProcessing,
			"bServerSide": true,
			"iDisplayLength": 10,
			"sAjaxSource": '/ajax.php?action=list_qf_log&nocache=' + new Date().getTime(),
			"fnServerData" : function(sSource, aoData, fnCallback ) {
				$.ajax({
					'dataType': 'json',
					'type': 'POST',
					'url': sSource,
					'data': aoData,
					'success': [fnCallback],
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
					"name": "filter_env",
					"value": $("#filter_env").selectpicker('val')
				});
				aoData.push({
					"name": "filter_date_start",
					"value": $("#filter_date_start").val()
				});
				aoData.push({
					"name": "filter_date_end",
					"value": $("#filter_date_end").val()
				});
				aoData.push({
					"name": "filter_text",
					"value": $("#filter_text").val()
				});
			},
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bFilter": false,
			"pageLength": 10,
			"stateSave": true,
			"aaSorting": [7, "desc"],
			"aoColumnDefs": [{
				'bSortable': false, "aTargets": [ 14 ]
			}],
			"aoColumns": [{
				"sName": "manual_uid",
				"data": "manual_uid"
			}, {
				"sName": "env_name",
				"data": "env_name"
			}, {
				"sName": "qf_type",
				"data": "qf_type"
			}, {
				"sName": "portal_name",
				"data": "portal_name"
			}, {
				"sName": "status",
				"data": "status"
			}, {
				"sName": "username",
				"data": "username"
			}, {
				"sName": "assignee",
				"data": "assignee"
			}, {
				"sName": "created",
				"data": "created"
			}, {
				"sName": "started_at",
				"data": "started_at"
			}, {
				"sName": "code_requested",
				"data": "code_requested"
			}, {
				"sName": "code_received",
				"data": "code_received"
			}, {
				"sName": "responded",
				"data": "responded"
			}, {
				"sName": "login_success",
				"data": "login_success"
			}, {
				"sName": "completed_at",
				"data": "completed_at"
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
			}
	    });
	}

    $('#filter_env, #filter_date_start, #filter_date_end').change(function() {
		grid_filter();
    });
});

function grid_filter() {
	$('#tbl_qf_log').dataTable().fnFilter('', null, false, true, false);
}
