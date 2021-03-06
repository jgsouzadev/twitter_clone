<?php 

namespace App\Controllers;

use App\Models\Tweet;
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function validarSessao() {

        session_start();

        if($_SESSION['id'] == "" || $_SESSION['nome'] == "" || !isset($_SESSION['id']) || !isset($_SESSION['nome'])) {
            header('Location: /?login=erro');
        }
    }

    public function timeline() {
        $this->validarSessao();

    
            $tweet = Container::getModel("tweet");
            $tweet->__set('id_usuario', $_SESSION['id']);

            $tweets = $tweet->getAll();
            
            $this->view->tweets = $tweets;

            $usuario = Container::getModel("Usuario");

            $usuario->__set("id", $_SESSION['id']);

            $this->view->data = $usuario->getInfoUsuario();
            
            $this->view->total_tweets = $usuario->getTotalTweets();

            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            
            $this->view->total_seguidores = $usuario->getTotalSeguidores();


            $this->render('timeline');
    
    }

    public function tweet() {

        $this->validarSessao();
            
        
            $tweet = Container::getModel("Tweet");
            
            $tweet->__set("tweet", $_POST['tweet']);
            $tweet->__set("id_usuario", $_SESSION['id']);

            if($tweet->salvar()) {
             header("Location: /timeline?erro");   
            } else {
             header("Location: /timeline?tweet");   
            }
            
        
    }

    public function quemSeguir() {
        $this->validarSessao();
        $pesquisarPor = isset($_GET['userSeguir']) ? $_GET['userSeguir'] : "" ;
        
        $usuario = Container::getModel("Usuario");

        $usuario->__set("id", $_SESSION['id']);

        $this->view->data = $usuario->getInfoUsuario();
        
        $this->view->total_tweets = $usuario->getTotalTweets();

        $this->view->total_seguindo = $usuario->getTotalSeguindo();
        
        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $usuarios = array();

        if($pesquisarPor != "") {
            $usuario = Container::getModel("Usuario");
            $usuario->__set("nome", $pesquisarPor);
            $usuario->__set("id", $_SESSION['id']);
            $usuarios = $usuario->getAll();
        }

        $this->view->usuarios = $usuarios;

        $this->render("quemSeguir");
    }

    public function acao() {
        $this->validarSessao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : "";
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : "";

        $usuario = Container::getModel("Usuario");
        $usuario->__set('id', $_SESSION['id']);

        if($acao == 'seguir') {
            
            if($usuario->seguirUsuario($id_usuario_seguindo)) {
                header("Location: /quemSeguir");
            };

        } else if ($acao == 'DeixarSeguir') {

            if($usuario->deixarSeguirUsuario($id_usuario_seguindo)) {
                header("Location: /quemSeguir");
            };

        }



    }

    public function remover() {
        $this->validarSessao();
        $id = $_POST['id_post'];
        $tweet = Container::getModel("Tweet");
        if($tweet->removerTweet($_SESSION['id'], $id)) {
            header("Location: /timeline?sucessoAoRemover");
        } else {
            header("Location: /timeline?erroAoRemover");
        };
    }
}

?>