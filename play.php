<?php
require_once 'Game.php';

session_start();

switch ($_POST['action']) {
    
    case 'createGame':
        /* FOR DEBUG USE */
        unset($_SESSION['gameId']);
        unset($_SESSION['playerId']);
        unset($_SESSION['questions']);
        /* FOR DEBUG USE */
        $game = new Game($_POST['playerId'], $_POST['categoryId'], $_POST['num']);
        $_SESSION['gameId'] = $game->getId();
        $_SESSION['playerId'] = $_POST['playerId'];
        $_SESSION['questions'] = $game->getQuestions();
		
		echo json_encode($_SESSION['questions']);
        break;
	case 'putQuestiontoSession':
	    $_SESSION['gameId'] = 'PvP';
        $_SESSION['playerId'] = $_SESSION['player_username'];
	    $_SESSION['questions'] = json_decode($_POST['questions']);
	    break;
        
    case 'getQuestion':
        echo json_encode($_SESSION['questions'][$_POST['index']]);
        break;
        
    case 'submitAnswers':
        echo Game::score($_POST['answers']);
		Game::endGame($_SESSION['gameId'], $_SESSION['playerId']);
		
        break;
    
    default:
        echo 'ERROR!';
        break;
}
?>