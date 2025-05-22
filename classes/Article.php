<?php

class Article {

    private $conn;
    private $table = 'articles';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getExcerpt($content, $length = 100) {
        if(strlen($content) > $length){
            return substr($content, 0, $length) . "...";
        }
        return $content;
    }

    public function get_all() {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table ." ORDER BY id DESC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getArticleById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table ." WHERE id = :id LIMIT 1");
        $stmt->bindParam(":id",$id);
        $stmt->execute();

        $article = $stmt->fetch(PDO::FETCH_OBJ);

        if($article) {
            return $article;
        } else {
            return false;
        }
    }

    public function getArticleWithOwnerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table ." INNER JOIN users ON articles.user_id = users.id WHERE articles.id = :id LIMIT 1");

        $stmt->bindParam(":id",$id);
        $stmt->execute();

        $article = $stmt->fetch(PDO::FETCH_OBJ);

        if($article) {
            return $article;
        } else {
            return false;
        }
    }

    public function getArticleByUser($id) {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table ." WHERE user_id = :id LIMIT 1");

        $stmt->bindParam(":id",$id);
        $stmt->execute();

        $articles = $stmt->fetchAll(PDO::FETCH_OBJ);

        if($articles) {
            return $articles;
        } else {
            return false;
        }
    }

    public function getArticlesByUser($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }

    public function createArticle($title, $content, $author_id, $created_at, $image) {
        $query = "INSERT INTO {$this->table} (title, content, user_id, created_at, image)
                VALUES (:title, :content, :user_id, :created_at, :image)";

        $stmt = $this->conn->prepare($query);

        $params = [
            ':title' => $title,
            ':content' => $content,
            ':user_id' => $author_id,
            ':created_at' => $created_at,
            ':image' => $image
        ];

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }

    public function uploadImage($featuredImage) {
        if (empty($featuredImage["tmp_name"]) || $featuredImage["error"] !== UPLOAD_ERR_OK) {
            return null;
        }

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($featuredImage["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($featuredImage["tmp_name"]);
        if($check !== false) {
            $uniquesavename = time() . uniqid(rand(), true);
            $imagePath = $target_dir . $uniquesavename . '.' . $imageFileType;

            move_uploaded_file($featuredImage["tmp_name"], $imagePath);
            return $imagePath;
        } else {
            echo "File is not an image.";
            return null;
        }
    }

    public function deleteWithImage($id) {

        $article =  $this->getArticleById($id);

        if($article){
            if($article->user_id == $_SESSION['user_id']) {

                if(!empty($article->image) && file_exists($article->image)){
                    if(!unlink($article->image)){
                        return false;
                    }
                }
                $query = "DELETE FROM " . $this->table . " WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();

            } else {
                redirect("admin.php");
            }

        }
        return false;
    }

    public function update($id, $title, $content, $author_id, $created_at, $imagePath = null) {

        $query = "UPDATE " . $this->table . " SET title = :title, content = :content, user_id = :user_id, created_at = :created_at";

        if($imagePath){
            $query .= ", image = :image";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':user_id', $author_id);
        $stmt->bindParam(':created_at', $created_at);

        if($imagePath){
            $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
        }
        return $stmt->execute();
    }

}

?>