<?php

class AuthController extends AbstractController{
    
    public function logout() : void
    {
        session_destroy();
        $this->redirect('index.php?route=login');
    }

     public function login() : void
    {
        $error = [];

        if(!empty($_POST))
        {
            if((empty($_POST["email"])) || (empty($_POST["password"])))
            {
                $error[] = "Veuillez remplir tous les champs";
                $this->render('auth/login.phtml', ["errors" => $error]);
                exit; 
            }
            else{
                $userManager = new UserManager;
                $user = $userManager->findByEmail($_POST["email"]);
                if($user === null)
                {
                    $error[] = "Identifiants invalides";
                    $this->render('auth/login.phtml', ["errors" => $error]);
                    exit;
                }
                else
                {
                    $mdp = $user->getPassword();
                    if(password_verify($_POST["password"], $mdp))
                    {
                        $_SESSION["firstName"] = $user->getFirstName();
                        $_SESSION["lastName"] = $user->getLastName();
                        $_SESSION["pseudo"] = $user->getPseudo();
                        $_SESSION["email"] = $user->getEmail();
                        $_SESSION["role"] = $user->getRole()->value;
                        $_SESSION["id"] = $user->getId();
                        $this->redirect("?route=home");
                        exit;
                    }
                    else
                    {
                        $error[] = "Identifiants invalides";
                        $this->render('auth/login.phtml', ["errors" => $error]);
                        exit;
                    }
                }
            }
        }
        else
        {
            $this->render('auth/login.phtml', ["errors" => $error]);
            exit;
        }

    }

    public function register() : void
    {
        $error = [];

        if(!empty($_POST))
        {
            if((empty($_POST["firstName"])) || (empty($_POST["lastName"])) || (empty($_POST["pseudo"])) || (empty($_POST["email"])) || (empty($_POST["password"])) || (empty($_POST["confirmPassword"])) )
            {
                $error[] = "Veuillez remplir tous les champs";
                $this->render('auth/register.phtml', ["errors" => $error]);
                exit; 
            }
            else
            {
                $ctrl = new UserManager;
                $email = $ctrl->findByEmail($_POST["email"]);
                if($email !== null)
                {
                    $error[] = "Cet utilisateur existe déjà !";
                    $this->render('auth/register.phtml', ["errors" => $error]);
                    exit;
                }
                else
                {
                    if($_POST["password"] !== $_POST["confirmPassword"])
                    {
                        $error[] = "Les mots de passe ne correspondent pas !";
                        $this->render('auth/register.phtml', ["errors" => $error]);
                        exit;
                    }
                    else
                    {
                        $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
                        $ctrl = new UserManager;
                        $user = new User($_POST["firstName"], $_POST["lastName"], $_POST["pseudo"], $_POST["email"], $hashedPassword, Role::USER);
                        $ctrl->create($user);
                        $this->redirect("index.php?route=login");
                    }
                }
            }

        }
        else
        {
            $this->render('auth/register.phtml', ["errors" => $error]);
        }
    }

    public function notFound():void
    {
        $this->render('error/notFound.phtml', []);
    }

}