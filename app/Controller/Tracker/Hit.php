<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Tracker {

    use Minute\Event\Dispatcher;
    use Minute\Event\UserSignupEvent;
    use Minute\Track\PageTracker;

    class Hit {
        /**
         * @var PageTracker
         */
        private $tracker;
        /**
         * @var Dispatcher
         */
        private $dispatcher;

        /**
         * Hit constructor.
         *
         * @param PageTracker $tracker
         * @param Dispatcher $dispatcher
         */
        public function __construct(PageTracker $tracker, Dispatcher $dispatcher) {
            $this->tracker    = $tracker;
            $this->dispatcher = $dispatcher;
        }

        public function index(int $page_content_id) {
            $this->tracker->hit($page_content_id);

            header('Content-Type: image/gif');
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            return base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');
        }
    }
}