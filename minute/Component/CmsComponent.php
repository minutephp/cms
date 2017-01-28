<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/23/2016
 * Time: 12:13 AM
 */
namespace Minute\Component {

    use App\Model\MTheme;
    use App\Model\MThemeComponent;
    use App\Model\MThemeGlobalDatum;
    use Minute\Cache\QCache;
    use Minute\Event\ViewEvent;
    use Minute\Tag\TagParser;

    class CmsComponent {
        /**
         * @var QCache
         */
        private $cache;
        /**
         * @var TagParser
         */
        private $parser;

        /**
         * CmsComponent constructor.
         *
         * @param QCache $cache
         * @param TagParser $parser
         */
        public function __construct(QCache $cache, TagParser $parser) {
            $this->cache = $cache;
            $this->parser = $parser;
        }

        public function render(ViewEvent $event) {
            $attrs = $event->getAttrs();
            $name  = $attrs['component'];
            $html  = $this->cache->get("cms-component-$name", function () use ($name) {
                if ($component = MThemeComponent::where('name', '=', $name)->first()) {
                    if ($componentHtml = MThemeGlobalDatum::where('theme_component_id', '=', $component->theme_component_id)->first()) {
                        return $componentHtml->compiled_html;
                    }
                }

                return null;
            }, 86400);

            if (!empty($html)) {
                $event->setContent($this->parser->replaceTags($html));
            }
        }
    }
}