<?php
class AcfPhpToJson {
    public $page_title = 'ACF - Convert PHP migration fields to JSON';
    public $menu_title = 'Convert PHP to JSON';
    public $menu_slug = 'acf-php-to-json';
    public $post_type = 'acf-field-group';

    function __construct(){}

    public function renderMainPage(){ 

        if (!isset($_GET['convert']) || $_GET['convert'] !== 'json') {
            return $this->renderIntroPage();
        }
        
        return $this->renderConvertPage();        
    }

    private function renderIntroPage() {
        $groups = $this->get_acf_field_goups();
        if (empty($groups)) {
            return '<p>There\'s no field groups in this theme. Make sure to generate your migration in PHP or place your file in the right place.</p>';
        }

        return '<a href="edit.php?post_type=acf-field-group&page=acf-php-to-json&convert=json" class="button button-primary">Convert Field Groups to JSON</a>';
    }

    private function renderConvertPage() {
        $groups = $this->get_acf_field_goups();
        $output = [];
        foreach ($groups as $group) {
            $output[] = $this->convertGroupToJson($group);
        }

        return  '[' . implode(',', $output) . ']';
    }

    /**
     * Get local field ground already set in a php migration file
     * 
     * @return array 
     */
    private function get_acf_field_goups() {
        $field_groups = acf_get_local_field_groups();
        if (empty($field_groups)) {
            return [];
        }

        return array_filter($field_groups, function($group) {
            return $group['local'] == 'php';
        });
    }

    /**
     * Get group fields and convert to JSON
     * 
     * @return string
     */
    private function convertGroupToJson($group) {
        $group['fields'] = acf_get_fields($group['key']);
    
        $id = acf_extract_var( $group, 'ID' );
        $group = acf_prepare_field_group_for_export( $group );

        $group['modified'] = get_post_modified_time('U', true, $id, true);

        return acf_json_encode( $group );
    }
}