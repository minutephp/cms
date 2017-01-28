<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 7/14/2016
 * Time: 11:06 PM
 */
namespace App\Controller\Admin\Pages {

    use Minute\View\Helper;
    use Minute\View\View;

    class ContentsEdit {
        public function index() {
            return (new View())->with(new Helper('AngularJsonForm'))->with(new Helper('JsonEditor'));
        }
    }
}