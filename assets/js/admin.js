var frame, gframe;
(function ($) {
  $(document).ready(function () {
    // show already uploaded preview images
    var images_url = $("#wppool_zi_projects_images_url").val();
    images_url = images_url ? images_url.split(";") : [];
    for (i in images_url) {
      var _thumbnail_image_url = images_url[i];
      $("#images-container").append(
        `<img style="margin-left: 10px; margin-top: 10px;" src='${_thumbnail_image_url}' />`
      );
    }

    // handle upload preivew images
    $("#upload_images").on("click", function () {
      if (gframe) {
        gframe.open();
        return false;
      }

      gframe = wp.media({
        title: "Select Image",
        button: {
          text: "Insert Image",
        },
        multiple: true,
      });

      gframe.on("select", function () {
        var image_ids = [];
        var image_urls = [];
        var attachments = gframe.state().get("selection").toJSON();
        //console.log(attachments);
        $("#images-container").html("");
        console.log(attachments);
        for (i in attachments) {
          var attachment = attachments[i];
          image_ids.push(attachment.id);
          image_urls.push(attachment.sizes.thumbnail.url);
          $("#images-container").append(
            `<img style="margin-right: 10px; margin-top: 10px;" src='${attachment.sizes.thumbnail.url}' />`
          );
        }
        $("#wppool_zi_projects_images_id").val(image_ids.join(";"));
        $("#wppool_zi_projects_images_url").val(image_urls.join(";"));
      });

      gframe.open();
      return false;
    });
  });
})(jQuery);

