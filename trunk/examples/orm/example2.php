<?php

include('../config.php');
include('orm_config.php');
include('JoinPostMapper.php');
include('UserMapper.php');
include('DomainObjects.php');

$mapper = new JoinPostMapper($db);
$user = $mapper->getById(2);
p($user);