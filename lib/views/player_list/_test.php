<style>
button {margin-bottom:5px;}
#timer{text-align:center;}
#timeout{color:#FF0066;height:200px;margin-top:130px;text-align:center;}
#scoretest{text-align:center;}
#scoretest p{font-size:20px}
#myModal{
width:70%;

}
.answerbtn  {
margin:1px;
padding-left:8px;
min-width:500px;
text-align:left;
height:30px;
font-size:17px;
background-color:#FFFFFF;

border: 1px solid #cccccc;
  *border: 0;
  border-color: #e6e6e6 #e6e6e6 #bfbfbf;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  border-bottom-color: #b3b3b3;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
          box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
}
.answerbtn:hover  {
 color: #ffffff;
  background-color: #0044cc;
}
#question {
font-size:20px;
padding:10px;
border: 2px solid #742EFF;
  *border: 0;
  border-color: #e6e6e6 #e6e6e6 #bfbfbf;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  border-bottom-color: #b3b3b3;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
     -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
          box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
background-color:#FFFFFF;
}
</style>

<script type="text/javascript">

            var current_position = 0;
            var answers = {};
            var timeLimit = 300;
            
            function createGame() {
                $.post('play.php', {
                    action: 'createGame',
                    playerId: getUsername(),
                    categoryId: $('#category').attr("name"),
                    num: 10
                },
                function(data){
                    current_position = 10;
                    console.log(data);
                    getQuestion(--current_position);
                    
                }
                
                );
               
            }

            function getQuestion(idx) {
                $.post('play.php', {
                    action: 'getQuestion',
                    index: idx
                },
                function(data) {
                    var question = JSON.parse(data);
					$("#test_skill").text($('#category').text());
					if($('#game_mode').text()=='pvp'){
					$("#test_opponet").text("Opponent: "+opponent);
					}
                    $("#qno").text(10-idx+" /10");
                    $("#question").val(question.question.id);
                    $("#question").text(question.question.text);
                    $('#test').show();  
                    var options = "";
                    $.each(question.options, function(i, elem) {
                        options = options + '<button id="answers" type="button" class="answerbtn" value="' + elem.id + '" onClick="next($(this).val())">' + elem.text + '</button><br>';
                    });
                    $("#options").html(options);
                    //--- load timer---------conflicts
                    if ($('#timer').is(':empty')) {
                        $('#timer').timeTo(timeLimit, function(result){
                            submitAnswers(result);
                            var answers = {};
                            var timeLimit = 300;
                        });
                        $('#timer div:first').hide();
                        $('#timer div:nth-child(2)').hide();
                        $('#timer span:first').hide();
                    }
                    //---------------
                });
                
            }

            function next(val) {
                question_id = $('#question').val();
                answer_id = val;
                answers[question_id] = answer_id;
                console.log(answers);
                
                if (current_position == 0) {
				    
                    $('#timer').timeTo('stop', function(data) {
					    usingtime=data;
                                     });
					submitAnswers(usingtime);
                }
                else { 
                    setTimeout(function() {}, 200);
                    getQuestion(--current_position);
                }
            }
            
            function begin() {
                createGame();           
                $("#entryForm").hide();
                
            }
            function pvsend()
			{$.post('play.php', {
                    action: 'endgame'					
                });
			}
            function submitAnswers(timeUsed)
            {   
                submittedAnswers = JSON.stringify(answers);
                answers={};
                $("#timer").empty();
                $.post('play.php', {
                    action: 'submitAnswers',
					mode:$('#game_mode').text(),
                    answers: submittedAnswers
                },
                function (data) {
                    result = JSON.parse(data);
                    $('#test').hide();
                    					
                    if($('#game_mode').text()=='pvs'){ 					//pvs part
					usingtime=timeUsed;
                    if (timeUsed >= timeLimit) {
                        $('#timeout').show();
                        setTimeout(function(){
                        $('#timeout').hide();
                        $('#result').show();
						$('#pvs_buttons').show();
                        $('#result p').html('<br>Correct: ' + result.correct + '<br> wrong: ' + result.wrong);},1500);
                        
                    } else {                       
                        $('#result p').html('<br>Finished in <i id="r_usedtime">' + timeUsed + '</i>s<br> ' +  'Correct: <i id="r_correct">' + result.correct + '</i><br> Wrong: ' + result.wrong);
						$('#result').show();
						$('#pvs_buttons').show();
                    }
                  }
				  else if($('#game_mode').text()=='pvp')  //pvp part
				  {
				   //console.log(result.correct);
				   //console.log(timeUsed);
				   usingtime=timeUsed;
				   if (timeUsed >= timeLimit) { 
				      $('#timeout').show();
				      setTimeout(function(){
                        $('#timeout').hide();                        
				        end(result, timeUsed);
						$('#result').show();
						$('#pvs_buttons').hide();
						},1500);
						}
					else {
					   end(result, timeUsed);
					   $('#result').show();
					   $('#pvs_buttons').hide();
				   }
				   
				  
				   
				   }
				  
                });
            }

