<style>
#timer{text-align:center;}
#timeout{color:#FF0066;height:200px;margin-top:130px;text-align:center;}
#scoretest{text-align:center;}
#scoretest p{font-size:20px}
#myModal{
width:900px;
margin-left:-450px;
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
                   		submitAnswers(data);
			})
            	}
            	else getQuestion(--current_position);
            }
            
            function begin() {
			    createGame();
				
                $("#entryForm").hide();
                
            }
            
            function submitAnswers(timeUsed)
            {
            	submittedAnswers = JSON.stringify(answers);
            	$.post('play.php', {
            	    action: 'submitAnswers',
            	    answers: submittedAnswers
            	},
            	function (data) {
            	    result = JSON.parse(data);
					$('#test').hide();
					
                    if (timeUsed >= timeLimit) {
                        $('#timeout').show();
						setTimeout(function(){
						$('#timeout').hide();
						$('#result').show();
						$('#result p').html('<br>Correct: ' + result.correct + '<br> wrong: ' + result.wrong);},1500);
						
                    } else {
					    $('#result').show();
                        $('#result p').html('<br>Finished in ' + timeUsed + 's<br> ' +  'Correct: ' + result.correct + '<br> Wrong: ' + result.wrong);
                    }
		    //			statusfree();
            
			//		location.reload();
            	});
            }

$('#PvS').click(function()

{
  $('#myModal .modal-body #entryForm h3').text('Dear '+getUsername()+',');  
  $('#myModal .modal-body #entryForm p').text('This will be a test about '+$('#category').text()+'. Are you ready?');
  $('#myModal .modal-body #test').hide();
  $('#result').hide(); 
  $("#entryForm").show(); 
  $("#myModal").modal({backdrop:'static'});   
      
});



        </script>


<div id="myModal" class="modal hide fade" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
  <div class="modal-body">
	    <div id='timeout' class='hide'><h1>Time Out</h1></div>
        <div id="result">
		    <div class="modal-header">
				<button type="button" onclick="statusfree();" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<h3 id="myModalLabel">Score</h3>
		    </div>
			<div id="scoretest">
			<p></p>
			<br>
			<button class="btn btn-info" onclick="statusfree();" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div>
        <form id="entryForm">
			<h3></h3>
			<p class='lead'></p>
			<input type="button" class='btn-large btn-warning' value="Begin" onClick="begin()">
			<button class="btn-large btn-info" onclick="statusfree();insert_db();" data-dismiss="modal" aria-hidden="true">Cancel</button>
        </form>
		<div id="test">
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
	
	
