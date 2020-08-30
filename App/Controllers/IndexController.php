<?php 

namespace App\Controllers;

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;


class IndexController extends Action {

   
    public function index() {

        // $this->view->dados = array('Sofa', 'Cadeira', 'Cama');
      
        $this->render('index');

    }    

    public function inscreverse() {

        // $this->view->dados = array('Sofa', 'Cadeira', 'Cama');
        $this->view->usuario = array('nome' => '',
            'email' => '',
            'senha' => ''
        );
        
        $this->view->erroCadastro = false;

        
        $this->render('inscreverse');
    }  

    public function registrar() {
        
        $usuario = Container::getModel("Usuario");


        $usuario->__set('nome', $_POST['nome']);
        $usuario->__set('email', $_POST['email']);
        
        $usuario->__set('senha', md5($_POST['senha']));

        if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {

                $usuario->salvar();

                $this->render('cadastro');

        } else {

            $this->view->usuario = array('nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => $_POST['senha']
        );

            $this->view->erroCadastro = true;

            $this->render('inscreverse');
        }

    }
}


?>