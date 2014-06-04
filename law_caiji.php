<?php
        $con = mysql_connect("localhost","root","123456");
        mysql_select_db('law',$con);
        mysql_query("set names utf8");
         include("Snoopy.class.php");
         $snoopy = new Snoopy;


        function GrabLawFile($url,$filename="") {
                if($url==""):return false;endif;
                if($filename=="") {
                  $filename=date("dMYHis").'.pdf';
                }
                ob_start();
                readfile($url);
                $img = ob_get_contents();
                ob_end_clean();
                $size = strlen($img);
                $fp2=@fopen($filename, "w+");
                fwrite($fp2,$img);
                fclose($fp2);
                return $filename;
        }



        for($i = 1;$i<198;$i++){
            $route = array();
            $title   =array();
            $from =array();
            $summary = array();
        $url = "http://law.soian.com/LawFiles/SearchView/Search?keywords=%E3%80%82&page=$i";
        //echo $url."<br>";
        $data = file_get_contents($url);
        //echo gettype($data) ;
        $preg = "/<a href=\"#\" style=\"width\: 65%; margin-left\: 20px\" class=\"preview-law\" (.*)<span>(.*)<\/span><\/a>/isU";
         preg_match_all($preg,$data,$r);
         foreach ($r[2] as $key => $value) {
                $title[] = strip_tags($value);
         }
         //print_r($title);

         $preg2 = "/<span><font size=\"2\">　(.*)　<\/font><\/span>/isU";
         preg_match_all($preg2,$data,$r2);
         foreach ($r2[1] as $key => $value) {
             $from[] = strip_tags($value);
         }
        // print_r($from);

        $preg3 = "/<div style=\"padding-left: 10px; padding-right: 10px\">(.*)<div st/isU";
        preg_match_all($preg3,$data,$r3); 
        foreach ($r3[1] as $key => $value) {
             $summary[] = preg_replace('/\r|\n/', '', strip_tags($value));
         }
         //print_r($summary);
        
        $preg4 = "/<a href=\"(.*)\"(.*)<\/a>/isU";
        preg_match_all($preg4,$data,$r4); 
        foreach ($r4[1] as $key => $value) {
             if(is_int(strpos($value,'/Base/File/RemoteDocument'))){
                 $route[] = 'http://law.soian.com'.$value;
              }
         }
         //print_r($route);


        for($j = 0;$j<20;$j++){ 
                GrabLawFile($route[$j],$title[$j].'.pdf');
                $time = time();
                $sql = "insert into law_file(title,fromwhere,route,summary,time) 
                value('".$title[$j]."','".$from[$j]."','".$title[$j].'.pdf'."','".$summary[$j]."','".$time."')";
                mysql_query($sql);
               // $tag = mysql_insert_id();
                echo "success";
        }
 
        echo '-'.$i;
}
?>
