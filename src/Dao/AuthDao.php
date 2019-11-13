<?php

namespace App\Dao;

use App\Model\Medecin;
use App\Model\Role;
use App\Model\Secretary;
use App\Model\User;
use App\Session\SessionInterface;
use \PDO;


class AuthDao extends CommonDao
{
    protected $table = "user";
    protected $class = User::class;
    private $session;
    private $user;

    function __construct(SessionInterface $session)
    {
        parent::__construct();
        $this->session = $session;
    }


    public function login($data)
    {
        $query = $this->pdo->prepare("SELECT * FROM user u  WHERE u.login =:login  and u.status=true");
        $query->execute(['login' => $data['login']]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $user = $query->fetch();
        if ($user && password_verify($_POST['password'], $user->getPassword())) {

            $this->hydrateUser($user);
            $auth = $this->getAuth($user->getId(), $user->getRole()->getTitle());
            $this->hydrateUser($auth);
            $this->session->set('auth.user', ($auth));
            // dd($auth);

            return $auth;

            // $this->session->set('auth.user', ($user));
            // $this->hydrateUser($user);
            // return $user;
        }
        return null;
    }


    public function getAuth($userId, $role)
    {
        $sql = "";
        $className = "";
        switch ($role) {
            case 'ROLE_ADMIN':
                $sql = "SELECT * FROM user  WHERE status=TRUE AND id = :id";
                $className = User::class;
                break;
            case 'ROLE_MEDECIN':
                $sql = "SELECT * FROM medecin m JOIN user u ON m.user_id =u.id WHERE status=TRUE AND u.id = :id";
                $className = Medecin::class;
                break;
            case 'ROLE_SECRETARY':
                $sql = "SELECT * FROM secretary s JOIN user u ON s.user_id =u.id WHERE u.status=TRUE AND u.id = :id";
                $className = Secretary::class;
                break;
            default:
                # code...
                break;
        }
        $query = $this->pdo->prepare($sql);
        $query->execute(['id' =>  $userId]);
        $query->setFetchMode(PDO::FETCH_CLASS,  $className);
        return $query->fetch();
    }


    public  function hydrateUser($user)
    {
        $query = $this->pdo->prepare("SELECT * FROM  role WHERE status=true  AND id=:id");
        $query->execute(['id' =>  $user->getRoleId()]);
        $query->setFetchMode(PDO::FETCH_CLASS, Role::class);
        $role = $query->fetch();
        $user->setRole($role);
    }
}
