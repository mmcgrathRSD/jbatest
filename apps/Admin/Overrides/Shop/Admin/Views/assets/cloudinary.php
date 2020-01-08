<style type=text/css" xmlns="http://www.w3.org/1999/html">
    .cl-text {
        position: relative;
        max-width: 90px;
        color: red;
    }
</style>

<div>
    <div class="row">
        <div class="col-lg-12">
            <div id="uploader" class="panel panel-default">
                <div class="panel-body text-center">
                    <div class="row">
                        <div class="col-lg-8 col-md-offset-2">
                            <h1>FILES <strong>MUST</strong> BE NAMED <strong>MODELNUMBER_ORDER.EXT</strong></h1>

                            <p>Uploaded images may take 10 or more minutes to appear on the product page.</p>

                            <p>If files are named incorrectly they will not be attached to the product they belong to.<br>
                                Results will appear below, please check the status of all uploaded images to make sure they uploaded correctly.
                            </p>

                            <button id="upload" class="btn btn-default">Upload images</button>
                        </div>
                    </div>

                    <br />

                    <div class="row">
                        <div class="alert alert-info" role="alert">Images that appear red below have bad filenames and need to be re-uploaded.</div>
                    </div>

                    <br />

                    <div class="row" id="results"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//widget.cloudinary.com/global/all.js" type="text/javascript"></script>

<script type="text/javascript">
    $('#upload').cloudinary_upload_widget({
        cloud_name: 'rallysport',
        upload_preset: 'xmgr9x79',
        folder: 'product_images',
        theme: 'white',
        sources: ['local'],
        button_class: 'btn btn-primary',
        button_caption: 'Upload images'
    }, function(error, result) {
        if (!error) {
            $('.cloudinary-thumbnails').hide();

            var data = {
                images: []
            };

            for (var i=0; i < result.length; i++) {
                data.images[i] = {
                    original_filename: result[i].original_filename,
                    public_id: result[i].public_id
                }
            }

            $.post('/admin/shop/assets/massupload/completeupload', data, function(response) {
                $.each($.parseJSON(response), function(item, value) {
                    $('#results').append('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12"><div class="thumbnail ' + (value.error ? 'alert-danger' : 'alert-success' ) + '" style="width: 133px"><img src="' + value.thumb + '" /><div class="caption">' + value.filename + '</div></div>');
                });
            }).fail(function() {
                $('#results').append('<div class="alert alert-danger" role="alert">An API error occurred, please try again in 1 hour.  If the problem persists, blame the developers.</div>');
            });
        }
    });
</script>