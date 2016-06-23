<?php
/**
 * PHP Class to parse variables at template file (.tpl)
 */
class parse_class {

    public  $vars = array();
    public  $template;

    function get_tpl($tpl_name){
        if(empty($tpl_name) || !file_exists($tpl_name)) {
            return false;
        }
        else {
          $this->template = file_get_contents($tpl_name);
        }
    }

    function set_tpl($key, $var) {
        $this->vars[$key] = $var;
    }

    function tpl_parse() {
        foreach($this->vars as $find => $replace) {
            $this->template = str_replace($find, $replace, $this->template);
        }
    }

}

?>