<?php
        $con = mysql_connect("127.0.0.1","root","123456");
        mysql_select_db('ceshi',$con);
        mysql_query("set names utf8");
        $sql = 'select CORPNAME,GUID from yh_zicha';
        $data = mysql_query($sql, $con);
        while ($row = mysql_fetch_array($data, MYSQL_ASSOC)) {
            $result[] = $row;
        }
        foreach ($result as $key => $value) {
            $sql2 = "select CORPID,CORPNAME from base_corp where CORPID = '{$value['CORPNAME']}'";
            $re = mysql_fetch_array(mysql_query($sql2, $con), MYSQL_ASSOC);
            if ($re) {
                $sql3 = "UPDATE yh_zicha set `CORPNAME` = '{$re['CORPNAME']}' where `GUID` = '{$value['GUID']}'";
                $d = mysql_query($sql3);
                print_r($d);
            }
        }
?>
