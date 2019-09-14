<?php  

namespace FlexiCreative\Repositories\Contracts;

interface BaseRepositoryInterface {

    /**
     * Get paginated result from the model 
     * 
     * @param int $limit
     * 
     * @return \Collection $model
      */
      public function paginate($limit);

    /**
     * Get all result from the model 
     *
     * @return \Collection $model
      */
      public function all();

    /**
     * Get all result from the model with 
     *
     * @return \Collection $model
      */
      public function allWith();

    /**
     * Get single result from the model 
     * 
     * @param int $id
     * 
     * @return Model $model
      */
      public function find($id);

    /**
     * Get the model count 
     * 
     * @param int $id
     * 
     * @return String|Int $count
      */
      public function getCount();
}