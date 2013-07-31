if (window.pubsub) {

var sess;
var opponent;               //store opponent's name
var o_finish=false;         // whether opponent finish the game
var playerFinished = false; // whether player finish the game
var invited = false;        // whether player was invited or busy
var autoselection=false;    //
var o_result={};            //store opponent's score
var localtime=new Date();   //user's localtime
var usingtime;              
var inviteTimer = null;

function publishEvent()    //add new player into the table
{     if (sess) {
      var evt = {};
       evt.username=getUsername();
       evt.category=$('#category').text();
       sess.publish("event:playerlist", evt);
    }
    
    return false;  
}
function statusfree()     //set the status is free 
{  invited=false;
   playerFinished = false;
   o_finish=false;
   opponent='';
   document.getElementById("PvP").disabled=false;
   $('#PvP').removeClass('disabled');
   if (sess) {
      var evt = {};
       evt.username=getUsername();
       sess.publish("event:statusfree", evt);
    }
    return false; 
}

function out()    // remove player from table
{if (sess) {
      var evt = {};
       evt.username=getUsername();
       evt.category=$('#category').text();
       sess.publish("event:outplayerlist", evt);
    }
    
    return false;
}

function end(gameResults, timeUsed) {  //end the game
    playerFinished=true;
    if (o_finish){ $('#result p').html(compare(o_finish,o_result['correct'],o_result['timeUsed'])); }
    else {
    $('#result p').html('Your opponent may need more time~ Please waite a moment~<br> If you close this window, you still can see this game\'s result from your message box.'); 
    if(sess) {
        sess.publish("event:" + opponent + ":gamefinish", {
            username: getUsername(),
            correct: gameResults.correct,
            wrong: gameResults.wrong,
            timeUsed: timeUsed,
            finish: true
        });
      }
    }
}

function compare(finish,correct,otimeUsed) {  //compare score, insert result to db and  show result
   if(finish) {
       
       if(result.correct > correct)
            { sess.publish("event:"+opponent+":gameresult", {result:'You lose',username:getUsername()});  
              insert_into_db(localtime,getUsername(),usingtime,$('#category').attr("name"),result.correct,opponent,1);
              insert_into_db(localtime,opponent,otimeUsed,$('#category').attr("name"),correct,getUsername(),0);
              return 'You win';  }     
       else if (result.correct < correct)
            { sess.publish("event:"+opponent+":gameresult", {result:'You win',username:getUsername()});
              insert_into_db(localtime,getUsername(),usingtime,$('#category').attr("name"),result.correct,opponent,0);
              insert_into_db(localtime,opponent,otimeUsed,$('#category').attr("name"),correct,getUsername(),1);
            return 'You lose'; }
       else {
           if (usingtime < otimeUsed)   
            {sess.publish("event:"+opponent+":gameresult", {result:'You lose',username:getUsername()});
            insert_into_db(localtime,getUsername(),usingtime,$('#category').attr("name"),result.correct,opponent,1);
            insert_into_db(localtime,opponent,otimeUsed,$('#category').attr("name"),correct,getUsername(),0);
            return 'You won';
            }
           else if (usingtime > otimeUsed)
            {sess.publish("event:"+opponent+":gameresult", {result:'You win',username:getUsername()});
           insert_into_db(localtime,getUsername(),usingtime,$('#category').attr("name"),result.correct,opponent,0);
           insert_into_db(localtime,opponent,otimeUsed,$('#category').attr("name"),correct,getUsername(),1);
           return 'You lose';}
            
           else 
           {sess.publish("event:"+opponent+":gameresult", {result:'Tie',username:getUsername()});
             alert('in');
                insert_into_db(localtime,getUsername(),usingtime,$('#category').attr("name"),result.correct,opponent,1);
                insert_into_db(localtime,opponent,otimeUsed,$('#category').attr("name"),correct,getUsername(),1);
            
            return 'Tie';}
        } 
    }
}

function countdown(user,mode) {
    document.getElementById(user+'timerBar').innerHTML="3:00";
    var TIME_LIMIT = 180;
    var minutes = 0, 
        seconds = 0,
        timeRemaining = TIME_LIMIT,           	
        inviteTimer; 		
		inviteTimer=setInterval(function(){
		if(document.getElementById(user+'timerBar')!=null){
		minutes =parseInt((timeRemaining / 60)%60);
        seconds = timeRemaining % 60;
        timeString = minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
        document.getElementById(user+'timerBar').innerHTML=timeString+" Left";
        if(timeRemaining == 0) {	
            clearTimeout(inviteTimer);		
		    if(mode=='auto') unsub();			
            else decline_invite(user);
        }
		else timeRemaining--;			
		}
		else clearTimeout(inviteTimer);
		}, 1000);
    
}

$('#In').click(function(){    // online
    $('#In').hide();
    $('#Out').show();
    publishEvent();
    insert_db();
    outofline=false;        
});
            
$('#Out').click(function()    //offline
{   outofline=true;
    offline(); 
    out(); 
    if(autoselection){unsub();autoselection=false;}	
    $('#In').show();
    $('#Out').hide();
});




$('#PvS').click(function()    //single game
{                  
   statusbusy();
   if (sess) {
      var evt = {};
       evt.username=getUsername();
       sess.publish("event:statusbusy", evt);
    }
    return false; 
});

function unsub()
{
invited = false;
sess.unsubscribe('event:public:' + $('#category').text());
rm_from_top(getUsername());
document.getElementById("PvP").disabled=false;
$('#PvP').removeClass('disabled');
}

$('#PvP').click(function() {     //begin auto selection 
        if (!sess) {
            return;
        }
        document.getElementById("PvP").disabled=true;
        $('#PvP').addClass('disabled');
		
        $('#player-list tr').each(function(){
        if ($(this).attr("id")==getUsername()) 
        {html=$(this).html();
         $(this).remove();
         return;
         }      
        }); 
        
        document.getElementById('player_list_table').style.width = '40%';
        /*show bubble*/
        $('#message-list tbody').prepend('<tr id="'+getUsername()+
		'"><td id="invite"><div class="bubble-box arrow-left"><div class="wrap"><span class="badge badge-success"><div style=\'width: 100%;\' id="'+getUsername()+	'timerBar"> </div></span> <b>Waiting for Match... </b> <button  class="btn text-right" onclick="unsub()">Cancel</button></div></div></td></tr>');        		
        /*----------*/
		countdown(getUsername(),'auto');
        $('#player-list tbody').prepend('<tr id="'+getUsername()+'">'+html+'</tr>'); 
        
        
        sess.subscribe('event:public:' + $('#category').text(), function(topicURI, event) {
            console.log('I m ready');
            autoselection=true;
            sess.publish('event:' + event.username + ':auto', {
            username: getUsername()
            });
        });

        sess.publish('event:public:' + $('#category').text(), {
            username: getUsername()
        });
    });
    
/******only works on firefox*******/
window.onbeforeunload=function()
{
if(invited){
offline();
alert('You will quit the game!');}
};

window.onload = function() {

    outofline=false;
    insert_db();  // online;
    setTimeout(function(){publishEvent();},1000);  //show online on table;
    $('#In').hide();   
  

 
   ab.connect("ws://localhost:9000",
 
      function (session) {
            sess = session;
            sess.prefix("event", "http://example.com/events/player_list");
          
            /***************send invite**************/
            sess.subscribe('event:' + getUsername(), function(topicURI, event) {
                $('#player-list tr').each(function(){
                    if ($(this).attr("id")==event.username) {
                        $(this).children('td').eq(0).html(event.username);
                        $(this).children('td').eq(2).remove();
                        html=$(this).html();
                        $(this).remove();
                    }      
                });
                $('#message-list tr').each(function(){
                    if ($(this).attr("id")==event.username) {                        
                        $(this).remove();
                    }      
                });
                showalert(event.username+ " invite you to a game.");
                document.getElementById('player_list_table').style.width = '40%';
                $('#player-list tbody').prepend('<tr id="'+event.username+'">'+html+'</tr>');
                /*show bubble*/          
                $('#message-list tbody').prepend('<tr id="' + event.username +
                    '"><td id="invite"><div class="bubble-box arrow-left">' +
                    '<div class="wrap">' +'<span class="badge badge-important">'+
                     '<div class=\'bar\' style=\'width: 100%;\'id="'+event.username+'timerBar">' +
                     '</div>' +'</span> '+
					event.username +
                    ' invited you to a game <button ' + 
                    'class="btn btn-warning" onclick="createPvPGame(\'' +
                    event.username + '\')">Accept</button><button ' +
                    'id="decline-invite" class="btn" ' +
                    'onclick="decline_invite(\'' + event.username +
                    '\')">Decline</button></div></div></td></tr>'); 
					
				countdown(event.username,'');	
            });
        
         
         /***************auto selection**************/

          sess.subscribe('event:' + getUsername() + ':auto', function(topicURI, event) {
                    if (invited || event.username === getUsername()) {
                        return;
                    }

                    invited = true;
                    
                    if (event.response) {
					    createPvPGame(event.username);                      
                        sess.unsubscribe('event:public:' + $('#category').text());
                        autoselection=false;
                        return;
                    }

                    sess.publish('event:' + event.username + ':auto', {
                        username: getUsername(),
                        response: true
                    });
                    //createPvPGame(event.username);
                   // sess.unsubscribe('event:public:' + $('#category').text());
                });
                
                
          sess.subscribe('event:outplayerlist',function (topicUri, event){
            rm_from_list(event.username);
            showalert(getUsername()+ " is offline. ");  
          });
          
          /***************set status busy**************/
          sess.subscribe('event:statusbusy',function (topicUri, event){
          $('#player-list tr').each(function(){
                   if ($(this).attr("id")==event.username) 
                   {
                   $(this).children('td').eq(1).removeClass();
                   $(this).children('td').eq(1).addClass('alert');
                   $(this).children('td').eq(0).html(event.username);
                   $(this).children('td').eq(1).text('busy');
                   return;}
                            });
          
          });
          
          /***************set status available**************/
          sess.subscribe('event:statusfree',function (topicUri, event){
          $('#player-list tr').each(function(){
                   if ($(this).attr("id")==event.username) {
                   $(this).children('td').eq(1).removeClass();                  
                   $(this).children('td').eq(1).addClass('alert-success');
                   $(this).children('td').eq(1).text('available');
                   if (event.username!=getUsername()){
                   $(this).children('td').eq(0).html('<a href="#" onclick="sendout(\''+event.username+'\')">'+event.username+'</a>');
                   }
                   return;}
                            });
          
          });
          
         /***************decline invite**************/
        sess.subscribe('event:' + getUsername() + ':decline', function(topicURI, event) {
            console.log('decline');
            showalert(event.username+ " cancelled this game.");
            rm_from_top(event.username);            
            $('#player-list tr').each(function(){
              if ($(this).attr("id")==event.username){ 
              $(this).children('td').eq(0).html('<a href="#" onclick="sendout(\''+event.username+'\')">'+event.username+'</a>');
               return;}        
               });
            if(inviteTimer != null) {
                clearInterval(inviteTimer);
                inviteTimer = null;
            }
        });
          
          /***************add to playerlist**************/
          sess.subscribe('event:playerlist',function (topicUri, event)
            {   
                var e;
                 $('#player-list tr').each(function(){
                   if ($(this).attr("id")==event.username) $(this).remove();
                                });
				 $('#message-list tr').each(function(){
                   if ($(this).attr("id")==event.username) $(this).remove();
                                });
                 
                if ($('#category').text()==event.category)
                {
                 if(getUsername()==event.username)
                 {$('#player-list tbody').append('<tr id='+event.username+'><td class="alert-info">'+event.username+'</td><td class=\'alert-success\'>available</td></tr>');}
                 else
                $('#player-list tbody').append('<tr id='+event.username+'><td><a href=\"#\" onclick="sendout(\''+event.username+'\')">'+event.username+'</a></td><td class=\'alert-success\'>available</td></tr>');
                
                }
            
            });
          /************game finish*******************/
         sess.subscribe("event:" + getUsername() + ":gamefinish",  
         function(topicUri, event) {
         o_finish=true; 
         o_result['correct']=event.correct;
         o_result['timeUsed']=event.timeUsed;
         if(playerFinished) $('#result p').html(compare(event.finish,event.correct,event.timeUsed));
         
            }
            );
          /************send result*******************/
         sess.subscribe("event:" + getUsername() + ":gameresult",  
         function(topicUri, event) {
         $('#result p').html(event.result);          
         showalert(event.result + " in the game with "+ event.username);         
         }          
            );
          /************get questions*******************/
          sess.subscribe('event:' + getUsername() + ':questions', function(topicURI, event){
          console.log(event.jsonQuestions);
          invited=true;
		  rm_from_top(event.username);
          rm_from_top(getUsername());
          if(autoselection) {autoselection=false;sess.unsubscribe('event:public:' + $('#category').text());}
          putQuestiontoSession(event.jsonQuestions);
          statusbusy(); 
          opponent=event.username;
   
        
           sess.publish("event:statusbusy", {username:getUsername()});
           /*display*/
            $('#game_mode').text('pvp');
            $('#myModal .modal-body #entryForm h3').html('<center>'+getUsername()+' <i>VS</i> '+event.username+'</center>');              
             $('#myModal .modal-body #entryForm h3').show();             
             $('#myModal .modal-body #entryForm button').hide();
             $('#myModal .modal-body #entryForm input').hide();          
             $("#entryForm").show(); 
             $('#myModal .modal-body #test').hide();
             $('#result').hide();   
             $("#myModal").modal({backdrop:'static'});           
           /*-------*/
             setTimeout(function(){
             current_position = 10;  
             $('#myModal .modal-body #entryForm').hide();            
             getQuestion(--current_position);
             },2000);
          });
      },
 
      function (code, reason) {
     // offline();

      }
   );
};

}

