<?php
/*TGBot Source Code Form is subject to the terms of the Mozilla Public
  License, v. 2.0. If a copy of the MPL was not distributed with TGBot
  file, You can obtain one at http://mozilla.org/MPL/2.0/.*/

if($TGBot->settings['adminMySQL']){
    $TGBot->mdb->query("CREATE TABLE IF NOT EXISTS $TGBot->table_name(
            chat_id INT(11) NOT NULL,
            first_name TEXT(255), 
            last_name TEXT(255),
            username TEXT(50), 
            ban TEXT(50),
            first_start TEXT(50),
            is_admin TEXT(50),
            is_staff TEXT(50),
            ticket TEXT(50),   
            ticket_number TEXT(50),
            nickname TEXT(50),
            rep_video TEXT(50),
            rep_online TEXT(50),
            rep_mod TEXT(50),
            rep_bugabusing TEXT(50),
            title TEXT(255),
            type TEXT(50),
            to_update TEXT(50)
            );");
    $la = $TGBot->mdb->prepare("SELECT * FROM $TGBot->table_name WHERE chat_id=?");
    $user = $TGBot->mdb->prepare("SELECT * FROM $TGBot->table_name WHERE chat_id=?");
    $filtro1 = $TGBot->filtro($TGBot->first_name);
    $filtro2 = $TGBot->filtro($TGBot->last_name);
    $filtro3 = $TGBot->filtro($TGBot->title);
    if(in_array($TGBot->type, ['supergroup', 'group', 'channel'])){
        $la->execute([$TGBot->chat_id]);
        $la = $la->fetch(\PDO::FETCH_ASSOC);
        if($TGBot->chat_id != $la['chat_id']){
            $insertprep = $TGBot->mdb->prepare("INSERT INTO $TGBot->table_name (chat_id, first_name, last_name, username, ban, first_start, is_admin, is_staff, ticket, ticket_number, nickname, rep_video, rep_online, rep_mod, rep_bugabusing, title, type, to_update) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $insertprep->execute([$TGBot->chat_id, null, null, null, '?', '?',  'false', 'false', 'false', null, null, 'false', 'false', null, 'false', $filtro3, $TGBot->type, true]);
        } else {
            if($la['to_update']){
                $update = $TGBot->mdb->prepare("UPDATE $TGBot->table_name SET title=?, type=?, to_update=? WHERE chat_id=?");
                $update->execute([$filtro3, $TGBot->type, true, $TGBot->chat_id]);
            }
        }
        $user->execute([$TGBot->user_id]);
        $user = $user->fetch(\PDO::FETCH_ASSOC);
        if(!$user){
            if($TGBot->user_id != $user['chat_id']){
                $insertprep = $TGBot->mdb->prepare("INSERT INTO $TGBot->table_name (chat_id, first_name, last_name, username, ban, first_start, is_admin, is_staff, ticket, ticket_number, nickname, rep_video, rep_online, rep_mod, rep_bugabusing, title, type, to_update) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $insertprep->execute([$TGBot->user_id, $filtro1, $filtro2, $TGBot->username, '?', '?', 'false', 'false', 'false', null, null, 'false', 'false', null, 'false', null, 'private', true]);
                if($TGBot->username != null){
                    if($TGBot->chat_id == $TGBot->user_id){
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->username</code> → <code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    } else {
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->username</code> → <code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    }
                } else {
                    if($TGBot->chat_id == $TGBot->user_id){
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->username</code> → <code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    } else {
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    }
                }
            }
        } else {
            if($user['to_update']){
                $update = $TGBot->mdb->prepare("UPDATE $TGBot->table_name SET first_name=?, last_name=?, username=?, type=?, to_update=? WHERE chat_id=?");
                $update->execute([$filtro1, $filtro2, $TGBot->username, 'private', true, $TGBot->user_id]);
            } 
        }
    } else {
        $user->execute([$TGBot->chat_id]);
        $user = $user->fetch(\PDO::FETCH_ASSOC);
        if(!$user){
            if($TGBot->user_id != $user['chat_id']){
                $insertprep = $TGBot->mdb->prepare("INSERT INTO $TGBot->table_name (chat_id, first_name, last_name, username, ban, first_start, is_admin, is_staff, ticket, ticket_number, nickname, rep_video, rep_online, rep_mod, rep_bugabusing, title, type, to_update) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $insertprep->execute([$TGBot->user_id, $filtro1, $filtro2, $TGBot->username, '?', '?', 'false', 'false', 'false', null, null, 'false', 'false', null, 'false', null, 'private', true]);
                if($TGBot->username != null){
                    if($TGBot->chat_id == $TGBot->user_id){
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->username</code> → <code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    } else {
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->username</code> → <code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    }
                } else {
                    if($TGBot->chat_id == $TGBot->user_id){
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->username</code> → <code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    } else {
                        $TGBot->sendMessage(-1001261566482, "Registrazione nel database:  \n\n[<code>$TGBot->user_id</code>] \n\n💾    →    ✅");
                    }
                }
            }
        } else {
            if($user['to_update']){
                $update = $TGBot->mdb->prepare("UPDATE $TGBot->table_name SET first_name=?, last_name=?, username=?, type=?, to_update=? WHERE chat_id=?");
                $update->execute([$filtro1, $filtro2, $TGBot->username, 'private', true, $TGBot->user_id]);
            } 
        }
    }
}