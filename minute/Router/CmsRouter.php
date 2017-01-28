<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 8/23/2016
 * Time: 2:30 AM
 */
namespace Minute\Router {

    use App\Model\MPage;
    use Minute\Cache\QCache;
    use Minute\Cms\CmsConfig;
    use Minute\Config\Config;
    use Minute\Event\RouterEvent;
    use Minute\Model\Permission;
    use Minute\Routing\RouteEx;
    use Minute\Routing\Router;
    use Minute\View\Redirection;

    class CmsRouter {
        /**
         * @var QCache
         */
        private $cache;
        /**
         * @var Router
         */
        private $router;
        /**
         * @var Config
         */
        private $config;

        /**
         * CmsRouter constructor.
         *
         * @param QCache $cache
         * @param Router $router
         * @param Config $config
         */
        public function __construct(QCache $cache, Router $router, Config $config) {
            $this->cache  = $cache;
            $this->router = $router;
            $this->config = $config;
        }

        public function handle(RouterEvent $event) {
            $method = $event->getMethod();
            $path   = '/' . (ltrim($event->getPath(), '/') ?: 'index');

            if (($method === 'GET') && (!$event->getRoute())) {
                $page = $this->cache->get("cms-route-$path", function () use ($path) {
                    /** @var MPage $page */
                    $page = MPage::where('slug', '=', $path)->where('enabled', '=', 'true')->first();

                    return $page ? $page->attributesToArray() : null;
                }, 300);

                if (!empty($page)) {
                    if (!empty($page['redirect'])) {
                        $redirect = new Redirection($page['redirect']);
                        $redirect->redirect();
                    } else {
                        $route = $this->router->get($path, 'CmsPage@render', $page['auth'] ?: false)->setDefault('page_id', $page['page_id']);
                    }
                } elseif ($config = $this->config->get(CmsConfig::CMS_KEY)) {
                    if (($config['blog']['url'] === $path)) {
                        list($controller, $type, $limit) = ['CmsBlog', 'blog', $config['blog']['posts']];
                    } elseif ($config['support']['url'] === $path) {
                        list($controller, $type, $limit) = ['CmsSupport', 'support', $config['support']['posts']];
                    }

                    if (!empty($controller) && !empty($type) && !empty($limit)) {
                        $route = $this->router->get($path, "$controller@render", false, "MPages[type][$limit] as pages", 'MPageContents[pages.page_id] as content ORDER BY RAND()', 'MConfigs[type=key] as configs')
                                              ->setDefault('type', $type)->setDefault('key', 'cms')
                                              ->setReadPermission('pages', Permission::EVERYONE)->setReadPermission('configs', Permission::EVERYONE)
                                              ->addConstraint('content', ['enabled', '=', 'true']);
                    }
                }

                if (!empty($route)) {
                    $event->setRoute($route);
                }
            }
        }
    }
}