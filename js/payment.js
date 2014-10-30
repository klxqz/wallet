(function($) {
    "use strict";
    $.wallet = {
        paymentInit: function() {
            /* init settings */
            var self = this;

            $('#s-settings-payment').sortable({
                distance: 5,
                opacity: 0.75,
                items: '> tbody > tr:visible',
                handle: '.sort',
                cursor: 'move',
                tolerance: 'pointer',
                update: function(event, ui) {
                    var id = parseInt($(ui.item).data('id'));
                    var after_id = $(ui.item).prev().data('id');
                    if (after_id === undefined) {
                        after_id = 0;
                    } else {
                        after_id = parseInt(after_id);
                    }
                    self.paymentSort(id, after_id, $(this));
                }
            }).find(':not(:input)').disableSelection();

            this.paymentPluginDelete();
            this.paymentPluginAdd();
            this.paymentPluginConfig();

        },
        paymentSort: function(id, after_id, list) {
            $.post('?plugin=wallet&action=paymentSort', {
                module_id: id,
                after_id: after_id
            }, function(response) {
                if (response.error) {
                    $.shop.error('Error occurred while sorting payment plugins', 'error');
                    list.sortable('cancel');
                } else if (response.status != 'ok') {
                    $.shop.error('Error occurred while sorting payment plugins', response.errors);
                    list.sortable('cancel');
                }
            }, 'json').error(function(response) {
                list.sortable('cancel');
                $.shop.error('Error occurred while sorting payment plugins', 'error');
                return false;
            });
        },
        paymentPluginDelete: function() {
            $('#s-settings-payment').on('click', '.delete-button', function() {
                var url = '?plugin=wallet&action=paymentDelete';
                var plugin_id = $(this).data('id');
                var self = $(this);
                $.post(url, {
                    plugin_id: plugin_id
                }, function(response) {
                    self.closest('tr').remove();
                });
                return false;
            });
        },
        paymentPluginAdd: function() {
            var self = this;
            $('#s-payment-menu').on('click', '.add-button', function() {
                var id = $(this).data('id');
                self.messageDialog(id);
                return false;

            });
        },
        paymentPluginConfig: function() {
            var self = this;
            $('#s-settings-payment').on('click', '.config-button', function() {
                var id = $(this).data('id');
                self.messageDialog(id);
                return false;

            });
        },
        messageDialog: function(plugin_id) {
            plugin_id = plugin_id || null;
            var showDialog = function() {
                $('#wallet-dialog').waDialog({
                    disableButtonsOnSubmit: true,
                    onSubmit: function(d) {
                        var form = $(this);
                        if ($('#messagebox-description-content').length) {
                            $('#messagebox-description-content').waEditor('sync');
                        }
                        $.ajax({
                            type: 'POST',
                            url: form.attr('action'),
                            dataType: 'json',
                            data: form.serialize(),
                            success: function(data, textStatus, jqXHR) {
                                if (data.status == 'ok') {
                                    var tpl = $('#plugin-tmpl').tmpl(data.data.plugin);
                                    if (data.data.action == 'add') {
                                        $(tpl).appendTo('#s-settings-payment tbody');
                                    } else {
                                        $('#s-settings-payment tbody tr[data-id=' + data.data.plugin.id + ']').replaceWith(tpl);
                                    }


                                    $('#dialog-response').text(data.data.message);
                                    $('#dialog-response').css('color', 'green');
                                    $('.dialog-buttons .cancel').click();
                                } else if (data.status == 'fail') {
                                    $('#dialog-response').text(data.errors);
                                    $('#dialog-response').css('color', 'red');
                                }

                            }
                        });
                        return false;
                    }
                });
            };
            var d = $('#wallet-dialog');
            var p;
            if (!d.length) {
                p = $('<div></div>').appendTo('body');
            } else {
                p = d.parent();
            }
            if (plugin_id) {
                p.load('?plugin=wallet&action=dialog&plugin_id=' + plugin_id, showDialog);
            } else {
                p.load('?plugin=wallet&action=dialog', showDialog);
            }
        },
    };
})(jQuery);