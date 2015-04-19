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
      console.log("status");
      console.log(status);
      var user_name = split_ray[2];
      console.log("user's name: " + user_name);
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
      get_recommended_events(status.split("-")[1]);
    }
    $('body').css('display','block');
  });//end done clause 
}
function get_recommended_events(username){
  var query= "MATCH (n:user{username:'"+username+"'}),(t1:tag{tag:n.like1}), (t2:tag{tag:n.like2}), (t3:tag{tag:n.like3}) OPTIONAL MATCH (e1)-[:tag]->(t1) OPTIONAL MATCH (e2)-[:tag]->(t2) OPTIONAL MATCH (e3)-[:tag]->(t3) return e1,e2,e3";
//  console.log("query: " + query);
//  console.log("stringify");
//  console.log(JSON.stringify({"statements":[{"statement":query}]}));
  $.ajax({//ajax to pull recommended events
    type: "POST",
    url: "http://104.131.68.36:7474/db/data/transaction",
    accepts: "application/json",
    dataType: "json",
    contentType: "application/json",
    crossDomain: true,
    "X-Stream": "true",
    headers:{
      "Authorization": "Basic bmVvNGo6Y2xlbWVucw=="
    },  
    data: JSON.stringify({
      "statements": [{
        "statement": query
      }] 
    }), 
    success: function(status){
      console.log("recommended events return: ");
      console.log(status);
      console.log("printed recommended events json");
      console.log(status.results[0].data);
//      var rec_events = 
//      $('#user_name').html("Welcome, "+user_name);

    },
    error: function(status){
      alert("error for recommended events");
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
  });
});
