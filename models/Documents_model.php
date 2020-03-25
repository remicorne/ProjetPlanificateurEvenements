<?php
class Documents_model extends Model{

    public function add_document($tmp_file, $directory_path, $document_name){
            $this->check_directory($directory_path);
            if (!move_uploaded_file($tmp_file, $directory_path ."/" .$document_name)) throw new Exception('Le fichier n\'a pas pu etre ajouté sur le serveur');    
    }

    public function check_directory($directory_path){
        if (!is_dir($directory_path)) 
            if (!mkdir($directory_path)) throw new Exception('Le dossier n\'a pas pu etre crée.');
    }

    public function delete_document($numEvent, $document_name){
        if (!unlink("uploads/" .$numEvent ."/" .$document_name)) throw new Exception ('Le document n\'a pas pu etre supprimé du serveur');
    }

    
    public function is_name_taken($numEvent, $docName){
        try {
            $statement = $this->db->prepare("SELECT * FROM DocsEvent WHERE numEvent=? AND nomDoc=?");
            $statement->execute([$numEvent, $docName]);
            return count($statement->fetchAll()) != 0;
        } catch (PDOException $e) {
            $this->loader->load('error', ['title'=>"Page d'erreur",
                          'exception' => $e]);

        }
    }

}