function getUsername() {
  var welcome = getTextNodesIn($('#welcome-user')[0], false)[0];
  return $.trim(welcome.textContent.replace('Welcome,', ''));
  
  function getTextNodesIn(node, includeWhitespaceNodes) {
      var textNodes = [], whitespace = /^\s*$/;

      function getTextNodes(node) {
          if (node.nodeType == 3) {
              if (includeWhitespaceNodes || !whitespace.test(node.nodeValue)) {
                  textNodes.push(node);
              }
          } else {
              for (var i = 0, len = node.childNodes.length; i < len; ++i) {
                  getTextNodes(node.childNodes[i]);
              }
          }
      }

      getTextNodes(node);
      return textNodes;
  }
}


function putQuestiontoSession(questions)
{
 $.ajax({
            url: "play.php",
            type: "post",
            data: {action:'putQuestiontoSession',
                   questions:questions
                   }
          });
}
function offline()            //offine;
{   
  $.ajax({
            url: "api.php",
            type: "post",
            data: {cmd:0,
                   username:getUsername()
                   }
          });
 
}

function createPvPGame(username) {
    var flag=1;
    $('#player-list tr').each(function(){
	    if ($(this).attr("id")==username) {
            if($(this).children('td').eq(1).html()=='busy') {
                showalert(username+" is busy. Please try later.");
                flag=0;
            }
        }});			
			if(flag==1){
                invited=true;
                console.log('CREATE GAME BETWEEN ' + getUsername() + ' AND ' + username);
                $.post('play.php', {
                    action: 'createGame',
                    playerId: getUsername(),
                    categoryId: $('#category').attr("name"),
                    num: 10
                },
                function(data){                   
                    console.log(data);
                    statusbusy();
                    rm_from_top(username);
                    rm_from_top(getUsername());
                    sess.publish("event:statusbusy", {username:getUsername()});             
                    sess.publish('event:' + username + ':questions', {
                    jsonQuestions: data,
                    username:getUsername()
                    }); 
                    opponent=username;
                    /*display*/
                     $('#game_mode').text('pvp');
                     $('#myModal .modal-body #entryForm h3').html('<center>'+getUsername()+'<i> VS </i>'+username+'</center>');  
                     $('#myModal .modal-body #entryForm button').hide();
                     $('#myModal .modal-body #entryForm input').hide();
                     $("#entryForm").show(); 
                     $('#myModal .modal-body #test').hide();
                     $('#result').hide(); 
                     $("#myModal").modal({backdrop:'static'}); 
                    /*-------*/ 

                     
                    current_position = 10;                    
                    setTimeout(function() {
                        $('#myModal .modal-body #entryForm').hide();
                        getQuestion(--current_position);
                    },2000);
                });
          }

}

