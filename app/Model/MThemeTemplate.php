<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 6/22/2016
 * Time: 8:22 AM
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MThemeTemplate extends ModelEx {
        protected $table      = 'm_theme_templates';
        protected $primaryKey = 'theme_template_id';
    }
}