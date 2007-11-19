<?php

class User extends ActiveRecord
{
    public function __construct($id = NULL)
    {
        parent::__construct();
        if ( $id ) {
            $this->set($this->primary[0], $id);
            $this->fetch();
        }
    }

    public function initFields()
    {
        $this->fields = array (
            'userID',
            'title',
            'name',
            'surname',
            'email',
            'password',
            'active'
        );
    }

    public function initTable()
    {
        $this->table = 'user';
    }

    public function initPrimary()
    {
        $this->primary[] = 'userID';
    }

    public function getFullname()
    {
        return $this->data['title'].', '.$this->data['name'].' '.$this->data['surname'];
    }
}

 $user = new User();
$user->set('title', 'Mr');
$user->set('name', 'Frodo');
$user->set('surname', 'Baggins');
$user->set('email', 'frodo@the-shire.com');
$user->insert();

$user->unsetAttr();

$rs = $user->getAll();
foreach ( $rs as $result ) {
    echo $result->get('fullname') ."\n";
    /* OR */
    echo $result->fullname ."\n";
}

$user->unsetAttr();

$user->set('name', 'Frodo');
$user->delete();

$user->unsetAttr();

$user->set('userID', 1);
$user->set('title', 'title');
$user->set('name', 'name');
$user->set('surname', 'surname');
$user->set('email', 'email');
$user->update();

$newUser = new User(1);
echo $newUser->fullname ."\n";

?>

