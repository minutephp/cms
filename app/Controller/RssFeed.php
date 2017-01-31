<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller {

    use App\Model\MPage;
    use Carbon\Carbon;
    use Minute\Cache\QCache;
    use Minute\Config\Config;
    use Minute\Model\CollectionEx;
    use Suin\RSSWriter\Channel;
    use Suin\RSSWriter\Feed;
    use Suin\RSSWriter\Item;

    class RssFeed {
        /**
         * @var QCache
         */
        private $cache;
        /**
         * @var Config
         */
        private $config;

        /**
         * RssFeed constructor.
         *
         * @param QCache $cache
         * @param Config $config
         */
        public function __construct(QCache $cache, Config $config) {
            $this->cache  = $cache;
            $this->config = $config;
        }

        public function index() {
            $items = $this->cache->get('rss-feed-items', function () {
                /** @var CollectionEx $items */
                $items = MPage::where('enabled', '=', 'true')->where('feed', '=', 'true')->limit(5)->orderBy('updated_at', 'desc')->get();

                return count($items) ? $items->toArray() : null;
            }, 86400);

            if (!empty($items)) {
                $feed = new Feed();
                $name = $this->config->getPublicVars('site_name');
                $host = $this->config->getPublicVars('host');
                $top  = $items[0];

                $channel = new Channel();
                $channel
                    ->title("$name feed")
                    ->description("Latest news from $name")
                    ->url($host)
                    ->language('en-US')
                    ->pubDate(Carbon::parse($top->updated_at ?: time())->timestamp)
                    ->lastBuildDate(Carbon::parse($top->updated_at ?: time())->timestamp)
                    ->ttl(86400)
                    ->appendTo($feed);

                foreach ($items as $post) {
                    $item = new Item();
                    $item
                        ->title(ucwords($post['name'] ?? 'title'))
                        ->description($post['name'])
                        //->contentEncoded('<div>Blog body</div>')
                        ->url($host . $post['slug'])
                        ->author($name)
                        ->pubDate(Carbon::parse($top->updated_at)->timestamp)
                        //->preferCdata(true)// By this, title and description become CDATA wrapped HTML.
                        ->appendTo($channel);
                }

                return $feed->render();
            }

            return '';
        }
    }
}