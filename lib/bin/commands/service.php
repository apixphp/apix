<?php
/**
 * Command write.
 * type array
 * package:command runner
 * user apix
 */


class service {

    public $fileprocess;

    public function __construct(){
        $this->fileprocess=$this->fileprocess();
    }


    //service create command
    public function create ($data){

        if(!file_exists('./env.php')){
            return 'Commands execution only can be run for environment local';
        }

       foreach ($this->getParams($data) as $key=>$value){
           if($key==0){
               foreach ($value as $project=>$service){
                   $version=require ('./src/app/'.$project.'/version.php');
                   $version=(is_array($version) && array_key_exists('version',$version)) ? $version['version'] : 'v1';
                   $list=[];
                   if($this->mkdir(''.$project.'/'.$version.'/__call/'.$service)){

                       $touchReadmeParams['execution']='project_readme';
                       $touchReadmeParams['params']['projectName']=$project;
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/README.md',$touchReadmeParams);

                       $touchServiceGetParams['execution']='services/getservice';
                       $touchServiceGetParams['params']['projectName']=$project;
                       $touchServiceGetParams['params']['serviceName']=$service;
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/getService.php',$touchServiceGetParams);

                       $touchServicePostParams['execution']='services/postservice';
                       $touchServicePostParams['params']['projectName']=$project;
                       $touchServicePostParams['params']['serviceName']=$service;
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/postService.php',$touchServicePostParams);


                       $touchdeveloperParams['execution']='services/developer';
                       $touchdeveloperParams['params']['projectName']=$project;
                       $touchdeveloperParams['params']['serviceName']=$service;
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/developer.php',$touchdeveloperParams);


                       $touchappParams['execution']='services/app';
                       $touchappParams['params']['projectName']=$project;
                       $touchappParams['params']['serviceName']=$service;
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/app.php',$touchappParams);


                       $touchServiceConfParams['execution']='services/serviceConf';
                       $touchServiceConfParams['params']['projectName']=$project;
                       $touchServiceConfParams['params']['serviceName']=$service;
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/serviceConf.php',$touchServiceConfParams);


                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches');
                       $list[]=$this->touch($project.'/v1/__call/'.$service.'/branches/index.html',null);
                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches/handle');

                       $touchHandleParams['execution']='services/handle';
                       $touchHandleParams['params']['projectName']=$project;
                       $touchHandleParams['params']['serviceName']=$service;
                       $touchHandleParams['params']['handleName']='index';
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/branches/handle/index.php',$touchHandleParams);

                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches/query');
                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches/query/get');
                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches/query/post');

                       $touchQueryParams['execution']='services/query';
                       $touchQueryParams['params']['projectName']=$project;
                       $touchQueryParams['params']['serviceName']=$service;
                       $touchQueryParams['params']['queryName']='index';
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/branches/query/index.php',$touchQueryParams);


                       $touchModelParamsGet['execution']='services/query';
                       $touchModelParamsGet['params']['projectName']=$project;
                       $touchModelParamsGet['params']['serviceName']=$service;
                       $touchModelParamsGet['params']['methodName']='get';
                       $touchModelParamsGet['params']['queryName']='index';
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/branches/query/get/index.php',$touchModelParamsGet);


                       $touchModelParamsPost['execution']='services/query';
                       $touchModelParamsPost['params']['projectName']=$project;
                       $touchModelParamsPost['params']['serviceName']=$service;
                       $touchModelParamsPost['params']['methodName']='post';
                       $touchModelParamsPost['params']['queryName']='index';
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/branches/query/post/index.php',$touchModelParamsPost);

                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches/source');
                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches/source/get');
                       $list[]=$this->mkdir($project.'/v1/__call/'.$service.'/branches/source/post');

                       $touchSourceParamsGet['execution']='services/source';
                       $touchSourceParamsGet['params']['projectName']=$project;
                       $touchSourceParamsGet['params']['serviceName']=$service;
                       $touchSourceParamsGet['params']['methodName']='get';
                       $touchSourceParamsGet['params']['sourceName']='index';
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/branches/source/get/index.php',$touchSourceParamsGet);


                       $touchSourceParamsPost['execution']='services/source';
                       $touchSourceParamsPost['params']['projectName']=$project;
                       $touchSourceParamsPost['params']['serviceName']=$service;
                       $touchSourceParamsPost['params']['methodName']='post';
                       $touchSourceParamsPost['params']['sourceName']='index';
                       $list[]=$this->touch($project.'/'.$version.'/__call/'.$service.'/branches/source/post/index.php',$touchSourceParamsPost);

                       return $this->fileProcessResult($list,function(){
                           return 'service has been created';
                       });

                   }

                   return 'service fail';
               }
           }
       }

    }


    //get bin params
    public function getParams($data){
        $params=[];
        foreach ($data as $key=>$value){

            $params[]=[$key=>$value];

        }

        return $params;
    }


    //set mkdir
    public function mkdir($data){

        return $this->fileprocess->mkdir($data);
    }

    //set mkdir
    public function touch($data,$param){

        return $this->fileprocess->touch($data,$param);
    }

    //mkdir process result
    public function fileProcessResult($data,$callback){

        if(count($data)==0 OR in_array(false,$data)){

            return 'service fail';
        }
        else {

            return call_user_func($callback);
        }

    }

    //get project name
    public function getProjectName($data){

        //get project name
        foreach ($data as $key=>$value){
            return $key;
        }
    }

    //file process
    public  function fileprocess(){

        //file process new instance
        $libconf=require("./lib/bin/commands/lib/conf.php");
        $fd=require ($libconf['libFile']);
        return new filedirprocess();

    }

}