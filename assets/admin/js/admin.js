var frame, gframe;
(function ($) {
  $(document).ready(function () {
    var image_url = $("#wppool_zi_projects_thumbnail_image_url").val();
    if (image_url) {
      $("#image-container").html(`<img src='${image_url}' />`);
    }

    // show already uploaded preview images
    var images_url = $("#wppool_zi_projects_images_url").val();
    images_url = images_url ? images_url.split(";") : [];
    for (i in images_url) {
      var _thumbnail_image_url = images_url[i];
      $("#images-container").append(
        `<img style="margin-left: 10px" src='${_thumbnail_image_url}' />`
      );
    }

    // handle thumbnail image upload
    $("#upload_image").on("click", function () {
      if (frame) {
        frame.open();
        return false;
      }

      frame = wp.media({
        title: "Select Image",
        button: {
          text: "Insert Image",
        },
        multiple: false,
      });

      frame.on("select", function () {
        var attachment = frame.state().get("selection").first().toJSON();
        console.log(attachment);
        $("#wppool_zi_projects_thumbnail_image_id").val(attachment.id);
        $("#wppool_zi_projects_thumbnail_image_url").val(
          attachment.sizes.thumbnail.url
        );
        $("#image-container").html(
          `<img src='${attachment.sizes.thumbnail.url}' />`
        );
      });

      frame.open();
      return false;
    });

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
            `<img style="margin-right: 10px;" src='${attachment.sizes.thumbnail.url}' />`
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

