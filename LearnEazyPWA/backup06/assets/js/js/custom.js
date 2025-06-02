setTimeout(() => {
    notification('notification-welcome', 5000);
}, 2000);
       
$(document).ready(function(){
  $(".navbar-toggle").click(function(){
    $(".headBg").toggleClass("showNav");
  });

  $(".filter_icon").click(function(){
    $('#overlay').toggle();
    $(this).parent().toggleClass("showSearch");
    //$(".filter_search").toggle();

     newheight = $(".filter_search").height() + 30 +'px';
     
     if($(this).parent().hasClass("showSearch")){
        $(".filter_Col.showSearch .filter_icon").css({'bottom': newheight});
     }
      else{
          $(".filter_Col .filter_icon").css({'bottom': "0"});
      }
    
  });

  
   $(".moreBtn").click(function(){
    $(".moreList").toggle();
  });

   $('.selector ul li').click( function() {
      $('.selector ul li').removeClass('selected');
      $(this).addClass('selected');
    });

   $('.selector').mousewheel(function(e, delta) {
        this.scrollLeft -= (delta * 40);
        e.preventDefault();
    });


     $("ol.questions li.selectedList").click(function(){
          $('#overlay,.questionire_bottom_col').toggle();
        });

    
});