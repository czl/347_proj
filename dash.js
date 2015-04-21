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
      var username = status.split("-")[1];
      get_recommended_events(username);
      get_follow(username);
    }
    $('body').css('display','block');
  });//end done clause 
}
function get_recommended_events(username){
  var query= "MATCH (n:user{username:'"+username+"'}),(t1:tag{tag:n.like1}), (t2:tag{tag:n.like2}), (t3:tag{tag:n.like3}) OPTIONAL MATCH (e)-[:tag]->(t1) OPTIONAL MATCH (e)-[:tag]->(t2) OPTIONAL MATCH (e)-[:tag]->(t3) return DISTINCT(e)";
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
      var data_result = status.results[0].data;
      console.log(data_result);
      for(i=0;i<data_result.length;i++){
        console.log(data_result[i].row[0].title);
        console.log(data_result[i].row[0].description);
        console.log(data_result[i].row[0].time);
//        console.log(data_result[i].row[0].date_start);
//        console.log(data_result[i].row[0].date_end);

      }
      html_recommended_events(data_result); 
    },
    error: function(status){
      alert("error for recommended events");
      console.log("error: ");
      console.log(status);
    }
  });
}

function get_follow(username){
  var query= "MATCH (n:user{username:'"+username+"'})-[:follow]-(m:user)return DISTINCT(m)";
  console.log("query: " + query);
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
      console.log("follow return: ");
      console.log(status);
      console.log("printed follow json");
      var data_result = status.results[0].data;
      console.log(data_result);
      for(i=0;i<data_result.length;i++){
        console.log(data_result[i].row[0].first);
        console.log(data_result[i].row[0].last);
//        console.log(data_result[i].row[0].time);
//        console.log(data_result[i].row[0].date_start);
//        console.log(data_result[i].row[0].date_end);

      }
      html_follow(data_result); 
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

function html_recommended_events(data){
  var html_build = '';
  for(i=0;i<data.length;i++){
    html_build += '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">';
    html_build += data[i].row[0].title;
    html_build += '</h4></div><div class="panel-body">';
    html_build += data[i].row[0].description;
    html_build += '</br>Time: ';
    html_build += data[i].row[0].time;
    html_build += '</div></div>';
/*<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">subpanel1 title</h4></div>
  <div class="panel-body">subpanel1 body</div>
</div>
*/

  }
  console.log(html_build);
  $('#recommended_events_body').html(html_build);
}

function html_follow(data){
  var html_build = '';
  for(i=0;i<data.length;i++){
    html_build += '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title">';
    html_build += data[i].row[0].first + "&nbsp";
    html_build += data[i].row[0].last;
    html_build += '</h4></div><div class="panel-body">';
    html_build += 'Likes: ';
    html_build += '<ul class="list-group">';
    html_build += '<li class="list-group-item">'+ data[i].row[0].like1 +'</li>';
    html_build += '<li class="list-group-item">'+ data[i].row[0].like2 +'</li>';
    html_build += '<li class="list-group-item">'+ data[i].row[0].like3 +'</li>';
    html_build += '</div></div>';
/*<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">subpanel1 title</h4></div>
  <div class="panel-body">subpanel1 body</div>
</div>
*/

  }
  console.log(html_build);
  $('#follow_body').html(html_build);
///////////////start build left panel
  html_build = '';
  html_build += '<ul class="list-group">';
  for(i=0;i<data.length;i++){
/*    html_build += '<li id="'+data[i].row[0].username+'" '+
                  'class="list-group-item">'+ data[i].row[0].first +
                  '&nbsp'+data[i].row[0].last+'</li>'; 
*/
    html_build += '<li class="list-group-item hover_name">'+ data[i].row[0].first +
                  '&nbsp'+data[i].row[0].last+'</li>'; 
  }
  console.log(html_build);
  $('#follow_left_panel').html(html_build);
 
}


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
