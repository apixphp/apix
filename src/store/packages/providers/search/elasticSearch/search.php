<?php
/*
 * This is the official PHP client for Elasticsearch. It is designed to be a very low-level client that does not stray from the REST API.
 * All methods closely match the REST API, and furthermore, match the method structure of other language clients (ruby, python, etc).
 * We hope that this consistency makes it easy to get started with a client, and to seamlessly switch from one language to the next with minimal effort.
 * The client is designed to be "unopinionated". There are a few universal niceties added to the client (cluster state sniffing,
 * round-robin requests, etc) but largely it is very barebones. This was intentional. We want a common base that more sophisticated libraries can build on top of.
 */

namespace src\store\packages\providers\search\elasticSearch;
use src\store\packages\providers\search\searchInterface;

/**
 * Represents a elastic search class.
 *
 * main call
 * return type string
 */

class search implements searchInterface {

    public $client;

    /**
     * elastic search class.
     *
     */
    public function __construct(){

        //search client
        $this->client=\Elasticsearch\ClientBuilder::create()->build();
    }


    /**
     * elastic search ping.
     * test start
     *
     */
    public function ping($data=array()){

        //search ping
        return $this->client->ping($data);
    }


    /**
     * elastic search health.
     * test health
     *
     */
    public function health($data=array()){

        //search ping
        return $this->client->cat()->health($data);
    }

    /**
     * elastic search count.
     * Get the number of documents in an index.
     *
     */
    public function count($data=array()){

        //search ping
        return $this->client->count($data);
    }



    /**
     * elastic node stats.
     * Any time that you start an instance of Elasticsearch, you are starting a node.
     * A collection of connected nodes is called a cluster. If you are running a single node of Elasticsearch, then you have a cluster of one node.
     * @return array
     */
    public function getNodeStats()
    {
        return $this->client->nodes()->stats();
    }

    /**
     * elastic index stats.
     * Indices level stats provide statistics on different operations happening on an index.
     * The API provides statistics on the index level scope (though most stats can also be retrieved using node level scope).
     * @return array
     */
    public function getIndexStats()
    {
        return $this->client->indices()->stats();
    }

    /**
     * elastic cluster stats.
     * The Cluster Stats API allows to retrieve statistics from a cluster wide perspective. The API returns basic index metrics
     * (shard numbers, store size, memory usage) and information about the current nodes that form
     * the cluster (number, roles, os, jvm versions, memory usage, cpu and installed plugins).
     * @return array
     */
    public function getClusterStats()
    {
        return $this->client->cluster()->stats();
    }

    /**
     * elastic search getAll.
     * The Get Mappings API will return the mapping details about your indexes and types.
     * Depending on the mappings that you wish to retrieve, you can specify a number of combinations of index and type:
     * @return array
     */
    public function getAll($params=array())
    {
        return $this->client->indices()->getMapping($params);
    }

    /**
     * elastic search getSource.
     * The Get Mappings API will return the mapping details about your indexes and types.
     * Depending on the mappings that you wish to retrieve, you can specify a number of combinations of index and type:
     * @return array
     */
    public function getSource($params=array())
    {
        return $this->client->getSource($params);
    }

    /**
     * elastic search delete.
     * Which means that the index was deleted successfully and we are now back to
     * where we started with nothing in our cluster.
     * @return array
     */
    public function deleteIndex($index=null)
    {
        return $this->client->indices()->delete(['index'=>$index]);
    }

    /**
     * elastic search index exists.
     * Used to check if the index (indices) exists or not
     * @return array
     */
    public function indexExists($index=null)
    {
        return $this->client->indices()->exists(['index'=>$index]);
    }

    /**
     * elastic search type exists.
     * Used to check if the type (indices) exists or not
     * @return array
     */
    public function existsType($index=null,$type=null)
    {
        return $this->client->indices()->existsType(['index'=>$index,'type'=>$type]);
    }



