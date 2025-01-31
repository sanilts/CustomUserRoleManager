jQuery(document).ready(function($) {
    // Role Manager functionality
    $('#add-role').on('click', function() {
        var template = $('#role-row-template').html();
        var index = $('.curm-role-row').length;
        template = template.replace(/\{index\}/g, index);
        $('#curm-roles-table tbody').append(template);
    });

    $(document).on('click', '.remove-role', function() {
        $(this).closest('tr').remove();
    });
});