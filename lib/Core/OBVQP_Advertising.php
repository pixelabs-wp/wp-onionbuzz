<?php
namespace OBVQP_WpPluginAutoload\Core;

class OBVQP_Advertising{

    private $item_id;
    private $wpdb;
    private $configs;
    public $settings_codes = array(
        'locations' => array(
            0 => 'before_story',
            1 => 'after_story',
            2 => 'under_result',
            3 => 'between_items',
            4 => 'between_count'
        ),
        'settings' => array(
            0 => 'no_ads_stories',
            1 => 'show_on_mobiles',
            2 => 'show_for_loggedin'
        )
    );

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $oConfig = new OBVQP_Config();
        $this->configs = $oConfig->get();

        return 1;
    }

    public function getById($id)
    {
        $results = $this->wpdb->get_row( "SELECT * FROM `{$this->wpdb->prefix}ob_advertisings` WHERE id = {$id}", ARRAY_A );
        $results = stripslashes_deep($results);
        return $results;
    }
    public function total (){
        return $this->wpdb->get_row( "SELECT count(0) as total FROM `{$this->wpdb->prefix}ob_advertisings` WHERE 1", ARRAY_A );
    }
    public function getAll($query = '', $page = 1, $orderby = '', $ordertype = ''){
        if($orderby != ''){
            $order = "ORDER BY {$orderby}";
        }
        if($ordertype != ''){
            $order = $order . " {$ordertype}";
        }
        if($query == 'all'){
            $query = "";
        }
        else if($query != ''){
            $query = "AND {$this->wpdb->prefix}ob_advertisings.title COLLATE UTF8_GENERAL_CI LIKE '%{$query}%'";
        }

        // count items
        $items_count = $this->wpdb->get_row( "SELECT count(0) as total FROM `{$this->wpdb->prefix}ob_advertisings` WHERE 1 {$query}", ARRAY_A );

        // prepares for pagination
        $start = 0;
        $per_page = 10; // may be move to the global settings, but its only for admin panel
        $results['page'] = $page;
        $results['total_items'] = $items_count['total'];
        $results['total_pages'] = ceil($results['total_items'] / $per_page);

        if($results['page'] > 1){
            $start = ($results['page']-1)*($per_page);
        }

        // select items
        $results['items'] = $this->wpdb->get_results( "SELECT *, DATE_FORMAT({$this->wpdb->prefix}ob_advertisings.date_added,'%m/%d/%Y') as date_added FROM `{$this->wpdb->prefix}ob_advertisings` WHERE 1 {$query} {$order} LIMIT {$start}, {$per_page}", ARRAY_A );

        foreach ($results['items'] as $k=>$v){
            $user_info = get_user_by('ID', $results['items'][$k]['user_id']);
            $results['items'][$k]['user_name'] = $user_info->user_login;

            if($results['items'][$k]['type'] == 'adsense'){
                $results['items'][$k]['image'] = MYPLUGIN__PLUGIN_URL_HTTP."static/admin/images/google-adsense.png";
            }
            else if($results['items'][$k]['type'] == 'custom'){
                $results['items'][$k]['image'] = MYPLUGIN__PLUGIN_URL_HTTP."static/admin/images/code.png";
            }
            $locations_count = $this->wpdb->get_row( "SELECT count(0) as total FROM `{$this->wpdb->prefix}ob_adv_settings` WHERE advertising_id = '{$results['items'][$k]['id']}' AND code <> 'no_ads_stories' AND code <> 'show_on_mobiles' AND code <> 'show_for_loggedin' AND code <> 'between_count'", ARRAY_A );
            $results['items'][$k]['locations_count'] = $locations_count['total'];
        }

        $results = stripslashes_deep($results);

        return json_encode($results);
    }
    public function getAllSimple(){
        $results = $this->wpdb->get_results( "SELECT id, title FROM `{$this->wpdb->prefix}ob_advertisings` ORDER BY title ASC", ARRAY_A );
        $return = stripslashes_deep($results);
        return $return;
    }
    public function getLocations(){

        foreach ($this->settings_codes['locations'] as $k=>$v){
            $return['items'][$this->settings_codes['locations'][$k]] = $this->wpdb->get_row( "SELECT advertising_id, `value` FROM `{$this->wpdb->prefix}ob_adv_settings` WHERE code = '{$this->settings_codes['locations'][$k]}' LIMIT 1", ARRAY_A );
        }

        return $return;
    }

    public function getSettings(){

        foreach ($this->settings_codes['settings'] as $k=>$v){
            $return['items'][$this->settings_codes['settings'][$k]] = $this->wpdb->get_row( "SELECT `value` FROM `{$this->wpdb->prefix}ob_adv_settings` WHERE code = '{$this->settings_codes['settings'][$k]}' LIMIT 1", ARRAY_A );
        }

        return $return;
    }
    public function getSettingByCode($code){ // and location by code

        $return = $this->wpdb->get_row( "SELECT advertising_id, `value` FROM `{$this->wpdb->prefix}ob_adv_settings` WHERE code = '{$code}' LIMIT 1", ARRAY_A );

        return $return;
    }
    public function prevFromId($id){
        return $results = $this->wpdb->get_row( "SELECT id FROM `{$this->wpdb->prefix}ob_advertisings` WHERE id < {$id} ORDER BY id DESC LIMIT 1", ARRAY_A );
    }
    public function nextFromId($id){
        return $results = $this->wpdb->get_row( "SELECT id FROM `{$this->wpdb->prefix}ob_advertisings` WHERE id > {$id} ORDER BY id ASC LIMIT 1", ARRAY_A );
    }
    public function saveLocations($data){
        foreach ($this->settings_codes['locations'] as $k=>$v){
            $this->wpdb->update(
                $this->wpdb->prefix.'ob_adv_settings',
                array(
                    'advertising_id' => $data[$this->settings_codes['locations'][$k]],
                    'value' => $data[$this->settings_codes['locations'][$k]]
                ),
                array(
                    'code' => $this->settings_codes['locations'][$k]
                )
            );
        }
        $return['success'] = 1;
        return json_encode($return);
    }
    public function saveSettings($data){
        foreach ($this->settings_codes['settings'] as $k=>$v){
            $this->wpdb->update(
                $this->wpdb->prefix.'ob_adv_settings',
                array(
                    'advertising_id' => $data[$this->settings_codes['settings'][$k]],
                    'value' => $data[$this->settings_codes['settings'][$k]]
                ),
                array(
                    'code' => $this->settings_codes['settings'][$k]
                )
            );
        }
        $return['success'] = 1;
        return json_encode($return);
    }
    public function save($item_id = 0, $data){

        // if attachment selected, get its wp id
        #$attachment_id = intval($data['attachment_id']);

        $current_user = wp_get_current_user();
        #echo 'Username: ' . $current_user->user_login . '<br />';
        #echo 'User email: ' . $current_user->user_email . '<br />';
        #echo 'User first name: ' . $current_user->user_firstname . '<br />';
        #echo 'User last name: ' . $current_user->user_lastname . '<br />';
        #echo 'User display name: ' . $current_user->display_name . '<br />';
        #echo 'User ID: ' . $current_user->ID . '<br />';

        // the UPDATE

        if($data['atype'] == 'adsense'){
            $data['code'] = $data['code_adsense'];
        }

        if($item_id > 0){

            $return['success'] = 0;
            $return['action'] = 'UPDATE advertising id:'.$item_id;

            // update advertising in db
            $this->wpdb->update(
                $this->wpdb->prefix.'ob_advertisings',
                array(
                    'title' => $data['title'],
                    'type' => $data['atype'],
                    'user_id' => $current_user->ID,
                    'code' => $data['code'],
                    'image' => $data['featured_image'],
                    'link' => $data['link'],
                    'flag_newwindow' => $data['flag_newwindow'],
                    'date_updated' => current_time('mysql', 1)
                ),
                array(
                    'id' => intval($data['id'])
                )
            );

            #$this->save_settings(intval($data['id']), $data);

            $return['success'] = 1;
            $return['id'] = $item_id;
        }
        // the INSERT
        else
        {
            $return['success'] = 0;
            $return['action'] = 'INSERT advertising';

            //db add row
            $this->wpdb->insert(
                $this->wpdb->prefix . 'ob_advertisings',
                array(
                    'title' => $data['title'],
                    'type' => $data['atype'],
                    'user_id' => $current_user->ID,
                    'code' => $data['code'],
                    'image' => $data['featured_image'],
                    'link' => $data['link'],
                    'flag_newwindow' => $data['flag_newwindow'],
                    'date_added' => current_time('mysql', 1),
                    'date_updated' => current_time('mysql', 1)
                )
            );
            $item_id = $this->wpdb->insert_id;

            #$this->save_settings(intval($item_id), $data);

            if ($item_id > 0) {
                $return['success'] = 1;
                $return['id'] = $item_id;
            }

        }

        return json_encode($return);

    }
    public function save_settings($item_id = 0, $data){
        $this->wpdb->delete( $this->wpdb->prefix.'ob_settings', array( 'quiz_id' => $item_id ), array( '%d' ) );
        #print_r($data);
        foreach($this->settings_codes['advanced'] as $k=>$v){

            $this->wpdb->insert(
                $this->wpdb->prefix.'ob_settings',
                array(
                    'quiz_id' => $item_id,
                    'type'  => 'advanced',
                    'value' => $data[$this->settings_codes['advanced'][$k]],
                    'code' => $this->settings_codes['advanced'][$k]
                )

            );
        }

        $return['success'] = 1;
        $return['id'] = $item_id;
        return json_encode($return);
    }

    public function delete($item_id){
        $item_id = intval($item_id);
        if($item_id > 0){
            // delete advertising
            $this->wpdb->delete( $this->wpdb->prefix.'ob_advertisings', array( 'id' => $item_id ), array( '%d' ) );

            $return['action'] = 'DELETE';
            $return['success'] = 1;
        }
        else{
            $return['success'] = 0;
        }
        $return['action'] = 'DELETE';
        $return['success'] = 1;
        return json_encode($return);

    }

}