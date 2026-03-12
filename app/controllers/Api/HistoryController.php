<?php

require_once("../app/model/history.php");

class HistoryController
{
    public function removeHistoryItem()
    {
        $history = new history();
        $history->removeHistoryItem($_POST);
    }
}
