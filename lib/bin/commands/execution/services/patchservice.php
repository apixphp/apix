<?php
/*
 * This file is main class of the  service named __serviceName__ on  __projectName__ project .
 * METHOD : PATCH
 * every service is called with index method as default
 * service name : __projectName__
 * namespace : src\app\__projectName__\v1\__call\__serviceName__
 * app class namespace : \src\app\__projectName__\v1\__call\__serviceName__\app
 */

namespace src\app\__projectName__\v1\__call\__serviceName__;
use src\store\services\Httprequest as request;
use src\store\services\Repository as repo;

/**
 * Represents a patchService class.
 * http method : patch
 * every method that on this service is called with patch method as http method on browser
 * every service extends app class
 * attention:provision condition can be needed for patch method
 * return type array
 */
class patchService extends app {

    public $request;
    public $forbidden=false;

    /**
     * Constructor.
     *
     * @param type dependency injection and __serviceName__ class
     * request method : symfony component
     * main loader as construct method
     */
    public function __construct(request $request){

        parent::__construct();
        $this->request=$request;
    }

    /**
     * index method is main method.
     * it is default method without needed interface implemantation
     * because method name is called on the url
     * method can produce ouput as string or array
     * converted to array everytime as output
     * produced json output as result
     * @return array @method
     */
    public function index(){

        //return index
        return ['patch'=>true];
    }
}