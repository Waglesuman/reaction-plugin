jQuery(document).ready(function ($) {
  $('.emoji-reaction-button').click(function () {
    var post_id = $(this).data('post-id');
    var reaction = $(this).data('reaction');
    var ip_add = $(this).data('ip-add');

    // Send an Ajax request to update the postmeta count for the selected reaction
    $.ajax({
      type: 'POST',
      url: emoji_reactions.ajaxurl,
      data: {
        action: 'emoji_reactions',
        post_id: post_id,
        reaction: reaction,
        ip_add : ip_add 
      },
      success: function () {

        if (reaction == "like") {
          jQuery("#cpmR1").html("1");
          jQuery("#cpmR2").html("0");
          jQuery("#cpmR3").html("0");
          jQuery("#cpmR4").html("0");
          jQuery(".like-High").removeClass('nullClass').addClass('highlighted');
          jQuery('.love-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.haha-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.angry-High').removeClass('highlighted').addClass('nullClass');
          
        } else if (reaction == "love") {
          jQuery("#cpmR1").html("0");
          jQuery("#cpmR2").html("1");
          jQuery("#cpmR3").html("0");
          jQuery("#cpmR4").html("0");
          jQuery('.like-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.love-High').removeClass('nullClass').addClass('highlighted');
          jQuery('.haha-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.angry-High').removeClass('highlighted').addClass('nullClass');
        } else if (reaction == "haha") {
          jQuery("#cpmR1").html("0");
          jQuery("#cpmR2").html("0");
          jQuery("#cpmR3").html("1");
          jQuery("#cpmR4").html("0");
          jQuery('.like-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.love-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.haha-High').removeClass('nullClass').addClass('highlighted');
          jQuery('.angry-High').removeClass('highlighted').addClass('nullClass');
        } else if (reaction == "angry") {
          jQuery("#cpmR1").html("0");
          jQuery("#cpmR2").html("0");
          jQuery("#cpmR3").html("0");
          jQuery("#cpmR4").html("1");
          jQuery('.like-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.love-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.haha-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.angry-High').removeClass('nullClass').addClass('highlighted');
        }
        else{
          jQuery("#cpmR1").html("0");
          jQuery("#cpmR2").html("0");
          jQuery("#cpmR3").html("0");
          jQuery("#cpmR4").html("0");
          jQuery('.like-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.love-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.haha-High').removeClass('highlighted').addClass('nullClass');
          jQuery('.angry-High').removeClass('highlighted').addClass('nullClass');
        }
      }
    });

  });
});
