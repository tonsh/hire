<?php
class BaseController {

    public function render($tpl, array $vars=array()) {
        $var['proj_host'] = PROJ_HOST;
        extract($vars);

        include TPL_DIR . '/' . $tpl;
    }

    public function json($data) {
        echo json_encode($data);
    }
}
?>
