jQuery(document).ready(function($){  
      console.log('aaaa');
        $(document).on('change',$('.list-tags input'), function() {
            var selectedTags = $('.list-tags input:checked').map(function() {
                return this.value;
            }).get();
            $('input[name="tags"]').val(JSON.stringify(selectedTags));
        });
        $(document).on('click',$('.form-search-block button'),function(e) {
            e.preventDefault();
            var formData = new FormData();
            formData.append("action", "search_ajax");
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