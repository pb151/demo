/**
 * Language file for english
 */
app.format = new Object();
app.format.datepicker = {
	closeText:"Done",
	prevText:"Prev",
	nextText:"Next",
	currentText:"Today",
	monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],
	monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
	dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
	dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
	dayNamesMin:["Su","Mo","Tu","We","Th","Fr","Sa"],
	weekHeader:"Wk",
	dateFormat:"mm/dd/yyyy",
	dateFormat2:"m/d/Y",
	dateFormat3:"mm/dd/yy",
	datetimeFormat:"m/d/Y G:i",
	firstDay:0,
	isRTL:!1,
	showMonthAfterYear:!1,
	yearSuffix:"",
	timeOnlyTitle: 'Choose Time',
	timeText: 'Time',
	hourText: 'Hour',
	minuteText: 'Minute',
	secondText: 'Second',
	millisecText: 'Millisecond',
	timezoneText: 'Time Zone',
	month: 'Month',
	day: 'Day',
	week: 'Week',
	allday: 'All Day',
	fc_columnFormat: {
		month: 'ddd',
		week: 'ddd M/d',
		day: 'dddd M/d'
	}
};

app.lang = new Object();
app.lang.lang_code = 'en';
app.lang.active = 'Active';
app.lang.inactive = 'Inactive';
app.lang.loading = 'Loading...';
app.lang.yes = 'Yes';
app.lang.no = 'No';
app.lang.thousand_separator = ',';
app.lang.decimal_separator = '.';

app.lang.next = 'Next';
app.lang.back = 'Back';
app.lang.save = 'Save';
app.lang.delete = 'Delete';

app.lang.success_add = 'Added Successfully';
app.lang.success_delete = 'Deleted Successfully';
app.lang.success_updated = 'Updated Successfully';
app.lang.successfully_assigned = 'Successfully assigned';
app.lang.success_published = 'Published Successfully';
app.lang.success_reviewed = 'Reviewed Successfully';

app.lang.saving_data = 'Saving data...';
app.lang.loading = 'Loading...';
app.lang.redirecting = 'Redirecting please wait...';
app.lang.refreshing = 'Refreshing please wait...';
app.lang.deleting_data = 'Deleting....';
app.lang.publishing_data = 'Publishing....';
app.lang.reviewing_data = 'Reviewing....';


app.lang.success_message = 'Request processed successfully';


app.lang.error_message = new Object();
app.lang.error_message.required = "This field is required.";
app.lang.error_message.server_not_responding = "Server not responding";
app.lang.error_message.delete_conformation = "Are you sure you want to delete selected record?";
app.lang.error_message.error_occured = "Error Occured";

app.lang.data_table_search = "Search";
app.lang.data_table_next = "Next";
app.lang.data_table_previous = "Previous";
app.lang.data_table_first = "First";
app.lang.data_table_last = "Last";
app.lang.data_table_showing_footer_info = "Showing _START_ to _END_ of _TOTAL_";
app.lang.data_table_showing_footer_info_empty = "Showing 0 to 0 of 0 entries";
app.lang.data_table_filtered = "(filtered from _MAX_ total entries)";
app.lang.data_table_processing = "Processing..";
app.lang.data_table_empty = "No data available in table";
app.lang.data_table_no_matching_records_found = "No matching records found";
app.lang.data_table_empty_records = "No data available in table";
app.lang.data_table_empty_records_tasks = "You currently have no reserved tasks. Please click above on the drop-down menu, select \"Unassigned Tasks\" and reserve yourself one of the tasks.";
app.lang.data_table_no_enteries = "Showing 0 to 0 of 0 entries";
app.bProcessing = 'Processing';

app.lang.data_table_showing_footer_info_msg = "Got a total of _TOTAL_ messages to show (_START_ to _END_)";

app.lang.edit_user = 'Edit user';
app.lang.add_ustack = 'New UStack Component';
app.lang.edit_ustack = 'Edit UStack Component';
app.lang.publish = 'Publish';
app.lang.publish_ustack_confirm = 'Do you want to publish this component to %count_number% of components?';
app.lang.no_gits = 'There is no related git';

app.lang.data_table_filter_repository = "Filter for repository";

app.lang.publish_git_confirm = 'Do you want to publish this git information?';
app.lang.git_publish_title = 'Merge from uStack - %commit_msg%';
app.lang.git_publish_desc = 'Merge the changes from %commit_url% by %author% to your project.';