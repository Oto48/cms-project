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
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($featuredImage["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        $check = getimagesize($featuredImage["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $target_dir = 'uploads/';
            $uniquesavename=time().uniqid(rand());
            $imagePath = $target_file . '_' . $uniquesavename . '.jpg';
            $filename = $featuredImage["tmp_name"];
            move_uploaded_file($filename, $imagePath);
            return $imagePath;
        } else {
            echo "File is not an image.";
        }
    }

}

?>