<?php
class DevisModel extends Model {
    public function add($lastname, $firstname, $street, $cp, $country, $city, $company, $language, $mail, $tel, $fax, $site, $description, $type, $query){
       $data = new stdClass();
        $data->lastname = $lastname;
        $data->firstname = $firstname;
        $data->tel = $tel;
        $data->fax = $fax;
        $data->site = $site;
        $data->street = $street;
        $data->cp = $cp;
        $data->country = $country;
        $data->city = $city;
        $data->company = $company;
        $data->language = serialize($language);
        $data->mail = $mail;
        $data->description = $description;
        $data->type = serialize($type);
        $data->query = $query;
        $data->ip = Securite::ipX();
        $data->maketime = time();
        return $this->save($data);
    }

    public function getList($page) {
        $page = (int) $page;

        $pre = array(
            'limit' => ($page-1) .', ' . ($page * 50),
            'order' => 'id DESC',
        );
        return $this->find($pre);
    }

    public function getById($id){
        $pre = array('conditions' => array('id' => $id));
        return $this->findFirst($pre);
    }
}