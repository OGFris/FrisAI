<?php
/**
 * Created by PhpStorm.
 * User: fris
 * Date: 08/05/18
 * Time: 05:10 PM
 */

class SelfLearning
{
    /** @var Conversation */
    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->setConversation($conversation);
    }

    /**
     * @param string $message
     * @param string $lastanswer
     */
    public function onAnswer(string $message, string $lastanswer): void
    {
        if ($this->getConversation()->isNeednewanswer()) {
                $this->add($lastanswer, $message);
        }
    }

    /**
     * @param string $message
     * @param string $answer
     */
    public function add(string $message, string $answer): void
    {
        $db = new Database("database.db");
        $db->addData($message, $answer)->close();
        unset($db);
    }

    /**
     * @return Conversation
     */
    public function getConversation(): Conversation
    {
        return $this->conversation;
    }

    /**
     * @param Conversation $conversation
     */
    public function setConversation(Conversation $conversation): void
    {
        $this->conversation = $conversation;
    }
}
