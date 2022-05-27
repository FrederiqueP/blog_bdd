<?php 

class ArticleModel {


    function getAllArticles(): array
    {   
        $sql = 'SELECT *
                FROM article AS A
                ORDER BY A.createdAt DESC';

        $db = new Database();

        return $db->getAllResults($sql);
    }   


    //function getOneArticle(string $idArticle): bool | array
    
    function getOneArticle(string $idArticle)
    {

        $sql = 'SELECT *
        FROM article AS A
        WHERE idArticle = ?';

        $db = new Database();

        return $db->getOneResult($sql,[$idArticle]);
    }

    function addArticle(string $title, string $abstract, string $content, string $image, int $fkUserId, int $fkCategoryId)
    {
              
        $sql = 'INSERT INTO article (title, abstract, content, image, createdAt, fkUserId, fkCategoryId) 
                VALUES (?,?,?,?, NOW(),?,?)';
        
        $values =[$title, $abstract, $content, $image, $fkUserId, $fkCategoryId];
        
        $db = new Database();

        return $db->executeQuery($sql,$values);
        
    }
}