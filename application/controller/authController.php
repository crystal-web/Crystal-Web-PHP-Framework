<?php
class authController extends Controller {
    public function index(){
        $session = Session::getInstance();
        $request = Request::getInstance();
        $template = Template::getInstance();
        $page = Page::getInstance(); $page->setLayout('start');
        $page->setBreadcrumb('auth', 'Authentification');
        $page->setPageTitle('Connexion');

        if ($session->isLogged()){
            /*$str = md5(uniqid());
            $str = substr($str, 0, 8 ) . '-' .
            substr($str, 8, 4) . '-' .
            substr($str, 12, 4) . '-' .
            substr($str, 16, 4) . '-' .
            substr($str, 20, 12);
            return;**/
        }

        if (isset($request->data->password, $request->data->user, $request->data->remember)){
            if (class_exists('memcache')){
                $memcache_obj = new Memcache;
                $memcache_obj->connect('127.0.0.1', 11211);
                $brute = $memcache_obj->get(__SQL . '_bruteforce_' . md5(Securite::ipX()));
                $brute = ($brute) ? $brute : 0;
                if ($brute > 5) {
                   die( json_encode( array('error' => 'Acc&egrave;s v&eacute;rrouill&eacute;.') ) );
                }
            }

            $request->data->remember = ($request->data->remember == 'true') ? true : false;

            $auth = new AuthModel();
            $check = $auth->checkPassword($request->data->user, $request->data->password, true, $request->data->remember);
            if (!$check) {
                //$session->setFlash('Identifiant incorrect');
                if (class_exists('memcache')){
                    $brute++;
                    $memcache_obj->set(__SQL . '_bruteforce_' . md5(Securite::ipX()), $brute, MEMCACHE_COMPRESSED, 300);
                }
                die( json_encode( array('error' => 'Identifiant incorrect') ) );
            } else {
                $log = new LogModel();
                $log->setLog('auth', 'Acc&egrave;s autoris&eacute; pour ' . $check->user . ' ' . Securite::ipX(), $check->id);
                $session->write('user', $check);
                die( json_encode( array('success' => $check) ) );
            }
        }

        $template->show('auth/login');
    }

    public function logout(){
        $session = Session::getInstance();
        $session->del('user');

        setcookie('oauth');
        setcookie('oauth', '', 0, '/');
        unset($_COOKIE['oauth']);

        return Router::redirect('auth');
    }

    public function oauth(){
        if(true == false && isset($_SESSION['name']) && isset($_SESSION['twitter_id'])) //check whether user already logged in with twitter
        {
            echo "Name :".$_SESSION['name']."<br>";
            echo "Twitter ID :".$_SESSION['twitter_id']."<br>";
            echo "Image :<img src='".$_SESSION['image']."'/><br>";
            echo "<br/><a href='logout.php'>Logout</a>";
        }
        else // Not logged in
        {

            $connection = new TwitterOAuth("lPL4TNJTj6PmGof4ePGfA", "p5OyK4rze7FTonrXROW2mBG6b1iMKXYrTWasPJk8Q");
            $request_token = $connection->getRequestToken(Router::url('auth/resp')); //get Request Token

            debug($request_token);
            if(	$request_token)
            {
                $token = $request_token['oauth_token'];
                $_SESSION['request_token'] = $token ;
                $_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];

                debug($connection);
                switch ($connection->http_code)
                {
                    case 200:
                        $url = $connection->getAuthorizeURL($token);
                        //redirect to Twitter .
                        debug($url);
                        //header('Location: ' . $url);
                        break;
                    default:
                        echo "Coonection with twitter Failed";
                        break;
                }

            }
            else //error receiving request token
            {
                echo "Error Receiving Request Token";
            }
        }
    }



     public function resp(){

         if(isset($_GET['oauth_token'])) {
             $connection = new TwitterOAuth("lPL4TNJTj6PmGof4ePGfA", "p5OyK4rze7FTonrXROW2mBG6b1iMKXYrTWasPJk8Q", $_SESSION['request_token'], $_SESSION['request_token_secret']);
             $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
             debug($access_token);
             if($access_token) {
                 $connection = new TwitterOAuth("lPL4TNJTj6PmGof4ePGfA", "p5OyK4rze7FTonrXROW2mBG6b1iMKXYrTWasPJk8Q", $access_token['oauth_token'], $access_token['oauth_token_secret']);
                 $params =array();
                 $params['include_entities']='false';
                 $content = $connection->get('account/verify_credentials',$params);
                debug($connection, $content);
                 if($content && isset($content->screen_name) && isset($content->name)) {
                     $_SESSION['name']=$content->name;
                     $_SESSION['image']=$content->profile_image_url;
                     $_SESSION['twitter_id']=$content->screen_name;

                     //redirect to main page.
                     //Router::redirect('auth/oauth');
                 } else {
                     echo "<h4> Login Error </h4>";
                 }
             } else {
                 echo "<h4> Login Error </h4>";
             }

         } else {
            die('error 1');
           //  header('Location: http://hayageek.com/examples/oauth/twitter/login.html');
         }
     }


    function postToMyTwitter( $mymessage ) {
        $token 			 = 'A REMPLIR';
        $token_secret 	 = 'A REMPLIR';
        $CONSUMER_KEY 	 = 'A REMPLIR';
        $CONSUMER_SECRET = 'A REMPLIR';

        $connection 	= new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $token, $token_secret);

        $twitterInfos 	= $connection->get('account/verify_credentials');

        if ( 200 == $connection->http_code ) {
            $parameters = array('status' => $mymessage);
            echo 'Message : ' . $mymessage;
            $status = $connection->post('statuses/update', $parameters);
        }
        return $status;
    }
}