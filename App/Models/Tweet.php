<?php 
    
namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {
    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function __get($property) {
        return $this->$property;
    }

    public function __set($property,$value) {
        $this->$property = $value;
    }

    // Salvar

    public function salvar() {

        $query = "insert into tweets(id_usuario, tweet) values (?,?)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get("id_usuario"));
        $stmt->bindValue(2, $this->__get("tweet"));
        

        $stmt->execute(); 
    }

    // Recuperar
    public function getAll() {
        $query = "
            select t.id, t.id_usuario,u.nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%m') as data from 
            tweets as t
            left join usuarios as u on(t.id_usuario = u.id)
            where id_usuario = ?
        order by
        t.data desc
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id_usuario'));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
}


?>