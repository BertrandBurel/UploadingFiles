<?php

if(!empty($_FILES['imageUploader']['name'][0])) {
    $files = $_FILES['imageUploader'];
    $uploaded = [];
    $failed = [];
    $allowed =['jpg', 'png', 'gif'];

    foreach ($files['name'] as $position => $file_name) {
        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];

        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed)) {
                if ($file_size <= 1000000) {
                    $file_name_new = uniqid('image', false) . '.' . $file_ext;
                    $file_destination = 'upload/' . $file_name_new;
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $uploaded[$position] = $file_destination;
                    } else {
                        $failed[$position] = "[{$file_name}] failed to upload";

                    }
                } else {
                    $failed[$position] = "[{$file_name}] is too large, 1Mo max.";
                }
        } else {
            $failed[$position] = "[{$file_name}] file extension '{$file_ext}' is not allowed!";
        }
    }
}

if (!empty($_POST['deleteImg'])) {
    unlink("upload/".$_POST['deleteImg']);
};
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    <title>Uploading Image Files</title>
</head>
<body>
<section>
    <div class="container-fluid">
    <form method="POST" action="uploading.php" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
        <label for="imageUploader[]">Files : </label>
        <input type="file" name="imageUploader[]" multiple="multiple">
        <p><?php
            if (isset($failed)) {
                foreach ($failed as $fail) {
                    echo $fail;
                }
            }
            ?></p>
        <br>
        <button type="submit" name="submit"> Upload files ! </button>
    </form>
    </div>
</section>

<section>
    <div class="container-fluid">

        <h1 class="font-weight-light text-center text-lg-left mt-4 mb-0">Thumbnail uploading Gallery</h1>

        <hr class="mt-2 mb-5">

        <div class="row">
        <?php
        $imgFiles = scandir('upload/');
        if (!empty($imgFiles)) {
            foreach ($imgFiles as $imgFile) {
                if ($imgFile === '.' || $imgFile === '..') {

                } else {
                    ?>
                    <div class="thumbnail col-lg-3 col-md-4 col-xs-6">
                            <img class="img-fluid img-thumbnail"
                                 src="upload/<?= $imgFile ?>"
                                 alt="">
                            <p><?= $imgFile ?></p>
                            <form action="#" method="post">
                                <input type="hidden" name="deleteImg" value="<?= $imgFile ?>" />
                                <input type="submit" name="submit" value="Delete file" />
                            </form>
                    </div>
                <?php }
            }
        }?>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>