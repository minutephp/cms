<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 6/22/2016
 * Time: 8:22 AM
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MThemeRelation extends ModelEx {
        protected $table      = 'm_theme_relations';
        protected $primaryKey = 'theme_relation_id';
    }
}