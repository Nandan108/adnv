(function ($) {

    // Use: <div class='poppy' id='my-popup-id'></div>
    // $my_trigger_button.poppy("my_popup_id" | $myPopupJqObject)
    $.fn.poppy = function ($target, addCloseButton = true) {
        // create overlay and place it
        $poppyScreen = $('<div id="poppyScreen"></div>');
        $("body").prepend($poppyScreen);

        $poppy = (typeof $target === 'string' || $target instanceof String ? $("#" + $target) : $target);
        $poppy.addClass('poppy').hide();

        // Add 'close' and 'min' buttons to popup if needed
        if (addCloseButton) {
            $poppy.not($poppy.has('.close-btn'))
                .prepend('<div class="close-btn"></div><div class="min-btn"></div>');
        }

        // make sure poppy pop-ups get resized according to window dimensions
        // function resizePoppy() {
        //     var l = $(window).width() / 4;
        //     // var w = l + "px";
        //     $poppy.css({ "left": 320 });
        // }
        // resizePoppy();
        // $(window).resize(resizePoppy);

        // when a close button or the overlay is clicked, fade out both overlay and popup
        function close() {
            $("#poppyScreen, .poppy").fadeOut();
            $('body').off('keydown');
        };
        $(".close-btn, #poppyScreen").on("click", close);

        // on click on the trigger, show the popup !
        function open() {
            [$poppyScreen, $poppy].forEach($el => $el.fadeIn().focus());
            $('body').on('keydown', function (event) {
                console.log(event);
                if (event.key === "Escape") close();
            });
        };
        this.on("click", open);

        // return the "remote control" to open() or close() this popup
        return { open, close }
    };
})(jQuery);
