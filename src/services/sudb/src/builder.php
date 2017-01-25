<?php
/*
 * This file is main part of the sudb.
 *
 * model is called for model file as default
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace src\services\sudb\src;
use \src\services\sudb\src\querySqlFormatter as querySqlFormatter;
use \src\services\sudb\src\selectBuilderOperation as selectBuilderOperation;
use \src\services\sudb\src\whereBuilderOperation as whereBuilderOperation;

/**
 * Represents a index class.
 *
 * main call
 * return type array
 */

class builder {


    private $model=null;
    private $select="*";
    private $find=null;
    private $where=[];
    private $page=0;
    private $order=null;
    private $groupBy=null;
    private $execute=[];
    private $querySqlFormatter;
    private $selectBuilderOperation;
    private $whereBuilderOperation;
    private $subClassOf=null;
    private $autoScope=null;

    private static $primarykey_static=null;
    private static $modelscope=null;
    private static $request=null;
    private static $toSql=null;
    private static $rand=null;
    private static $all=null;
    private static $callstatic_scope=[];
    private static $join=null;
    private static $joinType=null;
    private static $joinTypeField=null;
    private static $hasMany=null;
    private static $attach=null;
    private static $sum=null;
    private static $joiner='';
    private static $whereIn=null;
    private static $whereNotIn=null;
    private static $orWhere=[];
    private static $whereColumn=[];
    private static $whereYear=[];
    private static $whereMonth=[];
    private static $whereDay=[];
    private static $whereDate=[];
    private static $addToSelectSql=null;
    private static $having=[];

    public function __construct(querySqlFormatter $querySqlFormatter,selectBuilderOperation $selectBuilderOperation,whereBuilderOperation $whereBuilderOperation){
        $this->querySqlFormatter=$querySqlFormatter;
        $this->selectBuilderOperation=$selectBuilderOperation;
        $this->whereBuilderOperation=$whereBuilderOperation;
    }

    /**
     * scope method is main method.
     *
     * @return array
     */
    public function initial($data=null,$model=null){
        if($this->model==null){
            $this->model=$model;
        }
        return $this;
    }


    /**
     * select method is main method.
     *
     * @return array
     */
    public function select($select=null,$model=null){
        if($this->model==null){
            $this->model=$model;
        }

        if(is_array($select) && $model!==null){
            if(array_key_exists(0,$select) && count($select[0])){
                $this->select=$select[0];
            }

        }
        else{
            if(count($select)){
                $this->select=$select;
            }
        }

        return $this;
    }


    /**
     * where method is main method.
     *
     * @return array
     */
    public function where($field=null,$operator=null,$value=null,$model=null){
        if(is_callable($field)){
            if($operator!==null){
                $this->model=$operator;
            }
            call_user_func_array($field,[$this->model]);
        }
        else{

            if($this->model==null){
                $this->model=$model;
            }

            if($field!==null && $operator!==null && $value!==null){

                $this->where['field'][]=$field;
                $this->where['operator'][]=$operator;
                $this->where['value'][]=$value;
            }


        }

        return $this;
    }


    /**
     * where between method is main method.
     *
     * @return array
     */
    public function whereBetween($field=null,$operator=null,$value=null,$model=null){

        if($this->model==null){
            $this->model=$operator;
        }

        if($field!==null && is_array($field)){

            if(array_key_exists(0,$field) && array_key_exists(1,$field) && array_key_exists(2,$field)){
                $this->where['field'][]='between_'.$field[0];
                $this->where['operator'][]=$field[1];
                $this->where['value'][]=$field[2];
            }

        }
        else{

            if($field!==null && $operator!==null && $value!==null){
                $this->where['field'][]='between_'.$field;
                $this->where['operator'][]=$operator;
                $this->where['value'][]=$value;
            }

        }

        return $this;
    }


    /**
     * where between method is main method.
     *
     * @return array
     */
    public function whereNotBetween($field=null,$operator=null,$value=null,$model=null){

        if($this->model==null){
            $this->model=$operator;
        }

        if($field!==null && is_array($field)){

            if(array_key_exists(0,$field) && array_key_exists(1,$field) && array_key_exists(2,$field)){
                $this->where['field'][]='notbetween_'.$field[0];
                $this->where['operator'][]=$field[1];
                $this->where['value'][]=$field[2];
            }

        }
        else{

            if($field!==null && $operator!==null && $value!==null){
                $this->where['field'][]='between_'.$field;
                $this->where['operator'][]=$operator;
                $this->where['value'][]=$value;
            }

        }

        return $this;
    }



    /**
     * where today createdAt method is main method.
     *
     * @return array
     */
    public function today($args=null,$model=null){

        if($this->model==null){
            $this->model=$model;
        }

        $this->where['field'][]='today';
        $this->where['operator'][]=null;
        $this->where['value'][]=null;

        return $this;
    }


    /**
     * query order by.
     *
     * @return pdo class
     */
    public function orderBy($key=null,$order=null,$model=null){

        if($this->model==null){
            $this->model=$model;
        }

        if($key!==null && is_array($key)){
            $this->model=$order;

            if(count($key)){
                $this->order=['key'=>$key[0],'order'=>$key[1]];
            }

        }
        else{
            if($key!==null && $order!==null){
                $this->order=['key'=>$key,'order'=>$order];
            }

        }
        return $this;

    }

    /**
     * query group by.
     *
     * @return pdo class
     */
    public function groupBy($key=null,$model=null){

        if($this->model==null){
            $this->model=$model;
        }

        if(is_array($key)){
            $this->groupBy=$key[0];
        }
        else{
            $this->groupBy=$key;
        }


        return $this;

    }

