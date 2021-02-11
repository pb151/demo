jQuery(document).ready(function() {
	if($.fn.selectpicker) {
		$('.bs-select').selectpicker({
			iconBase: 'fa',
			tickIcon: 'fa-check'
		});
	}

	if($('#tbl_scaling_log').length > 0) {
	    $('#tbl_scaling_log').dataTable({
	    	"sPaginationType": "simple_numbers",
			"bProcessing": app.bProcessing,
			"bServerSide": true,
			"iDisplayLength": 25,
			"sAjaxSource": '/ajax.php?action=list_scaling_logs&nocache=' + new Date().getTime(),
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
					"name": "filter_txt",
					"value": $("#filter_txt").val()
				});
			},
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bFilter": false,
			"pageLength": 10,
			"aoColumnDefs": [{
				'bSortable': false, "aTargets": [ 0, 1 ]
			}],
			"stateSave": true,
			"aaSorting": [0, "desc"],
			"aoColumns": [{
				"sName": "created_on",
				"data": "created_on"
			}, {
				"sName": "log_desc",
				"data": "log_desc"
			}],
			"fnRowCallback": function(nRow, aData, iDisplayIndex) {
				var rowClass = aData.red_row == 1 ? 'red_row' : (aData.gray_row == 1 ? 'gray_row' : '');

				if (iDisplayIndex % 2 == 0) {
					nRow.className = "gradeA odd " + rowClass;
				} else {
					nRow.className = "gradeA even " + rowClass;
				}

				return nRow;
			}
	    });
	}

    $('#filter_date_start, #filter_date_end').change(function() {
		grid_filter();
    });
});

function grid_filter() {
	$('#tbl_scaling_log').dataTable().fnFilter('', null, false, true, false);
}

$('.date-picker').datetimepicker({
	pickDate: true,
	format : 'dd.mm.yyyy',
	minView: 2,
	autoclose: true
});
