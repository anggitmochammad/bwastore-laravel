$(function () {
  $(document).scroll(function () {
    var nav = $(".navbar-fixed-top");
    nav.toggleClass("scroll", $(this).scrollTop() > nav.height());
  });
});
