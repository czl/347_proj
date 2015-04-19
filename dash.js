  $.ajax({
    type: 'GET',
    url: 'session.php',
    dataType: 'text',
    success: function(status){
      console.log("status");
      console.log(status);
      $('#user_name').html("Welcome, "+status.substring(7));
    },
    error: function(status){
      alert("error occured");
      console.log("error");
      console.log(status);
    }
  }).done(function(){
    $('body').css('display','block'); 
  });
//end ajax

$(document).ready(function(){
  $("#logout_btn").click(function(){
    console.log("logout button clicked");
    $.ajax({
      type: 'GET',
      url: 'end_session.php',
      dataType: 'text',
      asyc: true,
      success: function(status){
	console.log(status);
	window.location = "http://staycalm.me";
      },
      error: function(status){
	console.log("error");
	console.log(status);
      }	
    });//end ajax
  });
});
