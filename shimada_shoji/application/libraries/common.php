<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common{

    function generateID($id)
    {
        preg_match_all("/[^0-9,.]/", $id, $pre); 
        preg_match_all('!\d+!', $id, $pos);
        $post = implode('',$pos[0]);
        $num =  strlen($post);
        $result = implode('',$pre[0]);
        $post = (int)$post + 1;
        $increa =  str_pad($post, $num, '0', STR_PAD_LEFT);     
        return ($result.$increa);       
    }
    /**
     * Get message
     * 
     * @author Duc Tam
     * @param screenId
     * @param messageId
     * @return message
     */
    public function getMessage($screenId, $messageId){
        return $this->lang->line($screenId + "_" + $messageId);
    }
}