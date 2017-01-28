<?php

/** @var Router $router */
use Minute\Model\Permission;
use Minute\Routing\Router;

$router->get('/admin/pages', null, 'admin', 'm_pages[5] as pages', 'm_page_contents[pages.page_id][1] as contents')
       ->setReadPermission('pages', 'admin')->setDefault('pages', '*');
$router->post('/admin/pages', null, 'admin', 'm_pages as pages', 'm_page_contents as contents')
       ->setAllPermissions('pages', 'admin')->setAllPermissions('contents', 'admin');

$router->get('/admin/pages/edit/{page_id}', null, 'admin', 'm_pages[page_id][1] as pages', 'm_page_contents[pages.page_id][9] as contents', 'm_page_stats[contents.page_content_id] as stats',
    'm_themes[5] as themes', 'm_theme_templates[themes.theme_id][5] as templates')->setReadPermission('pages', 'admin')->setReadPermission('themes', 'admin')
       ->setDefault('page_id', 0)->setDefault('themes', '*')->addConstraint('themes', ['name', '<>', 'global']);
$router->post('/admin/pages/edit/{page_id}', null, 'admin', 'm_pages as pages', 'm_page_contents as contents')
       ->setAllPermissions('pages', 'admin')->setAllPermissions('contents', 'admin')->setDefault('page_id', 0);

$router->get('/admin/pages/contents/edit/{page_content_id}', 'Admin/Pages/ContentsEdit', 'admin', 'm_page_contents[page_content_id][1] as contents', 'm_pages[contents.page_id] as page',
    'm_theme_templates[contents.theme_template_id] as template', 'm_theme_relations[contents.theme_template_id][99] as relations order by priority',
    'm_theme_components[relations.theme_component_id] as component', 'm_theme_global_data[component.theme_component_id] as global_data')
       ->setReadPermission('contents', 'admin')->setDefault('page_content_id', '0');
$router->post('/admin/pages/contents/edit/{page_content_id}', null, 'admin', 'm_page_contents as contents', 'm_theme_components as component', 'm_theme_global_data as global_data')
       ->setAllPermissions('contents', 'admin')->setAllPermissions('component', 'admin')->setAllPermissions('global_data', 'admin');

$router->get('/admin/pages/preview/{page_content_id}', 'Admin/Pages/Preview', 'admin', 'm_page_contents[page_content_id][1] as contents', 'm_theme_templates[contents.theme_template_id] as template',
    'm_themes[template.theme_id] as theme')->setReadPermission('contents', 'admin');

$router->get('/admin/pages/render/{page_content_id}', 'Admin/Pages/Render', 'admin', 'm_page_contents[page_content_id][1] as contents')
       ->setReadPermission('contents', 'admin');

$router->get('/admin/themes', null, 'admin', 'm_themes[5] as themes', 'm_theme_templates[themes.theme_id][1] as templates', 'm_theme_relations[templates.theme_template_id][1] as relations')
       ->setReadPermission('themes', 'admin')->setDefault('themes', '*');
$router->post('/admin/themes', null, 'admin', 'm_themes as themes', 'm_theme_templates as templates', 'm_theme_relations as relations')
       ->setAllPermissions('themes', 'admin')->setAllPermissions('templates', 'admin')->setAllPermissions('relations', 'admin')
       ->setDeleteCascade('themes', ['templates', 'm_theme_assets', 'm_theme_components'])->setDeleteCascade('templates', 'relations');

$router->get('/admin/themes/edit/{theme_id}', null, 'admin', 'm_themes[theme_id] as themes', 'm_theme_assets[themes.theme_id][99] as assets')
       ->setReadPermission('themes', 'admin')->setDefault('theme_id', '0');
$router->post('/admin/themes/edit/{theme_id}', null, 'admin', 'm_themes as themes', 'm_theme_assets as assets')
       ->setAllPermissions('themes', 'admin')->setAllPermissions('assets', 'admin')->setDefault('theme_id', '0');

$router->get('/admin/themes/components/edit/{theme_id}/{theme_component_id}', 'Admin/Themes/ComponentsEdit', 'admin', 'm_theme_components[theme_component_id][1] as components')
       ->setReadPermission('components', 'admin')->setDefault('theme_component_id', 0);
$router->post('/admin/themes/components/edit/{theme_id}/{theme_component_id}', null, 'admin', 'm_theme_components as components')
       ->setAllPermissions('components', 'admin')->setDefault('theme_component_id', 0);

$router->get('/admin/themes/components/list/{theme_id}', null, 'admin', 'm_themes[theme_id][1] as themes', 'm_theme_components[themes.theme_id][5] as components')
       ->setReadPermission('themes', 'admin')->setDefault('theme_id', 0);
$router->post('/admin/themes/components/list/{theme_id}', null, 'admin', 'm_theme_components as components')
       ->setAllPermissions('components', 'admin');

$router->get('/admin/themes/templates/list/{theme_id}', null, 'admin', 'm_themes[theme_id][1] as themes', 'm_theme_templates[themes.theme_id][5] as templates',
    'm_theme_relations[templates.theme_template_id][1] as relations')
       ->setReadPermission('themes', 'admin')->setDefault('theme_id', 0);
$router->post('/admin/themes/templates/list/{theme_id}', null, 'admin', 'm_theme_templates as templates', 'm_theme_relations as relations')
       ->setAllPermissions('templates', 'admin')->setAllPermissions('relations', 'admin');

$router->get('/admin/themes/templates/edit/{theme_id}/{theme_template_id}', null, 'admin', 'm_theme_templates[theme_template_id][1] as templates',
    'm_theme_relations[templates.theme_template_id][99] as relations', 'm_theme_components[5] as components')->setReadPermission('templates', 'admin')
       ->setReadPermission('components', 'admin')->setDefault('components', '*')->setDefault('theme_template_id', 0);
$router->post('/admin/themes/templates/edit/{theme_id}/{theme_template_id}', null, 'admin', 'm_theme_templates  as templates', 'm_theme_relations as relations')
       ->setDefault('theme_template_id', 0)->setAllPermissions('templates', 'admin')->setAllPermissions('relations', 'admin');

$router->get('/admin/pages/proofs/{page_content_id}', null, 'admin', 'm_page_contents[page_content_id][1] as contents', 'm_pages[contents.page_id] as page', 'm_page_proofs[contents.page_content_id][5] as proofs')
       ->setReadPermission('contents', 'admin');
$router->post('/admin/pages/proofs/{page_content_id}', null, 'admin', 'm_page_contents as contents', 'm_page_proofs as proofs')
       ->setAllPermissions('contents', 'admin')->setAllPermissions('proofs', 'admin');

$router->get('/_proofread/{hash}', null, false, 'm_page_proofs[hash] as proofs')
       ->setReadPermission('proofs', Permission::EVERYONE);
$router->post('/_proofread/{hash}', null, false, 'm_page_proofs as proofs')
       ->setAllPermissions('proofs', Permission::EVERYONE);

$router->get('/static/themes/{theme_name}/assets/{asset_name}', 'Theme/LoadAsset', false)
       ->setDefault('_noView', true);

$router->get('/admin/cms-config', null, 'admin', 'm_configs[type] as configs')
       ->setReadPermission('configs', 'admin')->setDefault('type', 'cms');
$router->post('/admin/cms-config', null, 'admin', 'm_configs as configs')
       ->setAllPermissions('configs', 'admin');

$router->get('/rss-feed', 'RssFeed', false)->setDefault('_noView', true);
$router->get('/page-hit-tracker/{page_content_id}', 'Tracker/Hit', false)->setDefault('_noView', true);

