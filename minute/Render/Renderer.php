<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/23/2016
 * Time: 2:12 AM
 */
namespace Minute\Render {

    use App\Model\MPageContent;
    use App\Model\MTheme;
    use App\Model\MThemeTemplate;
    use Minute\Cache\QCache;
    use Minute\Config\Config;
    use Minute\Log\LoggerEx;
    use Minute\Tag\TagParser;
    use Minute\View\Helper;
    use Minute\View\View;

    class Renderer {
        /**
         * @var QCache
         */
        private $cache;
        /**
         * @var LoggerEx
         */
        private $logger;
        /**
         * @var Config
         */
        private $config;
        /**
         * @var TagParser
         */
        private $parser;

        /**
         * CmsPage constructor.
         *
         * @param QCache $cache
         * @param LoggerEx $logger
         * @param Config $config
         * @param TagParser $parser
         */
        public function __construct(QCache $cache, LoggerEx $logger, Config $config, TagParser $parser) {
            $this->cache  = $cache;
            $this->logger = $logger;
            $this->config = $config;
            $this->parser = $parser;
        }

        /**
         * @param MPageContent $page_content
         *
         * @return View
         */
        public function render(array $content) {
            $page_html         = $content['compiled_html'];
            $page_content_id   = $content['page_content_id'];
            $theme_template_id = $content['theme_template_id'];

            $themeHtml = $this->cache->get("cms-theme-$page_content_id", function () use ($theme_template_id) {
                if ($template = MThemeTemplate::where('theme_template_id', '=', $theme_template_id)->first()) {
                    if ($theme = MTheme::where('theme_id', '=', $template->theme_id)->first()) {
                        return $theme->layout_html;
                    }
                }

                return null;
            }, 3600);

            $view  = new View();
            $count = 0;
            $about = [];

            if ($page_data = json_decode($content['data_json'], true)) { //no way around this :/
                $about = $page_data['model']['local']['about'];
            }

            if (!empty($content['custom_html'])) {
                $page_html .= PHP_EOL . $content['custom_html'];
            }

            $meta = sprintf("<title>%s</title>\n<meta name=\"description\" content=\"%s\" />\n", $about['title'] ?? $this->config->getPublicVars('domain'), $about['description'] ?? 'No description');
            $meta .= "<minute-event name=\"import.session.as.js\"></minute-event>\n\n";
            $layout = str_replace('</head>', "$meta</head>", $themeHtml ?? '', $count);

            $content = $this->parser->replaceTags($page_html ?? 'No content');
            $content .= sprintf('%s<scrip' . 't>(function(){ var img = new Image(); img.src = "/page-hit-tracker/%s"; })();</script>', PHP_EOL, $page_content_id);
            $content .= sprintf('<!-- Completed in %s secs -->', microtime(true) - APP_START_TIME);

            if ($count !== 1) {
                $this->logger->warn(sprintf("Unable to find tag </head> tag in template"));
            }

            $view->setContent($content)->setLayout($layout)->with(new Helper('Renderer'));

            return $view;
        }
    }
}