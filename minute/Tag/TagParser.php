<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 9/6/2016
 * Time: 2:57 PM
 */
namespace Minute\Tag {

    class TagParser {
        public function replaceTags($html) {
            $html = preg_replace('~<year></year>~', date('Y'), $html);
            $html = preg_replace('~<site></site>~', 'Hola', $html);

            return $html;
        }
    }
}