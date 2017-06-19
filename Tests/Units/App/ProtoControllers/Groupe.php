<?php
namespace Test\Units\App\ProtoControllers;

use App\ProtoControllers\Groupe as _Groupe;

class Groupe extends \Tests\Units\TestUnit
{
    private $result;
    private $db;

    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);
        $this->result = new \mock\Mysqli\Result();
        $this->db = new \mock\includes\SQL();
        $this->calling($this->db)->query = $this->result;;
    }
    
    public function testGetListeVide()
    {
        $this->calling($this->result)->fetch_assoc = [];
        $liste = _Groupe::getListe($this->db);
        $this->array($liste)->isEmpty();
    }
    
    public function testGetListeRempli()
    {
        $array = [
            'g_gid' => 12,
            'g_groupename' => 'POLICE BOX',
            'g_comment' => 'free for use of public',
            'g_double_valid' => 'Y', 
            'gu_gid' => 12,
            'gu_login' => 'who'
        ];
        
        $this->calling($this->result)->fetch_assoc[] = $array;
        $this->calling($this->result)->fetch_assoc[] = null;
        $liste = _Groupe::getListe($this->db);
        $this->array($liste)->hasKey(12)->child[12](function($child) use ($array)
        {
            $child->isIdenticalTo($array);
        });
    }
    
    public function testgetInfosGroupeVide()
    {
        $id = 1;
        $vide = [
                'nom' => '',
                'doubleValidation' => '',
                'comment' => ''
            ];
        $this->calling($this->result)->fetch_array = null;
        $infos = _Groupe::getInfosGroupe($id, $this->db);
        $this->array($infos)->isIdenticalTo($vide);
    }
    
    public function testgetInfosGroupeRempli()
    {
        $id = 1;
        $SQLarray = [
            'g_gid' => 1,
            'g_groupename' => 'testnom',
            'g_comment' => 'testcomment',
            'g_double_valid' => 'N', 
        ];
        $resultat = [
                'nom' => 'testnom',
                'doubleValidation' => 'N',
                'comment' => 'testcomment'
            ];
        $this->calling($this->result)->fetch_array = $SQLarray;
        $infos = _Groupe::getInfosGroupe($id, $this->db);

        $this->array($infos)->isIdenticalTo($resultat);
    }
    
    public function testgetListeIdVide()
    {
        $this->calling($this->result)->fetch_array = null;
        $array = _Groupe::getListeId($this->db);

        $this->array($array)->isEmpty();
    }
    
    public function testgetListeIdRempli()
    {
        $array = ['g_gid' => 1];
        $this->calling($this->result)->fetch_array = $array;
        $this->calling($this->result)->fetch_array[1] = null;
        $ids = _Groupe::getListeId($this->db);

        $this->array($ids)->isIdenticalTo($array);
    }
}