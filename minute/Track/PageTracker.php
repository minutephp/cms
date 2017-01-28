<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 10/9/2016
 * Time: 2:07 PM
 */
namespace Minute\Track {

    use App\Model\MPageStat;
    use Minute\Cache\QCache;
    use Minute\Http\HttpRequestEx;
    use Minute\Http\HttpResponseEx;

    class PageTracker {
        const QUEUE_LENGTH = 100;
        /**
         * @var QCache
         */
        private $cache;
        /**
         * @var HttpResponseEx
         */
        private $response;
        /**
         * @var HttpRequestEx
         */
        private $request;

        /**
         * TrackHits constructor.
         *
         * @param QCache $cache
         * @param HttpRequestEx $request
         * @param HttpResponseEx $response
         */
        public function __construct(QCache $cache, HttpRequestEx $request, HttpResponseEx $response) {
            $this->cache    = $cache;
            $this->request  = $request;
            $this->response = $response;
        }

        public function hit($page_content_id) {
            if (!empty($page_content_id)) {
                $memcached = $this->cache->getType() === 'memcached';
                $queue     = $memcached ? $this->cache->get('hit_counter', function () { return []; }) : [];
                $visits    = $this->request->getCookie('visited', '[]');
                $visited   = json_decode($visits, true) ?: [];
                $unique    = !in_array($page_content_id, $visited);

                array_push($queue, ['page_content_id' => $page_content_id, 'raw_hits' => 1, 'unique_hits' => $unique ? 1 : 0]);

                if (!$memcached || (count($queue) > self::QUEUE_LENGTH)) { #parse in batches of 100 records
                    $pages = [];

                    foreach ($queue as $row) {
                        if (!isset($pages[$row['page_content_id']])) {
                            $pages[$row['page_content_id']] = ['raw_hits' => 0, 'unique_hits' => 0];
                        }

                        $pages[$row['page_content_id']]['raw_hits'] += $row['raw_hits'];
                        $pages[$row['page_content_id']]['unique_hits'] += $row['unique_hits'];
                    }

                    foreach ($pages as $page_content_id => $data) {
                        /** @var MPageStat $record */
                        MPageStat::unguard(true);
                        $record = MPageStat::firstOrNew(['page_content_id' => $page_content_id]);
                        $record->raw_hits += $data['raw_hits'];
                        $record->unique_hits += $data['unique_hits'];
                        $record->save();
                    }

                    $queue = [];
                }

                if ($memcached) {
                    $this->cache->set('hit_counter', $queue);
                }

                if ($unique) {
                    array_push($visited, $page_content_id);
                    $this->response->setCookie('visited', json_encode($visited), '+1 year');
                }
            }
        }

        public function signup() {
            $this->track('signups');
        }

        public function conversion() {
            $this->track('conversions');
        }

        protected function track(string $type) {
            if ($visited = $this->request->getCookie('visited')) {
                if ($visits = json_decode($visited, true)) {
                    if (is_array($visits)) {
                        foreach ($visits as $page_content_id) {
                            /** @var MPageStat $record */
                            MPageStat::unguard(true);
                            $record = MPageStat::firstOrNew(['page_content_id' => $page_content_id]);
                            $record->$type += 1;
                            $record->save();
                        }
                    }
                }
            }
        }
    }
}