<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/23/2016
 * Time: 2:45 AM
 */
namespace App\Controller {

    use App\Model\MPage;
    use App\Model\MPageContent;
    use Minute\Cache\QCache;
    use Minute\Error\CmsError;
    use Minute\Http\HttpResponseEx;
    use Minute\Model\CollectionEx;
    use Minute\Render\Renderer;

    class CmsPage {
        const CMS_PAGE_COOKIE = "page_contents";
        /**
         * @var Renderer
         */
        private $renderer;
        /**
         * @var QCache
         */
        private $cache;
        /**
         * @var HttpResponseEx
         */
        private $response;

        /**
         * CmsPage constructor.
         *
         * @param QCache $cache
         * @param Renderer $renderer
         * @param HttpResponseEx $response
         */
        public function __construct(QCache $cache, Renderer $renderer, HttpResponseEx $response) {
            $this->cache    = $cache;
            $this->renderer = $renderer;
            $this->response = $response;
        }

        public function render($page_id) {
            $contents = $this->cache->get("cms-page-$page_id", function () use ($page_id) {
                if ($page = MPage::where('page_id', '=', $page_id)->where('enabled', '=', 'true')->first()) {
                    /** @var CollectionEx $contents */
                    $contents = MPageContent::where('page_id', '=', $page_id)->where('enabled', '=', 'true')->get();

                    return count($contents) ? $contents->toArray() : null;
                }

                return null;
            }, 300);

            if (!empty($contents)) {
                $contents = new CollectionEx($contents); //we can't save Collection in cache

                $history = json_decode(substr($_COOKIE[self::CMS_PAGE_COOKIE] ?? '[]', 0, 9999), true);

                if (!is_array($history)) {
                    $history = [];
                }

                if ($content_id = $history[$page_id] ?? 0) {
                    $content = $contents->where('page_content_id', '=', $content_id)->first();
                }

                if (empty($content)) {
                    $content = $contents->random();
                }

                $history[$page_id] = $content['page_content_id'];
                $this->response->setCookie(self::CMS_PAGE_COOKIE, json_encode($history), '+1 year');

                $view = $this->renderer->render($content);

                return $view;
            }

            throw new CmsError("No content found in page (or content is disabled)");
        }
    }
}