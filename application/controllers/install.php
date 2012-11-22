<?php
  /**
  * 
  */
  class Install extends CI_Controller
  {
    function __construct()
    {
      parent::__construct();
    }
    
    public function index($force='')
    {
      $q = Doctrine_Manager::getInstance()->connection();
      $stmt = $q->execute('show tables');
      $tables = $stmt->fetchAll();  
      foreach ($tables as $tableObject) {
        $tableName = $tableObject[0];
        $stmt = $q->execute("SHOW COLUMNS FROM $tableName;");
        $fields = $stmt->fetchAll(); 
        $this->_createModel($tableName, $fields, $force);
        $this->_createDal($tableName, $fields, $force);
        $this->_createBll($tableName, $force);
        $this->_createController($tableName, $force);
      }
      
    }
    
    
    public function _createModel($tableName, $fields, $force)
    {
      $fileName = APPPATH.'/models/model/'.$tableName.'_model.php';
      if (file_exists($fileName) && $force !='force') {
        return false;
      }
      $modelName = ucfirst($tableName).'_model';
      $stmt = "<?php\n  class $modelName extends Doctrine_Record\n";
      $stmt .= "  {\n"; 
      $stmt .= "    public function setTableDefinition()\n";
      $stmt .= "    {\n";
      $isTimestampable = 0;
      foreach ($fields as $f) {
        $stmt .="     \$this->hasColumn('".$f['Field']."');\n";
        if ($f['Field'] == 'updated_at' || $f['Field'] == 'created_at') $isTimestampable++;
      }
      $stmt .= "    }\n\n";
      $stmt .= "    public function setUp()\n";
      $stmt .= "    {\n";
      $stmt .= "      \$this->setTableName('".$tableName."');\n";
      if ($isTimestampable == 2){
        $stmt .= "      \$this->actAs('Timestampable');\n";
      }
      $stmt .= "    }\n";
      $stmt .= "  }\n"; 
      file_put_contents($fileName, $stmt);
      echo '<p>'.$modelName.' created, ok!</p>';
    }
    
    public function _createDal($tableName, $fields, $force)
    {
      $fileName = APPPATH.'/models/dal/'.$tableName.'_dal.php';
      if (file_exists($fileName) && $force !='force') {
        return false;
      }
      $dalName = ucfirst($tableName).'_dal';
      $modelName = ucfirst($tableName).'_model';
      $stmt = "<?php\n  class $dalName extends Dal\n";
      $stmt .= "  {\n"; 
      $stmt .= "    protected static \$_model_name = '$modelName';\n\n";
      $stmt .= "    protected static \$_primary_key = array('id');\n\n";
      $stmt .= "    public static function where(\$option)\n";
      $stmt .= "    {\n";
      $stmt .= "      extract(\$option);\n";
      $stmt .= "      \$q = self::from(self::\$_model_name, '".substr($tableName, 0, 1)."');\n";
      foreach ($fields as $f) {
        if (!preg_match('/^(int|enum)/i', $f['Type'])) continue;
        $stmt .= "      self::andWhereIn(\$q, '".$f['Field']."', \$".$f['Field'].");\n";
      }
      $stmt .= "      self::pagination(\$q, \$offset, \$limit);\n";
      $stmt .= "      return \$q;\n";
      $stmt .= "    }\n";
      $stmt .= "  }\n"; 
      file_put_contents($fileName, $stmt);
      echo '<p>'.$dalName.' created, ok!</p>';
    }
    
    
    public function _createBll($tableName, $force)
    {
      $fileName = APPPATH.'/models/bll/'.$tableName.'_bll.php';
      if (file_exists($fileName) && $force !='force') {
        return false;
      }
      $dalName = ucfirst($tableName).'_dal';
      $bllName = ucfirst($tableName).'_bll';
      $stmt = "<?php\n  class $bllName extends Bll\n";
      $stmt .= "  {\n"; 
      $stmt .= "    protected static \$_dal_name = '$dalName';\n";
      $stmt .= "  }\n";
      file_put_contents($fileName, $stmt);
      echo '<p>'.$bllName.' created, ok!</p>';
    }
    
    public function _createController($tableName, $force)
    {
      $fileName = APPPATH.'/controllers/'.$tableName.'.php';
      if (file_exists($fileName) && $force !='force') {
        return false;
      }
      $bllName = ucfirst($tableName).'_bll';
      $controllerName = ucfirst($tableName);
      $stmt = "<?php defined('BASEPATH') OR exit('No direct script access allowed');\n\n";
      $stmt .= "require APPPATH.'/libraries/REST_Controller.php';\n\n";
      $stmt .= "class $controllerName extends REST_Controller\n";
      $stmt .= "{\n";
      // list_get
      $stmt .= "  public function list_get()\n";
      $stmt .= "  {\n";
      $stmt .= "    \$option = \$this->option();\n";
      $stmt .= "    \$this->response($bllName::get(\$option));\n";
      $stmt .= "  }\n\n";
      //data_get
      $stmt .= "  public function data_get()\n";
      $stmt .= "  {\n";
      $stmt .= "    \$list = $bllName::get(\$this->option(), array('id'));\n";
      $stmt .= "    \$this->response(\$list[0]);\n";
      $stmt .= "  }\n\n";
      //count_get
      $stmt .= "  public function count_get()\n";
      $stmt .= "  {\n";
      $stmt .= "    \$list['count'] = $bllName::count(\$this->option());\n";
      $stmt .= "    \$this->response(\$list);\n";
      $stmt .= "  }\n\n";
      //data_post
      $stmt .= "  public function data_post()\n";
      $stmt .= "  {\n";
      $stmt .= "    \$option = \$this->option();\n";
      $stmt .= "    \$this->response($bllName::add(\$option));\n";
      $stmt .= "  }\n\n";
      //data_put
      $stmt .= "  public function data_put()\n";
      $stmt .= "  {\n";
      $stmt .= "    \$option = \$this->option();\n";
      $stmt .= "    \$this->response($bllName::edit(\$option));\n";
      $stmt .= "  }\n\n";
      //data_delete
      $stmt .= "  public function data_delete()\n";
      $stmt .= "  {\n";
      $stmt .= "    \$option = \$this->option();\n";
      $stmt .= "    \$this->response($bllName::remove(\$option));\n";
      $stmt .= "  }\n\n";
      $stmt .= "}\n";
      file_put_contents($fileName, $stmt);
      echo '<p>'.$controllerName.' created, ok!</p>';
      
    }
  }
  
  