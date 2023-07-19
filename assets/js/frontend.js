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
  $("#filter-buttons").on("click", "button", function () {
    var $this = $(this);
    $this.addClass("is-checked");
    $this.siblings().removeClass("is-checked");
    var filterValue = $(this).attr("data-filter");
    $grid.isotope({ filter: filterValue });
  });

  // Sorting functionality (optional)
  $("#sort-buttons").on("click", "button", function () {
    var $this = $(this);
    $this.addClass("is-checked");
    $this.siblings().removeClass("is-checked");
    var sortByValue = $(this).attr("data-sort-by");
    $grid.isotope({ sortBy: sortByValue });
  });
})(jQuery);

