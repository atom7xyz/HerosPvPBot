<?php
/*This Source Code Form is subject to the terms of the Mozilla Public
  License, v. 2.0. If a copy of the MPL was not distributed with this
  file, You can obtain one at http://mozilla.org/MPL/2.0/.*/
class TGBot
{
    private $token;
    private $ctoken;
    private $fparam;

    public function __construct($input, $ctoken, $fparam, $token)
    {
        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_POST           => true,
            CURLOPT_FORBID_REUSE   => true,
            CURLOPT_RETURNTRANSFER => true,
            ]);
        $this->token = $token;
        $this->input = $input;
        $this->ctoken = $ctoken;
        $this->fparam = $fparam;
        $this->update = json_decode($this->input, true);
        if (!empty($this->update)) {
            $this->chat_id = $this->update['message']['chat']['id'];
            $this->user_id = $this->update['message']['from']['id'];
            $this->is_bot = $this->update['message']['from']['is_bot'];
            $this->first_name = htmlspecialchars($this->update['message']['from']['first_name']);
            $this->last_name = htmlspecialchars($this->update['message']['from']['last_name']);
            $this->username = htmlspecialchars($this->update['message']['from']['username']);
            $this->type = $this->update['message']['chat']['type'];
            $this->document = $this->update['message']['document'];
            $this->photo = $this->update['message']['photo'];
            $this->video = $this->update['message']['video'];
            $this->location = $this->update['message']['location'];
            if ($this->type == 'supergroup' or $this->type == 'group' or $this->type == 'channel') {
                $this->title = $this->update['message']['chat']['title'];
                $this->new_chat_member = $this->update['message']['new_chat_members'];
                if ($this->type == 'group') {
                    $this->all_members_are_administrators = $this->update['message']['chat']['all_members_are_administrators'];
                }
            }
            if (isset($this->photo)) {
                $this->photo_name = $this->update['message']['photo']['file_name'];
                $this->photo_mime_type = $this->update['message']['photo']['mime_type'];
                $this->photo_file_id = $this->update['message']['photo'][0]['file_id'];
                $this->photo_file_size = $this->update['message']['photo']['file_size'];
            }
            if (isset($this->document)) {
                $this->document_name = $this->update['message']['document']['file_name'];
                $this->document_mime_type = $this->update['message']['document']['mime_type'];
                $this->document_file_id = $this->update['message']['document']['file_id'];
                $this->document_file_size = $this->update['message']['document']['file_size'];
            }
            if (isset($this->video)) {
                $this->video_name = $this->update['message']['video']['file_name'];
                $this->video_mime_type = $this->update['message']['video']['mime_type'];
                $this->video_file_id = $this->update['message']['video']['file_id'];
                $this->video_file_size = $this->update['message']['video']['file_size'];
            }
            if (isset($this->location)) {
                $this->longitude = $this->update['message']['location']['longitude'];
                $this->latitude = $this->update['message']['location']['latitude'];
            }
            $this->text = $this->update['message']['text'];
            $this->message_id = $this->update['message']['message_id'];
            $this->reply_to_message = $this->update['message']['reply_to_message'];
            if (isset($this->reply_to_message)) {
                $this->reply_to_message_text = $this->update['message']['reply_to_message']['text'];
                $this->reply_to_message_id = $this->update['message']['reply_to_message']['message_id'];
                $this->reply_to_message_first_name = $this->update['message']['reply_to_message']['from']['first_name'];
                $this->reply_to_message_last_name = $this->update['message']['reply_to_message']['from']['last_name'];
                $this->reply_to_message_username = $this->update['message']['reply_to_message']['from']['username'];
                $this->reply_to_message_user_id = $this->update['message']['reply_to_message']['from']['id'];
                $this->reply_to_message_language_code = $this->update['message']['reply_to_message']['from']['language_code'];
                $this->reply_to_message_is_bot = $this->update['message']['reply_to_message']['from']['is_bot'];
                if (isset($this->update['message']['reply_to_message']['photo'])) {
                    $this->reply_photo = $this->update['message']['reply_to_message']['photo'];
                    $this->reply_photo_file_id = $this->update['message']['reply_to_message']['photo'][0]['file_id'];
                    $this->reply_photo_caption = $this->update['message']['reply_to_message']['caption'];
                }
                if (isset($this->update['message']['reply_to_message']['document'])) {
                    $this->reply_document = $this->update['message']['reply_to_message']['document'];
                    $this->reply_document_file_id = $this->update['message']['reply_to_message']['document']['file_id'];
                    $this->reply_document_caption = $this->update['message']['reply_to_message']['caption'];
                }
                if (isset($this->update['message']['reply_to_message']['video'])) {
                    $this->reply_video = $this->update['message']['reply_to_message']['video'];
                    $this->reply_video_file_id = $this->update['message']['reply_to_message']['video']['file_id'];
                    $this->reply_video_caption = $this->update['message']['reply_to_message']['caption'];
                }
            }
            if (isset($this->update['channel_post'])) {
                $this->chat_id = $this->update['channel_post']['chat']['id'];
                $this->text = $this->update['channel_post']['text'];
                $this->message_id = $this->update['channel_post']['message_id'];
                $this->reply_to_message_id = $this->update['channel_post']['reply_to_message']['message_id'];
                $this->reply_to_message_title = htmlspecialchars($this->update['channel_post']['reply_to_message']['chat']['title']);
                $this->type = $this->update['channel_post']['chat']['type'];
                $this->author = $this->update['channel_post']['author_signature'];
                $this->date = $this->update['channel_post']['date'];
            } else {
                if (isset($this->update['edited_message'])) {
                    $this->text = $this->update['edited_message']['text'];
                    $this->edited_message_id = $this->update['edited_message']['message_id'];
                    $this->user_id = $this->update['edited_message']['from']['id'];
                    $this->is_bot = $this->update['edited_message']['from']['is_bot'];
                    $this->first_name = htmlspecialchars($this->update['message']['from']['first_name']);
                    $this->last_name = htmlspecialchars($this->update['message']['from']['last_name']);
                    $this->username = htmlspecialchars($this->update['message']['from']['username']);
                    $this->language_code = $this->update['edited_message']['from']['language_code'];
                    $this->chat_id = $this->update['edited_message']['chat']['id'];
                    $this->type = $this->update['edited_message']['chat']['type'];
                    $this->author = $this->update['edited_message']['author_signature']; //e pensare che volevi subito la risposta
                    if ($this->type == 'supergroup' or $this->type == 'group') {
                        $this->title = htmlspecialchars($this->update['edited_message']['chat']['title']);
                        if ($this->type == 'group') {
                            $this->all_members_are_administrators = $this->update['edited_message']['chat']['all_members_are_administrators'];
                        }
                    }
                    $this->reply_to_message_id = $this->update['edited_message']['message']['reply_to_message']['message_id'];
                    $this->reply_to_message_first_name = htmlspecialchars($this->update['edited_message']['message']['reply_to_message']['from']['first_name']);
                    $this->reply_to_message_last_name = htmlspecialchars($this->update['edited_message']['message']['reply_to_message']['from']['last_name']);
                    $this->reply_to_message_username = htmlspecialchars($this->update['edited_message']['message']['reply_to_message']['from']['username']);
                    $this->reply_to_message_user_id = $this->update['edited_message']['message']['reply_to_message']['from']['id'];
                    $this->reply_to_message_language_code = $this->update['edited_message']['message']['reply_to_message']['from']['language_code'];
                    $this->reply_to_message_is_bot = $this->update['edited_message']['message']['reply_to_message']['from']['is_bot'];
                    $this->date = $this->update['edited_message']['date'];
                    $this->edit_date = $this->update['edited_message']['edit_date'];
                    $this->location = $this->update['edited_message']['location'];
                    if (isset($this->location)) {
                        $this->edited_longitude = $this->update['edited_message']['location']['longitude'];
                        $this->edited_latitude = $this->update['edited_message']['location']['latitude'];
                    }
                }
                if (isset($this->update['edited_channel_post'])) {
                    $this->text = $this->update['edited_channel_post']['text'];
                    $this->edited_message_id = $this->update['edited_channel_post']['message_id'];
                    $this->user_id = $this->update['edited_channel_post']['from']['id'];
                    $this->is_bot = $this->update['edited_channel_post']['from']['is_bot'];
                    $this->first_name = htmlspecialchars($this->update['edited_channel_post']['from']['first_name)']);
                    $this->last_name = htmlspecialchars($this->update['edited_channel_post']['from']['last_name']);
                    $this->username = htmlspecialchars($this->update['edited_channel_post']['from']['username']);
                    $this->language_code = $this->update['edited_channel_post']['from']['language_code'];
                    $this->chat_id = $this->update['edited_channel_post']['chat']['id'];
                    $this->type = $this->update['edited_channel_post']['chat']['type'];
                    $this->author = $this->update['edited_channel_post']['author_signature'];
                    $this->date = $this->update['edited_channel_post']['date'];
                    $this->edit_date = $this->update['edited_channel_post']['edit_date'];
                    $this->reply_to_message_id = $this->update['edited_channel_post']['message']['reply_to_message']['message_id'];
                    $this->reply_to_message_first_name = htmlspecialchars($this->update['edited_channel_post']['message']['reply_to_message']['from']['first_name']);
                    $this->reply_to_message_last_name = htmlspecialchars($this->update['edited_channel_post']['message']['reply_to_message']['from']['last_name']);
                    $this->reply_to_message_username = htmlspecialchars($this->update['edited_channel_post']['message']['reply_to_message']['from']['username']);
                    $this->reply_to_message_user_id = $this->update['edited_channel_post']['message']['reply_to_message']['from']['id'];
                    $this->reply_to_message_language_code = $this->update['edited_channel_post']['message']['reply_to_message']['from']['language_code'];
                    $this->reply_to_message_is_bot = $this->update['edited_channel_post']['message']['reply_to_message']['from']['is_bot'];
                    $this->location = $this->update['edited_channel_post']['location'];
                    if (isset($this->location)) {
                        $this->edited_longitude = $this->update['edited_channel_post']['location']['longitude'];
                        $this->edited_latitude = $this->update['edited_channel_post']['location']['latitude'];
                    }
                }
                $this->cbdata = $this->update['callback_query'];
                if (isset($this->cbdata)) {
                    $this->message_id = $this->update['callback_query']['message']['message_id'];
                    $this->chat_id = $this->update['callback_query']['message']['chat']['id'];
                    $this->user_id = $this->update['callback_query']['from']['id'];
                    $this->cbdata_text = $this->update['callback_query']['data'];
                    $this->first_name = htmlspecialchars($this->update['callback_query']['from']['first_name']);
                    $this->last_name = htmlspecialchars($this->update['callback_query']['from']['last_name']);
                    $this->username = htmlspecialchars($this->update['callback_query']['from']['username']);
                    $this->is_bot = $this->update['callback_query']['from']['is_bot'];
                    $this->language_code = $this->update['callback_query']['from']['language_code'];
                    $this->type = $this->update['callback_query']['message']['chat']['type'];
                    if ($this->type == 'supergroup' or $this->type == 'group') {
                        $this->title = $this->update['callback_query']['message']['chat']['title'];
                    }
                    $this->cbid = $this->update['callback_query']['id'];
                    $this->author = $this->update['callback_query']['author_signature'];
                    $this->reply_to_message_id = $this->update['callback_query']['message']['reply_to_message']['message_id'];
                    $this->reply_to_message_first_name = htmlspecialchars($this->update['callback_query']['message']['reply_to_message']['from']['first_name']);
                    $this->reply_to_message_last_name = htmlspecialchars($this->update['callback_query']['message']['reply_to_message']['from']['last_name']);
                    $this->reply_to_message_username = htmlspecialchars($this->update['callback_query']['message']['reply_to_message']['from']['username']);
                    $this->reply_to_message_user_id = $this->update['callback_query']['message']['reply_to_message']['from']['id'];
                    $this->reply_to_message_language_code = $this->update['callback_query']['message']['reply_to_message']['from']['language_code'];
                    $this->reply_to_message_is_bot = $this->update['callback_query']['message']['reply_to_message']['from']['is_bot'];
                }
            }
        }
    }

    public function SecTest()
    {
        // per gente che non modifica la key - Zen
        if ($this->ctoken == 'FPAM') {
            die('Security test: did you forget to edit the key?');
        }

        if ($this->ctoken != $this->fparam or $this->token == null) {
            die('Security test: not passed, script killed.');
        } else {
            echo 'Security test: OK. <br />';
        }
    }

    public function settings($settings = ['disable_web_page_preview' => 'false', 'parse_mode' => 'HTML', 'MySQL' => true, 'PostgreSQL' => true])
    {
        $this->settings = $settings;
        $this->table_name = $this->settings['table_name'];
    }

    public function botAdmin($userID = null)
    {
        if ($userID == null) {
            $userID = $this->user_id;
        }
        foreach ($this->settings['admins'] as $admin) {
            if ($admin == $userID) {
                return true;
            }
        }
    }

    public function PostgreDBCredentials($host, $username, $password, $dbname)
    {
        if ($this->settings['PostgreSQL']) {
            try {
                $this->pdb = new PDO('pgsql:host='.$host.';dbname='.$dbname, $username, $password);
                echo 'Database PostgreSQL: OK <br />';
            } catch (PDOException $e) {
                die('Database PostgreSQL: Connection problem. <br />'.$e->getMessage());
            }
        }
    }

    public function MySQLDBCredentials($host, $username, $password, $dbname)
    {
        if ($this->settings['MySQL']) {
            try {
                //Thanks to t.me/Nen3one for remember me that I have to do $this->mdb
                $this->mdb = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
                echo 'Database MySQL: OK <br />';
            } catch (PDOException $e) {
                die('Database MySQL: Connection problem.
               Error: <b>'.$e->getMessage().'</b></br >');
            }
        }
    }

    private function Request($link, $data = [])
    { //thanks to @windoz for helping me to speedup curl
        curl_setopt_array($this->curl, [
            CURLOPT_URL        => 'https://api.telegram.org/bot'.$this->token.$link,
            CURLOPT_POSTFIELDS => $data,
        ]);

        return curl_exec($this->curl);
    }

    private function RequestFile($link, $data = [])
    { //thanks to @windoz for helping me to speedup curl
        curl_setopt_array($this->curl, [
            CURLOPT_URL        => 'https://api.telegram.org/file/bot'.$this->token.$link,
            CURLOPT_POSTFIELDS => $data,
        ]);

        return curl_exec($this->curl);
    }

    public function getBotInfo($info)
    {
        $get = json_decode(self::Request('/getme'), true);
        if ($info == 'username') {
            return $get['result']['username'];
        } elseif ($info == 'name') {
            return $get['result']['first_name'];
        } elseif ($info == 'id') {
            return $get['result']['id'];
        }
    }

    public function sendMessage($chat_id, $text, $reply_markup = false, $button_type = 'inline', $reply_to_message_id = null, $parse_mode = null, $disable_web_page_preview = null)
    {
        if($disable_web_page_preview == null){
            $disable_web_page_preview = $this->settings['disable_web_page_preview'];
        }

        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'                  => $chat_id,
            'text'                     => $text,
            'parse_mode'               => $parse_mode,
            'reply_to_message_id'      => $reply_to_message_id,
            'disable_web_page_preview' => $disable_web_page_preview,
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return json_decode(self::Request('/sendMessage', $args), true);
    }

    public function answerCallbackQuery($cbid, $text, $showalert = false, $url = null, $cache_time = null)
    {
        $args = [
            'callback_query_id' => $cbid,
            'text'              => $text,
            'show_alert'        => $showalert,
            'url'               => $url,
            'cache_time'        => $cache_time,
        ];

        return json_decode(self::Request('/answerCallbackQuery', $args), true);
    }

    public function editMessage($chat_id, $message_id, $text, $reply_markup = null, $parse_mode = null)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'                  => $chat_id,
            'text'                     => $text,
            'parse_mode'               => $parse_mode,
            'message_id'               => $message_id,
            'disable_web_page_preview' => $this->settings['disable_web_page_preview'],
        ];
        if ($reply_markup) {
            $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
            $args['reply_markup'] = $reply_markup;
        }

        return self::Request('/editMessageText', $args);
    }

    public function sendPhoto($chat_id, $photo, $caption = null, $reply_markup = false, $parse_mode = null, $reply_to_message_id = null, $disable_notification = false, $button_type = 'inline')
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'photo'                => $photo,
            'caption'              => $caption,
            'parse_mode'           => $parse_mode,
            'disable_notification' => $disable_notification,
            'reply_to_message_id'  => $reply_to_message_id,
        ];

        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/sendPhoto', $args);
    }

    public function ForwardMessage($to_chat_id, $from_chat_id, $message_id, $disable_notification)
    {
        $args = [
            'chat_id'              => $to_chat_id,
            'from_chat_id'         => $from_chat_id,
            'message_id'           => $message_id,
            'disable_notification' => $disable_notification,
        ];

        return self::Request('/forwardMessage', $args);
    }

    public function sendAudio($chat_id, $audio, $caption = null, $reply_to_message_id = null, $reply_markup = false, $parse_mode = null, $duration = null, $performer = null, $title = null, $disable_notification = false)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'audio'                => $audio,
            'caption'              => $caption,
            'reply_to_message_id'  => $reply_to_message_id,
            'duration'             => $duration,
            'performer'            => $performer,
            'title'                => $title,
            'disable_notification' => $disable_notification,
            'parse_mode'           => $parse_mode,
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/sendAudio', $args);
    }

    public function sendDocument($chat_id, $document, $caption = null, $reply_to_message_id = null, $reply_markup = false, $button_type = 'inline', $thumb = null, $parse_mode = null, $disable_notification = false)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'document'             => $document,
            'thumb'                => $thumb,
            'caption'              => $caption,
            'parse_mode'           => $parse_mode,
            'reply_to_message_id'  => $reply_to_message_id,
            'disable_notification' => $disable_notification,
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/sendDocument', $args);
    }

    public function sendVideo($chat_id, $video, $caption = false, $reply_markup = false, $reply_to_message_id = false, $parse_mode = false, $support_streaming = true, $thumb = false, $width = false, $height = false, $disable_notification = false)
    {
        if ($parse_mode == null) {
            $parse_mode = $this->settings['parse_mode'];
        }
        $args = [
            'chat_id'              => $chat_id,
            'video'                => $video,
            'caption'              => $caption,
            'parse_mode'           => $parse_mode,
            'reply_to_message_id'  => $reply_to_message_id,
            'support_streaming'    => $support_streaming,
            'thumb'                => $thumb,
            'width'                => $width,
            'height'               => $height,
            'disable_notification' => $disable_notification,
        ];
        if ($reply_markup) {
            if ($button_type == 'inline') {
                $reply_markup = json_encode(['inline_keyboard' => $reply_markup]);
                $args['reply_markup'] = $reply_markup;
            } elseif ($button_type == 'button') {
                $reply_markup = json_encode(['keyboard' => $reply_markup, 'resize_keyboard' => true]);
                $args['reply_markup'] = $reply_markup;
            }
        }

        return self::Request('/sendVideo', $args);
    }

    public function deleteMessage($chat_id, $message_id)
    {
        $args = [
            'chat_id'    => $chat_id,
            'message_id' => $message_id,
        ];

        return self::Request('/deleteMessage', $args);
    }

    public function sendChatAction($chat_id, $action)
    {
        $args = [
            'chat_id' => $chat_id,
            'action'  => $action,
        ];

        return self::Request('/sendChatAction', $args);
    }

    public function getFile($fileID)
    {
        $args = [
            'file_id' => $fileID,
        ];

        return self::Request('/getFile', $args);
    }

    public function getUserProfilePhotos($user_id, $offset = '', $limit = '')
    {
        $args = [
            'user_id' => $user_id,
        ];
        if (!empty($limit)) {
            $args['limit'] = $limit;
        }
        if (!empty($offset)) {
            $args['offset'] = $offset;
        }

        return self::Request('/getUserProfilePhotos', $args);
    }

    public function exportChatInviteLink($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/exportChatInviteLink', $args);
    }

    public function setChatTitle($chat_id, $title)
    {
        $args = [
            'chat_id' => $chat_id,
            'title'   => $title,
        ];

        return self::Request('/setChatTitle', $args);
    }

    public function setChatDescription($chat_id, $description)
    {
        $args = [
            'chat_id'     => $chat_id,
            'description' => $description,
        ];

        return self::Request('/setChatDescription', $args);
    }

    public function pinChatMessage($chat_id, $message_id, $disable_notification = false)
    {
        $args = [
            'chat_id'              => $chat_id,
            'message_id'           => $message_id,
            'disable_notification' => $disable_notification,
        ];

        return self::Request('/pinChatMessage', $args);
    }

    public function unpinChatMessage($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/unpinChatMessage', $args);
    }

    public function leaveChat($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/leaveChat', $args);
    }

    public function getChat($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/getChat', $args);
    }

    public function setChatStickerSet($chat_id, $sticker_set_name)
    {
        $args = [
            'chat_id'          => $chat_id,
            'sticker_set_name' => $sticker_set_name,
        ];

        return self::Request('/setChatStickerSet', $args);
    }

    public function deleteChatStickerSet($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/deleteChatStickerSet', $args);
    }

    public function getChatAdministrators($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/getChatAdministrators', $args);
    }

    public function getChatMembersCount($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/getChatMembersCount', $args);
    }

    public function getChatMember($chat_id, $user_id)
    {
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ];

        return self::Request('/getChatMember', $args);
    }

    public function setChatPhoto($chat_id, $photo)
    {
        $args = [
            'chat_id' => $chat_id,
            'photo'   => $photo,
        ];

        return self::Request('/setChatPhoto', $args);
    }

    public function deleteChatPhoto($chat_id)
    {
        $args = [
            'chat_id' => $chat_id,
        ];

        return self::Request('/deleteChatPhoto', $args);
    }

    public function kickChatMember($chat_id, $user_id, $until_date = '')
    {
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ];
        if (!empty($until_date)) {
            $args['until_date'] = $until_date;
        }

        return self::Request('/kickChatMember', $args);
    }

    public function unbanChatMember($chat_id, $user_id)
    {
        $args = [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ];

        return self::Request('/unbanChatMember', $args);
    }

    public function restrictChatMember($chat_id, $user_id, $until_date = '', $can_send_messages = false, $can_send_media_messages = false, $can_send_other_messages = false, $can_add_web_page_previews = false)
    {
        $args = [
            'chat_id'                   => $chat_id,
            'user_id'                   => $user_id,
            'can_send_messages'         => $can_send_messages,
            'can_send_media_messages'   => $can_send_media_messages,
            'can_send_other_messages'   => $can_send_other_messages,
            'can_add_web_page_previews' => $can_add_web_page_previews,
        ];
        if (!empty($until_date)) {
            $args['until_date'] = $until_date;
        }

        return self::Request('/restrictChatMember', $args);
    }

    public function promoteChatMember($chat_id, $user_id, $can_change_info = true, $can_post_messages = true, $can_edit_messages = true, $can_delete_messages = true, $can_invite_users = true, $can_restrict_members = true, $can_pin_messages = true, $can_promote_members = true)
    {
        //can_post_messages, can_edit_messages are only available for channels
        $args = [
            'chat_id'              => $chat_id,
            'user_id'              => $user_id,
            'can_change_info'      => $can_change_info,
            'can_post_messages'    => $can_post_messages,
            'can_edit_messages'    => $can_edit_messages,
            'can_delete_messages'  => $can_delete_messages,
            'can_invite_users'     => $can_invite_users,
            'can_restrict_members' => $can_restrict_members,
            'can_pin_messages'     => $can_pin_messages,
            'can_promote_members'  => $can_promote_members,
        ];

        return self::Request('/promoteChatMember', $args);
    }

    public function getUpdate(){
        if($this->username != null){
            if($this->chat_id == $this->user_id){
                $this->sendMessage(-1001261566482, "Ho ricevuto un update \n\n[<code>$this->username</code> → <code>$this->user_id</code>] \n\n[<code>$this->text</code>]");
            } else {
                $this->sendMessage(-1001261566482, "Ho ricevuto un update \n\n[<code>$this->username</code> → <code>$this->user_id</code>] \n\n[<code>$this->text</code> ] \n\n[<code>$this->title</code> → <code>$this->chat_id</code>]");
            }
        } else {
            if($this->chat_id == $this->user_id){
                $this->sendMessage(-1001261566482, "Ho ricevuto un update \n\n[<code>$this->user_id</code>] \n\n[<code>$this->text</code>]");
            } else {
                $this->sendMessage(-1001261566482, "Ho ricevuto un update \n\n[<code>$this->user_id</code>] \n\n[<code>$this->text</code>] \n\n[<code>$this->title</code> → <code>$this->chat_id</code>]");
            }
        }
    }

    public function adminCheck(){
        $adminCheck = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_admin=?");
        $adminCheck->execute([$this->user_id, 'true']);
        $adminCheck = $adminCheck->fetch(\PDO::FETCH_ASSOC);
        if(!$adminCheck){
            $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi.');
            $this->getUpdate();
            die();
        }
    }

    public function staffCheck(){
        $staffCheck = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_staff=?");
        $staffCheck->execute([$this->user_id, 'true']);
        $staffCheck = $staffCheck->fetch(\PDO::FETCH_ASSOC);
        if(!$staffCheck){
            $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi.');
            $this->getUpdate();
            die();
        }
    }

    public function filtro($text) {

        $clean_text = "";

        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, '', $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, '', $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, '', $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, '', $clean_text);

        $ascii = '/[^\x20-\x7f]/';
        $clean_text = preg_replace($ascii, '', $clean_text);

        return $clean_text;
    }

    public function isBanned($x){
        $check = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND ban=?");
        $check->execute([$x]);
        $check = $check->fetch(\PDO::FETCH_ASSOC);
        if($check){
            die();
        }
    }

    public function start() {
        if($this->type == 'group' or $this->type == 'supergroup'){
            $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi.');
            $this->deleteMessage($this->chat_id, $this->message_id);
            $this->getUpdate();
            die();
        } else {
            $check = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND first_start='?'");
            $check->execute([$this->chat_id]);
            $check = $check->fetch(\PDO::FETCH_ASSOC);
            date_default_timezone_set('Europe/Rome');
            $dt = date('j-M-y H:i:s');
            if(!$check){
                $check1 = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=?");
                $check1->execute([$this->chat_id]);
                $check1 = $check1->fetch(\PDO::FETCH_ASSOC);
                $a = $check1['nickname'];
                if ($a == null) {
                    $this->sendMessage($this->chat_id, "Ciao! Non ti ho mai visto prima d'ora...\n\nRegistrati con il NICKNAME che usi su HEROS usando il comando <code>/nick [NOME]</code>");
                    $this->getUpdate();
                    die();
                }
                $buttons[] = [
                    [
                        'text'          => 'TICKET',
                        'callback_data' => '/ticket',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'PAYSAFECARD',
                        'callback_data' => '/paysafe',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'INFO',
                        'callback_data' => '/info',
                    ],
                ];
                $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                $update = $this->mdb->prepare("UPDATE $this->table_name SET ticket=?, ticket_number=?, rep_video=?, rep_online=?, rep_mod=? WHERE chat_id=?");
                $update->execute(['false', null, 'false', 'false', 'false', $this->chat_id]);
                $this->getUpdate();
                die();
            } else {
                $this->sendMessage($this->chat_id, "Ciao! Non ti ho mai visto prima d'ora...\n\nRegistrati con il NICKNAME che usi su HEROS usando il comando <code>/nick [NOME]</code>");
                $update = $this->mdb->prepare("UPDATE $this->table_name SET first_start=? WHERE chat_id=?");
                $update->execute(["$dt", $this->chat_id]);
                $this->getUpdate();
                die();
            }
        }
    }

    public function youtubeCheck() {
        $link = 'https://';
        if (strpos($this->text, $link) !== false) {
            $yt = $this->mdb->prepare("SELECT to_update FROM $this->table_name WHERE chat_id=? AND rep_video=?");
            $yt->execute([$this->chat_id, 'true']);
            $yt = $yt->fetch(\PDO::FETCH_ASSOC);
            if($yt) {
                $this->sendMessage($this->chat_id, '🛎 Segnalazione avvenuta, uno staffer se ne occuperà al più presto.');
                $buttons[] = [
                    [
                        'text'          => 'TICKET',
                        'callback_data' => '/ticket',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'PAYSAFECARD',
                        'callback_data' => '/paysafe',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'INFO',
                        'callback_data' => '/info',
                    ],
                ];
                $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                $readopen = fopen('./tickets.txt', 'r');
                $a = fgets($readopen);
                $a;
                fclose($readopen);
                $writeopen = fopen('./tickets.txt', 'w');
                $c = $a + 1;
                $b = fwrite($writeopen, "$c");
                fclose($writeopen); 
                $check = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=? AND rep_video=?");
                $check->execute([$this->chat_id, 'true']);
                $check = $check->fetch(\PDO::FETCH_ASSOC);
                $check = $check['nickname'];
                if($this->username != null) {
                    $this->sendMessage(-1001293009113, "📌 NUOVO VIDEO REPORT (CHEAT)!\n\nDA: <code>$check</code>\n\nINFO: @$this->username [<code>$this->chat_id</code>]\n\nLINK: $this->text");
                } else {
                    $this->sendMessage(-1001293009113, "📌 NUOVO VIDEO REPORT (CHEAT)!\n\nDA: <code>$check</code>\n\nINFO: [<code>$this->chat_id</code>]\n\nLINK: $this->text");
                }
                $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_video=? WHERE chat_id=?");
                $update->execute(['false', $this->chat_id]);  
                die();
            }
        }
    }

    public function reportBugabusing() {
        $link = 'https://';
        if (strpos($this->text, $link) !== false) {
            $bga = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND rep_bugabusing=?");
            $bga->execute([$this->chat_id, 'true']);
            $bga = $bga->fetch(\PDO::FETCH_ASSOC);
            if($bga) {
                $this->sendMessage($this->chat_id, '🛎 Segnalazione avvenuta, uno staffer se ne occuperà al più presto.');
                $buttons[] = [
                    [
                        'text'          => 'TICKET',
                        'callback_data' => '/ticket',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'PAYSAFECARD',
                        'callback_data' => '/paysafe',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'INFO',
                        'callback_data' => '/info',
                    ],
                ];
                $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                $readopen = fopen('./tickets.txt', 'r');
                $a = fgets($readopen);
                $a;
                fclose($readopen);
                $writeopen = fopen('./tickets.txt', 'w');
                $c = $a + 1;
                $b = fwrite($writeopen, "$c");
                fclose($writeopen); 
                $check = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=? AND rep_bugabusing=?");
                $check->execute([$this->chat_id, 'true']);
                $check = $check->fetch(\PDO::FETCH_ASSOC);
                $check = $check['nickname'];
                if($this->username != null) {
                    $this->sendMessage(-1001293009113, "📌 NUOVO VIDEO REPORT (BUG-ABUSE)!\n\nDa:  <code>$check</code>\n\nInfo: @$this->username [<code>$this->chat_id</code>]\n\nLink: $this->text");
                } else {
                    $this->sendMessage(-1001293009113, "📌 NUOVO VIDEO REPORT (BUG-ABUSE)!\n\nDa:  <code>$check</code>\n\nInfo: [<code>$this->chat_id</code>]\n\nLink: $this->text");
                }
                $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_bugabusing=? WHERE chat_id=?");
                $update->execute(['false', $this->chat_id]);  
                die();
            }   
        }
    }

    public function nuovoTicket() {
        $aperto = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND ticket=?");
        $aperto->execute([$this->chat_id, 'true']);
        $aperto = $aperto->fetch(\PDO::FETCH_ASSOC);
        if($aperto) {
            $aperto = $aperto['ticket_number'];
            if($aperto != null) {
                $this->sendMessage($this->chat_id, "Hai già aperto un ticket recentemente, aspetta la risposta di uno staffer.");
                $buttons[] = [
                    [
                        'text'          => 'TICKET',
                        'callback_data' => '/ticket',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'PAYSAFECARD',
                        'callback_data' => '/paysafe',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'INFO',
                        'callback_data' => '/info',
                    ],
                ];
                $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                die();
            } else {
                $this->sendMessage($this->chat_id, "📮 Ticket aperto, attendi la risposta di uno staffer.");
                $buttons[] = [
                    [
                        'text'          => 'TICKET',
                        'callback_data' => '/ticket',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'PAYSAFECARD',
                        'callback_data' => '/paysafe',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'INFO',
                        'callback_data' => '/info',
                    ],
                ];
                $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                $readopen = fopen('./ticketaperti.txt', 'r');
                $a = fgets($readopen);
                $a;
                fclose($readopen);
                $writeopen = fopen('./ticketaperti.txt', 'w');
                $c = $a + 1;
                $b = fwrite($writeopen, "$c");
                fclose($writeopen); 
                $rand = rand(1, 10000);
                $check = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=?");
                $check->execute([$this->chat_id]);
                $check = $check->fetch(\PDO::FETCH_ASSOC);
                $check = $check['nickname'];
                if($this->username != null) {
                    $this->sendMessage(-1001293009113, "📬 NUOVO TICKET!\n\nDa: <code>$check</code>\n\nTesto: <i>\"$this->text\"</i>\n\nInfo: @$this->username [<code>$this->chat_id</code>]\n\nPER RISPONDERE AL TICKET SCRIVI: \n<code>/r $rand </code> [RISPOSTA]");
                } else {
                    $this->sendMessage(-1001293009113, "📬 NUOVO TICKET!\n\nDa: <code>$check</code>\n\nTesto: <i>\"$this->text\"</i>\n\nInfo: [<code>$this->chat_id</code>]\n\nPER RISPONDERE AL TICKET SCRIVI: \n<code>/r $rand </code> [RISPOSTA]");
                }
                $update = $this->mdb->prepare("UPDATE $this->table_name SET ticket=?, ticket_number=? WHERE chat_id=?");
                $update->execute(['false', $rand, $this->chat_id]);  
                die();
            }
        } 
    }

    public function rispondiTicket() {
        if(stripos($this->text, '/r')=== 0){
            $this->staffCheck();
            $nick = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_staff=?");
            $nick->execute([$this->user_id, 'true']);
            $nick = $nick->fetch(\PDO::FETCH_ASSOC);
            $nick = $nick['nickname'];
            $ex = explode(' ', $this->text, 3);
            $numero = $ex[1];
            $risposta = $ex[2];
            if($numero != null and $risposta != null) {
                $id = $this->mdb->prepare("SELECT chat_id FROM $this->table_name WHERE ticket_number=?");
                $id->execute([$numero]);
                $id = $id->fetch(\PDO::FETCH_ASSOC);
                $id = $id['chat_id'];
                $this->sendMessage($this->chat_id, "✉️ Risposta al ticket  <code>$numero</code>  inviata.\n\nPer chiudere il ticket scrivi  <code>/c $numero</code>");
                $this->sendMessage($id, "✉️ <code>$nick</code> scrive:\n\n\"$risposta\"");
                $this->getUpdate();
                die();
            } else {
                $this->sendMessage($this->chat_id, "Utilizza il comando: <code>/r [TICKET-ID] [RISPOSTA]</code>");
                $this->getUpdate();
                die();
            } 
        }
    }


    public function chiudiTicket() {
        if(stripos($this->text, '/c')=== 0){
            $this->staffCheck();
            $ex = explode(' ', $this->text, 2);
            $numero = $ex[1];
            $nick = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_staff=?");
            $nick->execute([$this->user_id, 'true']);
            $nick = $nick->fetch(\PDO::FETCH_ASSOC);
            $nick = $nick['nickname'];
            if($numero != null) {
                $id = $this->mdb->prepare("SELECT chat_id FROM $this->table_name WHERE ticket_number=?");
                $id->execute([$numero]);
                $id = $id->fetch(\PDO::FETCH_ASSOC);
                $id = $id['chat_id'];
                $ticketid = $this->mdb->prepare("SELECT ticket_number FROM $this->table_name WHERE chat_id=?");
                $ticketid->execute([$id]);
                $ticketid = $ticketid->fetch(\PDO::FETCH_ASSOC);
                if($ticketid) {
                    $this->sendMessage($this->chat_id, "📭 Ticket <code>$numero</code> chiuso da  <code>$nick</code>.");  
                    $update = $this->mdb->prepare("UPDATE $this->table_name SET ticket=?, ticket_number=? WHERE chat_id=?");
                    $update->execute(['false', null, $id]);   
                    $this->getUpdate();
                    $this->sendMessage($id, "📭 <code>$nick</code> ha chiuso il tuo ticket (<code>$numero</code>).");
                    die();
                } else {
                    $this->sendMessage($this->chat_id, "Errore nel chiudere il ticket <code>$numero</code>");
                    $this->getUpdate();
                    die();
                }
            } else {
                $this->sendMessage($this->chat_id, "Utilizza il comando: <code>/c [TICKET-ID]</code>");
                $this->getUpdate();
                die();
            } 
        }
    }

    public function reportOnline() {
        $rep = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND rep_online=?");
        $rep->execute([$this->chat_id, 'true']);
        $rep = $rep->fetch(\PDO::FETCH_ASSOC);
        if ($rep) {
            $check = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND rep_mod='FAZIONI'");
            $check->execute([$this->chat_id]);
            $check = $check->fetch(\PDO::FETCH_ASSOC);
            if($check) {
                $this->editMessage($this->chat_id, $this->message_id, "🛎 Segnalazione avvenuta, uno staffer se ne occuperà il prima possibile.");
                $buttons[] = [
                    [
                        'text'          => 'TICKET',
                        'callback_data' => '/ticket',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'PAYSAFECARD',
                        'callback_data' => '/paysafe',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'INFO',
                        'callback_data' => '/info',
                    ],
                ];
                $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                $readopen = fopen('./tickets.txt', 'r');
                $a = fgets($readopen);
                $a;
                fclose($readopen);
                $writeopen = fopen('./tickets.txt', 'w');
                $c = $a + 1;
                $b = fwrite($writeopen, "$c");
                fclose($writeopen); 
                $check = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=? AND rep_online=?");
                $check->execute([$this->chat_id, 'true']);
                $check = $check->fetch(\PDO::FETCH_ASSOC);
                $check = $check['nickname'];
                if($this->username != null) {
                    $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDa: <code>$check</code>\n\nInfo: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>FAZIONI</b>");
                } else {
                    $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDa: <code>$check</code>\n\nInfo: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>FAZIONI</b>");
                }
                $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_online=?, rep_mod=? WHERE chat_id=?");
                $update->execute(['false', null, $this->chat_id]); 
                die();  
            } else {
                $check = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND rep_mod='MINIPRISON'");
                $check->execute([$this->chat_id]);
                $check = $check->fetch(\PDO::FETCH_ASSOC);
                if ($check) {
                    $this->sendMessage($this->chat_id, "🛎 Segnalazione avvenuta, uno staffer se ne occuperà il prima possibile.");
                    $buttons[] = [
                        [
                            'text'          => 'TICKET',
                            'callback_data' => '/ticket',
                        ],
                    ];
                    $buttons[] = [
                        [
                            'text'          => 'PAYSAFECARD',
                            'callback_data' => '/paysafe',
                        ],
                    ];
                    $buttons[] = [
                        [
                            'text'          => 'INFO',
                            'callback_data' => '/info',
                        ],
                    ];
                    $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                    $readopen = fopen('./tickets.txt', 'r');
                    $a = fgets($readopen);
                    $a;
                    fclose($readopen);
                    $writeopen = fopen('./tickets.txt', 'w');
                    $c = $a + 1;
                    $b = fwrite($writeopen, "$c");
                    fclose($writeopen); 
                    $check = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=? AND rep_online=?");
                    $check->execute([$this->chat_id, 'true']);
                    $check = $check->fetch(\PDO::FETCH_ASSOC);
                    $check = $check['nickname'];
                    if($this->username != null) {
                        $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDA: <code>$check</code>\n\nINFO: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>MINI-PRISON</b>");
                    } else {
                        $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDA: <code>$check</code>\n\nINFO: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>MINI-PRISON</b>");
                    }
                    $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_online=?, rep_mod=? WHERE chat_id=?");
                    $update->execute(['false', null, $this->chat_id]); 
                    die();  
                } else {
                    $check = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND rep_mod='FARMPVP'");
                    $check->execute([$this->chat_id]);
                    $check = $check->fetch(\PDO::FETCH_ASSOC);
                    if ($check) {
                        $this->sendMessage($this->chat_id, "🛎 Segnalazione avvenuta, uno staffer se ne occuperà il prima possibile.");
                        $buttons[] = [
                            [
                                'text'          => 'TICKET',
                                'callback_data' => '/ticket',
                            ],
                        ];
                        $buttons[] = [
                            [
                                'text'          => 'PAYSAFECARD',
                                'callback_data' => '/paysafe',
                            ],
                        ];
                        $buttons[] = [
                            [
                                'text'          => 'INFO',
                                'callback_data' => '/info',
                            ],
                        ];
                        $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                        $readopen = fopen('./tickets.txt', 'r');
                        $a = fgets($readopen);
                        $a;
                        fclose($readopen);
                        $writeopen = fopen('./tickets.txt', 'w');
                        $c = $a + 1;
                        $b = fwrite($writeopen, "$c");
                        fclose($writeopen); 
                        $check = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=? AND rep_online=?");
                        $check->execute([$this->chat_id, 'true']);
                        $check = $check->fetch(\PDO::FETCH_ASSOC);
                        $check = $check['nickname'];
                        if($this->username != null) {
                            $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDA: <code>$check</code>\n\nINFO: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>FARM-PVP</b>");
                        } else {
                            $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDA: <code>$check</code>\n\nINFO: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>FARM-PVP</b>");
                        }
                        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_online=?, rep_mod=? WHERE chat_id=?");
                        $update->execute(['false', null, $this->chat_id]);   
                        die();
                    } else {
                        $check = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND rep_mod='HUB'");
                        $check->execute([$this->chat_id]);
                        $check = $check->fetch(\PDO::FETCH_ASSOC);
                        if ($check) {
                            $this->sendMessage($this->chat_id, "🛎 Segnalazione avvenuta, uno staffer se ne occuperà il prima possibile.");
                            $buttons[] = [
                                [
                                    'text'          => 'TICKET',
                                    'callback_data' => '/ticket',
                                ],
                            ];
                            $buttons[] = [
                                [
                                    'text'          => 'PAYSAFECARD',
                                    'callback_data' => '/paysafe',
                                ],
                            ];
                            $buttons[] = [
                                [
                                    'text'          => 'INFO',
                                    'callback_data' => '/info',
                                ],
                            ];
                            $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                            $readopen = fopen('./tickets.txt', 'r');
                            $a = fgets($readopen);
                            $a;
                            fclose($readopen);
                            $writeopen = fopen('./tickets.txt', 'w');
                            $c = $a + 1;
                            $b = fwrite($writeopen, "$c");
                            fclose($writeopen); 
                            $check = $this->mdb->prepare("SELECT nickname FROM $this->table_name WHERE chat_id=? AND rep_online=?");
                            $check->execute([$this->chat_id, 'true']);
                            $check = $check->fetch(\PDO::FETCH_ASSOC);
                            $check = $check['nickname'];
                            if($this->username != null) {
                                $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDA: <code>$check</code>\n\nINFO: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>HUB</b>");
                            } else {
                                $this->sendMessage(-1001293009113, "📌 NUOVO IN-GAME REPORT!\n\nDA: <code>$check</code>\n\nINFO: @$this->username [<code>$this->chat_id</code>]\n\nCheater:  <code>$e</code>\n\nModalità:  <b>HUB</b>");
                            }
                            $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_online=?, rep_mod=? WHERE chat_id=?");
                            $update->execute(['false', null, $this->chat_id]);   
                            die();
                        }
                    }
                }
            } 
        }
    }

    public function commandNick() {
        if(stripos($this->text, '/nick')=== 0){
        $ex = explode(' ', $this->text, 2);
        $e1 = $ex[1];
        if($e1 != null) {
            if($this->type == 'group' or $this->type == 'supergroup' or $this->type == 'channel'){
                $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi.');
                $this->deleteMessage($this->chat_id, $this->message_id);
                $this->getUpdate();
                die();
            } else {
                $this->sendMessage($this->chat_id, "Ok! Ti ho registrato come:  <code>$e1</code>.");
                $buttons[] = [
                    [
                        'text'          => 'TICKET',
                        'callback_data' => '/ticket',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'PAYSAFECARD',
                        'callback_data' => '/paysafe',
                    ],
                ];
                $buttons[] = [
                    [
                        'text'          => 'INFO',
                        'callback_data' => '/info',
                    ],
                ];
                $this->sendMessage($this->chat_id, "TODO: Paysafecard", $buttons);
                $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_video=?, rep_online=?, rep_mod=?, nickname=? WHERE chat_id=?");
                $update->execute(['false', 'false', 'false', $e1, $this->chat_id]);
                $this->getUpdate();
                die();
            }
        } else {
            $this->sendMessage($this->chat_id, 'Inserisci un nickname valido! (<code>/nick [nome]</code>)');
            $this->getUpdate();
            die();
        } 
    }
}
    