    /**
     * paginate method is main method.
     *
     * @return array
     */
    public function paginate($paginate=null,$model=null){
        if($this->model==null){
            $this->model=$model;
        }

        if(is_array($paginate)){
            if(array_key_exists(0,$paginate)){
                $this->page=$paginate[0];
            }

        }
        else{
            $this->page=$paginate;
        }

        return $this;
    }


    /**
     * where find method.
     *
     * @return array
     */
    public function find($value=null,$select=null,$model=null){

        if($this->model==null){
            $this->model=$select;
        }

        if(is_array($value)){
            if(count($value) && array_key_exists(0,$value)){
                if(property_exists($this->model->subClassOf,"primaryKey")){
                    $this->where['field'][]=$this->model->subClassOf->primaryKey;
                }
                else{
                    $this->where['field'][]='id';
                }

                $this->where['operator'][]='=';
                $this->where['value'][]=$value[0];
            }

        }
        else{
            $this->where['field'][]='id';
            $this->where['operator'][]='=';
            $this->where['value'][]=$value;
        }


        return $this->get();
    }

    /**
     * get method is main method.
     *
     * @return array
     */
    public function get($args=null,$model=null){

        if($this->model==null){
            $this->model=$model;
        }
        return $this->allMethodProcess(function(){
            $result=$this->queryFormatter();

            if(array_key_exists("paginator",$result) && $result['paginator']>0){
                $lastpage=(int)$result['getCountAllTotal']/(int)$result['paginator'];
                return [
                    'CountAllData'=>(int)$result['getCountAllTotal'],
                    'paginator'=>(int)$result['paginator'],
                    'currentPage'=>(int)$result['currentPage'],
                    'lastPage'=>(int)ceil($lastpage),
                    'data'=>$this->getColumnsType($result['result'],$result['columns'],$result['fields'])
                ];
            }
            else{
                return $result['result'];
            }

        });

    }

    /**
     * get method is main method.
     *
     * @return array
     */
    private function queryFormatter(){
        return $this->querySqlFormatter->getSqlPrepareFormatter($this->SqlPrepareFormatterHandleObject());
    }

    /**
     * get columns type is main method.
     *
     * @return array
     */
    private function getColumnsType($data,$types,$fields){
        $list=[];
        if($fields!==null && $data!==false){
            foreach ($fields as $key=>$value) {
                foreach($data as $dkey=>$dvalue){
                    if(array_key_exists($key,$types['type'])){
                        if(preg_match('@int@is',$types['type'][$key])){
                            $list[$dkey][$key]=(int)$dvalue->$key;
                        }
                        elseif(preg_match('@float@is',$types['type'][$key])){
                            $list[$dkey][$key]=(float)$dvalue->$key;
                        }
                        elseif(preg_match('@bool@is',$types['type'][$key])){
                            $list[$dkey][$key]=(bool)$dvalue->$key;
                        }
                        else{
                            $list[$dkey][$key]=$dvalue->$key;
                        }
                    }
                    else{
                        if(preg_match('@groupBy.*Total@is',$key)){
                            $list[$dkey][$key]=(int)$dvalue->$key;
                        }
                    }

                }
            }
        }


        return $list;


    }

    /**
     * subClassOf method is main method.
     *
     * @return array
     */
    public function subClassOf($class){
        $this->subClassOf=$class;
    }

    /**
     * subClassOf method is main method.
     *
     * @return array
     */
    private function SqlPrepareFormatterHandleObject(){
        return [
            'model'=>$this->subClassOf,
            'select'=>$this->select,
            'where'=>$this->where,
            'execute'=>$this->execute,
            'paginate'=>$this->page,
            'orderBy'=>$this->order,
            'groupBy'=>$this->groupBy
        ];
    }

    /**
     * allmethodprocess method is main method.
     *
     * @return array
     */
    private function allMethodProcess($callback){
        $this->autoScope=$this->getAutoScope();
        $this->select=$this->selectBuilderOperation->selectMainProcess($this->select,$this->SqlPrepareFormatterHandleObject());
        $whereOperation=$this->whereBuilderOperation->whereMainProcess($this->where,$this->SqlPrepareFormatterHandleObject());
        $this->where=$whereOperation->where;
        $this->execute=$whereOperation->execute;
        return call_user_func($callback);
    }

    /**
     * scope method is main method.
     *
     * @return array
     */
    public function scope($data=null,$model=null){
        if($this->model==null){
            $this->model=$model;
        }
        $model=$this->model;
        if(is_array($data) && count($data)){
            $static=$model->subClassOf;
            $scopedata=(is_array($data[0])) ? $data[0] : $data;
            foreach($scopedata as $value){
                $static->modelScope($value,$model::initial([]));
            }
        }
        else{
            $static=$model->subClassOf;
            $scopedata=[$data];
            foreach($scopedata as $value){
                $static->modelScope($value,$model::initial([]));
            }
        }

        return $this;


    }

    /**
     * scope method is main method.
     *
     * @return array
     */
    private function getAutoScope(){
        $model=$this->model;
        $static=$this->model->subClassOf;
        if(property_exists($static,"scope") && array_key_exists("auto",$static->scope)){
            $this->autoScope=$static->scope['auto'];
        }
        if($this->autoScope!==null && !is_array($this->autoScope)){
            $this->autoScope=[$this->autoScope];
        }

        $this->scope($this->autoScope,$model);

    }

    /**
     * select method is main method.
     *
     * @return array
     */
    public function create($data){
        if(request=="POST"){
            if(is_array($data) && count($data)){
                return $this->querySqlFormatter->getInsertQueryFormatter($data[0],$this->subClassOf);
            }
        }

        return [
            'error'=>true,
            'message'=>'You have to use post method for insert',
        ];

    }


}