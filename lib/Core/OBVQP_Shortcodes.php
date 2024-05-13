<?php
namespace OBVQP_WpPluginAutoload\Core;

class OBVQP_Shortcodes{

    private $wpdb;
    private $loader;
    private $templating;

    public function __construct($loader, $templating)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->templating = $templating;
        $this->loader = $loader;

        add_shortcode('onionbuzz', array($this, 'onionbuzz_func'));

    }

    public function onionbuzz_func( $atts, $content = null)	{
        if ( is_single() || is_page() ) {
            $atts = shortcode_atts(array(
                'message' => '',
                'postid' => 0,
                'quizid' => 0,
                'quiz_id' => 0,
                'quiz-id' => 0,
                'title' => 1,
                'description' => 1,
                'image' => 1,
                'embed' => 1
            ), $atts, 'onionbuzz');

            if(intval($atts['quiz_id']) > 0){
                $atts['quizid'] = $atts['quiz_id'];
            }
            else if(intval($atts['quiz-id']) > 0){
                $atts['quizid'] = $atts['quiz-id'];
            }
            else{
                $atts['quizid'] = intval($atts['quizid']);
            }

            if ($atts['quizid'] > 0 || $atts['postid'] > 0) {

                $data['options'] = $atts;
                $oQuizzes = new OBVQP_Quizzes();
                $oQuestions = new OBVQP_Questions();
                $oAnswers = new OBVQP_Answers();
                $oResults = new OBVQP_Results();

                // get settings
                $oSettings = new OBVQP_Settings();
                $data['settings']['general']        = $oSettings->getByType('general');
                $data['settings']['appearance']     = $oSettings->getByType('appearance');
                $data['settings']['social']         = $oSettings->getByType('social');
                $data['settings']['optin']          = $oSettings->getByType('optin');

                #echo '<pre>';
                #print_r($data['settings']);
                #echo '</pre>';
                //

                if ($atts['quizid'] > 0) {

                    $data['quiz_info'] = $oQuizzes->getById($atts['quizid']);
                    $data['settings']['quiz']           = $oSettings->getByQuizID($data['quiz_info']['id']);

                } else if ($atts['postid'] > 0) {

                    $data['quiz_info'] = $oQuizzes->getByPostId($atts['postid']);
                    $data['settings']['quiz']           = $oSettings->getByQuizID($data['quiz_info']['id']);

                }

                if($data['settings']['quiz']['questions_order'] == "random"){
                    $data['quiz_questions'] = $oQuestions->getAllByQuizID($data['quiz_info']['id'], "RAND()");
                }
                else if($data['settings']['quiz']['questions_order'] == "upvotes"){
                    $data['quiz_questions'] = $oQuestions->getAllByQuizID($data['quiz_info']['id'], "`upvotes` DESC");
                }
                else {
                    $data['quiz_questions'] = $oQuestions->getAllByQuizID($data['quiz_info']['id']);
                }

                if(count($data['quiz_questions']['items']) > 0){
                    foreach ($data['quiz_questions']['items'] as $k => $v) {
                        if($data['settings']['quiz']['answers_order'] == "random"){
                            $data['quiz_questions']['items'][$k]['answers'] = $oAnswers->getAllByQuestionID($data['quiz_questions']['items'][$k]['id'], "RAND()");
                        }
                        else{
                            $data['quiz_questions']['items'][$k]['answers'] = $oAnswers->getAllByQuestionID($data['quiz_questions']['items'][$k]['id']);
                        }

                    }
                }
                #print_r($data['quiz_questions']['items'][$k]['answers']['items']);

                // check if user see result lock or not (db row)
                $ip = $_SERVER['HTTP_CLIENT_IP']?$_SERVER['HTTP_CLIENT_IP']:($_SERVER['HTTP_X_FORWARDE‌​D_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']);
                $quiz_id = $data['quiz_info']['id'];
                $current_user = wp_get_current_user(); //
                $user_id = intval($current_user->ID);
                $check_lock_status = $this->wpdb->get_row( "SELECT count(0) as total FROM `{$this->wpdb->prefix}ob_result_unlocks` WHERE 1 AND quiz_id = '{$quiz_id}' AND user_id = '{$user_id}' AND ip = '{$ip}'", ARRAY_A );
                if($check_lock_status['total'] == 1){
                    $data['settings']['optin']['settings_resultlock'] = 0;
                }

                $dontlock_stories_array = explode(',', $data['settings']['optin']['lock_ignore_quizids']);
                if(in_array($quiz_id,$dontlock_stories_array)){
                    $data['settings']['optin']['settings_resultlock'] = 0;
                }

                // advertising code
                $oAdvertising = new OBVQP_Advertising();

                $settings_codes = array(
                    'locations' => array(
                        0 => 'before_story',
                        1 => 'after_story',
                        2 => 'under_result',
                        3 => 'between_items'
                    ));

                $data['advertising']['between_count'] = $oAdvertising->getSettingByCode('between_count');
                $data['advertising']['no_ads_stories'] = $oAdvertising->getSettingByCode('no_ads_stories');
                $data['advertising']['show_on_mobiles'] = $oAdvertising->getSettingByCode('show_on_mobiles');
                $data['advertising']['show_for_loggedin'] = $oAdvertising->getSettingByCode('show_for_loggedin');

                $oMobileDetect = new OBVQP_Mobile_Detect;//$detect->isMobile();

                $noadv_stories_array = explode(',', $data['advertising']['no_ads_stories']['value']);

                $show_advertising = 0;
                if(!in_array($quiz_id,$noadv_stories_array)){
                    if($data['advertising']['show_on_mobiles']['value'] == 0 && $oMobileDetect->isMobile()){

                    }
                    else{
                        if($data['advertising']['show_for_loggedin']['value'] == 0 && $user_id > 0){

                        }
                        else{
                            foreach ($settings_codes['locations'] as $k=>$v) {
                                $data['locations'][$settings_codes['locations'][$k]]['info'] = $oAdvertising->getSettingByCode($settings_codes['locations'][$k]);
                                if ($data['locations'][$settings_codes['locations'][$k]]['info']['advertising_id'] > 0) {
                                    $data['advertising'][$settings_codes['locations'][$k]]['is'] = 1;
                                    $data['advertising'][$settings_codes['locations'][$k]]['advertising_info'] = $oAdvertising->getById($data['locations'][$settings_codes['locations'][$k]]['info']['advertising_id']);
                                    if ($data['advertising'][$settings_codes['locations'][$k]]['advertising_info']['type'] == 'image') {
                                        if($data['advertising'][$settings_codes['locations'][$k]]['advertising_info']['flag_newwindow'] == 1){
                                            $_blank = 'target="_blank"';
                                        }
                                        else{
                                            $_blank = '';
                                        }
                                        $data['advertising'][$settings_codes['locations'][$k]]['content'] = '<a '.$_blank.' href="' . $data['advertising'][$settings_codes['locations'][$k]]['advertising_info']['link'] . '"><img src="' . $data['advertising'][$settings_codes['locations'][$k]]['advertising_info']['image'] . '"></a>';
                                    }
                                    if ($data['advertising'][$settings_codes['locations'][$k]]['advertising_info']['type'] == 'adsense' || $data['advertising'][$settings_codes['locations'][$k]]['advertising_info']['type'] == 'custom') {
                                        $data['advertising'][$settings_codes['locations'][$k]]['content'] = $data['advertising'][$settings_codes['locations'][$k]]['advertising_info']['code'];
                                    }
                                }
                            }
                        }
                    }
                }

                // count up views
                $quiz_views = $this->wpdb->get_row( "SELECT views_count FROM `{$this->wpdb->prefix}ob_quizzes` WHERE 1 AND id = '{$quiz_id}'", ARRAY_A );
                $new_views = $quiz_views['views_count']+1;
                $this->wpdb->update(
                    $this->wpdb->prefix.'ob_quizzes',
                    array(
                        'views_count' => $new_views
                    ),
                    array(
                        'id' => intval($quiz_id)
                    )
                );

                $data['content'] = $content;
                if($data['quiz_info']['layout'] == 'fulllist') {
                    ob_start();
                    $this->templating->render('frontend/templates/quiz-shortcode', $data);
                    $result = ob_get_contents();
                    ob_end_clean();
                }
                else if($data['quiz_info']['layout'] == 'slider') {
                    ob_start();
                    $this->templating->render('frontend/templates/quiz-shortcode-slider', $data);
                    $result = ob_get_contents();
                    ob_end_clean();
                }

                return $result;
            } else {
                return "Wrong parameters";
            }
        }

    }



}