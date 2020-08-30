<?php
class AcfPhpToJson 
{
    public $page_title = 'ACF - Convert PHP migration fields to JSON';
    public $menu_title = 'Convert PHP to JSON';
    public $menu_slug = 'acf-php-to-json';
    public $post_type = 'acf-field-group';

    private $groups = [];
    
    function __construct()
    {
        $this->groups = $this->get_acf_field_groups();
    }

    /**
     * Render content based on url request
     * 
     * @return string
     */
    public function renderMainPage() 
    { 
        if (!isset($_GET['convert']) || $_GET['convert'] !== 'json') {
            return $this->renderIntroPage();
        }
        
        return $this->renderConvertPage();        
    }

    /**
     * Render Intro page based on field groups
     * 
     * @return string
     */
    private function renderIntroPage() 
    {
        if (empty($this->groups)) {
            return $this->createIntroPageContent('Field Groups Not Found.', 'There\'s no field groups in this theme. Make sure to generate your migration in PHP or place your file in the right place.');
        }
        
        return $this->createIntroPageContent('The following fields has been found.', 'Click on the button below to generate a ACF Json Migration', $this->groups);
    }

    /**
     * Create content for Intro page
     * 
     * @param $title string
     * @param $message string
     * @param $field_groups array
     * 
     * @return string
     */
    private function createIntroPageContent($title, $message, $field_groups = null) 
    {
        $html = new DOMDocument('1.0','iso-8859-1' );
        $html->formatOutput = true;

        $wrap = $html->createElement('div');
        $wrap->setAttribute('class', 'wrap');

        $title = $html->createTextNode($title);
        $header = $html->createElement('h1');
        $header->appendChild($title);

        $paragraph = $html->createElement('p');
        $message = $html->createTextNode($message);
        $paragraph->appendChild($message);

        $wrap->appendChild($header);
        $wrap->appendChild($paragraph);

        if ($field_groups) {
            $list = $html->createElement('ul');
            $list->setAttribute('class', 'list-grid');

            foreach($field_groups as $group) {
                $list_item = $html->createElement('li');
                $group_title = $html->createTextNode($group['title']);

                $list_item->appendChild($group_title);
                $list->appendChild($list_item);
            }

            $wrap->appendChild($list);

            $link = $html->createElement('a');
            $link->setAttribute('href', 'edit.php?post_type=acf-field-group&page=acf-php-to-json&convert=json');
            $link->setAttribute('class', 'button button-primary');

            $link_text = $html->createTextNode('Convert Field Groups to JSON');
            $link->appendChild($link_text);

            $wrap->appendChild($link);
        }
        
        $html->appendChild($wrap);

        return $html->saveHTML();
    }

    private function renderConvertPage() 
    {
        return $this->createConvertPageContent('You converted the following field groups', 'Copy the Json output with your migration', $this->groups);
    }

    /**
     * Create content for Convert page
     * 
     * @param $title string
     * @param $message string
     * @param $field_groups array
     * 
     * @return string
     */
    private function createConvertPageContent($title, $message, $field_groups = null) 
    {
        if ($field_groups) {
            $output = [];
            foreach ($field_groups as $group) {
                $output[] = $this->convertGroupToJson($group);
            }

            $html = new DOMDocument('1.0','iso-8859-1' );
            $html->formatOutput = true;
    
            $wrap = $html->createElement('div');
            $wrap->setAttribute('class', 'wrap');
    
            $title = $html->createTextNode($title);
            $header = $html->createElement('h1');
            $header->appendChild($title);
    
            $paragraph = $html->createElement('p');
            $message = $html->createTextNode($message);
            $paragraph->appendChild($message);
    
            $wrap->appendChild($header);
            $wrap->appendChild($paragraph);

            $pretag = $html->createElement('textarea');
            $pretag->setAttribute('class', 'json-output');
            $pretag->setAttribute('disabled', 'disabled');
            $output_json = $html->createTextNode('[' . implode(',', $output) . ']');
            $pretag->appendChild($output_json);

            $copyButton = $html->createElement('a');
            $copyButton->setAttribute('class', 'button button-primary copy-json');
            $copyButton->setAttribute('href', '#');
            $copyButtonText = $html->createTextNode('Copy JSON');
            $copyButton->appendChild($copyButtonText);

            $wrap->appendChild($pretag);
            $wrap->appendChild($copyButton);

            $html->appendChild($wrap);

            return $html->saveHTML();
        }
    }

    /**
     * Get local field ground already set in a php migration file
     * 
     * @return array 
     */
    private function get_acf_field_groups() 
    {
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
     * @param $group array
     * 
     * @return string
     */
    private function convertGroupToJson($group) 
    {
        $group['fields'] = acf_get_fields($group['key']);
    
        $id = acf_extract_var( $group, 'ID' );
        $group = acf_prepare_field_group_for_export( $group );

        $group['modified'] = get_post_modified_time('U', true, $id, true);

        return acf_json_encode( $group );
    }
}
