$().ready(function() {
      $('textarea.tinymce').tinymce({
        // Location of TinyMCE script
        script_url : 'tinymce/jscripts/tiny_mce.js',

        // General options
        language : "de", // change language here
        theme : "advanced",
        relative_urls : false,
        plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,jbimages,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

        // Theme options
        theme_advanced_buttons1 : "cut,copy,paste,pasteword,|,undo,redo,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,visualchars,nonbreaking,code,preview,fullscreen,help",
        theme_advanced_buttons2 : "fontselect,fontsizeselect,|,forecolor,|,bullist,numlist,|,outdent,indent,|,link,unlink,anchor,image,jbimages,|,insertdate,inserttime",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,

        // Example content CSS (should be your site CSS)
        content_css : "tinymce/contentstyles.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "tinymce/lists/template_list.js",
        external_link_list_url : "tinymce/lists/link_list.js",
        external_image_list_url : "tinymce/lists/autoimglist.php",
        media_external_list_url : "tinymce/lists/media_list.js",

      });
    });