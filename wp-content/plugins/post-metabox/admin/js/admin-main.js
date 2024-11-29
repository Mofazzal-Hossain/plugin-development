(function ($) {
    $(document).ready(function () {
        $("#ptmb_book_year").datepicker();

        var $disabledResults = $("#ptmb_book_chapter");
            $disabledResults.select2({
            placeholder: "Select a chapter",
            allowClear: true,
        });

        var store_book_image_id = $("#ptmbBookCoverId").val();
        store_book_image_id = store_book_image_id ? store_book_image_id.split(";") : [];
        
        var store_book_image_url = $('#ptmbBookCoverUrl').val();
        store_book_image_url = store_book_image_url ? store_book_image_url.split(';') : [];
        for( i in store_book_image_url){
            console.log(store_book_image_url);
            $('.image-container').append('<img src="'+store_book_image_url[i]+'" id="'+store_book_image_id[i] +'" class="me-2" style="width:70px;height:70px" />');
        }

        // book image upload handle
        $("#uploadBookImage").on("click", function () {
            frame = wp.media({
                title: "Select book cover image",
                button: {
                    text: "Insert image",
                },
                multiple: true,
            });

            frame.on("select", function () {
                var attachment = frame.state().get('selection').toJSON();
                // var bookID = attachment.id;
                // var bookURL = attachment.url;

                var book_image_ids = [];
                var book_image_urls = [];
                var imageContainer = $('.image-container');

                console.log(attachment);

                imageContainer.html('');
                for(i in attachment){
                    // var attachment = attachment[i];
                    console.log(attachment);
                    book_image_ids.push(attachment[i].id);
                    book_image_urls.push(attachment[i].url);
                    imageContainer.append('<img src="'+attachment[i].url+'" alt="'+attachment[i].title+'" class="me-2" style="width:70px;height:70px" />');
                }
                
                $("#ptmbBookCoverId").val(book_image_ids.join(';'));
                $("#ptmbBookCoverUrl").val(book_image_urls.join(';'));
               
                // console.log( book_image_ids);
                // console.log( book_image_urls);
            });

            frame.open();
            return false;
        });
    });
})(jQuery);
