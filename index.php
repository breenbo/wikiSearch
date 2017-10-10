<?php
    // $choice="random";
    $choice=$_GET[random];
    // get the user articles
    if ($choice=='random') {
    // get the random articles
        $request = "https://".$_GET[langue].".wikipedia.org/w/api.php?action=query&list=random&rnnamespace=0&rnlimit=10&format=xml";
        echo 'some random articles';
    } else {
        $request = "https://".$_GET[langue].".wikipedia.org/w/api.php?action=opensearch&search=".urlencode($_GET[article])."&namespace=0&format=xml";
    }
    // fetch and decode content
    $urlContent = file_get_contents($request);
    $xmlContent = simplexml_load_string($urlContent);
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <!-- online, using CDN -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
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
        #button {
            background-color:lightblue;
        }
    </style>
    <body>
        <h1 class="title">wikiView</h1>
        <form method="GET" action="index.php">
            <select name='langue'>

<?php
    // set the multilanguage selector
    $lang=array(en=>English, fr=>Français, es=>Espagnol, de=>Deutsch, pt=>Portuges, 
        it=>Italian, nl=> Nederland, ru=>Russe, ar=>Arabian, zh=>Chinese);
    foreach ($lang as $key=>$value) {
        echo "<option value='".$key."'>".$value."</option>";
    }
?>
            </select>
            <input type="radio" name="random" value="random">Random articles</br>
            <input type="radio" name="random" value="choosen" checked>Choosen articles</br>
            Article ?
            <input type="text" name="article">
            <input type="submit">
        </form>
        <div id="button">Random article</div>
        <div id="result">Results</div>

        <?php
            // Find a way to display random articles
    // set title to display the title of the random articles
    // set id to retrieve the full article when cliked
    if ($choice=='random'){
        // create foreach loop to iterate and display each page
        $title=$xmlContent->query->random->page[1]->attributes()->title;
        $id=$xmlContent->query->random->page[1]->attributes()->id;
        // display title of random article, and link to full article on click
        echo "<a href='http://en.wikipedia.org/?curid=".$id."'><h2>$title</h2></a>";
    } else {
            // retrieve info from xml, with the source of the picture !! Important fields : Text (for title), Url, Description, Image->attributes() 
            // use attributes() iot retrieve the image source
            // Display content for all results
            if ($xmlContent) {
                foreach ($xmlContent->Section->Item as $item) {
                    // change the picture size with regexp
                    $ImageUrl = $item->Image->attributes();
                    $bigImageUrl = preg_replace("/..px/", "100px", $ImageUrl);

                    echo "<div class='card'>
                        <!-- get picture and content in link iot go to article when click -->
                            <a href='".$item->Url."'>
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
            <h3 class="text">1. Random article button - display several random articles</h3>
            <h3 class="text">2. Expand resume when hovering, with picture and plan</h3>
            <h3 class="text">3. Expand cards with related article when hover, like file tree</h3>
            <h3 class="text">5. Traditional material design</h3>
            <h3 class="text">Flags for different langage</h3>
        </div>
        <div id="footer" class="footer">
            Footer with : logo FELB, link to ecowebhosting, twitter and LinkedIn
        </div>
        <script src="wikiView.js" type="text/javascript" charset="utf-8"></script>
    </body>
</html>
