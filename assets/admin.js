/**
 * Admin functions for
 * mapado settings page
 */

(function declaration($) {

    // Import pages
    $(function onready($) {
        var $userListsTable = $('#mapado_user_lists_table');
        if (!$userListsTable.length) {
            return true;
        }
        
        /* Slug in lower case */
        $userListsTable
            .on('change', '.list-slug', function () {
                $(this).val($(this).val().toLowerCase());
            })

            /* Import list action */
            .on('submit', 'form', function (e) {
                e.preventDefault();
                $form = $(this);
                $button = $form.find('.button');

                if (this['list-slug'].value == '') {
                    alert('Vous devez indiquer un slug pour votre liste.');
                    return false;
                }
                if ($button.hasClass('disabled')) {
                    alert('Une action est déjà en cours.');
                    return false;
                }

                /* Action to do */
                var action = this.action.value;
                if (action == 'delete') {
                    if (!confirm('Etes-vous sûr de vouloir supprimer cette liste ?')) {
                        return false;
                    }
                }

                /* Disable button during update */
                $button.addClass('disabled');

                $.post(ajaxurl, {
                    action: 'ajaxUpdateListSettings',
                    uuid: this.uuid.value,
                    slug: this['list-slug'].value,
                    title: this['list-title'].value,
                    mapado_action: action
                }, function (json) {
                    /* No error, refresh list */
                    if (json.state == 'updated') {
                        loadUserLists();
                        $('.mapado-setting-nav-item--options')
                            .toggleClass('mapado-setting-nav-item--disable', json.count == 0);
                    } else {
                        $button.removeClass('disabled');
                    }

                    $('#mapado-settings-updated').addClass(json.state).html('<p><strong>' + json.msg + '</strong></p>').show();

                }, 'json');
            });

        loadUserLists();

        /* Refresh user lists */
        $('#mapado_user_lists_refresh').click(function (e) {
            loadUserLists();

            e.preventDefault();
            return false;
        });
    });

    // Bandeau image position
    $(function onready($) {

        /* Thumb position support nice bandeau */
        var $positionRadios = $('[name="mapado_card_thumb_position"]');
        var $orientationRadios = $('[name="mapado_card_thumb_orientation"]');
        if (!$positionRadios.length || !$orientationRadios.length) {
            return true;
        }
        var oldUserOrientation;
        function updateOrientationForm() {
            if ($positionRadios.filter(':checked').val() == 'top') {
                oldUserOrientation = $orientationRadios.filter(':checked').val();
                $orientationRadios.attr('disabled', true)
                    .filter('[value="landscape"]')
                    .attr('checked', true);
            } else {
                $orientationRadios.attr('disabled', false);
                if (oldUserOrientation) {
                    $orientationRadios
                        .filter('[value="'+oldUserOrientation+'"]')
                        .attr('checked', true);
                    oldUserOrientation = null;
                }
            }
        }

        $positionRadios.on('change', updateOrientationForm);
        updateOrientationForm();
    });

    $(function onready($) {
        $(document).on('click', '.mpd-table-dropdown__trigger', function(){
            $(this).parents('tr').next('.mpd-table-dropdown__body').toggle();
        })
        .on('click', '.js-mapado_template_reset', function(){
            if (! confirm('Etes-vous sûr de vouloir revenir au modèle par défaut ?')) {
                return false;
            }
            var defaultValue = $(this).siblings('.js-mapado_template_default').html();
            $(this).siblings('.js-mapado_template_input').val(defaultValue);
        });
    });


    /**
     * Load dynamically user lists
     */
    function loadUserLists() {
        var $tbody = $('#mapado_user_lists_table tbody');
        $tbody.html('<tr><td colspan="3">' + ajaxUserLists.msg + '</td></tr>');
        
        if (ajaxUserLists.load === true) {
            $.post(ajaxurl, {
                action: 'ajaxGetUserLists'
            }, function (html) {
                $tbody.html(html);
            });
        }
    }

})(jQuery);