<?php
require_once 'connect.php';

$dbh = new PDO('mysql:host=localhost;dbname=ltitacom_dev', 'ltitacom_dev', 'kL[Th?ri8PTi');

$answers = json_decode($_POST['answers'], true);
$total = count($answers);
$correct = 0;

/* Check whether player’s answer is correct from db */
$stmt = $dbh->prepare("SELECT correct
                        FROM answers
                        WHERE id=:answer_id;");

foreach ($answers as $question_id => $answer_id) {
    $stmt->bindValue(':answer_id', $answer_id, PDO::PARAM_INT);
    $stmt->execute();
    //$result = $stmt->fetch(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll();
    if ($result[0]['correct'] == 1) $correct++;
}

// TODO: we may want to add code here to write score into database

require_once 'end.php'; // remove the game record from database

$dbh = null;

/* Return the number of correct and wrong answers (Json) */
/*
$return = json_encode(array("correct"=>$correct,
                            "wrong"=>$total-$correct));

echo $return;
*/
$wrong = $total - $correct;

$ret['correct'] = $correct;
$ret['wrong'] = $wrong;
echo json_encode($ret);
?>