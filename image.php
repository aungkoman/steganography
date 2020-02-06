<?php
        $image = isset($_GET['v']) ? $_GET['v'] : null;
        if($image == null ) die("can't find v");
        $image_url = "http://localhost/image_share/images/".$image.".png";
        echo "<img src='$image_url' />";
        echo "image_url is ".$image_url;
?>

<form action="decrypt.php" method="post">
        <input type="text" name="v" value="<?php echo $image; ?>">
        <input type="password" id="password" name="password" required>
        <input type="submit" value="decrypt">
</form>