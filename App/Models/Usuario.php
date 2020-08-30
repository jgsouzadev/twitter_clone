<?php 
    
namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {
    private $id;
    private $nome;
    private $email;
    private $senha;


    public function __get($prop) {
        return $this->$prop;
    } 


    public function __set($prop, $value) {
        $this->$prop = $value;
    } 

    // salvar
    public function salvar() {
        $query = "insert into usuarios(nome, email, senha) values(?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('nome'));
        $stmt->bindValue(2, $this->__get('email'));
        $stmt->bindValue(3, $this->__get('senha')); // md5() -> hash 32 caraccteres
        $stmt->execute();

        return $this;


    }
    // validar se um cadastro pode ser feito
    public function validarCadastro() {
        $valido = true;
        if(strlen($this->__get('nome')) < 3) {
            $valido = false;
        };

        if(strlen($this->__get('email')) < 3) {
            $valido = false;
        };

        if(strlen($this->__get('senha')) < 3) {
            $valido = false;
        };
        return $valido;
    }

    // recuperar usuario por email
    
    public function getUsuarioPorEmail() {
        $query = "select nome, email from usuarios where email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue("email", $this->__get('email'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function autenticar() {
        $query = "select id,nome, email from usuarios where email = ? and senha = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('email'));
        $stmt->bindValue(2, $this->__get('senha'));
        $stmt->execute();
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($usuario['id'] != "" && $usuario['nome'] != "") {
            
            $this->__set('id', $usuario['id']);
            $this->__set('nome', $usuario['nome']);


        }
        return $usuario;


    }   

    public function getAll() {
        $query = "select 
        u.id,
        u.nome,
         u.email,
         (
             select count(*)
             from usuarios_seguidores as us
             where
             us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
         ) as seguindo_sn 
         from 
         usuarios as u 
        where u.nome like :nome and u.id != :id_usuario

        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":nome", '%'.$this->__get('nome').'%');
        $stmt->bindValue(":id_usuario", $this->__get('id'));

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id) {
        $query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) values (:id_usuario, :id_usuario_seguindo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo',$id);
        $stmt->execute();
        return true;

    }

    public function deixarSeguirUsuario($id) {
        $query = "delete from usuarios_seguidores where id_usuario = ? and id_usuario_seguindo = ?";
        $stmt=$this->db->prepare($query);
        $stmt->bindValue(1, $this->__get('id'));
        $stmt->bindValue(2, $id);
        $stmt->execute();

        return true;


    }
}

?>