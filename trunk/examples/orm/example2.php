<?php

include('../config.php');
include('orm_config.php');
#include('JoinPostMapper.php');
#include('UserMapper.php');
#include('DomainObjects.php');

$mapper = new JoinPostMapper($db);

$user = $mapper->find(2);
$user->body = 'This is the old body';
p($user);
$user->body = 'This is the new body';
$mapper->update($user);
p($user);
$user = $mapper->find(2);
p($user);