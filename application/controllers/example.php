<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Example extends REST_Controller
{

  /**
   * @param int $id
   * @return array
   * [
   *   {id,name,created_at}
   * ]
   */
    public function list_get()
    {
      $option = $this->option();
      $this->response(Chatroom_bll::get($option));
    }

    /**
     * @param int $id (required)
     * @return object 
     * {id,name,created_at}
     */
    public function data_get()
    {
      $list = Chatroom_bll::get($this->option(), array('id'));
      $this->response($list[0]);
    }

    /**
     * @param int $name 
     */
    public function data_post()
    {
      $option = $this->option();
      $this->response(Chatroom_bll::add($option));
    }

    /**
     * @param int $id (required)
     * @param string $name
     */
    public function data_put()
    {
      $option = $this->option();
      $this->response(Chatroom_bll::edit($option));
    }

    /**
     * @param int $id (required)
     */
    public function data_delete()
    {
      $option = $this->option();
      $this->response(Chatroom_bll::remove($option));
    }
    
    
}




