
$(document).ready(function () {

  $('#taula').DataTable();
  $('#taula2').DataTable();

  $('.article').hide();
  $('.comment').hide();
  $('#deviceInfo').hide();
  $('.emotes').hide();

  $('.article').slideDown();
  $('.comment').slideDown();
  $('#deviceInfo').slideDown();
  $('#emoteBt').click(function (){
    $('.emotes').slideToggle();
  });
});

$(window).bind('scroll', function () {

  parallaxScroll();

});

function parallaxScroll() {

  var scrolled = $(window).scrollTop();

  $('#menu').css('top', maxTop() + 'px');

  function maxTop() {

    if ((85 - (scrolled)) > 0) {
      return (85 - (scrolled));
    } else {
      return 0;
    }

  }
}
