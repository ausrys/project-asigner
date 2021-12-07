<?php 
class Utility {
    // Check if group is already full or not
    public static function isGroupFull($arr, $grp_id, $max_num) {
        $i = 0;
        foreach($arr as $group) {
            if($group['group_id'] == $grp_id) {
                $i++;
            }
        }
        if ($i>= $max_num) {
            return true;
        }
        else {
            return false;
        }
    }


}