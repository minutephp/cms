<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 6/22/2016
 * Time: 8:22 AM
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MPageContent extends ModelEx {
        protected $table      = 'm_page_contents';
        protected $primaryKey = 'page_content_id';
    }
}