$('#player_list_table').mouseover(function(){
updatelist();
inactive_check();
if ($('#message-list tbody').html()=="")
{document.getElementById('player_list_table').style.width = '80%';
}
});


function statusbusy()   //set status is busy in db
{$.ajax({
            url: "api.php",
            type: "post",
            data: {cmd:2,
                   username:getUsername()}
            });
}
function updatelist()   //update playerlist 
{$.ajax({
            url: "api.php",
            type: "post",
            data: {cmd:1,
                   username:getUsername(),
                   category:$('#category').attr("name")
                   },
            dataType: "json",
            success: function(data) {
		   $('#message-list tbody tr').each(function(){
		   a=$(this).attr("id");
		   c=1;
		   $.each(data,function(i,v){
		   if(v==a) c=0;
		   }
		   );
			if(c==1) {$(this).remove();}
						});  
            $('#player-list tbody tr').each(function(){
                   a=$(this).attr("id");
                   c=1;
                   $.each(data,function(i,v){
                   if(v==a) c=0;
                   }
                   );
                    if(c==1) {$(this).remove();}
                                });  
            }
                   
          });
          
          }

function decline_invite(user)   
{
    rm_from_top(user);
	$('#player-list tr').each(function(){
       if ($(this).attr("id")==user){ 
         $(this).children('td').eq(0).html('<a href="#" onclick="sendout(\''+user+'\')">'+user+'</a>');
        return;}        
    });
    sess.publish('event:' + user + ':decline', {
        username: getUsername()
    });

    if(inviteTimer != null) {
        clearInterval(inviteTimer);
        inviteTimer = null;
    }
}         
function rm_from_list(user)
{

 $('#player-list tr').each(function(){
       if ($(this).attr("id")==user) 
        {$(this).remove();return;   }       
                    }); 
}

