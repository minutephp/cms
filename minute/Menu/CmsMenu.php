<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 7/8/2016
 * Time: 7:57 PM
 */
namespace Minute\Menu {

    use Minute\Event\ImportEvent;

    class CmsMenu {
        public function adminLinks(ImportEvent $event) {
            $links = [
                'cms' => ['title' => 'CMS', 'icon' => 'fa-sitemap', 'priority' => 4],
                'pages' => ['title' => 'Pages', 'href' => '/admin/pages', 'icon' => 'fa-code', 'priority' => 1, 'parent' => 'cms'],
                'themes' => ['title' => 'Themes', 'href' => '/admin/themes', 'icon' => 'fa-paint-brush', 'priority' => 2, 'parent' => 'cms'],
                'special-pages' => ['title' => 'Special pages', 'href' => '/admin/cms-config', 'icon' => 'fa-star', 'priority' => 99, 'parent' => 'cms'],
                //'link-checker' => ['title' => 'Check links', 'href' => '/admin/check-links', 'icon' => 'fa-check', 'priority' => 60, 'parent' => 'expert']
            ];

            $event->addContent($links);
        }

        public function memberLinks(ImportEvent $event) {
            $links = [
                'member-support' => ['title' => "Help & Support", 'icon' => 'fa-life-buoy', 'priority' => 80],
                'member-help' => ['title' => "Knowledge base", 'icon' => 'fa-book', 'priority' => 2, 'parent' => 'member-support', 'href' => '/help', 'target' => '_blank'],
            ];

            $event->addContent($links);
        }

    }
}