<?php
/**
 * Created by PhpStorm.
 * User: fris
 * Date: 07/05/18
 * Time: 08:28 AM
 */

require_once "Database.php";
require_once "Conversation.php";
require_once "SelfLearning.php";

$conversation = new Conversation();
$SL = new SelfLearning($conversation);
$conversation->start($SL);

/**
 * @param string $text
 * @param bool $newline
 */
function fprint(string $text, bool $newline = true): void
{
    echo $text;
    echo $newline ? PHP_EOL : null;
}
/* Implemented a new to add new text.
function run_addtext(): void
{
    fprint("you say:");
    $line = readline("you say:");
    fprint("i say to " . $line . " :");
    $line2 = readline("i say:");
    $db = new Database("database.db");
    $db->addData($line, $line2)->close();
    unset($db);
    run_addtext();
}*/

/**
 * @param int $number1
 * @param int $number2
 * @return int
 */
function difference(int $number1, int $number2): int
{
    return max($number1, $number2) - min($number1, $number2);
}
