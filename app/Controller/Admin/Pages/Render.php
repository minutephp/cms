<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Admin\Pages {

    use Minute\Render\Renderer;

    class Render {
        /**
         * @var Renderer
         */
        private $renderer;

        /**
         * Render constructor.
         *
         * @param Renderer $renderer
         */
        public function __construct(Renderer $renderer) {
            $this->renderer = $renderer;
        }

        public function index(array $_models) {
            return $this->renderer->render($_models['contents'][0]);
        }
    }
}