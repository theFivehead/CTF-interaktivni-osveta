<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>threat analysis</title>
    <link rel="stylesheet" href="main.css">
    <!-- cmatrix pro js vytvořil jcubic -->
    <script src="https://cdn.jsdelivr.net/npm/cmatrix"></script>
</head>
<body>
    
    <h1>threat analysis</h1>
    <form action="submit.php" method="POST">
        <textarea name="post_links" cols="40" rows="10" <?php session_start();
        if(isset($_GET['status'])){
            if(strcmp($_GET['status'],"f87a12e367556abaa3d9a496a3769e7dde334c762b212fd1e77dc8eece79df9a")==0){
                echo "class='success'";
                $flag="flag{dOnt0v3r5har3}";}
                else if(strcmp($_GET['status'],"false")==0){
                    echo "class='fail'";
                }} ?>><?php if($flag){echo "\n\n".$flag;}
                else if(isset($_SESSION["links"])){
                    $i=1;
                    $n=count($_SESSION["links"]);
                    foreach($_SESSION["links"] as $link){
                        if($i==$n){
                            echo $link;
                        }
                        else{
                            echo $link."\n";
                        }
                        $i++;
                        
                    }} ?></textarea>
        <button type="submit">analyzovat</button>

    </form>
    <canvas id="matrix">
    </canvas>
    <script>
        const failElements = document.getElementsByClassName("fail");
        let characters=['0', '1'];//nastavení znaků pro cmatrix
        setTimeout(function(){
            for(let i=0;i<failElements.length;i++){
                failElements[i].classList.remove("fail");
            }
        },3000);
        const textarea = document.querySelector("textarea");
        if(textarea.classList.contains("success")){
            textarea.setAttribute("readonly", "readonly");
            textarea.setAttribute("rows", "3");
            document.querySelector("button[type='submit']").style.display = "none";
            characters=[':D', ':)',' ','XD','𓀐'];//nastavi smajlíky pro cmatrix
        }

    matrix(document.getElementById("matrix"), {
        chars: characters,
        font_size: 16,
        background:"rgba(10, 15, 26,0.06)",
        font: "Datatype, monospace"
    });
    </script>
</body>
</html>