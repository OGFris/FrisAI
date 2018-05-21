<?php
/**
 * Created by PhpStorm.
 * User: fris
 * Date: 08/05/18
 * Time: 07:44 AM
 */

class Conversation
{
    /** @var string */
    public $name;
    /** @var int */
    public $age;
    /** @var array */
    public $messages = [];
    /** @var string */
    public $lastanswer = "";
    /** @var int */
    public $repeat = 0;
    /** @var SelfLearning */
    public $SL;
    /** @var bool */
    public $neednewanswer = false;

    /**
     * @param SelfLearning $SL
     */
    public function start(SelfLearning $SL): void
    {
        $this->setSL($SL);
        $this->setName($this->askName());
        $this->setAge($this->askAge());
        fprint("Fris > How are you today " . $this->getName() . " ?");
        $this->run();
    }

    public function run(): void
    {
        $line = readline("You > ");
        if ($line == "/*"){
            $correct = readline("Can you correct me please: ");
            $this->getSL()->add($this->getLastMessage(), $correct);
            fprint("Fris > " . $correct);
            $this->addMessage($correct);
            $this->run();
        } else {
            if (strlen($this->getLastMessage()) > 0) $this->getSL()->onAnswer($line, $this->getLastanswer());
            if ($this->checkRepeat(strval($line))) {
                $this->addMessage($line);
                $this->run();
            } else {
                $this->addMessage($line);
                fprint("Fris > " . $this->getAnswer($line)["answer"]);
                $this->run();
            }
        }
    }

    /**
     * @param string $message
     * @return array
     */
    public function getAnswer(string $message): array
    {
        $array = explode(" ", $message);
        $db = new Database("database.db");
        $answer = "idk";
        $lastcount = 0;
        foreach ($db->getData() as $key => $value){
            $words = explode(" ", $key);
            $count = 0;
            for ($i = 0; $i <= (count($array)-1); ++$i){
                for ($i2 = 0; $i2 <= (count($words)-1); ++$i2) {
                    if (strtolower($array[$i]) === strtolower($words[$i2])) {
                        ++$count;
                    }
                }
            }
            if ($lastcount < $count){
                $lastcount = $count;
                $answer = str_replace("{name}", $this->getName(), $db->getData()[$key]);
            }
        }
        $neednewanswer = false;
        if (difference($lastcount, count($array)) > 2){
            $neednewanswer = true;
        }
        $db->close();
        $this->setLastanswer(str_replace([".", ",", "!", "?"], null, $answer));
        $this->setNeednewanswer($neednewanswer);
        return ["answer" => $answer, "neednewanswer" => $neednewanswer];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    // Incognito mode

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    public function askName(): string
    {
        fprint("Fris > Hello, What's your name ?");
        $name = strval(readline("You > my name is..."));
        while (strlen($name) < 2 or strlen($name) > 15){
            fprint(["Fris > Quit joking with me and tell me your real name !", "Fris > just tell me your real name homie.", "Fris > i don't think this name is real :/"][mt_rand(0, 2)]);
            $name = readline("You > my name is...");
        }
        $this->addMessage($name);
        return $name;
    }

    public function askAge(): int
    {
        fprint(str_replace("{name}", $this->getName(), "Fris > so {name}, How old are you ?"));
        $age = intval(readline("You > I'm..."));
        while ($age < 1 or $age > 100){
            fprint(["Fris > Quit joking with me and tell me your real age," . $this->getName() . " !", "Fris > just tell me your real age " . $this->getName() . ".", "Fris > i don't think this age is real :/," . $this->getName() . "..."][mt_rand(0, 2)]);
            $age = intval(readline("You > I'm..."));
        }
        return $age;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @param string $message
     */
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return string
     */
    public function getLastMessage(): string
    {
        return $this->messages[count($this->messages) - 1];
    }

    /**
     * @return int
     */
    public function getRepeat(): int
    {
        return $this->repeat;
    }

    /**
     * @param int $repeat
     */
    public function setRepeat(int $repeat): void
    {
        $this->repeat = $repeat;
    }

    /**
     * @param int $r
     */
    public function addRepeat(int $r = 1): void
    {
        $this->setRepeat($this->getRepeat() + $r);
    }

    /**
     * @param string $message
     * @return bool
     */
    public function checkRepeat(string $message): bool
    {
        if (strtolower($message) == strtolower($this->getLastMessage())){
            $this->addRepeat();
            switch ($this->getRepeat()){
                case 1:
                    fprint("Fris > Yes i know.");
                break;

                case 2:
                    fprint("Fris > i told you that i know.");
                break;

                case 3:
                    fprint("Fris > Ok?");
                break;
                case 4:
                    fprint("Fris > IT'S TIME TO STOP!");
                break;
                default:
                    fprint("Fris > Wow! you're so smart...*sarcasm*");
                    $this->setRepeat(0);
                break;
            }
            return true;
        } else return false;
    }

    /**
     * @return string
     */
    public function getLastanswer(): string
    {
        return $this->lastanswer;
    }

    /**
     * @param string $lastanswer
     */
    public function setLastanswer(string $lastanswer): void
    {
        $this->lastanswer = $lastanswer;
    }

    /**
     * @return SelfLearning
     */
    public function getSL(): SelfLearning
    {
        return $this->SL;
    }

    /**
     * @param SelfLearning $SL
     */
    public function setSL(SelfLearning $SL): void
    {
        $this->SL = $SL;
    }

    /**
     * @return bool
     */
    public function isNeednewanswer(): bool
    {
        return $this->neednewanswer;
    }

    /**
     * @param bool $neednewanswer
     */
    public function setNeednewanswer(bool $neednewanswer): void
    {
        $this->neednewanswer = $neednewanswer;
    }
}
