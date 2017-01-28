<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 7/16/2016
 * Time: 11:26 PM
 */
namespace App\Controller\Admin\Pages {

    use Minute\Model\ModelLoader;
    use Minute\View\Helper;
    use Minute\View\View;

    class Preview {
        /**
         * @var ModelLoader
         */
        private $modelLoader;

        /**
         * PagesPreview constructor.
         *
         * @param ModelLoader $modelLoader
         */
        public function __construct(ModelLoader $modelLoader) {
            $this->modelLoader = $modelLoader;
        }

        public function index($_models) {
            $layout = $_models['contents'][0]->template->theme->layout_html;

            $view = new View('Admin/Pages/Preview', [], false);
            $view->with(new Helper('Markdown'))->with(new Helper('DynamicHtml'))->setLayout($layout)
                 ->setVars(['_models' => $_models])->setFinal(true); //setVars: so model printer doesn't have to load it again

            return $view;
        }
    }
}