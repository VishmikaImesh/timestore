<?php
require_once(BASE."/app/model/messages.php");

class messageController{
    private messages $message;

    public function __construct()
    {
        $this->message = new messages();
    }

    public  function loadMsgSenders(){
        $this->message->loadMsgSenders();
    }

    public  function loadMessages(){
        $this->message->loadMessages();
    }

    public  function loadUserMessages(){
        $this->message->loadUserMessages($_POST);
    }

    public  function changeMessageState(){
        $this->message->changeMessageState($_POST);
    }

}
