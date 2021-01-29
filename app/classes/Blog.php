<?php



namespace App\classes;

use App\classes\Database;



class Blog
{
    
    protected function saveBlogImage(){
        
        $pictureName = $_FILES['blog_image']['name'];

$directory = '../assets/blog_images/';

$targetFile = $directory . $pictureName;

$fileType = pathinfo($pictureName, PATHINFO_EXTENSION);

$check = getimagesize($_FILES['blog_image']['tmp_name']);

if ($check) {

    if (!file_exists($targetFile)) {

        if ($fileType == 'jpg' || $fileType = 'png') {

            if ($_FILES['blog_image']['size'] < 1000000) {
                
                move_uploaded_file($_FILES['blog_image']['tmp_name'],$targetFile);
                
                return $targetFile;
              
                
            } else {
                echo ('your files size is too large');
            }
        } else {
            echo ('please use jpg file');
        }
    } else {
        echo ('file already exist');
    }
} else {
    echo ('please use an image  file');
}


        
    }

    
    public function saveBlogInfo($data){
        
        
        
       $targetFile= Blog::saveBlogImage();
        
        $sql = "INSERT INTO blogs (category_id, blog_title, author_name, blog_description,blog_image,publication_status) VALUES ('$data[category_id]', '$data[blog_title]',  '$_SESSION[name]', '$data[blog_description]','$targetFile' ,'$data[publication_status]' )";
        $link = Database::db_connect();
        if (mysqli_query($link, $sql) ) {
            $message = "Blog info save successfully";
            return $message;
        } else {
            die('Query Problem'.mysqli_error($link));
        }


    }

    public function getAllBlogInfo(){

       $sql="SELECT b.*, c.category_name FROM blogs as b, categories as c WHERE b.category_id=c.id  ORDER BY b.id DESC";
        $link = Database::db_connect();
        if (mysqli_query($link, $sql) ) {
            $queryResult=mysqli_query($link, $sql);
            return $queryResult;
        } else {
            die('Query Problem'.mysqli_error($link));
        }



    }
    
    
    public function getBlogInfoByID($id){
        
         $sql="SELECT b.*, c.category_name FROM blogs as b, categories as c WHERE b.category_id=c.id AND b.id='$id'";
         
        $link = Database::db_connect();
        if (mysqli_query($link, $sql) ) {
            $queryResult=mysqli_query($link, $sql);
            return $queryResult;
        } else {
            die('Query Problem'.mysqli_error($link));
        }

        
    }
    
    
    public function unpublishedBlogByid($id){
        
        $sql="UPDATE blogs SET publication_status=0 WHERE id='$id'";
         
        $link = Database::db_connect();
        if (mysqli_query($link, $sql) ) {
            $message="Blog info unpublised successfully";
            return $message;
        } else {
            die('Query Problem'.mysqli_error($link));
        }
        
        
    }

       public function publishedBlogByid($id){
        
        $sql="UPDATE blogs SET publication_status=1 WHERE id='$id'";
         
        $link = Database::db_connect();
        if (mysqli_query($link, $sql) ) {
            $message="Blog info publised successfully";
            return $message;
        } else {
            die('Query Problem'.mysqli_error($link));
        }
        
        
    }
    
    public function updateBlogInfo($data) {
        $link = Database::db_connect();
        
        
        
        $imageName = $_FILES['blog_image']['name'];

        if ($imageName) {
            
           $blogId= $_POST["blog_id"];
           $BlogSql="SELECT* FROM blogs WHERE id='$blogId'";
           $queryResult=mysqli_query($link, $blogId);
          $bloginfo= mysqli_fetch_assoc($queryResult);
          unlink($bloginfo[['blog_image']]);
            $targetFile = Blog::saveBlogImage();
            $sql = "UPDATE blogs SET category_id='$data[category_id]',blog_title='$data[blog_title]',blog_description='$data[blog_description]',blog_image=' $targetFile',publication_status='$data[publication_status]', WHERE id='$data[blog_id]' ";

            if (mysqli_query($link, $sql)) {
                header("Location: manage-blog.php");
            } else {
                die('Queary Probleam'.mysqli_error($link));
            }
        } else {
             $targetFile = Blog::saveBlogImage();
            $sql = "UPDATE blogs SET category_id='$data[category_id]',blog_title='$data[blog_title]',blog_description='$data[blog_description]',blog_image=' $targetFile',publication_status='$data[publication_status]', WHERE id='$data[blog_id]' ";

            if (mysqli_query($link, $sql)) {
                header("Location: manage-blog.php");
            } else {
                die('Queary Probleam'.mysqli_error($link));
            }
        }
    }

}