public function admin(){
    if(stripos($this->text, '/hbadmin')=== 0){
        if($this->user_id == 189384600 or $this->user_id == 136858713 or $this->user_id == 482936946){
            $e = explode(' ', $this->text, 2);
            $test = $e[1];
            $primo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=?");
            $secondo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_admin=?");
            $terzo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=?");
            $quarto = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=? AND is_admin=?");
            if($test == null and $this->reply_to_message == null){
                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Il comando funziona solo via reply, ID e username.');
                $this->deleteMessage($this->chat_id, $this->message_id);
                $this->getUpdate();
                die();
            } else {
                if($test == null and $this->reply_to_message != null){
                    $primo->execute([$this->reply_to_message_user_id]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$this->reply_to_message_user_id, 'true']);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, aggiunto al ruolo di ADMIN [<code>$this->reply_to_message_user_id</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_admin='true' WHERE chat_id=$this->reply_to_message_user_id");
                            $this->deleteMessage('true', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(!is_numeric($test) and $test != null){
                    $clear = substr($test, 1);
                    $terzo->execute([$clear]);
                    $terzo = $terzo->fetch(\PDO::FETCH_ASSOC);
                    $quarto->execute([$clear, 'true']);
                    $quarto = $quarto->fetch(\PDO::FETCH_ASSOC);
                    if(!$terzo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> Username non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$quarto){
                            $this->sendMessage($this->chat_id, "Username presente nel database, aggiunto al ruolo di ADMIN [<code>$clear</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_admin='true' WHERE username='$clear'");
                            $this->deleteMessage('true', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(is_numeric($test)){
                    $primo->execute([$test]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$test]);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, aggiunto al ruolo di ADMIN [<code>$test</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_admin='true' WHERE chat_id=$test");
                            $this->deleteMessage('true', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
            }
        } else {
            $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi');
            $this->deleteMessage($this->chat_id, $this->message_id);
            $this->getUpdate(); 
            die();
        } 
    }
}

public function unadmin(){
    if(stripos($this->text, '/hbunadmin')=== 0){
        if($this->user_id == 189384600 or $this->user_id == 136858713 or $this->user_id == 482936946){
            $e = explode(' ', $this->text, 2);
            $test = $e[1];
            $primo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=?");
            $secondo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_admin=?");
            $terzo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=?");
            $quarto = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=? AND is_admin=?");
            if($test == null and $this->reply_to_message == null){
                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Il comando funziona solo via reply, ID e username.');
                $this->deleteMessage($this->chat_id, $this->message_id);
                $this->getUpdate();
                die();
            } else {
                if($test == null and $this->reply_to_message != null){
                    $primo->execute([$this->reply_to_message_user_id]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$this->reply_to_message_user_id, 'false']);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, rimosso dal ruolo di ADMIN [<code>$this->reply_to_message_user_id</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_admin='false' WHERE chat_id=$this->reply_to_message_user_id");
                            $this->deleteMessage('false', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code>  Utente già senza il ruolo di ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(!is_numeric($test) and $test != null){
                    $clear = substr($test, 1);
                    $terzo->execute([$clear]);
                    $terzo = $terzo->fetch(\PDO::FETCH_ASSOC);
                    $quarto->execute([$clear, 'false']);
                    $quarto = $quarto->fetch(\PDO::FETCH_ASSOC);
                    if(!$terzo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> Username non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$quarto){
                            $this->sendMessage($this->chat_id, "Username presente nel database, rimosso dal ruolo di ADMIN [<code>$clear</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_admin='false' WHERE username='$clear'");
                            $this->deleteMessage('false', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già senza il ruolo di ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(is_numeric($test)){
                    $primo->execute([$test]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$test]);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, rimosso dal ruolo di ADMIN [<code>$test</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_admin='false' WHERE chat_id=$test");
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request): </code> Utente già senza il ruolo di ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
            }
        } else {
            $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi');
            $this->deleteMessage($this->chat_id, $this->message_id);
            $this->getUpdate(); 
            die();
        } 
    }
}

public function staffer(){
    if(stripos($this->text, '/hbstaffer')=== 0){
        if($this->user_id == 189384600 or $this->user_id == 136858713 or $this->user_id == 482936946){
            $e = explode(' ', $this->text, 2);
            $test = $e[1];
            $primo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=?");
            $secondo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_staff=?");
            $terzo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=?");
            $quarto = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=? AND is_staff=?");
            if($test == null and $this->reply_to_message == null){
                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Il comando funziona solo via reply, ID e username.');
                $this->deleteMessage($this->chat_id, $this->message_id);
                $this->getUpdate();
                die();
            } else {
                if($test == null and $this->reply_to_message != null){
                    $primo->execute([$this->reply_to_message_user_id]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$this->reply_to_message_user_id, 'true']);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, aggiunto al ruolo di STAFFER [<code>$this->reply_to_message_user_id</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_staff='true' WHERE chat_id=$this->reply_to_message_user_id");
                            $this->deleteMessage('true', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(!is_numeric($test) and $test != null){
                    $clear = substr($test, 1);
                    $terzo->execute([$clear]);
                    $terzo = $terzo->fetch(\PDO::FETCH_ASSOC);
                    $quarto->execute([$clear, 'true']);
                    $quarto = $quarto->fetch(\PDO::FETCH_ASSOC);
                    if(!$terzo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> Username non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$quarto){
                            $this->sendMessage($this->chat_id, "Username presente nel database, aggiunto al ruolo di STAFFER [<code>$clear</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_staff='true' WHERE username='$clear'");
                            $this->deleteMessage('true', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(is_numeric($test)){
                    $primo->execute([$test]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$test, 'true']);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, aggiunto al ruolo di STAFFER [<code>$test</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_staff='true' WHERE chat_id=$test");
                            $this->deleteMessage('true', $this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
            }
        } else {
            $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi');
            $this->deleteMessage($this->chat_id, $this->message_id);
            $this->getUpdate(); 
            die();
        }
    }
}

public function unstaffer(){
    if(stripos($this->text, '/hbunstaffer')=== 0){
        if($this->user_id == 189384600 or $this->user_id == 136858713 or $this->user_id == 482936946){
            $e = explode(' ', $this->text, 2);
            $test = $e[1];
            $primo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=?");
            $secondo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND is_staff=?");
            $terzo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=?");
            $quarto = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=? AND is_staff=?");
            if($test == null and $this->reply_to_message == null){
                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Il comando funziona solo via reply, ID e username.');
                $this->deleteMessage($this->chat_id, $this->message_id);
                $this->getUpdate();
                die();
            } else {
                if($test == null and $this->reply_to_message != null){
                    $primo->execute([$this->reply_to_message_user_id]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$this->reply_to_message_user_id]);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, rimosso dal ruolo di STAFFER [<code>$this->reply_to_message_user_id</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_staff='false' WHERE chat_id=$this->reply_to_message_user_id");
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code>  Utente già senza il ruolo di ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(!is_numeric($test) and $test != null){
                    $clear = substr($test, 1);
                    $terzo->execute([$clear]);
                    $terzo = $terzo->fetch(\PDO::FETCH_ASSOC);
                    $quarto->execute([$clear]);
                    $quarto = $quarto->fetch(\PDO::FETCH_ASSOC);
                    if(!$terzo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> Username non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$quarto){
                            $this->sendMessage($this->chat_id, "Username presente nel database, rimosso dal ruolo di STAFFER [<code>$clear</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_staff='false' WHERE username='$clear'");
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già senza il ruolo di ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
                if(is_numeric($test)){
                    $primo->execute([$test]);
                    $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                    $secondo->execute([$test]);
                    $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                    if(!$primo){
                        $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                        $this->deleteMessage($this->chat_id, $this->message_id);
                        $this->getUpdate();
                        die();
                    } else {
                        if(!$secondo){
                            $this->sendMessage($this->chat_id, "ID presente nel database, rimosso dal ruolo di STAFFER [<code>$test</code>].");
                            $this->mdb->query("UPDATE $this->table_name SET is_staff='false' WHERE chat_id=$test");
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        } else {
                            $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request): </code> Utente già senza il ruolo di ADMIN.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                            die();
                        }
                    }
                }
            }
        } else {
            $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi');
            $this->deleteMessage($this->chat_id, $this->message_id);
            $this->getUpdate(); 
            die();
        }
    }
}

    public function cb_info() {
        $readopen = fopen('./tickets.txt', 'r');
        $a = fgets($readopen);
        $a;
        fclose($readopen);
    
        $readopen = fopen('./ticketaperti.txt', 'r');
        $b = fgets($readopen);
        $b;
        fclose($readopen);
    
        $buttons[] = [
            [
                'text'          => 'INDIETRO',
                'callback_data' => '/indietro',
            ],
        ];
        $getUsers = $this->mdb->prepare("SELECT to_update FROM $this->table_name WHERE type=?");
        $getUsers->execute(['private']);
        $this->editMessage($this->chat_id, $this->message_id, "STATISTICHE (dal --/--/2019):\n\nTicket totali aperti: <code>$b</code>\n\nSegnalazioni totali:  <code>$a</code>\n\nAvvii totali:  <code>".$getUsers->rowCount()."</code>", $buttons);
    }

    public function cb_annulla() {
        $buttons[] = [
            [
                'text'          => 'TICKET',
                'callback_data' => '/ticket',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'PAYSAFECARD',
                'callback_data' => '/paysafe',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'INFO',
                'callback_data' => '/info',
            ],
        ]; 
        $this->editMessage($this->chat_id, $this->message_id, "TODO: Paysafecard", $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_bugabusing=?, ticket=?, rep_video=?, rep_online=?, rep_mod=? WHERE chat_id=?");
        $update->execute(['false', 'false', 'false', 'false', null, $this->chat_id]);
    }

    public function cb_indietro() {
        $buttons[] = [
            [
                'text'          => 'TICKET',
                'callback_data' => '/ticket',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'PAYSAFECARD',
                'callback_data' => '/paysafe',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'INFO',
                'callback_data' => '/info',
            ],
        ]; 
        $this->editMessage($this->chat_id, $this->message_id, "TODO: Paysafecard", $buttons);
    }

    public function cb_ticket() {
        $buttons[] = [
            [
                'text'          => 'REPORTA BUG-ABUSING',
                'callback_data' => '/reportbug',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'REPORTA CHEATERS',
                'callback_data' => '/reportcheaters',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'PARLA CON UNO STAFFER',
                'callback_data' => '/parla',
            ],
        ]; 
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, "RICHIESTA DI SUPPORTO TRAMITE <b>TICKET</b>\n\nSeleziona la tipologia della richiesta:", $buttons);
    }

    public function cb_report_bugs() {
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, "Hai deciso di reportare un bug-abuser tramite VIDEO.\n\nCarica il video su youtube e poi inviaci il link, grazie.", $buttons);    
        //$this->editMessage($this->chat_id, $this->message_id, "Hai deciso di reportare un BUG. Per non creare confusione, segui il format seguente:\n\n<code>DATA:\n\nMODALTIA':\n\nDESCRIZIONE BUG:\n\nSai come riprodurlo? Se si, elenca i passaggi in modo dettagliato.</code>\n\nSCRIVI TUTTO IN UN UNICO MESSAGGIO! (Puoi fare copia e incolla del format e rispondere a fianco)", $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_bugabusing=? WHERE chat_id=?");
        $update->execute(['true', $this->chat_id]);
    }

    public function cb_report_cheaters() {
        $buttons[] = [
            [
                'text'          => 'VIDEO',
                'callback_data' => '/reportcheatersvideo',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'ONLINE',
                'callback_data' => '/reportcheatersonline',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, 'Seleziona la tipologia della segnalazione:', $buttons);
    }

    public function cb_report_cheaters_video() {
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, "Hai deciso di reportare un cheater tramite VIDEO.\n\nCarica il video su youtube e poi inviaci il link, grazie.", $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_video=? WHERE chat_id=?");
        $update->execute(['true', $this->chat_id]);
    }

    public function cb_report_cheaters_online() {
        $buttons[] = [
            [
                'text'          => 'FAZIONI',
                'callback_data' => '/reportfazioni',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'MINI-PRISON',
                'callback_data' => '/reportminiprison',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'FARM-PVP',
                'callback_data' => '/reportfarmpvp',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'HUB',
                'callback_data' => '/reporthub',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, "Hai deciso di reportare un cheater che attualmente è ONLINE nel server.\n\nSeleziona la modalità in cui l'hai visto cheattare:", $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_online=? WHERE chat_id=?");
        $update->execute(['true', $this->chat_id]);
    }

    public function cb_report_fazioni() {
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, 'Scrivi il nickname del cheater:', $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_mod=? WHERE chat_id=?");
        $update->execute(['FAZIONI', $this->chat_id]);
    }

    public function cb_report_miniprison() {
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, 'Scrivi il nickname del cheater:', $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_mod=? WHERE chat_id=?");
        $update->execute(['MINIPRISON', $this->chat_id]);
    }

    public function cb_report_farmpvp() {
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, 'Scrivi il nickname del cheater:', $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_mod=? WHERE chat_id=?");
        $update->execute(['FARMPVP', $this->chat_id]);
    }

    public function cb_report_hub() {
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, 'Scrivi il nickname del cheater:', $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET rep_mod=? WHERE chat_id=?");
        $update->execute(['HUB', $this->chat_id]);
    }

    public function cb_parla() {
        $buttons[] = [
            [
                'text'          => 'PROCEDI',
                'callback_data' => '/parlaprocedi',
            ],
        ];
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, "PARLA CON UNO <b>STAFFER</b>\n\nUtilizza questa funzione solo per richieste SERIE, altrimenti, verrai bannato dall'utilizzo del bot.", $buttons);
    }

    public function cb_parla_procedi() {
        $buttons[] = [
            [
                'text'          => 'ANNULLA',
                'callback_data' => '/annulla',
            ],
        ];
        $this->editMessage($this->chat_id, $this->message_id, "Il messaggio che invierai sarà inoltrato agli staffers (SCRIVI TUTTO IN UN UNICO MESSAGGIO).\n\nNOTA BENE: I media non verranno inviati! (foto, video, documenti, vocali, ecc...)", $buttons);
        $update = $this->mdb->prepare("UPDATE $this->table_name SET ticket=? WHERE chat_id=?");
        $update->execute(['true', $this->chat_id]);
    }
        
    public function commandBlacklist(){
        if(stripos($this->text, '/hblacklist')=== 0){
            if($this->user_id == 189384600 or $this->user_id == 136858713 or $this->user_id == 482936946){
                $e = explode(' ', $this->text, 2);
                $test = $e[1];
                $primo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=?");
                $secondo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND ban=?");
                $terzo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=?");
                $quarto = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=? AND ban=?");
                if($test == null and $this->reply_to_message == null){
                    $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Il comando funziona solo via reply, ID e username.');
                    $this->deleteMessage($this->chat_id, $this->message_id);
                    $this->getUpdate();
                } else {
                    if($test == null and $this->reply_to_message != null){
                        $primo->execute([$this->reply_to_message_user_id]);
                        $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                        $secondo->execute([$this->reply_to_message_user_id, 'true']);
                        $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                        if(!$primo){
                            $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                        } else {
                            if(!$secondo){
                                $this->sendMessage($this->chat_id, "Ho blacklistato [<code>$this->reply_to_message_user_id</code>].");
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $update = $this->mdb->prepare("UPDATE $this->table_name SET ban=? WHERE chat_id=?");
                                $update->execute(['true', $this->reply_to_message_user_id]);
                                $this->sendMessage($this->reply_to_message_user_id, 'Sei stato blacklistato, non potrai più utilizzare il bot!');
                                $this->getUpdate();
                            } else {
                                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già in blacklist.');
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->getUpdate();
                            }
                        }
                    }
                    if(!is_numeric($test) and $test != null){
                        $clear = substr($test, 1);
                        $terzo->execute([$clear]);
                        $terzo = $terzo->fetch(\PDO::FETCH_ASSOC);
                        $quarto->execute([$clear, 'true']);
                        $quarto = $quarto->fetch(\PDO::FETCH_ASSOC);
                        if(!$terzo){
                            $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> Username non presente nel database.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                        } else {
                            if(!$quarto){
                                $this->sendMessage($this->chat_id, "Ho blacklistato [<code>$clear</code>].");
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $update = $this->mdb->prepare("UPDATE $this->table_name SET ban=? WHERE username=?");
                                $update->execute(['true', $clear]);
                                $this->getUpdate();
                            } else {
                                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già in blacklist.');
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->getUpdate();
                            }
                        }
                    }
                    if(is_numeric($test)){
                        $primo->execute([$test]);
                        $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                        $secondo->execute([$test, 'true']);
                        $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                        if(!$primo){
                            $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                        } else {
                            if(!$secondo){
                                $this->sendMessage($this->chat_id, "Ho blacklistato [<code>$test</code>].");
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->sendMessage($test, 'Sei stato blacklistato, non potrai più utilizzare il bot!');
                                $update = $this->mdb->prepare("UPDATE $this->table_name SET ban=? WHERE chat_id=?");
                                $update->execute(['true', $test]);
                                $this->getUpdate();
                            } else {
                                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente già in blacklist.');
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->getUpdate();
                            }
                        }
                    }
                }
            } else {
                $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi');
                $this->deleteMessage($this->chat_id, $this->message_id);
                $this->getUpdate(); 
            } 
        }
    }

    public function commandUnBlacklist(){
        if(stripos($this->text, '/hbunblacklist')=== 0){
            if($this->user_id == 189384600 or $this->user_id == 136858713 or $this->user_id == 482936946){
                $e = explode(' ', $this->text, 2);
                $test = $e[1];
                $primo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=?");
                $secondo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE chat_id=? AND ban=?");
                $terzo = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=?");
                $quarto = $this->mdb->prepare("SELECT * FROM $this->table_name WHERE username=? AND ban=?");
                if($test == null and $this->reply_to_message == null){
                    $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Il comando funziona solo via reply, ID e username.');
                    $this->deleteMessage($this->chat_id, $this->message_id);
                    $this->getUpdate();
                } else {
                    if($test == null and $this->reply_to_message != null){
                        $primo->execute([$this->reply_to_message_user_id]);
                        $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                        $secondo->execute([$this->reply_to_message_user_id, 'false']);
                        $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                        if(!$primo){
                            $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                        } else {
                            if(!$secondo){
                                $this->sendMessage($this->chat_id, "Ho unblacklistato [<code>$this->reply_to_message_user_id</code>].");
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $update = $this->mdb->prepare("UPDATE $this->table_name SET ban=? WHERE chat_id=?");
                                $update->execute(['false', $this->reply_to_message_user_id]);
                                $this->sendMessage($this->reply_to_message_user_id, 'Sei stato unblacklistato, puoi nuovamente utilizzare il bot!');
                                $this->getUpdate();
                            } else {
                                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente non blacklistato.');
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->getUpdate();
                            }
                        }
                    }
                    if(!is_numeric($test) and $test != null){
                        $clear = substr($test, 1);
                        $terzo->execute([$clear]);
                        $terzo = $terzo->fetch(\PDO::FETCH_ASSOC);
                        $quarto->execute([$clear, falso]);
                        $quarto = $quarto->fetch(\PDO::FETCH_ASSOC);
                        if(!$terzo){
                            $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> Username non presente nel database.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                        } else {
                            if(!$quarto){
                                $this->sendMessage($this->chat_id, "Ho unblacklistato [<code>$clear</code>].");
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $update = $this->mdb->prepare("UPDATE $this->table_name SET ban=? WHERE username=?");
                                $update->execute(['false', $clear]);
                                $this->sendMessage($this->reply_to_message_user_id, 'Sei stato unblacklistato, puoi nuovamente utilizzare il bot!');
                                $this->getUpdate();
                            } else {
                                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente non blacklistato.');
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->getUpdate();
                            }
                        }
                    }
                    if(is_numeric($test)){
                        $primo->execute([$test]);
                        $primo = $primo->fetch(\PDO::FETCH_ASSOC);
                        $secondo->execute([$test, 'false']);
                        $secondo = $secondo->fetch(\PDO::FETCH_ASSOC);
                        if(!$primo){
                            $this->sendMessage($this->chat_id, '<code>Errore 404 (Not Found):</code> ID non presente nel database.');
                            $this->deleteMessage($this->chat_id, $this->message_id);
                            $this->getUpdate();
                        } else {
                            if(!$secondo){
                                $this->sendMessage($this->chat_id, "Ho unblacklistato [<code>$test</code>].");
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->sendMessage($test, 'Sei stato unblacklistato, puoi nuovamente utilizzare il bot!');
                                $update = $this->mdb->prepare("UPDATE $this->table_name SET ban=? WHERE chat_id=?");
                                $update->execute(['false', $test]);
                                $this->getUpdate();
                            } else {
                                $this->sendMessage($this->chat_id, '<code>Errore 400 (Bad Request):</code> Utente non blacklistato.');
                                $this->deleteMessage($this->chat_id, $this->message_id);
                                $this->getUpdate();
                            }
                        }
                    }
                }
            } else {
                $this->sendMessage($this->chat_id, '<code>Errore 403 (Forbidden):</code> Non hai i permessi');
                $this->deleteMessage($this->chat_id, $this->message_id);
                $this->getUpdate(); 
            } 
        }
    }

    public function paysafe() {
        /*  
             Lista buycraft (?)
             Si sceglie cosa prendere (?)
             Invia paysafe al canale

             oppure


            Stessa cosa dei report
        */

    }

}