<?php 
/**
* @title Auth  
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

class Auth {


    /* Vérifie la connection membre/client
    **************************************/
    static function checkLogin($user, $password)
    {
    $cleaner = strtr($user, 
    'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $user = preg_replace('/([^\.a-z0-9]+)/i', '-', $cleaner);

    $pdo = DB::getInstance();
    $req = $pdo->prepare("SELECT *, `" . __SQL . "_member`.`idmember` AS `id_user` FROM `" . __SQL . "_member` WHERE `loginmember` = :login AND `passmember` = :pass ");
    $req->bindValue(':login', $user, PDO::PARAM_STR);
    $req->bindValue(':pass', Securite::Hcrypt(strtolower($user).$password), PDO::PARAM_STR);
    $req->execute();
    $result = $req->fetch();
            return $result;
    }	// END checkLogin



    /* Mise a jour lastactivity
    ***************************/
    static function updateLastactivity($idmember)
    {
    $pdo = DB::getInstance();
    $req = $pdo->prepare("UPDATE  `" . __SQL . "_member` SET  `lastactivitymember` =  '" . time() . "' WHERE  `" . __SQL . "_member`.`idmember` =:idmember LIMIT 1 ;");
    $req->bindValue(':idmember', $idmember, PDO::PARAM_INT);
    return $req->execute();
    }	// END checkLogin



    /* Recherche par id
    ********************/
    static function searchMemberById($id)
    {
    $pdo = DB::getInstance();
    $requete = $pdo->prepare("SELECT *
    FROM `" . __SQL . "_member` 
    WHERE `idmember`
            LIKE  :idmember
    ");
    $requete->bindValue(':idmember', $id, PDO::PARAM_INT);
    $requete->execute();
    if ($result = $requete->fetch(PDO::FETCH_ASSOC)) {

            $requete->closeCursor();
            return $result;
    }
    return false;
    }	// END searchMemberByMail



    /* Recherche par e-mail	
    ***********************/
    static function searchMemberByMail($mail)
    {
    $pdo = DB::getInstance();
    $requete = $pdo->prepare("SELECT *
    FROM `" . __SQL . "_member` 
    WHERE `mailmember`
            LIKE  :mail
    ");
    $requete->bindValue(':mail', $mail, PDO::PARAM_STR);
    $requete->execute();
    if ($result = $requete->fetch(PDO::FETCH_ASSOC)) {

            $requete->closeCursor();
            return $result;
    }
    return false;
    }	// END searchMemberByMail



    /* Recherche par login	
    **********************/
    static function searchMemberByLogin($login)
    {
    $pdo = DB::getInstance();
    $requete = $pdo->prepare("SELECT *
    FROM `" . __SQL . "_member` 
    WHERE `loginmember`
            LIKE  :login
    ");
    $requete->bindValue(':login', $login, PDO::PARAM_STR);
    $requete->execute();
    if ($result = $requete->fetch(PDO::FETCH_ASSOC)) {

            $requete->closeCursor();
            return $result;
    }
    return false;
    }	// END searchMemberByLogin



    /* Changement de mot de passe
    *****************************/
    static function updatePassword($iduser , $password, $user)
    {

    $pdo = DB::getInstance();
    $requete = $pdo->prepare("UPDATE  `" . __SQL . "_member`
    SET  `passmember` =  :pass
    WHERE  `" . __SQL . "_member`.`idmember` = :id ;");
    $requete->bindValue(':pass', Securite::Hcrypt(strtolower($user).$password), PDO::PARAM_STR);
    //var_dump(Securite::Hcrypt(strtolower($user).$password));
    $requete->bindValue(':id', $iduser, PDO::PARAM_INT);
    $count_res = $requete->execute();

    return ($count_res == 1);
    }	//END updatePassword



    /* Liste des membre
    *******************/
    static function listMember($start=0,$limit=30,$order='idmember', $inv='ASC'){
    $pdo = DB::getInstance();
    $requete = $pdo->prepare("SELECT *
    FROM `" . __SQL . "_member`
    ORDER BY  `" . __SQL . "_member`.`" . $order . "` " . $inv . "
    LIMIT :start, :limit
    ");
    $requete->bindValue(':start', $start, PDO::PARAM_INT);
    $requete->bindValue(':limit', $limit, PDO::PARAM_INT);
    $requete->execute();
    return $requete->fetchAll();
    }	// END listMember



    /* Recherche par login	
    **********************/
    static function count()
    {
    $pdo = DB::getInstance();
    $requete = $pdo->prepare("SELECT COUNT( * ) 
    FROM  `" . __SQL . "_member` ");
    $requete->execute();
    $arr = $requete->fetch(PDO::FETCH_ASSOC);
    return $arr['COUNT( * )'];
    }	// END count



    /* 
    Ajout d'un membre
    *****************/
    static function addMember($login, $password, $email, $hash_validation)
    {
    $cleaner = strtr($login, 
    'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
    $login = preg_replace('/([^.a-z0-9]+)/i', '-', $cleaner);

    $pdo = DB::getInstance();
    $requete = $pdo->prepare("INSERT INTO  `" . __SQL . "_member` SET
    loginmember = :login,
    passmember = :password,
    mailmember = :mail,
    lastactivitymember = :time,
    firstactivitymember = :time,
    hash_validation = :hash_validation
    ");

    $requete->bindValue(':login',$login, PDO::PARAM_STR);
    $requete->bindValue(':password', Securite::Hcrypt(strtolower($login).$password), PDO::PARAM_STR);
    $requete->bindValue(':mail',$email, PDO::PARAM_STR);
    $requete->bindValue(':time',time(), PDO::PARAM_INT);
    $requete->bindValue(':hash_validation',$hash_validation, PDO::PARAM_STR);

    $requete->execute();
    return $pdo->lastInsertId();
    }

    static function isAuth()
    {
        if ($_SESSION['user']['power_level'] < 2)
        {
        return false;
        }
        else
        {
        Auth::updateLastactivity($_SESSION['user']['id']);
        return $_SESSION['user']['power_level'];
        }
    }




    /*********************************************/
    /* Verifie le hash membre/client			 */
    /*********************************************/
    static function checkHash($hash, $mail=null)
    {
    $pdo = DB::getInstance();
        if ($mail == null && $hash != NULL)
        {

                $requete = $pdo->prepare("UPDATE  `" . __SQL . "_member` SET
                        `hash_validation` =  '',
                        `levelmember` = 2,
                        `validemember` = 'on'
                        WHERE `hash_validation`
                                LIKE  :hash;");
                $requete->bindValue(':hash', $hash, PDO::PARAM_STR);
        }
        else
        {

                $requete = $pdo->prepare("UPDATE  `" . __SQL . "_member` SET
                        `hash_validation` =  '',
                        `levelmember` = 2,
                        `validemember` = 'on'
                        WHERE `hash_validation`
                                LIKE  :hash
                                AND `mailmember`
                                        LIKE :mail");
                $requete->bindValue(':hash', $hash, PDO::PARAM_STR);
                $requete->bindValue(':mail', $mail, PDO::PARAM_STR);
        }
        $requete->execute();
        return ($requete->rowCount());
    }
	
}
