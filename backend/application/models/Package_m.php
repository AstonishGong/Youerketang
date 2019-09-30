<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Package_m extends MY_Model
{

    protected $_table_name = 'tbl_package';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "tbl_package.no asc";

    function __construct()
    {
        parent::__construct();
    }


    public function getItemsByPage($arr = array(), $pageId, $cntPerPage, $queryStr)
    {
        $this->db->select($this->_table_name . '.*, 
              tbl_sites.title as course');

        if ($queryStr != '') {
            $this->db->where(
                '( '.$this->_table_name.'.title like \'%' . $queryStr . '%\' '
                . 'or tbl_sites.title like \'%' . $queryStr . '%\' '
                . 'or '.$this->_table_name.'.no like \'%' . $queryStr . '%\' )'
            );
        }
        $this->db->like($arr)
            ->from($this->_table_name)
            ->join('tbl_sites', $this->_table_name . '.site_id = tbl_sites.id', 'left')
            ->where('tbl_sites.status', 1)
            ->order_by($this->_order_by)
            ->limit($cntPerPage, $pageId);

        $query = $this->db->get();
        return $query->result();
    }

    public function get_count($arr = array())
    {
        $this->db->like($arr)
            ->from($this->_table_name)
            ->join('tbl_sites', $this->_table_name . '.site_id = tbl_sites.id', 'left')
            ->where('tbl_sites.status', 1)
            ->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getItems()
    {
        $this->db->select('*')
            ->from($this->_table_name)
            ->where($this->_table_name . '.status', 1)
            ->order_by($this->_order_by);
        $query = $this->db->get();
        return $query->result();
    }

    public function add($arr)
    {
        $this->db->insert($this->_table_name, $arr);
        return $this->db->insert_id();
    }

    public function get_single($arr = array())
    {
        return parent::get_single($arr);
    }

    public function get_where($array = array(), $subCondition = NULL)
    {
        return parent::get_where($array, $subCondition); // TODO: Change the autogenerated stub
    }

    public function get_where_in($key, $array = array())
    {
        return parent::get_where_in($key, $array);
    }

    public function delete($item_id)
    {
        $this->db->where($this->_primary_key, $item_id);
        $this->db->delete($this->_table_name);
        return $this->getItems();
    }

    public function publish($item_id, $publish_st, $site_id = 1)
    {
        $this->db->set('status', $publish_st);
        $this->db->where($this->_primary_key, $item_id);
        $this->db->update($this->_table_name);
        return $this->getItems();
    }

    public function edit($arr, $item_id)
    {
        $this->db->where($this->_primary_key, $item_id);
        $this->db->update($this->_table_name, $arr);
        return $this->getItems();
    }

    function get_package()
    {
        $query = $this->db->get($this->_table_name);
        return $query->result();
    }

    function get_single_package($item_id)
    {
        $arr = array(
            $this->_primary_key => $item_id
        );
        return parent::get_single($arr);
    }

    public function hash($string)
    {
        return parent::hash($string);
    }
}

?>