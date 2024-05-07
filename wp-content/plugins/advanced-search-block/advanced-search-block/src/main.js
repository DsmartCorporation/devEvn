jQuery(document).ready(function($){  
        $('.list-tags input').on('change', function() {
            var selectedTags = $('.list-tags input:checked').map(function() {
                return this.value;
            }).get();
            $('input[name="tags"]').val(JSON.stringify(selectedTags));
        });
        $('.form-search-block button').on('click',function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append("action", "search_ajax_block");
            formData.append("keyword", $('input[name="inputkeyword"]').val());
            formData.append("category", $('select[name="select-category"] option:checked').val());
            formData.append("tags", $('input[name="tags"]').val());
            $.ajax({
              url: "/wp-admin/admin-ajax.php",
              type: "POST",
              dataType: "json",
              contentType: false,
              processData: false,
              data: formData,
              beforeSend: function () {},
              success: function (data) {
                $('.list-post').html(data.content);
              },
              error: function () {},
            });
        });

    });