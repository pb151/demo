<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/respond.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>"></script>
<script src="<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/excanvas.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>"></script>
<![endif]-->

<script type="text/javascript">
	var app = new Object();
</script>

<script type="text/javascript" src="<?php echo $GLOBALS['config']['cms']['design_path']; ?>js/lang/<?php echo $_SESSION['cms']['language_code']; ?>.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>"></script>

<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/jquery.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/jquery-migrate.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/bootstrap/js/bootstrap.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/jquery.blockui.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/js.cookie.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>

<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/uniform/jquery.uniform.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>

<?php if( $_SESSION['cms']['language_code'] == 'de_de') { ?>
	<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/jquery-validation/js/localization/messages_de.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
	<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/jquery-validation/js/localization/methods_de.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
<?php } ?>

<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/bootstrap-toastr/toastr.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>"></script>

<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/scripts/app.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/layout/scripts/layout.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>
<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>admin/global/scripts/quick-sidebar.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>

<script src="/<?php echo $GLOBALS['config']['cms']['design_path']; ?>js/app.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

<script src="/<?php echo $GLOBALS['config']['cms']['theme_path']; ?>global/plugins/jquery-validation/js/jquery.validate.min.js?v=<?php echo $GLOBALS['config']['cms']['build_version'];?>" type="text/javascript"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<?php if(isset($GLOBALS['cms']['footerJSInlineCode'])) {
	echo $GLOBALS['cms']['footerJSInlineCode'];
} ?>
<?php
if(isset($GLOBALS['cms']['includeJS'])) {
	$include_js_set = array_unique($GLOBALS['cms']['includeJS']);
	foreach($include_js_set as $js) {
		echo '<script type="text/javascript" src="/'.$js.'?v='.$GLOBALS['config']['cms']['build_version'].'"></script>'."\n";
	}
	unset($js);
}
?>
<script type="text/javascript">
	var design_path = '<?php echo $GLOBALS['config']['cms']['design_path'];?>';
	var theme_path = '<?php echo $GLOBALS['config']['cms']['theme_path'];?>';
	$(document).ready(function() {
		<?php if(isset($GLOBALS['cms']['footerJS'])) {
		echo $GLOBALS['cms']['footerJS'];
	} ?>
		$("html").removeClass("loadstate");
	});
	var page_limit = '<?php echo $GLOBALS['config']['cms']['page_limit'];?>';
	var language_code = '<?php echo $_SESSION['cms']['language_code'];?>';
	<?php if(isset($GLOBALS['cms']['footerJSInline'])) {
		echo $GLOBALS['cms']['footerJSInline'];
	} ?>
</script>

</body>
<!-- END BODY -->
</html>