<script>
    $(document).ready(function() {

        // Cropper ////////////////////////////////////////////////////////////////////////////////////

        var $useCropper = $('#useCropper');
        var $thumbnailCrop = $('#thumbnailCrop');
        var $thumbnailUpload = $('#thumbnailUpload');

        var useCropper = $useCropper.is(':checked');

        updateCropper();

        $useCropper.on('change', function(e) {
            useCropper = $useCropper.is(':checked');

            updateCropper();
        });

        function updateCropper() {
            if (useCropper) {
                $thumbnailUpload.addClass('hide');
                $thumbnailCrop.removeClass('hide');
            } else {
                $thumbnailCrop.addClass('hide');
                $thumbnailUpload.removeClass('hide');
            }
        }

        // Designers and artists //////////////////////////////////////////////////////////////////////

        $('.selectize').selectize();
        $('.add-designer').on('click', function(e) {
            e.preventDefault();
            addDesignerRow($(this));
        });

        function addDesignerRow($trigger) {
            var $clone = $('.designer-row').clone();
            $('#designerList').append($clone);
            $clone.removeClass('hide designer-row');
            $clone.addClass('d-flex');
            $clone.find('.add-designer').on('click', function(e) {
                e.preventDefault();
                addDesignerRow($(this));
            })
            $trigger.css({
                visibility: 'hidden'
            });
            $clone.find('.designer-select').selectize();
        }

        $('.add-artist').on('click', function(e) {
            e.preventDefault();
            addArtistRow($(this));
        });

        function addArtistRow($trigger) {
            var $clone = $('.artist-row').clone();
            $('#artistList').append($clone);
            $clone.removeClass('hide artist-row');
            $clone.addClass('d-flex');
            $clone.find('.add-artist').on('click', function(e) {
                e.preventDefault();
                addArtistRow($(this));
            })
            $trigger.css({
                visibility: 'hidden'
            });
            $clone.find('.artist-select').selectize();
        }

        // Traits /////////////////////////////////////////////////////////////////////////////////////
    
        var selectedFeatureIds;

        function updateSelectedFeatures() {
            selectedFeatureIds = [];
            $('.feature-select').each(function() {
                var selectedId = $(this).val();
                if (selectedId && selectedId !== '') {
                    selectedFeatureIds.push(selectedId);
                }
            });
            if (typeof checkIfChimeric === 'function') {
                checkIfChimeric(selectedFeatureIds);
            }
        }

        @if (config('lorekeeper.extensions.organised_traits_dropdown'))
            $('.initial.feature-select').selectize({
                render: {
                    item: featureSelectedRender
                },
                onChange: function(value) {
                    updateSelectedFeatures();
                }
            });
        @else
            $('.initial.feature-select').selectize({
                onChange: function(value) {
                    updateSelectedFeatures();
                }
            });
        @endif
        $('#add-feature').on('click', function(e) {
            e.preventDefault();
            addFeatureRow();
        });
        $('.remove-feature').on('click', function(e) {
            e.preventDefault();
            removeFeatureRow($(this));
        })

        function addFeatureRow() {
            var $clone = $('.feature-row').clone();
            $('#featureList').append($clone);
            $clone.removeClass('hide feature-row');
            $clone.addClass('d-flex');
            $clone.find('.remove-feature').on('click', function(e) {
                e.preventDefault();
                removeFeatureRow($(this));
            })
            @if (config('lorekeeper.extensions.organised_traits_dropdown'))
                $clone.find('.feature-select').selectize({
                    render: {
                        item: featureSelectedRender
                    },
                    onChange: function(value) {
                        updateSelectedFeatures();
                    }
                });
            @else
                $clone.find('.feature-select').selectize({
                    onChange: function(value) {
                        updateSelectedFeatures();
                    }
                });
            @endif
        }

        function removeFeatureRow($trigger) {
            $trigger.parent().remove();
            updateSelectedFeatures();
        }

        function featureSelectedRender(item, escape) {
            return '<div><span>' + escape(item["text"].trim()) + ' (' + escape(item["optgroup"].trim()) + ')' + '</span></div>';
        }

        // Genetics ///////////////////////////////////////////////////////////////////////////////////

        var primary = $('.genetics-primary-table tbody');
        var secondary = $('.genetics-secondary-table tbody');

        $('.add-primary-gene-button').click(function(e) {
            e.preventDefault();

            const newRow = primary.find('.gene-row:last').clone();
            const lastIndex = newRow.data('id');
            const newIndex = lastIndex + 1;
            newRow.attr('data-id', newIndex);

            newRow.find('select').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${newIndex}]`);
                $(this).attr('name', newName);
            });

            newRow.find('input').val('');
            primary.append(newRow);

            const count = primary.find('.gene-row').length;
            toggleDeleteButtons(primary, count > 1);
        });

        $('.add-secondary-gene-button').click(function(e) {
            e.preventDefault();

            const newRow = secondary.find('.gene-row:last').clone();
            const lastIndex = newRow.data('id');
            const newIndex = lastIndex + 1;
            newRow.attr('data-id', newIndex);

            newRow.find('select').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${newIndex}]`);
                $(this).attr('name', newName);
            });

            newRow.find('input').val('');
            secondary.append(newRow);

            const count = secondary.find('.gene-row').length;
            toggleDeleteButtons(secondary, count > 1);
        });

        primary.on('click', '.delete-gene-button', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();

            const count = primary.find('.gene-row').length;
            toggleDeleteButtons(primary, count > 1);
        });

        secondary.on('click', '.delete-gene-button', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();

            const count = secondary.find('.gene-row').length;
            toggleDeleteButtons(secondary, count > 1);
        });

        function toggleDeleteButtons(tbody, enabled = true) {
            tbody.find('.delete-gene-button').prop('disabled', !enabled);
        }

        // Croppie ////////////////////////////////////////////////////////////////////////////////////

        var thumbnailWidth = {{ config('lorekeeper.settings.masterlist_thumbnails.width') }};
        var thumbnailHeight = {{ config('lorekeeper.settings.masterlist_thumbnails.height') }};
        var $cropper = $('#cropper');
        var c = null;
        var $x0 = $('#cropX0');
        var $y0 = $('#cropY0');
        var $x1 = $('#cropX1');
        var $y1 = $('#cropY1');
        var zoom = 0;

        @if (isset($useUploaded) && $useUploaded)
            // This is for modification of an existing image:
            c = new Croppie($cropper[0], {
                viewport: {
                    width: thumbnailWidth,
                    height: thumbnailHeight
                },
                boundary: {
                    width: thumbnailWidth + 100,
                    height: thumbnailHeight + 100
                },
                update: function() {
                    updateCropValues();
                }
            });
            c.bind({
                url: $cropper.data('url'),
                // points: [$x0.val(),$x1.val(),$y0.val(),$y1.val()], // this does not work
            }).then(function() {
                updateCropValues();
            });
            console.log(($x1.val() - $x0.val()) / thumbnailWidth);
        @else
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $cropper.attr('src', e.target.result);
                        c = new Croppie($cropper[0], {
                            viewport: {
                                width: thumbnailWidth,
                                height: thumbnailHeight
                            },
                            boundary: {
                                width: thumbnailWidth + 100,
                                height: thumbnailHeight + 100
                            },
                            update: function() {
                                updateCropValues();
                            }
                        });
                        console.log(c);
                        updateCropValues();
                        $('#cropSelect').addClass('hide');
                        $cropper.removeClass('hide');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#mainImage").change(function() {
                readURL(this);
            });
        @endif

        function updateCropValues() {
            var values = c.get();
            console.log(values);
            //console.log([$x0.val(),$x1.val(),$y0.val(),$y1.val()]);
            $x0.val(values.points[0]);
            $y0.val(values.points[1]);
            $x1.val(values.points[2]);
            $y1.val(values.points[3]);
        }


    });
</script>
