/**
 * Hiển thị phương thức xóa tin nhắn thay vì thực hiện trên một trang riêng biệt.
 *
 * @module     local_message
 */
define(['jquery', 'core/modal_factory', 'core/str', 'core/modal_events', 'core/ajax', 'core/notification'], function($, ModalFactory, String, ModalEvents, Ajax, Notification) {
    var trigger = $('.local_message_delete_button');
    ModalFactory.create({
        type: ModalFactory.types.SAVE_CANCEL,
        title: String.get_string('delete_message', 'local_message'),
        body: String.get_string('delete_message_confirm', 'local_message'),
        preShowCallback: function(triggerElement, modal) {
            // Làm gì trước khi hiển thị phương thức xóa.
            triggerElement = $(triggerElement);

            let classString = triggerElement[0].classList[0]; 
            let messageid = classString.substr(classString.lastIndexOf('local_messageid') + 'local_messageid'.length);
            // Đặt id tin nhắn trong phương thức 
            modal.params = {'messageid': messageid};
            modal.setSaveButtonText(String.get_string('delete_message', 'local_message'));
        },
        large: true,
    }, trigger)
        .done(function(modal) {
            // Làm gì với phương thức mới
            modal.getRoot().on(ModalEvents.save, function(e) {
                // Dừng hành vi lưu mặc định để đóng phương thức.
                e.preventDefault();

                let footer = Y.one('.modal-footer');
                footer.setContent('Deleting...');
                let spinner = M.util.add_spinner(Y, footer);
                spinner.show();
                let request = {
                    methodname: 'local_message_delete_message',
                    args: modal.params,
                };
                Ajax.call([request])[0].done(function(data) {
                    if (data === true) {
                        // Chuyển hướng lý trang.
                        window.location.reload();
                    } else {
                        Notification.addNotification({
                            message: String.get_string('delete_message_failed', 'local_message'),
                            type: 'error',
                        });
                    }
                }).fail(Notification.exception);
            });
        });

});
