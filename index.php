<?php
    $choice="";
    // get the user articles
    if ($choice=='random') {
    // get the random articles
        $request = "https://en.wikipedia.org/w/api.php?action=query&list=random&rnnamespace=0&rnlimit=10&format=json";
    } else {
        $request = "https://".$_GET[langue].".wikipedia.org/w/api.php?action=opensearch&search=".urlencode($_GET[article])."&namespace=0&format=xml";
        $fmrequest = "https://".$_GET[langue].".wikipedia.org/w/api.php?action=opensearch&search=".urlencode($_GET[article])."&namespace=0&format=xmlfm";
    }
    // fetch and decode content
    $urlContent = file_get_contents($request);
    $xmlContent = simplexml_load_string($urlContent);
    $xmlfmContent = file_get_contents($fmrequest);
    $articleContent = json_decode($urlContent, true);
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <title>wikiView</title>
    </head>
    <style type="text/css">
        body {
            background-color: lightgray;
        }
        a {
            text-decoration: none;
            color:black;
        }
        .card {
            width: 50vw;
            border: solid 1px;
            margin:1vw;
            background-color: white;
        }
    </style>
    <body>
        <form method="GET" action="index.php">
            <select name='langue'>
<?php
    // $lang=array(en=>English, fr=>Français, es=>Espagnol);
    // write code to display following lines with only one array
    // foreach ($lang as $key=>$value) {}
?>
                <option value='en'>English</option>
                <option value='fr'>Français</option>
                <option value='es'>Espagnol</option>
                <option value='de'>Deutsch</option>
                <option value='pt'>Portuges</option>
                <option value='it'>Italian</option>
                <option value='nl'>Nederland</option>
                <option value='ru'>Russe</option>
                <option value='ar'>Arabian</option>
                <option value='zh'>Chinese</option>
            </select>
            <input type="radio" name="random" value="random">Random articles</br>
            <input type="radio" name="random" value="choosen" checked>Choosen articles</br>
            Article ?
            <input type="text" name="article">
            <input type="submit">
        </form>

        <?php
            // Find a way to display random articles
            // Display search results
            // retrieve info from xml, with the source of the picture !! Important fields : Text (for title), Url, Description, Image->attributes() 
            // print_r($xmlContent);
            echo "Description :" .$xmlContent->Section->Item[0]->Description."</br>";
            echo "Url :" .$xmlContent->Section->Item[0]->Url."</br>";
            // use attributes() iot retrieve the image source
            echo "Image :" .$xmlContent->Section->Item[0]->Image->attributes()."</br>";
            // Display content for all results
            if ($xmlContent) {
                foreach ($xmlContent->Section->Item as $item) {
                    echo "<div class='card'>
                            <a href='".$item->Url."'>
                                <h3 class='title'>".$item->Text."</h3>
                                <p class='description'>".$item->Description."</p>
<p>".$item->Image->attributes()."</p>
                                <img class='pic' src='".$item->Image->attributes()."'>

                            </a>
                        </div>";
// change the picture size with regexp
                }
            } else {
                echo 'There is no spoon';
            }
        ?>

        <h1 class="title">wikiView</h1>
        <div class="todo">
            <h2 class="todo">Todos :</h2>
            <h3 class="text">1. Random article button - display several random articles</h3>
            <h3 class="text">2. Expand resume when hovering, with picture and plan</h3>
            <h3 class="text">3. Expand cards with related article when hover, like file tree</h3>
            <h3 class="text">5. Traditional material design</h3>
        </div>
        <div id="footer" class="footer">
            Footer with : logo FELB, link to ecowebhosting, twitter and LinkedIn
        </div>
    </body>
</html>
