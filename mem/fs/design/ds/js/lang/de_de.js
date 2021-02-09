/**
 * Language file for german
 */
app.format = new Object();
app.format.datepicker = {
	closeText:"Fertig",
	prevText:"Zurück",
	nextText:"Weiter",
	currentText:"Heute",
	monthNames:["Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"],
	monthNamesShort:["Jan","Feb","Mar","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"],
	dayNames:["Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag"],
	dayNamesShort:["So","Mo","Di","Mi","Do","Fr","Sa"],
	dayNamesMin:["So","Mo","Di","Mi","Do","Fr","Sa"],
	weekHeader:"Wk",
	dateFormat:"mm/dd/yyyy",
	dateFormat2:"m/d/Y",
	dateFormat3:"mm/dd/yy",
	datetimeFormat:"m/d/Y G:i",
	firstDay:0,
	isRTL:!1,
	showMonthAfterYear:!1,
	yearSuffix:"",
	timeOnlyTitle: 'Zeitraum auswählen',
	timeText: 'Zeit',
	hourText: 'Stunde',
	minuteText: 'Minute',
	secondText: 'Sekunde',
	millisecText: 'Millisekunde',
	timezoneText: 'Zeitzone',
	month: 'Monat',
	day: 'Tag',
	week: 'Woche',
	allday: 'Ganztägig',
	fc_columnFormat: {
		month: 'ddd',
		week: 'ddd M/d',
		day: 'dddd M/d'
	}
};

app.lang = new Object();
app.lang.lang_code = 'de';
app.lang.no_file_selected = "Keine Datei ausgewählt";
app.lang.active = 'Aktiv';
app.lang.inactive = 'Inaktiv';
app.lang.loading = 'Lädt...';
app.lang.yes = 'Ja';
app.lang.no = 'Nein';
app.lang.thousand_separator = '.';
app.lang.decimal_separator = ',';

app.lang.next = 'Nächste';
app.lang.back = 'Zurück';
app.lang.save = 'Speichern';
app.lang.delete = 'Löschen';

app.lang.success_add = 'Erfolgreich hinzugefügt';
app.lang.success_delete = 'Erfolgreich gelöscht';
app.lang.success_updated = 'Erfolgreich aktualisiert';
app.lang.successfully_assigned = 'Erfolgreich zugewiesen';
app.lang.success_published = 'Erfolgreich veröffentlicht';
app.lang.success_reviewed = 'Erfolgreich überprüft';

app.lang.saving_data = 'Speichert...';
app.lang.loading = 'Lädt...';
app.lang.redirecting = 'Umleitung, bitte warten...';
app.lang.refreshing = 'Aktualisiert, bitte warten...';
app.lang.deleting_data = 'Löscht....';
app.lang.publishing_data = 'Veröffentlichung....';
app.lang.reviewing_data = 'Überprüfung....';

app.lang.success_message = 'Anfrage erfolgreich verarbeitet';

app.lang.error_message = new Object();
app.lang.error_message.required = "Dieses Feld ist ein Pflichtfeld.";
app.lang.error_message.server_not_responding = "Server antwortet nicht";
app.lang.error_message.delete_conformation = "Sind Sie sicher, dass Sie den ausgewählten Datensatz löschen möchten?";
app.lang.error_message.tos_accept = "Bitte akzeptieren Sie Nutzungsbedingungen und Datenschutzerklärung";
app.lang.error_message.error_occured = "Error Occured";


app.lang.data_table_search = "Suche";
app.lang.data_table_next = "Nächste";
app.lang.data_table_previous = "Zurück";
app.lang.data_table_first = "Erste";
app.lang.data_table_last = "Letzte";
app.lang.data_table_showing_footer_info = "Zeige _START_ bis _END_ von _TOTAL_";
app.lang.data_table_showing_footer_info_empty = "Zeige  0 bis 0 von 0 Einträgen";
app.lang.data_table_filtered = "(gefiltert aus _MAX_ total Einträgen)";
app.lang.data_table_processing = "Wird verarbeitet..";
app.lang.data_table_empty = "Keine Daten verfügbar";
app.lang.data_table_no_matching_records_found = "Keine passenden Ergebnisse gefunden";
app.lang.data_table_empty_records = "Keine Daten verfügbar";
app.lang.data_table_empty_records_tasks = 'Sie haben keine zugewiesenen Aufgaben. Bitte klicken Sie oben auf das Dropdown-Menü, wählen Sie "Nicht zugewiesene Aufgaben " und reservieren Sie sich eine der Aufgaben.';
app.lang.data_table_no_enteries = "Zeige 0 bis 0 von 0 Einträgen";
app.bProcessing = 'In Bearbeitung';

app.lang.data_table_showing_footer_info_msg = "Got a total of _TOTAL_ messages to show (_START_ to _END_)";

app.lang.edit_user = 'Edit user';
app.lang.add_ustack = 'New UStack Component';
app.lang.edit_ustack = 'Edit UStack Component';
app.lang.publish = 'Veröffentlichen';
app.lang.publish_ustack_confirm = 'Möchten Sie diese Komponente in %count_number% Komponenten veröffentlichen?';
app.lang.no_gits = 'Es gibt keinen verwandten git';

app.lang.data_table_filter_repository = "Filter für Repository";

app.lang.publish_git_confirm = 'Möchten Sie diese Git-Informationen veröffentlichen?';
app.lang.git_publish_title = 'Merge from uStack - %commit_msg%';
app.lang.git_publish_desc = 'Merge the changes from %commit_url% by %author% to your project.';