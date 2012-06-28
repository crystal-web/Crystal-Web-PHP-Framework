<?php

class Zip {
private $error;
public $file;


		function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true)
		{
		  if(function_exists("zip_open"))
		  {
		      if(!is_resource(zip_open($src_file)))
		      { 
		          $src_file=dirname($_SERVER['SCRIPT_FILENAME'])."/".$src_file; 
		      }

		      if (is_resource($zip = zip_open($src_file)))
		      {          
		          $splitter = ($create_zip_name_dir === true) ? "." : "/";
		          if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
 
		          // Cr�ez les r�pertoires pour le dossier de destination si elles n'existent pas d�j�
		          $this->create_dirs($dest_dir);
 
		          // Pour chaque fichier dans l'archive de paquet
		          $zip_entry = zip_read($zip);
		          while ($zip_entry)
		          {
				  
		            // Maintenant, nous allons cr�er les r�pertoires dans les r�pertoires de destination
 
		            // Si le fichier n'est pas dans le r�pertoire racine
		            $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
		            if ($pos_last_slash !== false)
		            {
		              // Cr�ez le r�pertoire o� le fichier zip d'entr�e doivent �tre enregistr�es (avec un "/" � la fin)
		              $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
		            }
 
		            // Ouvrez l'entr�e zip
		            if (zip_entry_open($zip,$zip_entry,"r"))
		            {
 
		              // Le nom du fichier � enregistrer sur le disque
		              $file_name = $dest_dir.zip_entry_name($zip_entry);
 
		              // V�rifiez si les fichiers doivent �tre �cras�s ou non
		              if ($overwrite === true || $overwrite === false && !is_file($file_name))
		              {
		                // Recherche les fichiers dans le zip
		                $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));           
 
		                if(!is_dir($file_name))            
		                file_put_contents($file_name, $fstream );
		                // Attribue les droits
		                if(file_exists($file_name))
		                {
		                    chmod($file_name, 0777);
		                    $this->file.="<span style=\"color:#1da319;\">file saved: </span>".$file_name."<br />";
		                }
		                else
		                {
		                    $this->file.="<span style=\"color:red;\">file not found: </span>".$file_name."<br />";
		                }
		              }
 
		              // Ferme l'entr� zip
		              zip_entry_close($zip_entry);
		            }      
		          }
		          // Ferme le zip
		          zip_close($zip);
		      }
		      else
		      {
		        $this->error="N'est pas un zip valide";
		        return false;
		      }
 
		      return true;
		  }
		  else
		  {
		      if(version_compare(phpversion(), "5.2.0", "<"))
		      $infoVersion="(utiliser PHP 5.2.0 ou supp&eacute;rieur)";
 
		      $this->error="Vous devez installer/activer l'extension php_zip.dll ".$infoVersion; 
		  }
		}
 
		function create_dirs($path)
		{
		  if (!is_dir($path))
		  {
		    $directory_path = "";
		    $directories = explode("/",$path);
		    array_pop($directories);
 
		    foreach($directories as $directory)
		    {
		      $directory_path .= $directory."/";
		      if (!is_dir($directory_path))
		      {
		        mkdir($directory_path);
		        chmod($directory_path, 0777);
		      }
		    }
		  }
		}

		function error()
		{
			return $this->error;
		}
}

/*
$zip = new Zip;
$zip->unzip('test.zip','repertoire_destination/') or die($zip->error());
echo $zip->file;*/
?>