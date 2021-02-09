<?php
/**
 * Overview
 *
 * @package		gmi
 * @subpackage	master-ds
 *
 * @copyright	GetMyInvoices
 */

/**
 * Include configuration
 */
include_once('configs/config.inc.php');

verify_login();
verify_has_access(array('overview'));
$selected_menu = 'overview';

$GLOBALS['config']['cms']['title'] = $GLOBALS['i18']['overview'] . ' - ' . $GLOBALS['config']['cms']['title'];

/**
 * BEGIN - Include CSS
 */
include_once($GLOBALS['config']['cms']['design_path'].'base/header.inc.php');
/**
 * END - Include CSS
 */

/**
 * BEGIN - Include JS
 */
/**
 * END - Include JS
 */

/**
 * BEGIN - Business Logic
 */

if(io_is_dir($GLOBALS['config']['cms']['cache_folder'].'ds_systems_data')) {
	if(io_file_exists($GLOBALS['config']['cms']['cache_folder'].'ds_systems_data/data.txt')) {
		$file_content = file_get_contents($GLOBALS['config']['cms']['cache_folder'].'ds_systems_data/data.txt');
		$ds_systems_data = json_decode($file_content, true);
	}
}

/**
 * END - Business Logic
 */

/**
 * BEGIN - HTML Output
 */
