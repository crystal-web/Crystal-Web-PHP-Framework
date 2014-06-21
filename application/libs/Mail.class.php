<?php
/*##################################################
 *                              Mail.class.php
 *                            -------------------
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/
class Mail {
    private $header;			// Message header
    private $frontiere;			// Boundary

    private $to;				// Destinataire
    private $from = false;		// Exepéditeur
    private $subject;			// Objet du mail
    private $message;			// Message a envoyé

    private $siteconfig;		// Configuration du site

    public function __construct($to , $subject , $message, $from = false) {
        $this->siteconfig = Config::getInstance();

        $this->frontiere = '-----=' . md5(uniqid(mt_rand()));
        $this->to = $to;
        $this->subject = $subject;


        $this->message = $message . PHP_EOL . PHP_EOL .
            '---------------------------------------------------------------------------------------------' . PHP_EOL .
            'In case of abuse or use by a third party, do not hesitate to let us know' . PHP_EOL .
            'Contact: ' . $this->siteconfig->getSiteMailContact() . PHP_EOL .
            'IP: '.Securite::ipX() . PHP_EOL .
            'Date: '.date('r');
        $this->from = ($from) ? $from : $this->siteconfig->getSiteMail();
    }


    private function loadHeader() {
        $this->headers = 'From: "'. $this->siteconfig->getSiteTeam().'" <'.$this->from.'>'."\n";

        $this->headers .= 'Return-Path: <'.$this->siteconfig->getSiteMailContact().'>'."\n";
        $this->headers .= 'MIME-Version: 1.0'."\n";
        $this->headers .= 'Content-Type: multipart/alternative; boundary="'.$this->frontiere.'"';
    }


    public function sendMail() {
        $this->loadHeader();

        $message = 'This is a multi-part message in MIME format.'."\n\n";

        // Message PLAIN
        $message .= '--'.$this->frontiere."\n";
        $message .= 'Content-Type: text/plain; charset="utf-8"'."\n";
        $message .= 'Content-Transfer-Encoding: 8bit'."\n\n";
        try {
            $html2text = new Html2text(clean($this->message, 'stripbbcode'));
            $msg = $html2text->get_text()."\n\n";
        }catch (Exception $e) {
            $msg = clean($this->message, 'stripbbcode')."\n\n";
        }

        $message .= $msg."\n\n";


        /* Message HTML
        $message .= '--'.$this->frontiere."\n";
        $message .= 'Content-Type: text/html; charset="utf-8"'."\n";
        $message .= 'Content-Transfer-Encoding: 8bit'."\n\n";
        $message .= $this->mailInTemplate."\n\n"; //*/

        $message .= '--'.$this->frontiere."--\n\n";

        return mail($this->to, $this->subject, $message, $this->headers);
    }
}