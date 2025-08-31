(function($) {
    'use strict';

    $(function() {
        const filterForm = $('#cat-filter-form');
        const catGrid = $('#cat-grid');
        const statusFilter = $('#adoption_status_filter');
        const breedFilter = $('#cat_breed_filter');

        function performAjaxRequest() {
            const status = statusFilter.val();
            const breed = breedFilter.val();

            // Show a simple loading state
            catGrid.html('<p>Loading...</p>');

            $.ajax({
                url: omnikitty_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: omnikitty_ajax_object.action,
                    security: omnikitty_ajax_object.nonce,
                    adoption_status_filter: status,
                    cat_breed_filter: breed
                },
                success: function(response) {
                    catGrid.html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error: ' + textStatus, errorThrown);
                    catGrid.html('<p>An error occurred. Please try again.</p>');
                }
            });
        }

        // Perform AJAX request on filter change
        statusFilter.on('change', performAjaxRequest);
        breedFilter.on('change', performAjaxRequest);
    });

})(jQuery);
