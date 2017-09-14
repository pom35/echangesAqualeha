
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

<!-- Section CSS -->
<!-- jQuery UI (REQUIRED) -->
<link rel="stylesheet" href="jquery/jquery-ui-1.12.0.css" type="text/css">

<!-- elfinder css -->
<link rel="stylesheet" href="css/commands.css"    type="text/css">
<link rel="stylesheet" href="css/common.css"      type="text/css">
<link rel="stylesheet" href="css/contextmenu.css" type="text/css">
<link rel="stylesheet" href="css/cwd.css"         type="text/css">
<link rel="stylesheet" href="css/dialog.css"      type="text/css">
<link rel="stylesheet" href="css/fonts.css"       type="text/css">
<link rel="stylesheet" href="css/navbar.css"      type="text/css">
<link rel="stylesheet" href="css/places.css"      type="text/css">
<link rel="stylesheet" href="css/quicklook.css"   type="text/css">
<link rel="stylesheet" href="css/statusbar.css"   type="text/css">
<link rel="stylesheet" href="css/theme.css"       type="text/css">
<link rel="stylesheet" href="css/toast.css"       type="text/css">
<link rel="stylesheet" href="css/toolbar.css"     type="text/css">

<!-- Section JavaScript -->
<!-- jQuery and jQuery UI (REQUIRED) -->
<script src="jquery/jquery-1.12.4.js" type="text/javascript" charset="utf-8"></script>
<script src="jquery/jquery-ui-1.12.0.js" type="text/javascript" charset="utf-8"></script>

<!-- elfinder core -->
<script src="js/elFinder.js"></script>
<script src="js/elFinder.version.js"></script>
<script src="js/jquery.elfinder.js"></script>
<script src="js/elFinder.mimetypes.js"></script>
<script src="js/elFinder.options.js"></script>
<script src="js/elFinder.options.netmount.js"></script>
<script src="js/elFinder.history.js"></script>
<script src="js/elFinder.command.js"></script>
<script src="js/elFinder.resources.js"></script>

<!-- elfinder dialog -->
<script src="js/jquery.dialogelfinder.js"></script>

<!-- elfinder default lang -->
<script src="js/i18n/elfinder.en.js"></script>

<!-- elfinder ui -->
<script src="js/ui/button.js"></script>
<script src="js/ui/contextmenu.js"></script>
<script src="js/ui/cwd.js"></script>
<script src="js/ui/dialog.js"></script>
<script src="js/ui/fullscreenbutton.js"></script>
<script src="js/ui/navbar.js"></script>
<script src="js/ui/navdock.js"></script>
<script src="js/ui/overlay.js"></script>
<script src="js/ui/panel.js"></script>
<script src="js/ui/path.js"></script>
<script src="js/ui/places.js"></script>
<script src="js/ui/searchbutton.js"></script>
<script src="js/ui/sortbutton.js"></script>
<script src="js/ui/stat.js"></script>
<script src="js/ui/toast.js"></script>
<script src="js/ui/toolbar.js"></script>
<script src="js/ui/tree.js"></script>
<script src="js/ui/uploadButton.js"></script>
<script src="js/ui/viewbutton.js"></script>
<script src="js/ui/workzone.js"></script>

<!-- elfinder commands -->
<script src="js/commands/archive.js"></script>
<script src="js/commands/back.js"></script>
<script src="js/commands/copy.js"></script>
<script src="js/commands/cut.js"></script>
<script src="js/commands/chmod.js"></script>
<script src="js/commands/colwidth.js"></script>
<script src="js/commands/download.js"></script>
<script src="js/commands/duplicate.js"></script>
<script src="js/commands/edit.js"></script>
<script src="js/commands/empty.js"></script>
<script src="js/commands/extract.js"></script>
<script src="js/commands/forward.js"></script>
<script src="js/commands/fullscreen.js"></script>
<script src="js/commands/getfile.js"></script>
<script src="js/commands/help.js"></script>
<script src="js/commands/hidden.js"></script>
<script src="js/commands/home.js"></script>
<script src="js/commands/info.js"></script>
<script src="js/commands/mkdir.js"></script>
<script src="js/commands/mkfile.js"></script>
<script src="js/commands/netmount.js"></script>
<script src="js/commands/open.js"></script>
<script src="js/commands/opendir.js"></script>
<script src="js/commands/paste.js"></script>
<script src="js/commands/places.js"></script>
<script src="js/commands/quicklook.js"></script>
<script src="js/commands/quicklook.plugins.js"></script>
<script src="js/commands/reload.js"></script>
<script src="js/commands/rename.js"></script>
<script src="js/commands/resize.js"></script>
<script src="js/commands/restore.js"></script>
<script src="js/commands/rm.js"></script>
<script src="js/commands/search.js"></script>
<script src="js/commands/selectall.js"></script>
<script src="js/commands/selectinvert.js"></script>
<script src="js/commands/selectnone.js"></script>
<script src="js/commands/sort.js"></script>
<script src="js/commands/undo.js"></script>
<script src="js/commands/up.js"></script>
<script src="js/commands/upload.js"></script>
<script src="js/commands/view.js"></script>
<script src="js/commands/sendmail.js"></script>

<!-- elfinder 1.x connector API support (OPTIONAL) -->
<script src="js/proxy/elFinderSupportVer1.js"></script>

<!-- Extra contents editors (OPTIONAL) -->
<script src="js/extras/editors.default.js"></script>

<!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
<script src="js/extras/quicklook.googledocs.js"></script>

<!-- elFinder Basic Auth JS -->
<script src="js/elfinderBasicAuth.js"></script>

<!-- elfinder initialization  -->
<script>
(function($){
	var i18nPath = 'js/i18n',
	start = function(lng) {
		$().ready(function() {
			var elf = $('#elfinder').elfinder({
				// Documentation for client options:
				// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
				baseUrl : './',
				lang : lng,
				allowShortcuts : false,
				url	 : 'php/connector.php'	// connector URL (REQUIRED)
			}).elfinder('instance');
		});
	},
	loct = window.location.search,
	full_lng, locm, lng;
	
	// detect language
	if (loct && (locm = loct.match(/lang=([a-zA-Z_-]+)/))) {
		full_lng = locm[1];
	} else {
		full_lng = (navigator.browserLanguage || navigator.language || navigator.userLanguage);
	}
	lng = full_lng.substr(0,2);
	if (lng == 'ja') lng = 'jp';
	else if (lng == 'pt') lng = 'pt_BR';
	else if (lng == 'zh') lng = (full_lng.substr(0,5) == 'zh-tw')? 'zh_TW' : 'zh_CN';
	
	if (lng != 'fr') {
		$.ajax({
			url : i18nPath+'/elfinder.'+lng+'.js',
			cache : true,
			dataType : 'script'
		})
		.done(function() {
			start(lng);
		})
		.fail(function() {
			start('fr');
		});
	} else {
		start(lng);
	}
})(jQuery);


</script>

</head>
<body>
<div id="elfinder"></div>
</body>
</html>

