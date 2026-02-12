<?php
require_once("../app/model/messages.php");

class messageController{

    public  function loadMsgSenders(){
        $message = new messages();
        $message->loadMsgSenders();
    }

    public  function loadMessages(){
        $message = new messages();
        $message->loadMessages();
    }

    public  function loadUserMessages(){
        $message = new messages();
        $message->loadUserMessages($_POST);
    }

    public  function changeMessageState(){
        $message = new messages();
        $message->changeMessageState($_POST);
    }

}
