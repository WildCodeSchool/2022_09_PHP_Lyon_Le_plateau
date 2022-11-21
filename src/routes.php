<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index'],
    'home' => ['HomeController', 'index'],
    'games' => ['GameController', 'gamesPages'],
    'games/add' => ['GameController', 'addAdmin'],
    'users/add' => ['UserController', 'addAdmin'],
    'games/show' => ['GameController', 'index'],
    'users/show' => ['UserController', 'index'],
    'games/edit' => ['GameController', 'editAdmin', ['id']],
    'users/edit' => ['UserController', 'editAdmin', ['id']],
    'users/editPassword' => ['UserController', 'editPasswordAdmin', ['id']],
    'contact' => ['ContactController', 'add',],
    'contact/show' => ['ContactController', 'index',],
    'contact/isread' => ['ContactController', 'changeReadStatus', ['id']],
    'users/login' => ['UserController', 'login'],
    'users/logout' => ['UserController', 'logout'],
    'users/register' => ['UserController', 'publicRegister'],
    'myaccount' => ['BorrowController', 'myAccount'],
    'myaccount/myborrow' => ['BorrowController', 'addBorrow', ['id']],
    'myaccount/myborrow/cancel' => ['BorrowController', 'cancelBorrow', ['id']],
    'myaccount/manageRequests' => ['BorrowController', 'manageRequests', ['id', 'status']],
    'myaccount/returngame' => ['BorrowController', 'giveBackGame', ['id']],
    'myaccount/addgame' => ['GameController', 'addPublic'],
    'myaccount/editgame' => ['GameController', 'editPublic', ['id']],
    'myaccount/gameavailability' => ['GameController', 'editGameAvailability', ['id', 'availability']],
];
