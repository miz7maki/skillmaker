<!--
■課題
*Twitter投稿機能
*Webデザイン
*レスポンシブデザイン

canvas内画像読み込み時のリサイズから開始
 -->
<!DOCTYPE html>
<html lang="jp">
  <head>
    <meta charset="utf-8">
    <title>気まぐれ画竜点睛メイカー</title>
    <link rel="stylesheet" href="./css/index.css">
    <script src="./js/libs/jquery-1.12.4.min.js"></script>
  </head>
  <body>
    <header>
      <h1>気まぐれ画竜点睛メイカー</h1>
    </header>
    <div class="contents">
<?php
//[!"#$%&'()\*\+\-\.,\/:;<=>?\[\\\]^_`{|}~]/
if(empty($_POST['name'])){
  $filename = "./words.dat";
  $line = file($filename);
  $pattern = count($line);
  $str = <<< EOM
  <div id="sampleImgWrapper">
    <img src="./images/image.png" alt="サンプル画竜">
  </div>
  気まぐれで今のあなたにぴったりの必殺技(四字熟語モドキ)を生成します！<br>
  おみくじ感覚でやってみよう！<br>
  全{$pattern}パターン
  <form action="" method="post" id="form">
    <input type="text" name="name" value="" placeholder="名前を記入してください" required><br>
    <input type="submit" value="やってみる">
  </form>
  <a href="https://miz7lab.xsrv.jp">戻る</a>
EOM;
echo $str;
}else{
  $name = htmlspecialchars($_POST['name']);
  $str = <<< EOM
{$name}はスキル"<span id="text"></span>"を使った！<br>
      ※気まぐれであなたにぴったりの必殺技(四字熟語モドキ)を生成します！<br>
      <div id="canvasWrapper">
        <canvas id="canvas"></canvas>
      </div>
      <div id="imgWrapper">
        <img id="image_png">
      </div>
      <form action="./download.php" method="post" name="form">
        <input type="hidden" name="imagedata" value="">
        <input type="hidden" name="filename" value="garyou_image.png">
        <input type="submit" value="画像をダウンロードする" onclick="download()">
      </form>
      <a href="./index.php">戻る</a>
EOM;
//<canvas id="canvas" width="960" height="540"></canvas>
echo $str;
}

?>
    </div>
    <footer>
      トーラムオンラインは株式会社アソビモが運営するオンラインゲームです。<br>
      &copy; 2019 Sawa's Notes
    </footer>
<?php
// データファイル読み込み
$filename = "./words.dat";
$line = file($filename);

// ランダムで四字熟語?を選定
$str = $line[rand( 0, count($line))];

// 文末の改行コード削除
$str = str_replace("\r\n",'',$str);

// 一文字毎に分割して配列に格納する(マルチバイト対応)
$array = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
?>
  <script type="text/javascript">
// phpから配列データを連携してもらう
var array = <?php echo json_encode($array); ?>;
$("#text").text(array[0] + array[1] + array[2] + array[3]);
onload = function(){
  draw();
};

function draw(){
  var canvas = document.getElementById('canvas');
  if(!canvas || !canvas.getContext){
    return false;
  }
  var context = canvas.getContext('2d');
  var img = [
    'image01.png',
    'image02.png',
    'image03.png',
    'image04.png'
  ];
  var images = [];
  // var images = new Image;
  // images.src = './images/image03.png?' + new Date().getTime();
  for(var i = 0; i < img.length; i++){
    images.push(new Image());
    images[i].src = './images/' + img[i] + '?' + new Date().getTime();
  }

  var loadCount = 1;

  for(var i in images){
    images[i].addEventListener('load',function(){
      if(loadCount == images.length){
        for(var j in images){
            context.drawImage(images[j],0,0);
        }

        context.font = "bold 140px 'ＭＳ 明朝'";
        context.lineWidth = 24;
        context.strokeStyle = "#000";
        context.strokeText(array[0],585,200);
        context.strokeText(array[1],585,440);
        context.strokeText(array[2],220,200);
        context.strokeText(array[3],220,440);
        // context.strokeText(array[0],585,200);
        // context.strokeText(array[1],585,440);
        // context.strokeText(array[2],220,200);
        // context.strokeText(array[3],220,440);
        context.fillStyle = "#fff";
        context.fillText(array[0],585,200);
        context.fillText(array[1],585,440);
        context.fillText(array[2],220,200);
        context.fillText(array[3],220,440);
        // context.fillText(array[0],585,200);
        // context.fillText(array[1],585,440);
        // context.fillText(array[2],220,200);
        // context.fillText(array[3],220,440);

        // canvas画像をpng変換して表示する
        try{
          $("#canvas").hide();
          var img_png_src = canvas.toDataURL();
          // img_png_src = img_png_src.replace(/^.*,/,'');
          document.getElementById("image_png").src = img_png_src;
        }catch(e){
          // $("#canvas").show();
          document.getElementById("image_png").alt = "未対応";
        }
      }
      loadCount++;
    },false);
  }
}
function download(){
  var imgData = canvas.toDataURL("image/png");
  imgData = imgData.replace(/^.*,/,'');
  var form = document.form;
  form.imagedata.value = imgData;
  form.submit();
}

(function(){
  $('#canvas').attr('width', 960);
  $('#canvas').attr('height', 540);
})();
    </script>
  </body>
</html>