function rm_from_top(user)              
{ $('#player-list tr').each(function(){
       if ($(this).attr("id")==user){        
        html=$(this).html();       
        $(this).remove();
        return;}        
    }); 
    $('#message-list tr').each(function(){
       if ($(this).attr("id")==user){       
        $(this).remove();
        return;}        
    });
    
$('#player-list tbody').append('<tr id="'+user+'">'+html+'</tr>'); 

                    
}

function sendout(user)        //send invite
{
    if (outofline){
        alert("please login first");
    } else {
        html='<td>'+user+'</td><td class="alert-success">available</td>';
        rm_from_list(user);  
        document.getElementById('player_list_table').style.width = '40%';
        $('#player-list tbody').prepend('<tr id="'+user+'">'+html+'</tr>');
        /*show bubble*/
        $('#message-list tbody').prepend(
            '<tr id="' + user + '">' +
                '<td id=\'wait\'>' +
                    '<div class="bubble-box arrow-left">' +
                        '<div class="wrap"><span class="badge badge-info">' +
                            '<div class=\'bar\' style=\'width: 100%;\'' +
                                'id="'+user+'timerBar">' +
                            '</div></span>  Invite for Player '+user+                        
                        ' <button  class="btn text-right" ' +
                            'onclick="decline_invite(\''+user+'\')">' +
                            'Cancel' +
                        '</button>' +
                    '</div></div>' +
                '</td>' +
            '</tr>'); 
        countdown(user,'');
              
        if (sess) {
            var evt = {
                username: getUsername()
            }
            sess.publish("event:" + user, evt);
        }
        
        return false;
    }

}