?>

	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="portlet light ">
				<div class="portlet-title">
					<div class="caption caption-md">
						<span class="caption-subject font-green-steel bold uppercase"><?php echo $GLOBALS['i18']['overview']; ?></span>
						<span class="caption-helper"><?php echo $GLOBALS['i18']['overview_helptext']; ?></span>
					</div>

					<div class="actions">
					</div>
				</div>
				<div class="portlet-body overview-content">
					<?php if(!empty($ds_systems_data)) { ?>
						<?php foreach($GLOBALS['ds_systems'] as $ds_system) { ?>
							<?php if(array_key_exists(str_replace(' ', '_', $ds_system['name']), $ds_systems_data) && array_key_exists($_SESSION['username'], $ds_systems_data[str_replace(' ', '_', $ds_system['name'])])) { ?>
								<h4><?php echo $ds_system['name']; ?></h4>
								<div class="container-fluid">
									<div id="list-<?php echo str_replace(' ', '_', $ds_system['name']); ?>" class="row list-row">
										<div class="list-container">
											<div class="page-header-menu">
												<div class="hor-menu">
													<ul class="nav navbar-nav">
														<?php
														$new_portals = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['new_portals']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['new_portals']['permission'];
														$broken_portal = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['broken_portal']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['broken_portal']['permission'];
														$portal_setting = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['portal_setting']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['portal_setting']['permission'];
														$failed_portals = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['failed_portals']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['failed_portals']['permission'];
														$invoice_review = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['invoice_review']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['invoice_review']['permission'];
														$test_portal_script = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['test_portal_script']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['test_portal_script']['permission'];
														$mail_import_rule = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['mail_import_rule']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['mail_import_rule']['permission'];
                                                        $mail_auto_rules = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['auto_rules']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['auto_rules']['permission'];
                                                        $mail_exclusion_rules = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['exclusion_rules']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['exclusion_rules']['permission'];
                                                        $mail_skipped_detections = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['skipped_detections']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['skipped_detections']['permission'];

														$manual_processes = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['manual_processes']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['manual_processes']['permission'];
														$script_tickets = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['script_tickets']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['script_tickets']['permission'];
														$skipped_detections = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['skipped_detections']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['skipped_detections']['permission'];
														$servers = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['servers']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['servers']['permission'];
														$url_monitoring = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['url_monitoring']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['url_monitoring']['permission'];
                                                        $aws_servers = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['aws_servers']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['aws_servers']['permission'];
                                                        $aws_users = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['aws_users']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['aws_users']['permission'];
                                                        $security_groups = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['security_groups']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['security_groups']['permission'];
                                                        $aws_buckets = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['aws_buckets']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['aws_buckets']['permission'];
                                                        $dev_machines = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['servers']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['servers']['permission'];
                                                        $cron_monitoring = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['cron_monitoring']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['cron_monitoring']['permission'];
                                                        $disc_monitoring = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['disc_monitoring']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['disc_monitoring']['permission'];
                                                        $snapshot_monitoring = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['snapshot_monitoring']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['snapshot_monitoring']['permission'];
                                                        $mysql_backup_monitoring = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['mysql_backup_monitoring']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['mysql_backup_monitoring']['permission'];
														$manual_upload = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['manual_upload']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['manual_upload']['permission'];

														$transactions = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['transactions']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['transactions']['permission'];
														$banking_transactions = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['banking_transactions']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['banking_transactions']['permission'];
														$assigned_transactions = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['assigned_transactions']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['assigned_transactions']['permission'];

														?>
														<div style="float: left;" class="dropdown dropup portal-dropdown-list <?php if(($new_portals && $broken_portal && $portal_setting) || (!in_array('new_portals', $ds_system['elements']) && !in_array('broken_portal', $ds_system['elements']) && !in_array('portal_setting', $ds_system['elements']))) { echo ' hidden';} ?>">
															<a class="main-dropdown-button dropdown-toggle" type="button" data-toggle="dropdown"> <?php echo $GLOBALS['i18']['portals']; ?> </a>
															<ul class="dropdown-menu dropdown-content">
																<?php if(($new_portals || !in_array('new_portals', $ds_system['elements']))){ ?>
																	<li>
																		<a target="_blank" href="<?php echo $ds_system['url']; ?>portal_creation_queue.php">
																			<?php echo $GLOBALS['i18']['new_portals']; ?>
																			<span class="notif_badge badge badge-danger menu_badge new_portal_badge hidden">0</span>
																		</a>
																	</li>
																<?php } ?>
																<?php if(($broken_portal || !in_array('broken_portal', $ds_system['elements']))){ ?>
																	<li>
																		<a target="_blank" href="<?php echo $ds_system['url']; ?>portal_updates.php">
																			<?php echo $GLOBALS['i18']['update_portals_Data']; ?>
																			<span class="notif_badge badge badge-danger menu_badge broken_portal_badge hidden">0</span>
																		</a>
																	</li>
																<?php } ?>
																<?php if(($portal_setting || !in_array('portal_setting', $ds_system['elements']))){ ?>
																	<li>
																		<a target="_blank" href="<?php echo $ds_system['url']; ?>portals.php">
																			<?php echo $GLOBALS['i18']['portals_settings']; ?>
																		</a>
																	</li>
																<?php } ?>
															</ul>
														</div>
														<li class="<?php if($manual_processes || !in_array('manuals', $ds_system['elements'])) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>manuals.php">
																<?php echo $GLOBALS['i18']['manuals']; ?>
																<span class="notif_badge badge badge-danger menu_badge manual_processes_badge hidden">0</span>
															</a>
														</li>
														<li class="<?php if($failed_portals || !in_array('credential_problems', $ds_system['elements'])) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>credential_problems.php">
																<?php echo $GLOBALS['i18']['problems']; ?>
																<span class="notif_badge badge badge-danger menu_badge new_login_badge hidden">0</span>
															</a>
														</li>
														<li class="<?php if($script_tickets || !in_array('script_tickets', $ds_system['elements'])) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>script_tickets.php">
																<?php echo $GLOBALS['i18']['tickets']; ?>
																<span class="notif_badge badge badge-danger menu_badge script_tickets_badge hidden">0</span>
															</a>
														</li>
                                                        <li class="portal-dropdown-list <?php if(($mail_import_rule && $mail_auto_rules && $mail_exclusion_rules && $mail_skipped_detections) || !in_array('mail_import_rule', $ds_system['elements'])) { echo ' hidden';} ?>">
                                                            <div class="dropdown">
                                                                <a class="main-dropdown-button"> <?php echo $GLOBALS['i18']['mails']; ?> <i class="fa fa-caret-down"></i>
                                                                    <span class="notif_badge badge badge-danger menu_badge mail_import_rule_badge hidden">0</span>
                                                                </a>
                                                                <div class="dropdown-content">
                                                                    <?php
                                                                    if(!$mail_auto_rules) {
                                                                        echo '<a target="_blank" href="'.$ds_system['url'].'auto_rules.php"> '.$GLOBALS['i18']['mail_auto_rules'].'</a>';
                                                                    }
                                                                    if(!$mail_exclusion_rules) {
                                                                        echo '<a target="_blank" href="'.$ds_system['url'].'exclusion_rules.php"> '.$GLOBALS['i18']['mail_exclusion_rules'].'</a>';
                                                                    }
                                                                    if(!$mail_import_rule) {
                                                                        ?>
                                                                        <a target="_blank"
                                                                           href="<?php echo $ds_system['url']; ?>mail_import_rule.php">
                                                                            <?php echo $GLOBALS['i18']['mail_import_rule']; ?>
                                                                            <span class="notif_badge badge badge-danger menu_badge mail_import_rule_badge hidden">0</span>
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                    if(!$mail_skipped_detections) {
                                                                        echo '<a target="_blank" href="'.$ds_system['url'].'skipped_detections.php"> '.$GLOBALS['i18']['mail_skipped_detections'].'</a>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <?php if(!$transactions || !$banking_transactions || !$assigned_transactions) { ?>
                                                            <li class="portal-dropdown-list">
                                                                <div class="dropdown">
                                                                    <a class="main-dropdown-button transactions_menu"> 
                                                                    	<?php echo $GLOBALS['i18']['banking']; ?>
                                                                        <span class="notif_badge badge badge-danger menu_badge transactions_menu_badge hidden">0</span>
                                                                        <i class="fa fa-caret-down"></i>
                                                                    </a>
                                                                    <div class="dropdown-content">
                                                                        <?php
                                                                        if(!$banking_transactions) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'banking_transactions.php"> '.$GLOBALS['i18']['banking_transactions'].'
                                                                                <span class="notif_badge badge badge-danger menu_badge banking_transactions_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$assigned_transactions) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'assigned_transactions.php"> '.$GLOBALS['i18']['assigned_transactions'].' <span class="notif_badge badge badge-danger menu_badge assigned_transactions_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </li>
														<?php } ?>

														<li class="<?php if($servers || !in_array('servers', $ds_system['elements'])) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>servers.php">
																<?php echo $GLOBALS['i18']['servers']; ?>
															</a>
														</li>
														<li class="<?php if($test_portal_script || !in_array('test_portal_script', $ds_system['elements'])) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>test_portal_script.php">
																<?php echo $GLOBALS['i18']['test_engine']; ?>
																<span class="notif_badge badge badge-danger menu_badge test_portal_script_badge hidden">0</span>
															</a>
														</li>
														<li class="<?php if($manual_upload || !in_array('manual_upload', $ds_system['elements'])) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>manual_upload.php">
																<?php echo $GLOBALS['i18']['manual_uplaod']; ?>
															</a>
														</li>
														<li class="<?php if($invoice_review || !in_array('invoice_review', $ds_system['elements'])) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>invoice_review.php">
																<?php echo $GLOBALS['i18']['reviews']; ?>
																<span class="notif_badge badge badge-danger menu_badge inv_review_badge hidden">0</span>
															</a>
														</li>
														<?php
														$live_recording_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['live_recording']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['live_recording']['permission'];
														$corrections_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_invoices']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_invoices']['permission'];
														$corrections_fino_kws_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_fino_kws']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_fino_kws']['permission'];
														$corrections_statement_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_statement']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_statement']['permission'];
														$correction_securities_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['correction_securities']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['correction_securities']['permission'];
														$correction_remittances_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['correction_remittances']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['correction_remittances']['permission'];
														$corrections_invoices_line_items_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_invoices_line_items']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_invoices_line_items']['permission'];
														$corrections_invoices_payment_method_permission = !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_invoices_payment_method']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_invoices_payment_method']['permission'];
														?>


														<div style="float: left;" class="dropdown dropup portal-dropdown-list <?php if(($correction_remittances_permission && $corrections_invoices_line_items_permission && $corrections_invoices_payment_method_permission && $corrections_permission && $corrections_fino_kws_permission && $corrections_statement_permission && $correction_securities_permission) || (!in_array('corrections_invoices', $ds_system['elements']) && !in_array('corrections_fino_kws', $ds_system['elements']) && !in_array('corrections_statement', $ds_system['elements']) && !in_array('correction_securities', $ds_system['elements']) && !in_array('correction_remittances', $ds_system['elements']) && !in_array('corrections_invoices_line_items', $ds_system['elements']) && !in_array('correction_remittances', $ds_system['elements']))) { echo ' hidden';} ?>">
															<a class="main-dropdown-button dropdown-toggle" type="button" data-toggle="dropdown"> <?php echo $GLOBALS['i18']['corrections']; ?> </a>
															<ul class="dropdown-menu dropdown-content">
																<?php if(!$corrections_permission){ ?>
																	<li>
																		<a class="<?php if($corrections_permission) { echo ' hidden';} ?>" target="_blank" href="<?php echo $ds_system['url']; ?>corrections_invoices.php">
																			<?php echo $GLOBALS['i18']['invoices']; ?>
																			<span class="notif_badge badge badge-danger menu_badge correction_badge hidden">0</span>
																		</a>
																	</li>
																<?php } ?>
																<?php if(!$corrections_invoices_payment_method_permission || !$corrections_invoices_line_items_permission) { ?>
																	<li class="dropdown-submenu">
																		<a tabindex="-1" href="#"><?php echo $GLOBALS['i18']['invoices_parts']; ?> </a>
																		<ul class="dropdown-menu">

																			<li <?php if($corrections_invoices_payment_method_permission) {echo 'class="hidden"';} ?>>
																				<a tabindex="-1" target="_blank" href="<?php echo $ds_system['url']; ?>corrections_invoices_payment_method.php">
																					<?php echo $GLOBALS['i18']['corrections_invoices_payment_method']; ?>
																					<span class="notif_badge badge badge-danger menu_badge corrections_invoices_payment_method_badge hidden">0</span>
																				</a>
																			</li>
																			<li <?php if($corrections_invoices_payment_method_permission) {echo 'class="hidden"';} ?>>
																				<a tabindex="-1" target="_blank" href="<?php echo $ds_system['url']; ?>corrections_invoices_line_items.php">
																					<?php echo $GLOBALS['i18']['corrections_invoices_line_items']; ?>
																					<span class="notif_badge badge badge-danger menu_badge corrections_invoices_line_items_badge hidden">0</span>
																				</a>
																			</li>
																		</ul>
																	</li>
																<?php } ?>


																<?php if(!$correction_remittances_permission){ ?>
																	<li>
																		<a class="<?php if($correction_remittances_permission) { echo ' hidden';} ?>" target="_blank" href="<?php echo $ds_system['url']; ?>correction_remittances.php">
																			<?php echo $GLOBALS['i18']['correction_remittances']; ?>
																			<span class="notif_badge badge badge-danger menu_badge correction_remittances_badge hidden">0</span>
																		</a>
																	</li>
																<?php } ?>
																<?php if(!$corrections_statement_permission){ ?>
																	<li>
																		<a class="<?php if($corrections_statement_permission) { echo ' hidden';} ?>" target="_blank" href="<?php echo $ds_system['url']; ?>corrections_statement.php">
																			<?php echo $GLOBALS['i18']['statement']; ?>
																			<span class="notif_badge badge badge-danger menu_badge correction_statement_badge hidden">0</span>
																		</a>
																	</li>
																<?php } ?>
																<?php if(!$correction_securities_permission){ ?>
																	<li>
																		<a class="<?php if($correction_securities_permission) { echo ' hidden';} ?>" target="_blank" href="<?php echo $ds_system['url']; ?>correction_securities.php">
																			<?php echo $GLOBALS['i18']['securities_settlements']; ?>
																			<span class="notif_badge badge badge-danger menu_badge correction_securities_badge hidden">0</span>
																		</a>
																	</li>
																<?php } ?>
																<?php if(!$corrections_fino_kws_permission){ ?>
																	<li>
																		<a class="<?php if($corrections_fino_kws_permission) { echo ' hidden';} ?>" target="_blank" href="<?php echo $ds_system['url']; ?>corrections_fino_kws.php">
																			<?php echo $GLOBALS['i18']['fino_kws']; ?>
																			<span class="notif_badge badge badge-danger menu_badge correction_fino_kws_badge hidden">0</span>
																		</a>
																	</li>
																<?php } ?>
															</ul>
														</div>

														<li class="<?php if($live_recording_permission) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>live_recording.php">
																<?php echo $GLOBALS['i18']['live_recording']; ?>
																<span class="notif_badge badge badge-danger menu_badge live_recording_badge hidden">0</span>
															</a>
														</li>
														<li class="<?php if(
																(
																	!in_array('live_validation', $ds_system['elements']) ||
																	!isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['live_validation']['permission']) ||
																	!$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['live_validation']['permission']
																) &&
																(
																	!in_array('background_queue', $ds_system['elements']) ||
																	!isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['background_queue']['permission']) ||
																	!$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['background_queue']['permission']
																) &&
																(
																	!in_array('corrections_portal', $ds_system['elements']) ||
																	!isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_portal']['permission']) ||
																	!$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_portal']['permission']
																)
														) { echo ' hidden';} ?> dropdown dropup portal-dropdown-list">
															<a class="main-dropdown-button dropdown-toggle" type="button" data-toggle="dropdown" target="#"><?php echo $GLOBALS['i18']['live_validation']; ?> <span class="notif_badge badge badge-danger menu_badge live_validation_badge hidden">0</span></a>
															<ul class="dropdown-menu dropdown-content">
																<li <?php if(!in_array('live_validation', $ds_system['elements']) ||
																	!isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['live_validation']['permission']) ||
																	!$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['live_validation']['permission']) {echo 'class="hidden"';} ?>>
																	<a tabindex="-1" target="_blank" href="<?php echo $ds_system['url']; ?>live_validation.php">
																		Liew View
																		<span class="notif_badge badge badge-danger menu_badge live_view_badge hidden">0</span>
																	</a>
																</li>
																<li <?php if(!in_array('background_queue', $ds_system['elements']) ||
																	!isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['background_queue']['permission']) ||
																	!$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['background_queue']['permission']) {echo 'class="hidden"';} ?>>
																	<a tabindex="-1" target="_blank" href="<?php echo $ds_system['url']; ?>background_queue.php">
																		Document Review
																		<span class="notif_badge badge badge-danger menu_badge background_queue_badge hidden">0</span>
																	</a>
																</li>
																<li <?php if(!in_array('corrections_portal', $ds_system['elements']) ||
																	!isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_portal']['permission']) ||
																	!$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['corrections_portal']['permission']) {echo 'class="hidden"';} ?>>
																	<a tabindex="-1" target="_blank" href="<?php echo $ds_system['url']; ?>corrections_portal.php">
																		Document Review
																		<span class="notif_badge badge badge-danger menu_badge corrections_portal_badge hidden">0</span>
																	</a>
																</li>
															</ul>
														</li>
														<li class="<?php if(!in_array('company_validation', $ds_system['elements']) || !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['company_validation']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['company_validation']['permission']) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>company_validation.php">
																<?php echo $GLOBALS['i18']['company_validation']; ?>
																<span class="notif_badge badge badge-danger menu_badge company_validation_badge hidden">0</span>
															</a>
														</li>
														<li class="<?php if(!in_array('company_detection', $ds_system['elements']) || !isset($ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['company_detection']['permission']) || !$ds_systems_data[str_replace(' ', '_', $ds_system['name'])][$_SESSION['username']]['company_detection']['permission']) { echo ' hidden';} ?>">
															<a target="_blank" href="<?php echo $ds_system['url']; ?>company_detections.php">
																<?php echo $GLOBALS['i18']['company_detection']; ?>
																<span class="notif_badge badge badge-danger menu_badge company_detection_badge hidden">0</span>
															</a>
														</li>
														<?php if(!$url_monitoring || !$aws_servers || !$aws_users || !$security_groups || !$dev_machines || !$cron_monitoring || !$disc_monitoring || !$snapshot_monitoring || !$mysql_backup_monitoring) { ?>
                                                            <li class="portal-dropdown-list">
                                                                <div class="dropdown">
                                                                    <a class="main-dropdown-button sysops_menu"> SysOps
                                                                        <span class="notif_badge badge badge-danger menu_badge sysops_menu_badge hidden">0</span>
                                                                        <i class="fa fa-caret-down"></i>
                                                                    </a>
                                                                    <div class="dropdown-content">
                                                                        <?php
                                                                        if(!$dev_machines) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'servers.php"> Dev Machines</a>';
                                                                        }
                                                                        if(!$aws_servers) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'aws_servers.php"> AWS Servers
                                                                                <span class="notif_badge badge badge-danger menu_badge aws_server_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$aws_users) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'aws_iam_users.php"> AWS IAM Users
                                                                                <span class="notif_badge badge badge-danger menu_badge aws_users_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$security_groups) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'security_groups.php"> Security Groups
                                                                                <span class="notif_badge badge badge-danger menu_badge security_groups_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$aws_buckets) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'aws_buckets.php"> S3 Buckets
                                                                                <span class="notif_badge badge badge-danger menu_badge aws_buckets_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$url_monitoring) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'url_monitoring.php"> URL Monitoring</a>';
                                                                        }
                                                                        if(!$cron_monitoring) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'cron_monitoring.php"> Cron Monitoring
                                                                            	<span class="notif_badge badge badge-danger menu_badge failed_cron_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$disc_monitoring) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'disc_monitoring.php"> Disc Monitoring
                                                                            	<span class="notif_badge badge badge-danger menu_badge failed_dm_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$snapshot_monitoring) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'snapshot_monitoring.php"> Snapshot Monitoring
                                                                            	<span class="notif_badge badge badge-danger menu_badge failed_sm_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        if(!$mysql_backup_monitoring) {
                                                                            echo '<a target="_blank" href="'.$ds_system['url'].'backup_monitoring.php"> MySQL Backup Monitoring
                                                                            	<span class="notif_badge badge badge-danger menu_badge failed_bm_badge hidden">0</span>
                                                                            </a>';
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </li>
														<?php } ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

<?php

/**
 * END - HTML Output
 */
include_once($GLOBALS['config']['cms']['design_path'].'base/footer.inc.php');
?>
