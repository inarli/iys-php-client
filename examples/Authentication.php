<?php
    $apiUri='https://api.sandbox.iys.org.tr';
    $iysBrandCode = 'YOUR_BRAND_CODE';
    $iysCode = 'YOUR_IYS_CODE';
    // test account
    $email = 'YOUR_ACCOUNT_MAIL';
    $password = 'YOUR_ACCOUNT_PASSWORD';

    $iys = new Iys\IysApi($apiUri,$iysCode,$iysBrandCode);

    // Oauth login is not available on production since Jan 1st, 2021. Oauth2 is suggested
    $login = $iys->authentication->login($email, $password);
    if ($login instanceof \Iys\Auth\Response\Token){
        $iys->setToken($login->getAccessToken());
        $iys->setRefreshToken($login->getRefreshToken());
    }
    dump('==Oauth==');
    dump($login);

    $oauth2 = $iys->authentication->loginWithOauth2($email, $password);
    if ($oauth2 instanceof \Iys\Auth\Response\Token){
        $iys->setToken($oauth2->getAccessToken());
        $iys->setRefreshToken($oauth2->getRefreshToken());
    }
    dump('==Oauth 2==');
    dump($oauth2);

    $refresh = $iys->authentication->refreshToken();
    if ($refresh instanceof \Iys\Auth\Response\RefreshToken){
        $iys->setRefreshToken($refresh->getRefreshToken());
        $iys->setToken($refresh->getAccessToken());
    }
    dump('==Refresh Token==');
    dump($refresh);

