jQuery(document).ready(function ($) {

    $('#color-primary .wp-picker-container').on('change', function() {
        var color = $(this).find('.wp-color-picker').val(); // Get the value of the primary color picker
        // Update the value of the secondary color picker
        $('#color-secondary .wp-color-picker').val(color).change();
    });

    if (!window.hasOwnProperty('vc')) {
        return;
    }
    window.vcAdminPostTitle = vc.shortcode_view.extend({
        //Called every time when params is changed/appended. Also on first initialisation
        changeShortcodeParams: function (model) {
            var attr = Object.keys(model.attributes.params).filter(function (key) {
                return key.indexOf('_id') > 0;
            }).shift();
            var postID = model.attributes.params[attr];
            var data = {
                'action': 'vc_post_id_to_title',
                'post_id': postID
            };
            $.post(ajaxurl, data, function (response) {
                if (response.success) {
                    model.view.$el.find('.vc_admin_label').html(response.data);
                }
            });
            window.vcAdminPostTitle.__super__.changeShortcodeParams.call(this, model);
        }
    });
    window.vcLibraryOnEditPanelShown = function () {
        $('.vc_deprecated_text').each(function () {
            var $container = $(this);
            var $input = $container.find('input');
            if (!$input.val()) {
                $container.remove();
            }
        });
    };
});
