<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mmenutop extends MY_Model
{
	public function get_data($induk=0)
	{
		$userpk = $_SESSION['userpk'];
		$data = array();
		$result = $this->db->query("SELECT  t1.menuid,t1.menuname,t1.menuicon,t3.class,t3.method
			FROM tblmenu t1 left join tblacos t3 on t1.acoid = t3.acosid
						WHERE t1.menuparent='$induk'   ORDER BY menuseq ASC");

		foreach($result->result() as $row)
		{
			$data[] = array(
					'menuid'	=>$row->menuid,
					'menuname'	=>$row->menuname,
					'menuicon'	=>$row->menuicon,
					'menuexe'	=>$row->class."/".$row->method,
					// recursive
					'child'	=>$this->get_data($row->menuid)
				);
		}

			// print_r($data);
		return $data;
	}
	public function get_child($menuid)
	{
		$data = array();
		$this->db->from('tblmenu');
		$this->db->where('menuparent',$menuid);
		$this->db->order_by("menuseq", "asc");
		$result = $this->db->get();
		foreach($result->result() as $row)
		{
			$data[$row->menuid] = $row->menuname;
		}
		return $data;
	}

	function get_menuid($menuexe){
		$userpk = $_SESSION['userpk'];
		$result = $this->db->query("SELECT t2.acl
			FROM tblmenu t1, tblusermenu t2
			WHERE t1.menuexe='$menuexe' AND t1.menuid=t2.menuid AND t2.userpk='$userpk'");
		foreach($result->result() as $row)
		{
			$data = $row->acl;
		}
		return @$data;
	}
}