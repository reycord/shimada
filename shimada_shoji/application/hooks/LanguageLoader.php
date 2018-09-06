<?php
class LanguageLoader
{
    public function initialize()
    {
        $ci = &get_instance();
        $ci->load->helper('language');
        $siteLang = $ci->session->userdata('site_lang');
        if ($siteLang) {
            $ci->lang->load(array('message', 'title', 'status', 'validate_message'), $siteLang);
        } else {
            $ci->lang->load(array('message', 'title', 'status', 'validate_message'), 'english');
        }
    }
}
