<?php

switch($TGBot->text) {
    default:
    # Parla con staff
        $TGBot->nuovoTicket();
    # Youtube
        $TGBot->youtubeCheck();
    # Report bug abusing
        $TGBot->reportBugabusing();
    # Nickname
        $TGBot->commandNick();
    # Ticket
        $TGBot->rispondiTicket();
        $TGBot->chiudiTicket();
    # Pex admin
        $TGBot->admin();
        $TGBot->unadmin();
    # Pex staffer
        $TGBot->staffer();
        $TGBot->unstaffer();
    # Blacklist
        $TGBot->commandBlacklist();
        $TGBot->commandUnBlacklist();
    # Report online
        $TGBot->reportOnline();
        break;
    case '/start':
        $TGBot->start();
        break;
    case '':
        break;
    case '/id':
        $TGBot->sendMessage($TGBot->chat_id, 'ID: <code>'.$TGBot->chat_id.'</code>');
        break;
}

?>