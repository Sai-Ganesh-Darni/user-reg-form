document.addEventListener('DOMContentLoaded', function () {
    const addEventListenerOrig = EventTarget.prototype.addEventListener;
    EventTarget.prototype.addEventListener = function (type, listener, options) {
        if (type === 'touchstart' || type === 'touchmove') {
            if (typeof options === 'object') {
                options.passive = true;
            } else {
                options = { passive: true };
            }
        }
        addEventListenerOrig.call(this, type, listener, options);
    };
});

// document.addEventListener('touchstart', onTouchStart, {passive: true});

// view[0].addEventListener('touchstart', tap, {passive: true});
// view[0].addEventListener('touchmove', drag, {passive: true});
