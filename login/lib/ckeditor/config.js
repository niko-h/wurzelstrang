/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	config.extraPlugins = 'codemirror';
	// # Set this to the theme you wish to use (codemirror themes)
  config.codemirror_theme = 'monokai';
  // # Whether or not you want to show line numbers
  // config.codemirror_lineNumbers = false;
  // # Whether or not you want to use line wrapping
  // config.codemirror_lineWrapping = true;
  // # Whether or not you want to highlight matching braces
  // config.codemirror_matchBrackets = true;
  // # Whether or not you want tags to automatically close themselves
  // config.codemirror_autoCloseTags = false;
  // # Whether or not to enable search tools, CTRL+F (Find), CTRL+SHIFT+F (Replace), CTRL+SHIFT+R (Replace All), CTRL+G (Find Next), CTRL+SHIFT+G (Find Previous)
  // config.codemirror_enableSearchTools = true;
  // # Whether or not you wish to enable code folding (requires 'lineNumbers' to be set to 'true')
  // config.codemirror_enableCodeFolding = true;
  // # Whether or not to enable code formatting
  // config.codemirror_enableCodeFormatting = true;
  // # Whether or not to automatically format code should be done every time the source view is opened
  // config.codemirror_autoFormatOnStart = true;
  // # Whether or not to automatically format code which has just been uncommented
  // config.codemirror_autoFormatOnUncomment = true;
  // # Whether or not to highlight the currently active line
  // config.codemirror_highlightActiveLine = true;
  // # Whether or not to highlight all matches of current word/selection
  // config.codemirror_highlightMatches = true;
  // # Whether or not to display tabs
  // config.codemirror_showTabs = false;
  // # Whether or not to show the format button on the toolbar
  // config.codemirror_showFormatButton = true;
  // # Whether or not to show the comment button on the toolbar
  // config.codemirror_showCommentButton = true;
  // # Whether or not to show the uncomment button on the toolbar
  // config.codemirror_showUncommentButton = true;

	config.filebrowserBrowseUrl = './lib/kcfinder/browse.php?type=files';
	config.filebrowserImageBrowseUrl = './lib/kcfinder/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = './lib/kcfinder/browse.php?type=flash';
	config.filebrowserUploadUrl = './lib/kcfinder/upload.php?type=files';
	config.filebrowserImageUploadUrl = './lib/kcfinder/upload.php?type=images';
	config.filebrowserFlashUploadUrl = './lib/kcfinder/upload.php?type=flash';

	config.width = 590;
	config.autoGrow_maxHeight = 800;
  config.enterMode = CKEDITOR.ENTER_BR;
  config.shiftEnterMode = CKEDITOR.ENTER_P;

  config.entities  = false;
  config.basicEntities = true;

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbar = 'Toolbar';
	config.toolbar_Toolbar = [
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'tools', items : [ 'Maximize' ] }
	];

	  // Load the German interface.
  config.language = 'de';

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Subscript,Superscript';

};