    /**
     * elastic search set Map.
     * You can specify any parameters that would normally be included in a new index creation API.
     * All parameters that would normally go in the request body are located in the body parameter
     * @return array
     */
    public function setMap($data=array())
    {
        if(!array_key_exists("settings",$data)){
            $settings=[
                'number_of_shards' => 1,
                'number_of_replicas' =>0
            ];
        }
        else{
            $settings=$data['settings'];
        }
        $params = [
            'index' => $data['index'],
            'body' => [
                'settings' => $settings,
                'mappings' => [
                    $data['type'] => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => $data['properties']
                    ]
                ]
            ]
        ];

        // Create the index with mappings and settings now
        return $this->client->indices()->create($params);
    }


    /**
     * elastic search create.
     * When you add documents to Elasticsearch, you index JSON documents.
     * This maps naturally to PHP associative arrays, since they can easily be encoded in JSON. Therefore, in Elasticsearch-PHP
     * you create and pass associative arrays to the client for indexing.
     * There are several methods of ingesting data into Elasticsearch, which we will cover here.
     * @return array
     */
    public function create($data=array())
    {
        $params = [
            'index' => $data['index'],
            'type' =>$data['type'],
            'id' =>$data['id'],
            'body' =>$data['body']
        ];


        // Document will be indexed to my_index/my_type/my_id
        return $this->client->create($params);

    }


    /**
     * elastic search closer search.
     * Whereas a phrase query simply excludes documents that don’t contain the exact query phrase, a proximity query—a phrase
     * query where slop is greater than 0—incorporates the proximity of the query terms into the final relevance _score.
     * By setting a high slop value like 50 or 100,
     * you can exclude documents in which the words are really too far apart,
     * but give a higher score to documents in which the words are closer together.
     *
     * @return array
     */
    public function closerSearch($data,$filter=array())
    {
        $params['index'] =$data['index'];
        $params['type'] =$data['type'];
        $params['body']['query']['match_phrase'][$data['field']]['query']=$data['search'];
        $params['body']['query']['match_phrase'][$data['field']]['slop']=50;


        $result=$this->client->search($params);

        return $this->filterResults($result,$filter,function() use($result) {
            return $result;
        });

    }

    /**
     * elastic search search as you type.
     * Leaving postcodes behind, let’s take a look at how prefix matching can help with full-text queries.
     * Users have become accustomed to seeing search results before they have finished typing their query—so-called instant search,
     * or search-as-you-type.
     * Not only do users receive their search results in less time,
     * but we can guide them toward results that actually exist in our index.
     *
     * @return array
     */
    public function searchAsYouType($data,$filter=array())
    {
        $params['index'] =$data['index'];
        $params['type'] =$data['type'];
        $params['body']['query']['match_phrase_prefix'][$data['field']]['query']=$data['search'];
        $params['body']['query']['match_phrase_prefix'][$data['field']]['slop']=50;


        $result=$this->client->search($params);

        return $this->filterResults($result,$filter,function() use($result) {
            return $result;
        });

    }


    /**
     * elastic search match all.
     * The match_all query simply matches all documents.
     * It is the default query that is used if no query has been specified:
     *
     * @return array
     */
    public function getAllMatch($data,$filter=array())
    {
        $params['index'] =$data['index'];
        $params['type'] =$data['type'];
        $params['body']['query']['match_all']=[];


        $result=$this->client->search($params);

        return $this->filterResults($result,$filter,function() use($result) {
            return $result;
        });

    }



    /**
     * elastic search filter search.
     * document filter
     *
     * @return array
     */
    private function filterResults($result,$filter,$callback){
        $list=[];
        if(count($filter)){
            foreach ($result['hits']['hits'] as $key=>$value){
                foreach($filter as $filterVal){
                    $list[$filterVal][]=$result['hits']['hits'][$key][$filterVal];
                }

            }
        }
        if(count($list)){
            return $list;
        }

        return call_user_func($callback);

    }




}
