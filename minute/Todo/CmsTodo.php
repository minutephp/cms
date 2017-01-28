<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 11/5/2016
 * Time: 11:04 AM
 */
namespace Minute\Todo {

    use App\Model\MPage;
    use App\Model\MPageContent;
    use Minute\Config\Config;
    use Minute\Event\ImportEvent;

    class CmsTodo {
        /**
         * @var TodoMaker
         */
        private $todoMaker;
        /**
         * @var Config
         */
        private $config;

        /**
         * MailerTodo constructor.
         *
         * @param TodoMaker $todoMaker - This class is only called by TodoEvent (so we assume TodoMaker is be available)
         * @param Config $config
         */
        public function __construct(TodoMaker $todoMaker, Config $config) {
            $this->todoMaker = $todoMaker;
            $this->config    = $config;
        }

        public function getTodoList(ImportEvent $event) {
            $slugs = ['index', 'pricing', 'features', 'faq', 'contact', 'terms', 'press'];
            $todos = [];

            foreach ($slugs as $slug) {
                $enabled = false;

                if ($page = MPage::where('slug', '=', "/$slug")->where('enabled', '=', 'true')->first()) {
                    if ($content = MPageContent::where('page_id', '=', $page->page_id)->where('enabled', '=', 'true')->count()) {
                        $enabled = 'y';
                    }
                }

                $todos[] = ['name' => "/$slug page", 'description' => $enabled ? "" : "Create a $slug page", 'status' => $enabled ? 'complete' : 'incomplete', 'link' => '/admin/pages'];
            }

            $todos[] = ['name' => 'Setup blog', 'description' => 'Setup your website blog', 'status' => $this->config->get('cms/blog/url') ? 'complete' : 'incomplete', 'link' => '/admin/cms-config'];
            $todos[] = ['name' => 'Setup help pages', 'description' => 'Create a site knowledge base', 'status' => $this->config->get('cms/support/url') ? 'complete' : 'incomplete',
                        'link' => '/admin/cms-config'];

            $event->addContent(['CMS' => $todos]);
        }
    }
}