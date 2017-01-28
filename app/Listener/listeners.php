<?php

/** @var Binding $binding */
use Minute\Component\CmsComponent;
use Minute\Event\AdminEvent;
use Minute\Event\Binding;
use Minute\Event\CmsEvent;
use Minute\Event\MemberEvent;
use Minute\Event\RouterEvent;
use Minute\Event\TodoEvent;
use Minute\Event\UserSignupEvent;
use Minute\Menu\CmsMenu;
use Minute\Router\CmsRouter;
use Minute\Theme\CmsTheme;
use Minute\Todo\CmsTodo;
use Minute\Track\PageTracker;

$binding->addMultiple([
    //cms
    ['event' => AdminEvent::IMPORT_ADMIN_MENU_LINKS, 'handler' => [CmsMenu::class, 'adminLinks']],
    ['event' => MemberEvent::IMPORT_MEMBERS_SIDEBAR_LINKS, 'handler' => [CmsMenu::class, 'memberLinks']],
    ['event' => CmsEvent::IMPORT_CMS_COMPONENT, 'handler' => [CmsComponent::class, 'render']],
    ['event' => CmsEvent::IMPORT_CMS_THEME_CSS, 'handler' => [CmsTheme::class, 'importCss']],

    //router
    ['event' => RouterEvent::ROUTER_GET_FALLBACK_RESOURCE, 'handler' => [CmsRouter::class, 'handle'], 'priority' => 100],

    //tracking
    ['event' => UserSignupEvent::USER_SIGNUP_COMPLETE, 'handler' => [PageTracker::class, 'signup']],

    //tasks
    ['event' => TodoEvent::IMPORT_TODO_ADMIN, 'handler' => [CmsTodo::class, 'getTodoList']],

    //payment related
    ['event' => 'user.wallet.first.payment', 'handler' => [PageTracker::class, 'conversion']],
]);