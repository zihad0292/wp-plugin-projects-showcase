// Initialize Isotope after the document has loaded
(function ($) {
  var $grid = $("#wppool-grid").isotope({
    // Options and settings here
    itemSelector: ".element-item",
    layoutMode: "fitRows", // You can choose different layout modes
    getSortData: {
      category: "[data-category]", // value of attribute
      title: "[data-title]", // value of attribute
    },
  });

  // Filtering functionality
  $("#filter-buttons").on("click", "a", function (e) {
    e.preventDefault();
    var $this = $(this);
    $this.addClass("is-checked");
    $this.siblings().removeClass("is-checked");
    var filterValue = $(this).attr("data-filter");
    $grid.isotope({ filter: filterValue });
  });

  // Sorting functionality (optional)
  $("#sort-buttons").on("click", "a", function (e) {
    e.preventDefault();
    var $this = $(this);
    $this.addClass("is-checked");
    $this.siblings().removeClass("is-checked");
    var sortByValue = $(this).attr("data-sort-by");
    $grid.isotope({ sortBy: sortByValue });
  });

  // Attach a click event to a link or button that will trigger the AJAX request
  $(".ajax-load-post").on("click", function (e) {
    e.preventDefault();
    var post_id = $(this).data("post-id");
    var title = $(this).data("title");
    // set the modal title to post title
    $(".modal .modal-title").text(title);
    // show the loader
    $(".modal .loader-wrapper").show();
    // set the modal content to ""
    $(".modal .modal-body .content-wrapper").html("");

    $.ajax({
      type: "POST",
      url: ajax_data.ajax_url,
      data: {
        action: "wppool_zi_projects_ajax_load_single_post_data",
        post_id: post_id,
        security: ajax_data.security,
      },
      success: function (response) {
        console.log(response);
        // hide the loader
        $(".modal .loader-wrapper").hide();
        // Update the container where you want to display the single post content
        $(".modal .modal-body .content-wrapper").html(response);
      },
      error: function () {
        alert("An error occurred while loading the post.");
      },
    });
  });

  // lightbox customization
  lightbox.option({
    maxWidth: 800,
    fadeDuration: 300,
    disableScrolling: true,
  });
})(jQuery);

