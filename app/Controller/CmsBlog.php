<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 9/7/2016
 * Time: 5:44 PM
 */
namespace App\Controller {

    use Minute\View\View;

    class CmsBlog {
        public function render() {
            return (new View('ActiveTheme/CmsBlog'));
        }
    }
}