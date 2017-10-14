<?php
    session_start();

    // set default language : english
    $_SESSION['langue'] = 'en';

    // set SESSION variable iot remind the choice for new submit
    // so user has just to change langage and keep his search.
    $postValues = array('langue','article','random');
    foreach ($postValues as $value) {
        if(isset($_POST[$value])){
            $_SESSION[$value]=$_POST[$value];
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- online, using CDN -->
        <title>wikiView</title>
<!-- offline -->
        <script src="framework/jquery.min.js"></script>
<!-- online, use CDN -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="wikiview.css" type="text/css">
    </head>
    <body>
    <?php
        // $choice="random";
        $choice=$_SESSION['random'];
        $langue = $_SESSION['langue'];
        // use htmlspecialchars iot protect from malicious code : ALWAYS DO 
        // THAT !
        $article = urlencode(htmlspecialchars($_SESSION['article']));

        // get the user articles
        if ($choice=='random') {
        // get the random articles
            $request = "https://".$langue.".wikipedia.org/w/api.php?action=query&list=random&rnnamespace=0&rnlimit=10&format=xml";
            // echo 'some random articles';
            // echo $request;
        } else if ($choice=='choosen') {
            $request = "https://".$langue.".wikipedia.org/w/api.php?action=opensearch&search=".$article."&namespace=0&format=xml";
            // echo 'some choosen articles';
            // echo $request;
        }
        // fetch and decode content
        $urlContent = file_get_contents($request);
        $xmlContent = simplexml_load_string($urlContent);
    ?>

<div class='page'>
    <div class="titre">
        <h1><img src="./wikipediaLogo.svg">wikiView</h1>
    </div>
        <form method="POST" action="index.php">
        <!-- submit form when change langage : the SESSION value will be set on each change -->
            <select name='langue' onchange='this.form.submit()'>
            <?php
                // set the multilanguage selector
                $lang=array("en"=>"English", "fr"=>"Français", "es"=>"Espagnol", "de"=>"Deutsch", "pt"=>"Portuges", 
                    "it"=>"Italian", "nl"=> "Nederland", "ru"=>"Russe", "ar"=>"Arabian", "zh"=>"Chinese");
                foreach ($lang as $key=>$value) {
                    if ($key==$langue) {
                        echo "<option value='".$key."' selected>".$value."</option>";
                    } else {
                        echo "<option value='".$key."'>".$value."</option>";
                    }
                }
            ?>
            </select>
        </form>
        <form method="POST">
            <input class="radio" type="radio" name="random" value="choosen" checked>
            Article ?
            <input type="text" name="article" placeholder="Please type your research" autofocus="true">
            <input id="submit" class="button" type="submit">
        </form>
        <!-- Trick to have a clickable button for random articles : hide radio button and use the submit button with tuned label, iot send datas through POST method -->
        <form method="POST">
            <input class="radio" type="radio" name="random" value="random" checked>
            <input id="randomButton" class="button" type="submit" value="Random Articles">
        </form>
</div>

        <?php
            if ($choice=='random'){
                foreach ($xmlContent->query->random->page as $page) {
                    // retrieve title and id article
                    $title = $page->attributes()->title;
                    $id = $page->attributes()->id;
                    // display title of random article, and link to full article on click
                    echo "  <div class='card'>
                                <a target='_blank' href='http://".$langue.".wikipedia.org/?curid=".$id."'>
                                    <h2>$title</h2>
                                </a>
                            </div>";
                }
            } else if($choice=='choosen'){
                    // retrieve info from xml, with the source of the picture !! Important fields : Text (for title), Url, Description, Image->attributes() 
                    // use attributes() iot retrieve the image source
                    if ($xmlContent) {
                        foreach ($xmlContent->Section->Item as $item) {
                            // change the picture size with regexp
                            $ImageUrl = $item->Image->attributes();
                            $bigImageUrl = preg_replace("/..px/", "100px", $ImageUrl);
                            // Display content for all results
                            echo "  <div class='card'>
                                    <!-- get picture and content in link iot go to article when click -->
                                        <a target='_blank' href='".$item->Url."'>
                                            <h3 class='title'>".$item->Text."</h3>
                                            <p class='description'>".$item->Description."</p>
                                            <img class='pic' src='".$bigImageUrl."'>
                                        </a>
                                    </div>";
                        }
                    } else {
                        echo 'There is no spoon';
                    }
            }
        ?>

        <div class="todo">
            <h2 class="todo">Todos :</h2>
            <h3 class='text'>1. Display flag of choosen language</h3>
            <h3 class="text">2. Expand resume when hovering, with picture and plan</h3>
            <h3 class="text">3. Expand cards with related article when hover, like file tree</h3>
            <h3 class="text">4. Traditional material design</h3>
            <h3 class="text">5. Flags for different langage</h3>
            <h3 class="text">6. Message if no article found</h3>
            <h3 class="text">7. add wikipedia logo in header</h3>
        </div>
        <div id="footer" class="footer">
            Footer with : logo FELB, link to ecowebhosting, twitter and LinkedIn
        </div>
        <script src="./wikiview.js" type="text/javascript" charset="utf-8"></script>
    </body>
</html>
