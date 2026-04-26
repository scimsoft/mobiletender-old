/**
 * Optional: call setNotification() from final thank-you page if needed.
 */
window.setNotification = function setNotification() {
    if (!('Notification' in window)) {
        return;
    }
    Notification.requestPermission().then(function (permission) {
        if (permission === 'granted') {
            new Notification('Bienvenido al mundo @Playaalta');
        }
    });
};

/** Legacy name from inline script on thank-you page */
window.set_notificacion = window.setNotification;
