$(document).ready(function(){
  $('.panel.top .left, .panel.top .right').superfish({delay:100});
  $(document).tooltip({
    position: {
      my: "left top+5",
      at: "left bottom",
      collision: "flipfit" 
    }
  });
});