$('#PvS').click(function()

{
  $('#game_mode').text('pvs');
  $('#myModal .modal-body #entryForm h3').text('Dear '+getUsername()+',');  
  $('#myModal .modal-body #entryForm p').text('This will be a test about '+$('#category').text()+'. Are you ready?');
  $('#myModal .modal-body #entryForm input').show();
  $('#myModal .modal-body #entryForm button').show();
  $('#myModal .modal-body #test').hide();
  $('#result').hide(); 
  $("#entryForm").show(); 
  $("#myModal").modal({backdrop:'static'});   
      
});

function send_email(){
$("#input_email").html("<p>Input your friends email: <input id='f_email' type='text'><button onclick='sending_email()' id='s_email' class='btn'>Send</button>");


}

function sending_email(){
$("#input_email").html("Sending ... ");
$.post('play.php', {
		action: 'sendemail',
		email: $("#f_email").val(),
		usedtime:$("#r_usedtime").text(),
		correct:$("#r_correct").text(),
		categoryId: $('#category').attr("name")
      },
	  function(data){
	  $("#input_email").html("Send");
	  $("#email").hide();
	  });
	  }

        </script>


<div id="myModal" class="modal hide fade" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
  <div class="modal-body">
        <input id="game_mode" type="hidden">
        <div id='timeout' class='hide'><h1>Time Out</h1></div>
        <div id="result">
            <div class="modal-header">
                <button type="button" onclick="statusfree();" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h3 id="myModalLabel">Score</h3>
            </div>
            <div id="scoretest">
            <p></p>
            <br>
			<div id='pvs_buttons'>
			<button id='fb' class='btn' onclick="shareonFB()">Show off on Facebook</button><br>
			<button id='email' class='btn' href="#" onclick="send_email()">Invite your friend to the game!</button>
			<div id="input_email"></div>
			</div>
            
			<button class="btn btn-info" onclick="statusfree();" data-dismiss="modal" aria-hidden="true">Close</button>
			<br><a href="ssi.php">&nbsp Change skill?</a>
            </div>
        </div>
        <form id="entryForm">
            <h3></h3>
            <p class='lead'></p>
            <input type="button" class='btn-large btn-warning' value="Begin" onClick="begin()">
            <button class="btn-large btn-info" onclick="statusfree();insert_db();" data-dismiss="modal" aria-hidden="true">Cancel</button>
        </form>
        <div id="test">
		    <div id="test_skill" style="display:inline"></div><div id="test_opponet" style="display:inline;float:right"></div>
			<p id="timer"></p>
			<p id='qno'></p>
            <p id="question"></p>
            <br>
            <p id="options"></p>
        </div>
  </div>
  <div class="modal-footer">
  </div>
</div>
    
    
