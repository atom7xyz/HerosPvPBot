<?php
/*This Source Code Form is subject to the terms of the Mozilla Public
  License, v. 2.0. If a copy of the MPL was not distributed with this
  file, You can obtain one at http://mozilla.org/MPL/2.0/.*/
include 'TGBot.php';
$TGBot = new TGBot(file_get_contents('php://input'), '<FPAM>', $_GET['fpam'], $_GET['token']);
$TGBot->SecTest();
include 'conf.php';
include 'mysql.php';
$TGBot->isBanned($TGBot->user_id);
include 'callback_switch.php';
include 'text_switch.php';