function showalert(msg)   
{
$('.alert-error').html('<b>'+msg+'</b>');
            $('.alert-error').show();
            setTimeout(function(){$('.alert-error').fadeOut();},3000);
}

function insert_into_db(localtime,username,time,catid,correct,opponent,winbool) //score into db
{
    $.ajax({
            url: "insertScores.php",
            type: "post",
            data: { usename:username,
                    gametime:localtime,
                    dbtime:time,
                    cat:catid,
                    cor:correct,
                    op:opponent,
                    win:winbool
                    }
          });
}
function inactive_check()
{      
	playertable=$('#player-list tbody').text();
	username=getUsername();
	//alert(playertable.indexOf(username));
	if(playertable.indexOf(username)==-1 &&  $('#Out').css('display')=="block")
	{
	 $('#myModal .modal-body #entryForm h3').html('<center> you have been logged off. Click to refresh</center>');
	 $('#myModal .modal-body #entryForm button').hide();
	 $('#myModal .modal-body #entryForm input').hide();
	 $("#entryForm").show(); 
	 $('#myModal .modal-body #test').hide();
	 $('#result').hide(); 
	 $("#myModal").modal({backdrop:'static'});
     var e = document.createElement('input');
	 e.type="button";
	 e.value = "Online";
	 e.onclick=function(){location.reload();};	
	 $("#entryForm").append(e);
	 return true;
	}
	
}
