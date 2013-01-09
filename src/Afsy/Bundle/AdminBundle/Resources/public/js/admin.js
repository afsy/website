jQuery(document).ready(function() {
    if (jQuery('#epiceditor').length && EpicEditor !== undefined) {

        var opts = {
          basePath: '/bundles/afsyadmin',
          clientSideStorage: false,
          parser: Markdown
        }

        var editor = new EpicEditor(opts).load();
        var markdownBody = jQuery('.markdown-body')[0];
        var htmlBody = jQuery('.html-body')[0];

        editor.importFile('osef', jQuery(markdownBody).attr('value'));

        jQuery('form').submit(function () {
            var html = editor.exportFile(null, 'html');

            if (0 < html.length) { // yoda condition yeah
                jQuery(htmlBody).attr('value', html);
                jQuery(markdownBody).attr('value', editor.exportFile(null, 'text'));
            }

            return true;
        });
    }

    if (jQuery('.jquery-tag-it').length > 0 && jQuery.fn.tagit) {
        jQuery('.jquery-tag-it').tagit({
            singleField: true
        });
    }
});
