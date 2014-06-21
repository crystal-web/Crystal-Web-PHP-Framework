<?php
/**
 * Retourne les derniers statuts d'un utilisateur Twitter et des ses amis en incluant les "retweets".
 *
 * twitter_statuses_home_timeline() retourne les 20 derniers statuts postés par l'utilisateur authentifié et ses amis.
 * Ceci est l'équivalent de "/timeline/home" sur le Web.
 *
 * Remarque: Cette fonction est identique à twitter_statuses_friends_timeline() sauf qu'elle contient aussi les statuts "retweets"
 * que twitter_statuses_friends_timeline() n'a pas (pour des raisons de compatibilité ascendante).
 * Dans une version future de l'API (API Twitter), twitter_statuses_friends_timeline() disparaitra au profit de twitter_statuses_home_timeline().
 *
 * @param $username
 * @param $password
 * @param array $parameters
 * @return mixed
 */
function twitter_statuses_home_timeline($username, $password, $parameters = array())
{
    $url = 'http://api.twitter.com/1/statuses/home_timeline.json';
    if(count($parameters))
        $url .= '?'. http_build_query($parameters);

    $context = array('method'=>'GET','timeout'=>5);
    if($username && $password)
        $context['header'] = sprintf("Authorization: Basic %s\r\n", base64_encode($username.':'.$password));
    $context = stream_context_create(array('http'=>$context));

    return json_decode(file_get_contents($url, false, $context));
}

/**
 * Retourne les derniers statuts d'un utilisateur Twitter et des ses amis.
 *
 * twitter_statuses_friends_timeline() retourne les 20 derniers statuts postés par l'utilisateur authentifié et ses amis.
 * Ceci est l'équivalent de "/timeline/home" sur le Web.
 *
 * Remarque: Pour des raisons de compatibilité ascendante, les retweets ne sont pas retournés lors de l'appel de la "friends_timeline".
 * Si vous voulez inclure les "retweets", vous devez utiliser la fonction twitter_statuses_home_timeline()
 *
 * @param $username
 * @param $password
 * @param array $parameters
 * @return mixed
 */
function twitter_statuses_friends_timeline($username, $password, $parameters = array())
{
    $url = 'http://twitter.com/statuses/friends_timeline.json';
    if(count($parameters))
        $url .= '?'. http_build_query($parameters);

    $context = array('method'=>'GET','timeout'=>5);
    if($username && $password)
        $context['header'] = sprintf("Authorization: Basic %s\r\n", base64_encode($username.':'.$password));
    $context = stream_context_create(array('http'=>$context));

    return json_decode(file_get_contents($url, false, $context));
}


/**
 * Retourne les derniers statuts d'un utilisateur Twitter.
 *
 * twitter_statuses_user_timeline() retourne les 20 derniers statuts postés par l'utilisateur authentifié.
 * Il est également possible de demander les statuts d'un autre utilisateur via le paramètre id.
 * Ceci est l'équivalent de la page "/<user>" sur le Web.
 *
 * Remarque: Pour des raisons de compatibilité ascendante, les retweets ne sont pas retournés lors de l'appel de la "user_timeline".
 *
 * @param $username
 * @param $password
 * @param array $parameters
 * @return mixed
 */
function twitter_statuses_user_timeline($username, $password, $parameters = array())
{
    if(isset($parameters['id']))
    {
        $url = "http://twitter.com/statuses/user_timeline/{$parameters['id']}.json";
        unset($parameters['id']);
    }
    else
    {
        $url = 'http://twitter.com/statuses/user_timeline.json';
    }
    if(count($parameters))
        $url .= '?'. http_build_query($parameters);

    $context = array('method'=>'GET','timeout'=>5);
    if($username && $password)
        $context['header'] = sprintf("Authorization: Basic %s\r\n", base64_encode($username.':'.$password));
    $context = stream_context_create(array('http'=>$context));

    return json_decode(file_get_contents($url, false, $context));
}


