/*
 * Chained - jQuery / Zepto chained selects plugin
 *
 * Copyright (c) 2010-2014 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/chained
 *
 * Version: 1.0.0
 *
 * Updated by Samuel de Rougemont on 2024-02-15
 *     - Use let, not var
 *     - Allow more than 2 parents
 *     - Added 2nd argument to $.chained(,class_value_separator) to allow
 *       specifying a compound class values separator other than the default "\".
 *     - Fix bug due to selector's parent order not being respected for
 *       multi-parent selects when checking which options match parent values.
 *     - Rather than disabling select when there's only the default option,
 *       make select read-only when there's no choice (i.e. zero or one option)
 */

;(function($, window, document, undefined) {
    "use strict";

    $.fn.chained = function(parent_selector, class_value_separator = '\\') {
        // prepare parent_selector as an array if it's not already one
        if (!Array.isArray(parent_selector)) {
            parent_selector = parent_selector.split(/\s*,\s*/);
        }

        // return array[parents] in the order of given selector
        function getParents() {
            return parent_selector.reduce((acc, sel) => acc.concat($(sel).toArray()), []);
        }

        /* Zepto class regexp dies with classes like foo\bar. */
        if (window.zepto) {
            class_value_separator = class_value_separator.replace('\\', '\\\\');
        }

        return this.each(function() {

            /* Save this to child because this changes when scope changes. */
            let $child_select = $(this);
            let $backup = $child_select.clone();

            getParents().forEach(parent => {
                // update children on parent change
                $(parent).on("change", updateChildren);
                // and update children right now!
                updateChildren();
            });

            function updateChildren() {
                let trigger_change = true;
                // remember the currently selected value
                let currently_selected_value = $("option:selected", $child_select).val();

                // restore original state before removing options that don't match parent's value
                $child_select.html($backup.html());

                let parent_chained_value = getParents()
                    // get parents' current value (e.g. foo\bar).
                    .map(parent => $("option:selected", parent).val())
                    // if there's more than one, joing them with separator (e.g. foo\bar)
                    .join(class_value_separator);

                // loop on options, removing options that are neither match the chained
                // parent's value nor are the default value (empty string).
                // Also, select any option whose val() === currently_selected_value
                $("option", $child_select).each(function() {
                    let $option = $(this), opt_val = $option.val();
                    if (!opt_val || $option.hasClass(parent_chained_value)) {
                        if (opt_val === currently_selected_value) {
                            $option.prop("selected", true);
                            trigger_change = false;
                        }
                    } else {
                        $option.remove();
                    }
                });

                // If there's only one or less options, make select read-only
                let no_choice = $("option", $child_select).size() <= 1;
                // Set readonly attr rather than prop, so that it can be styled.
                $child_select.attr('readonly', no_choice ? 'readonly' : null);

                if (trigger_change) {
                    $child_select.trigger("change");
                }
            }
        });
    };

    /* Alias for those who like to use more English like syntax. */
    $.fn.chainedTo = $.fn.chained;

    /* Default settings for plugin. */
    $.fn.chained.defaults = {};

})(window.jQuery || window.Zepto, window, document);