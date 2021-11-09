<?php
session_start();
    $rows = fopen("Ielts.csv",'r');
    $rows = fgetcsv($rows);
    $len = count($rows);
    
if(!$_SESSION['word']){
    //get the word
    $r = rand(0, $len);
    $word = $rows[$r];
    $_SESSION['word'] = $word;
}else{
    $word = $_SESSION['word'];
}

//get the audio
$txt=rawurlencode($word);
$audio=file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q='.$txt.'&tl=en-UK');
$speech='<audio src="data:audio/mpeg;base64,'.base64_encode($audio).'" autoplay controls></audio>';

if($_POST){
    if(isset($_POST['word']) && $_POST['word']!=''){
        if(strtolower($_POST['word'])==strtolower($word)){
            $_SESSION['success'] = 'Correct! <strong>'.$word.'</strong>';
                //get the word
            $r = rand(0, $len);
            $word = $rows[$r];
            $_SESSION['word'] = $word;
            
            //get the audio
            $txt=rawurlencode($word);
            $audio=file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q='.$txt.'&tl=en-UK');
            $speech='<audio src="data:audio/mpeg;base64,'.base64_encode($audio).'" autoplay controls></audio>';
        }else{
            $_SESSION['error'] = 'Incorrect! Try Again';
        }
    }elseif($_POST['reset']){
        $_SESSION['error'] = 'it was <strong>'.$word.'</strong>';
    }else{
        $message = 'Please listen and type';
    }
}

?>
<!DOCTYPE HTML>
<html>
    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <style>
            .insert{color:blue;}
            .sent{color:green;}
            .reminded{color:purple;}
            .cancel{color:red;}
        </style>

    </head>
    <body class="body">
        <div class="container">
            <div class="row m-2 p-2">
                <div class="col-3"></div>
                <div class="col-4">
                    <h1>IELTS Spell Practice (1000 Common Listening Words)</h1>
                    <?php if(isset($message)) echo($message); ?> 
                    <?php if($_SESSION['success']){ ?>
                    <div class="alert alert-success alert-dismissible" role="alert">
                      <?php echo($_SESSION['success']); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                <?php } ?>
                <?php if($_SESSION['error']){ ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                      <?php echo($_SESSION['error']); ?>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                <?php } ?>
                </div>
                <div class="col-3"></div>
            </div>
            <div class="row m-2 p-2">
                <?php echo($speech); ?>
                <form class="form mb-2" method="post" spellcheck="false"  autocomplete="off">
                    <div class="input-group">
                        <label for="title">Write it down</label>
                      <input name="word" type="text" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <input class="btn btn-secondary" type="submit" name="reset" value="Skip">
                </form>
            </div>
        </div>
        

        <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
    
</html>
<?php 
$_SESSION['error'] = false;
$_SESSION['success'] = false;
?>
