<?php
if(isset($_POST['post_links'])){
    $links=array_filter(array_map('trim', explode("\n", $_POST['post_links'])));
    if (!file_exists("correct_links.txt")) {
    die("Soubor nebyl nalezen.");
    }
    $correctLinks=file("correct_links.txt",FILE_IGNORE_NEW_LINES);
    $spravne=true;
    $tolerance=0;
    
    if(count($links)==count($correctLinks)){
        for($i=0,$n=count($links);$i<$n;$i++){
            $sameLink=false;
            for($j=0,$k=count($links);$j<$k;$j++){
            if(strcasecmp($links[$i],$correctLinks[$j])==0){
                $sameLink=true;
                break;
            }        
        }
            if(!$sameLink){
                if(--$tolerance<0){
                    $spravne=false;
                    break;
                }
            }
        }
    }
    else{
      $spravne=false;
    }
    if($spravne){
        header('Location: index.php?status=f87a12e367556abaa3d9a496a3769e7dde334c762b212fd1e77dc8eece79df9a');
    }
    else{
        header('Location: index.php?status=false');
    }
}
else{
    header('Location: index.php');
}
?>