<script>
    $(document).ready(function() {
        $('#userSelect').selectize();

        $('#ancestorSire').selectize();
        $('#ancestorDam').selectize();

        $('#ancestorSS').selectize();
        $('#ancestorSD').selectize();
        $('#ancestorDS').selectize();
        $('#ancestorDD').selectize();

        $('#ancestorSSS').selectize();
        $('#ancestorSSD').selectize();
        $('#ancestorSDS').selectize();
        $('#ancestorSDD').selectize();
        $('#ancestorDSS').selectize();
        $('#ancestorDSD').selectize();
        $('#ancestorDDS').selectize();
        $('#ancestorDDD').selectize();

        // Resell options /////////////////////////////////////////////////////////////////////////////

        var $resellable = $('#resellable');
        var $resellOptions = $('#resellOptions');

        var resellable = $resellable.is(':checked');

        updateOptions();

        $resellable.on('change', function(e) {
            resellable = $resellable.is(':checked');

            updateOptions();
        });

        function updateOptions() {
            if (resellable) $resellOptions.removeClass('hide');
            else $resellOptions.addClass('hide');
        }
    });
</script>
