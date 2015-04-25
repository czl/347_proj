function pull_user_name(){
  $.ajax({//ajax to pull up user name
    type: 'GET',
    url: 'session.php',
    dataType: 'text',
    success: function(status){
//      console.log("raw status: " + status);
      split_ray = status.split("-");
      if(split_ray[0] == "no session"){
	window.location = "http://staycalm.me";
        return;
      }
      console.log("pull_user_name success");
//      console.log(status);
      var user_name = split_ray[2];
//      console.log("user's name: " + user_name);
      $('#user_name').html("Welcome, "+user_name);
    },
    error: function(status){
      alert("error occured");
      console.log("error");
      console.log(status);
    }
  }).done(function(status){
    if(status != '' && status.split("-").length ==3){
//      console.log("if condition met");
      var username = status.split("-")[1];
      get_recommended_events(username);
      get_follows(username);
      get_follow_events(username);
      get_attend_events(username);
    }
    $('body').css('display','block');
  });//end done clause 
}
function get_attend_events(username){
  var get_attend_events = 'call=get_attend_html&username='+username;
  console.log("get_attend_events_html");
        $.ajax({
                type: 'GET',
                url: "REST/REST.php",
//                accepts: "application/json",
                data: get_attend_events,
                success: function(status){
                        console.log("get_attend_events success: ");
                        console.log(status);
			$('#events_right_panel').html(status);
                },
                error: function(status){
                        console.log("error: ");
                        console.log(status);
                }
        });
}
function get_follow_events(username){
  var get_follows_events = 'call=get_follows_events_html&username='+username;
  console.log("get_follow_events_html");
        $.ajax({
                type: 'GET',
                url: "REST/REST.php",
//                accepts: "application/json",
                data: get_follows_events,
                success: function(status){
                        console.log("get_follow_events success: ");
//                        console.log(status);
			$('#follow_events_body').html(status);
                },
                error: function(status){
                        console.log("error: ");
                        console.log(status);
                }
        });
}

function get_recommended_events(username){
  var get_recommended_events = 'call=get_recommended_events_html&username='+username;
  console.log("get_recommended_events_html");
        $.ajax({
                type: 'GET',
                url: "REST/REST.php",
                data: get_recommended_events,
                success: function(status){
                        console.log("get_recommended_events success: ");
                        console.log(status);
			$('#recommended_events_body').html(status);
                },
                error: function(status){
                        console.log("error: ");
                        console.log(status);
                }
        });
}

function get_follows(username){
  var get_follows = 'call=get_follows_html&username='+username;
  console.log("get_follow__html");
        $.ajax({
                type: 'GET',
                url: "REST/REST.php",
//                accepts: "application/json",
                data: get_follows,
                success: function(status){
                        console.log("get_follow success: ");
//                        console.log(status);
			var temp_ray = status.split('~~~~~');
			$('#follow_body').html(temp_ray[0]);
			$('#follow_left_panel').html(temp_ray[1]);
		 	 
                },
                error: function(status){
                        console.log("error: ");
                        console.log(status);
                }
        });
}

function pre_load(){
  pull_user_name();
}pre_load();


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
  });//end of logout function
 
//  $(".hover_name.popover_title").css("display","none");
//  $('[data-toggle="popover"]').popover();
  $(".hover_name").popover();//{trigger: "hover"});
});
