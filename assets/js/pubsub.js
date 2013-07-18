if (window.pubsub) {

var sess;
function publishEvent()    //add new player into the table
{     if (sess) {
      var evt = {};
	   evt.username=getUsername();
	   evt.category=$('#category').text();
	   sess.publish("event:playerlist", evt);
    }
    
    return false;  
}
function statusfree()     //set the status is free on the table
{
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

$('#In').click(function(){
	$('#In').hide();
	$('#Out').show();
	publishEvent();
	insert_db();
			
});
			
$('#Out').click(function()
{   offline(); 
    out();
	$('#In').show();
	$('#Out').hide();
});




$('#PvS').click(function()
{                  
   statusbusy();
   if (sess) {
      var evt = {};
	   evt.username=getUsername();
	   sess.publish("event:statusbusy", evt);
    }
    return false; 
});



window.onload = function() {
   
	insert_db();  // online;
	setTimeout(function(){publishEvent();},1000);  //show online on table;
	$('#In').hide();   

   
	  $('#player-list a').click(function() {
	       $(this).parent().parent().remove();						
           $('#player-list tbody').prepend('<tr id='+$(this).html()+'><td><a href=\"#\">'+$(this).html()+'</a></td><td id=\'wait\' class=\'alert-error\'>Waiting  <div style="margin-bottom:-5px; width:200px; display:inline-block;" class=\'progress progress-striped active\'><div class=\'bar\' style=\'width: 100%;\'></div></div><button class="btn text-right">Cancel</button></td></tr>'); 
                  
		if (sess) {
		  var evt = {
			username: getUsername()
		  }
		  sess.publish("event:" + $(this).text(), evt);
		}
		
		return false;
	  });
 
   ab.connect("ws://localhost:9000",
 
      function (session) {
          sess = session;
          sess.prefix("event", "http://example.com/events/player_list");
          sess.subscribe('event:' + getUsername(), function(topicURI, event) {
         //   $('.thumbnails .challenge-request').text('You received a challenge from ' + event.username);
		   $('#player-list tr').each(function(){
				   if ($(this).attr("id")==event.username) 
					$(this).remove();			
								});	 
           $('#player-list tbody').prepend('<tr id='+event.username+'><td><a href=\"#\">'+event.username+'</a></td><td id="invite" class=\'alert\'>Invite you to a game<button class="btn btn-warning">Accept</button><button class="btn">Decline</button></td></tr>'); 
         //   $('.thumbnails').show();
          });
         // createDialogue();
		  
		  sess.subscribe('event:outplayerlist',function (topicUri, event){
			 $('#player-list tr').each(function(){
				   if ($(this).attr("id")==event.username) 
					$(this).remove();			
								});	 
			   
		  });
		  sess.subscribe('event:statusbusy',function (topicUri, event){
		  $('#player-list tr').each(function(){
				   if ($(this).attr("id")==event.username) 
				   {
				   $(this).children('td').eq(1).removeClass();
				   $(this).children('td').eq(1).addClass('alert');
				   $(this).children('td').eq(0).html(event.username);
				   $(this).children('td').eq(1).text('busy');}
				    		});
		  
		  });
		  sess.subscribe('event:statusfree',function (topicUri, event){
		  $('#player-list tr').each(function(){
				   if ($(this).attr("id")==event.username) 
				   {$(this).children('td').eq(1).removeClass();
				    
				   $(this).children('td').eq(1).addClass('alert-success');
				   $(this).children('td').eq(0).html('<a>'+event.username+'</a>');
				   $(this).children('td').eq(1).text('available');}
				    		});
		  
		  });
		  sess.subscribe('event:playerlist',function (topicUri, event)
			{   
			    var e;
				 $('#player-list tr').each(function(){
				   if ($(this).attr("id")==event.username) e=0;
								});
				 
			    if (e!=0&&$('#category').text()==event.category)
				{
				 if(getUsername()==event.username)
				 {$('#player-list tbody').append('<tr id='+event.username+'><td class="alert-info">'+event.username+'</td><td class=\'alert-success\'>available</td></tr>');}
				 else
				$('#player-list tbody').append('<tr id='+event.username+'><td><a href=\"#\">'+event.username+'</a></td><td class=\'alert-success\'>available</td></tr>');
				
				}
            
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

function createDialogue() {
  var total = $(document).height();
  var height = $(window).height();
  var top = (height - $('.thumbnails').height());
  $('.thumbnails').css('top', top + 'px');
  
  var width = $(window).width();
  $('.thumbnails').css('left', (width - $('.thumbnails').width()) + 'px');
  
  $(window).scroll(function() {
    var scroll = $(this).scrollTop();
    if (top + scroll > total) {
      return;
    }
    $('.thumbnails').css('top', top + scroll);
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

$('#player_list_table').mouseover(function(){
updatelist();});

function statusbusy()   //set status is busy
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
			
			$('#player-list tbody tr').each(function(){
			       a=$(this).attr("id");
				   c=1;
				   $.each(data,function(i,v){
				   if(v==a) c=0;
				   }
				   );
			   		if(c==1) $(this).remove(); 
								});	 
			}
				   
		  });
		  
		  }
		  
