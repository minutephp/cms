<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 6/22/2016
 * Time: 8:22 AM
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MThemeGlobalDatum extends ModelEx {
        protected $table      = 'm_theme_global_data';
        protected $primaryKey = 'theme_global_data_id';
    }
}