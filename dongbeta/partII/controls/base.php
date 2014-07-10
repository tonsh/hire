<?php
class BaseController {

    public function render($tpl, array $vars=array()) {
        $vars['proj_url'] = PROJ_URL;
        extract($vars);

        include TPL_DIR . '/' . $tpl;
    }

    public function json($data) {
        echo json_encode($data);
    }
}