/**
 * Retourne un statut Twitter.
 *
 * twitter_statuses_show() retourne un statut dont l'ID a été spécifié en paramètre.
 * L'auteur du statut sera aussi retourné.
 *
 * @param $username
 * @param $password
 * @param $id
 * @return mixed
 */
function twitter_statuses_show($username, $password, $id)
{
    $url = "http://twitter.com/statuses/show/{$id}.json";

    $context = array('method'=>'GET','timeout'=>5);
    if($username && $password)
        $context['header'] = sprintf("Authorization: Basic %s\r\n", base64_encode($username.':'.$password));
    $context = stream_context_create(array('http'=>$context));

    return json_decode(file_get_contents($url, false, $context));
}

/**
 * Actualise le statut d'un utilisateur Twitter.
 *
 * twitter_statuses_update() actualise le status de l'utilisateur authentifié.
 *
 * @param $username
 * @param $password
 * @param $status
 * @param array $parameters
 * @return bool
 */
function twitter_statuses_update($username, $password, $status, $parameters = array())
{
    $parameters['status'] = $status;
    $content = http_build_query($parameters);

    $context = stream_context_create(array(
        'http' => array(
            'method'  => 'POST',
            'header'  => sprintf("Authorization: Basic %s\r\n", base64_encode($username.':'.$password)).
                "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => $content,
            'timeout' => 5
        )
    ));
    $ret = file_get_contents('http://twitter.com/statuses/update.xml', false, $context);

    return ( $ret !== false );
}


/**
 * Retourne les amis d'un utilisateur Twitter.
 *
 * twitter_statuses_friends() retourne les amis d'un utilisateur,
 * chacun avec son statut actuel. Ils sont classés dans l'ordre dans
 * lequel ils ont été ajoutés, les plus récents en premier, 100 à la fois.
 * (Veuillez noter que le résultat de 100 n'est pas garanti car les utilisateurs
 * suspendus seront filtrés.) Utilisez le paramètre cursor pour récupérer les amis
 * plus anciens. En l'absence d'utilisateur spécifié, les amis de l'utilisateur
 * authentifié seront retournés. Il est aussi possible de demander la liste des amis
 * d'un autre utilisateur en spécifiant les paramètres id, screen_name ou user_id.
 *
 * @param $username
 * @param $password
 * @param array $parameters
 * @return mixed
 */
function twitter_statuses_friends($username, $password, $parameters = array())
{
    $url = 'http://twitter.com/statuses/friends.json';
    if(count($parameters))
        $url .= '?'. http_build_query($parameters);

    $context = array('method'=>'GET','timeout'=>5);
    if($username && $password)
        $context['header'] = sprintf("Authorization: Basic %s\r\n", base64_encode($username.':'.$password));
    $context = stream_context_create(array('http'=>$context));

    return json_decode(file_get_contents($url, false, $context));
}


/**
 * Retourne les followers d'un utilisateur Twitter.
 *
 * twitter_statuses_followers() retourne les followers d'un utilisateur,
 * chacun avec son statut actuel. Ils sont classés dans l'ordre dans lequel
 * ils ont été ajoutés, les plus récents en premier, 100 à la fois.
 * (Veuillez noter que le résultat de 100 n'est pas garanti car les utilisateurs
 * suspendus seront filtrés.) Utilisez le paramètre cursor pour récupérer les followers plus anciens.
 *
 * @param $username
 * @param $password
 * @param array $parameters
 * @return mixed
 */
function twitter_statuses_followers($username, $password, $parameters = array())
{
    $url = 'http://twitter.com/statuses/followers.json';
    if(count($parameters))
        $url .= '?'. http_build_query($parameters);

    $context = array('method'=>'GET','timeout'=>5);
    if($username && $password)
        $context['header'] = sprintf("Authorization: Basic %s\r\n", base64_encode($username.':'.$password));
    $context = stream_context_create(array('http'=>$context));

    return json_decode(file_get_contents($url, false, $context));
}
