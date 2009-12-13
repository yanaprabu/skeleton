<?php
include '../config.php';
include 'A/Db/MySQL.php';
include 'A/Db/Activerecord.php';

class Projects extends A_Db_ActiveRecord
{
	public function __construct($db=null)
    {
        $this->setColumns(array (
            'projectID',
            'title',
            'name',
            'surname',
            'email',
            'password',
            'active'
        ));
        $this->table('project');
        $this->key('projectID');
        parent::__construct($db);
    }

    public function getFullname()
    {
        return $this->data['title'].', '.$this->data['name'].' '.$this->data['surname'];
    }
}

$db = new A_Db_MySQL($config['db']);
$db->connect();
if ($db->isError()) die('ERROR: ' . $db->getMessage());

Projects::setDb($db);
$project = new Projects();

#$project->find("id='3'");
$project->find("client_id='2'");

#$project->set('title', 'Mr');
#$project->set('name', 'Frodo');
#$project->set('surname', 'Baggins');
#$project->set('email', 'frodo@the-shire.com');
//User::getInstance();
echo 'table=' . $project->getTable() . '<br/>';
dump($project->toArray());

/*

$project->insert();

$project->unsetAttr();

$rs = $project->getAll();
foreach ( $rs as $result ) {
    echo $result->get('fullname') ."\n";
    // OR
    echo $result->fullname ."\n";
}

$project->unsetAttr();

$project->set('name', 'Frodo');
$project->delete();

$project->unsetAttr();

$project->set('projectID', 1);
$project->set('title', 'title');
$project->set('name', 'name');
$project->set('surname', 'surname');
$project->set('email', 'email');
$project->update();

$newUser = new User(1);
echo $newUser->fullname ."\n";

*/