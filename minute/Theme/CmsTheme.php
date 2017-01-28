<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 9/7/2016
 * Time: 2:54 PM
 */
namespace Minute\Theme {

    use App\Model\MTheme;
    use Minute\Cms\CmsConfig;
    use Minute\Config\Config;
    use Minute\Event\ViewEvent;

    class CmsTheme {
        /**
         * @var Config
         */
        private $config;

        /**
         * CmsTheme constructor.
         *
         * @param Config $config
         */
        public function __construct(Config $config) {
            $this->config = $config;
        }

        public function importCss(ViewEvent $event) {
            $css     = $this->config->get(CmsConfig::CMS_KEY . '/themes/active/css', ['/static/themes/global/assets/global.css']);
            $content = '';

            foreach ($css as $href) {
                $content .= sprintf('<link rel="stylesheet" href="%s" />', $href);
            }

            $event->setContent($content);
        }
    }
}