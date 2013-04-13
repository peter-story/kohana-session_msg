<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Session Message core class
 *
 * @package     Session Message
 * @author      Peter Story
 * @copyright   (C) 2012 Peter Story
 */
class Message_Core {
    const KEY     = 'session_messages';

    const SUCCESS = 1;

    const NOTICE  = 2;

    const ERROR   = 3;

    protected static $types = array(
        1 => 'success',
        2 => 'notice',
        3 => 'error'
    );

    static function render_all($delete = TRUE, $view = 'session-message/main') {
        $session  = Session::instance();

        $all_msgs = $session->get(Message::KEY);

        if ($delete) {
            $session->delete(Message::KEY);
        }

        return View::factory($view)
            ->set(array('messages' => $all_msgs))
            ->render();
    }

    static function set($msg, $key = NULL, $type = Message::NOTICE) {
        $session  = Session::instance();

        $all_msgs = $session->get(Message::KEY);

        if ($key) {
            $all_msgs[Message::$types[$type]][$key] = $msg;
        } else {
            $all_msgs[Message::$types[$type]][]     = $msg;
        }

        $session->set(Message::KEY, $all_msgs);
    }

    static function get_type_messages($type, $session = NULL) {
        if ( ! is_object($session)) {
            $session = Session::instance();
        }

        $all_msgs    = $session->get(Message::KEY);

        return Arr::get($all_msgs, Message::$types[$type], array());
    }

    static function get($key, $type = Message::NOTICE, $delete = TRUE) {
        $session   = Session::instance();

        $type_msgs = Message::get_type_messages($type, $session);

        $msg       = Arr::get($type_msgs, $key, array());

        Message::clean_session_messages($session, $key, Message::$types[$type], $delete);

        return $msg;
    }

    static function count($type = Message::NOTICE) {
        return count(Message::get_type_messages($type));
    }

    static function count_success() {
        return Message::count(Message::SUCCESS);
    }

    static function count_notice() {
        return Message::count(Message::NOTICE);
    }

    static function count_error() {
        return Message::count(Message::ERROR);
    }

    static function set_success($msg, $key = NULL) {
        Message::set($msg, $key, Message::SUCCESS);
    }

    static function set_notice($msg, $key = NULL) {
        Message::set($msg, $key, Message::NOTICE);
    }

    static function set_error($msg, $key = NULL) {
        Message::set($msg, $key, Message::ERROR);
    }

    static function remove($key, $type = Message::NOTICE) {
        Message::clean_session_messages(Session::instance(), $key, Message::$types[$type]);
    }

    protected static function clean_session_messages($session, $key, $type = Message::NOTICE, $delete_key = TRUE) {
        $all_msgs  = $session->get(Message::KEY);

        $type_msgs = Arr::get($all_msgs, Message::$types[$type], array());

        if ($delete_key && isset($type_msgs[$key])) {
            unset ($type_msgs[$key]);

            $all_msgs[Message::$types[$type]] = $type_msgs;
        }

        if (empty($type_msgs) && isset($all_msgs[Message::$types[$type]])) {
            unset($all_msgs[Message::$types[$type]]);
        }

        if (empty($all_msgs)) {
            $session->delete(Message::KEY);
        }
    }
}