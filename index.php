<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php
            function getContent() {
                $file = "./feed-cache.txt";
                $current_time = time();
                $expire_time = 5 * 120;
                $file_time = filemtime($file);
                if(file_exists($file) && ($current_time - $expire_time < $file_time)) {
                    return file_get_contents($file);
                }
                else {
                    $content = getNewContent();
                    file_put_contents($file, $content);
                    return $content;
                }
            }
            function getNewContent() {
                $html = "";
                $newsSource = [
                    [
                        "title" => "BBC",
                        "url" => "http://feeds.bbci.co.uk/news/world/rss.xml"
                    ],
                    [
                        "title" => "CNN",
                        "url" => "http://rss.cnn.com/rss/cnn_latest.rss"
                    ],
                    [
                        "title" => "Fox News",
                        "url" => "http://feeds.foxnews.com/foxnews/latest"
                    ],
                ];
                function getFeed($url){
                    $html = "";
                    $rss = simplexml_load_file($url);
                    $count = 0;
                    $html .= '<ul>';
                    foreach($rss->channel->item as $item) {
                        $count++;
                        if($count > 5){
                            break;
                        }
                        $html .= '<li><a href="'.htmlspecialchars($item->link).'">'.htmlspecialchars($item->title).'</a></li>';
                    }
                    $html .= '</ul>';
                    return $html;
                }
                foreach($newsSource as $source) {
                    $html .= '<h2>'.$source["title"].'</h2>';
                    $html .= getFeed($source["url"]);
                }
                return $html;
            }
            print getContent();
            ?>
        </div>
    </div>
</div>
</body>