<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CmsInitialMigration extends AbstractMigration
{
    public function change()
    {
        // Automatically created phinx migration commands for tables from database minute

        // Migration for table m_page_contents
        $table = $this->table('m_page_contents', array('id' => 'page_content_id'));
        $table
            ->addColumn('page_id', 'integer', array('limit' => 11))
            ->addColumn('created_at', 'datetime', array('null' => true))
            ->addColumn('updated_at', 'datetime', array('null' => true))
            ->addColumn('theme_template_id', 'integer', array('null' => true, 'limit' => 11))
            ->addColumn('name', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('data_json', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('custom_html', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('compiled_html', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('enabled', 'enum', array('null' => true, 'default' => 'true', 'values' => array('true','false')))
            ->addIndex(array('page_id'), array())
            ->create();


        // Migration for table m_page_proofs
        $table = $this->table('m_page_proofs', array('id' => 'page_proof_id'));
        $table
            ->addColumn('page_content_id', 'integer', array('limit' => 11))
            ->addColumn('created_at', 'datetime', array())
            ->addColumn('updated_at', 'datetime', array())
            ->addColumn('name', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('hash', 'string', array('null' => true, 'limit' => 16))
            ->addColumn('original_json', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('proof_json', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('status', 'enum', array('default' => 'pending', 'values' => array('pending','proofed','merged')))
            ->create();


        // Migration for table m_page_stats
        $table = $this->table('m_page_stats', array('id' => 'page_stat_id'));
        $table
            ->addColumn('page_content_id', 'integer', array('limit' => 11))
            ->addColumn('raw_hits', 'integer', array('default' => '0', 'limit' => 11))
            ->addColumn('unique_hits', 'integer', array('default' => '0', 'limit' => 11))
            ->addColumn('signups', 'integer', array('default' => '0', 'limit' => 11))
            ->addColumn('conversions', 'integer', array('default' => '0', 'limit' => 11))
            ->addIndex(array('page_content_id'), array('unique' => true))
            ->create();


        // Migration for table m_pages
        $table = $this->table('m_pages', array('id' => 'page_id'));
        $table
            ->addColumn('slug', 'string', array('limit' => 255))
            ->addColumn('name', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('created_at', 'datetime', array())
            ->addColumn('updated_at', 'datetime', array())
            ->addColumn('category', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('keywords', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('auth', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('type', 'enum', array('values' => array('page','support','blog')))
            ->addColumn('redirect', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('feed', 'enum', array('null' => true, 'default' => 'true', 'values' => array('true','false')))
            ->addColumn('enabled', 'enum', array('null' => true, 'default' => 'true', 'values' => array('true','false')))
            ->addIndex(array('slug'), array('unique' => true))
            ->addIndex(array('category'), array())
            ->create();

        // Migration for table m_theme_assets
        $table = $this->table('m_theme_assets', array('id' => 'theme_asset_id'));
        $table
            ->addColumn('theme_id', 'integer', array('limit' => 11))
            ->addColumn('name', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('type', 'string', array('null' => true, 'limit' => 20))
            ->addColumn('url', 'string', array('null' => true, 'limit' => 255))
            ->addIndex(array('theme_id', 'name'), array('unique' => true))
            ->create();


        // Migration for table m_theme_components
        $table = $this->table('m_theme_components', array('id' => 'theme_component_id'));
        $table
            ->addColumn('theme_id', 'integer', array('limit' => 11))
            ->addColumn('name', 'string', array('limit' => 255))
            ->addColumn('description', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('form_data_json', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('component_html', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('sample_data_json', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addIndex(array('theme_id', 'name'), array('unique' => true))
            ->create();


        // Migration for table m_theme_global_data
        $table = $this->table('m_theme_global_data', array('id' => 'theme_global_data_id'));
        $table
            ->addColumn('theme_component_id', 'integer', array('limit' => 11))
            ->addColumn('global_data_json', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addColumn('compiled_html', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->create();


        // Migration for table m_theme_relations
        $table = $this->table('m_theme_relations', array('id' => 'theme_relation_id'));
        $table
            ->addColumn('relation_name', 'string', array('limit' => 55))
            ->addColumn('theme_template_id', 'integer', array('null' => true, 'limit' => 11))
            ->addColumn('theme_component_id', 'integer', array('null' => true, 'limit' => 11))
            ->addColumn('priority', 'integer', array('null' => true, 'limit' => 11))
            ->addIndex(array('relation_name', 'theme_template_id'), array('unique' => true))
            ->create();


        // Migration for table m_theme_templates
        $table = $this->table('m_theme_templates', array('id' => 'theme_template_id'));
        $table
            ->addColumn('theme_id', 'integer', array('limit' => 11))
            ->addColumn('name', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('description', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('screenshot', 'string', array('null' => true, 'limit' => 255))
            ->addColumn('html_code', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addIndex(array('theme_id', 'name'), array('unique' => true))
            ->addIndex(array('theme_id'), array())
            ->create();


        // Migration for table m_themes
        $table = $this->table('m_themes', array('id' => 'theme_id'));
        $table
            ->addColumn('name', 'string', array('limit' => 255))
            ->addColumn('layout_html', 'text', array('null' => true, 'limit' => MysqlAdapter::TEXT_LONG))
            ->addIndex(array('name'), array('unique' => true))
            ->create();


    }
}