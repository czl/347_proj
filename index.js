$to_show = false;
$.ajax({//check to see if session exists
  type: 'GET',
  url: 'session.php',
  dataType: 'text',
  success: function(status){
    if(status.substring(0,7) == "session"){
      console.log("already in session");
      console.log(status);
      window.location = "dash.html";
    }
    else{
      console.log(status);
      $to_show = true;
    }
  },
  error: function(status){
    console.log("error: ");
    console.log(status);
  }
}).done(function(){//end ajax call
  if($to_show)
    $('body').css('display','block');
});
$(document).ready(function(){
$("#login_form").submit(function(event){
  event.preventDefault();//prevent url from changing
  $.ajax({
    type: 'GET',
    url: 'login.php',
    dataType: 'text',
    data: $(login_form).serialize(),
    success: function(status){
      if(status == "good"){
        console.log("good login");
        window.location.href = "dash.html";
      }
      else{
	console.log($(login_form).serialize());
	console.log("status: ");
        console.log(status);
        console.log("bad login");
        alert("invalid user-password combination");
      }
    },
    error: function(status){
      alert("error occured");
      console.log("error");
      console.log(status);
    } 
  });//end ajax
});//end login_form submit
});

