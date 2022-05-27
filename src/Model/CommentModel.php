<?php 

class CommentModel {
    function insertComment(string $content, int $idUser, int $idArticle)
    {

        $sql = 'INSERT INTO comment (content, createdAt,fkArticleId,fkUserId) VALUES (?,NOW(),?,?)';
        $values =[$content,$idArticle,$idUser];

        $db = new Database();

        return $db->executeQuery($sql,$values);

    }

    function getCommentsByArticleId(int $idArticle) {

        $sql = 'SELECT content, C.createdAt, firstname, lastname
            FROM comment  AS C
            INNER JOIN user AS U
            ON C.fkUserId = U.idUser
            WHERE fkArticleId = ?
            ORDER BY C.createdAt DESC';

        $values =[$idArticle];

        $db = new Database();

        return $db->getAllResults($sql,$values);

    }
    

}

