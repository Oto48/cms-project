<?php 

require 'init.php';

if(isPostRequest()) {

    $id = $_POST['id'];
    $article = new Article();
    
    if($article->deleteWithImage($id)){
        redirect('admin.php');
    } else {
        echo "Delete failed";
    }

} else {
    echo 'Failed to delete';
}

?>