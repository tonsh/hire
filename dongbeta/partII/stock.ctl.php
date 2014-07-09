<?php
class StockController {

    public function on_view() {
        if(isset($_GET['startdate'])) {
            $starttime = strtotime($_GET['startdate']);
        } else {
            $starttime = time() - 86400;
        }

        if(isset($_GET['enddate'])) {
            $endtime = strtotime($_GET['enddate']);
        } else {
            $endtime = time();
        }

        $mod = new StockModel();
        $data = $mod->get_by_patch('AAPL', $starttime, $endtime);
        var_dump($data);
    }
}
?>
