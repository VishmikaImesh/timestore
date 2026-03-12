<?php

require_once(BASE."/app/model/history.php");

class HistoryController
{
    private history $history;

    public function __construct()
    {
        $this->history = new history();
    }

    public function removeHistoryItem()
    {
        $this->history->removeHistoryItem($_POST);
    }
}
