<?php

namespace Emrad\Repositories;

use Emrad\Repositories\Contracts\BaseRepositoryInterface;

class BaseRepository  implements BaseRepositoryInterface
{
    /**
     * Get paginated result from the model
     *
     * @param int $limit
     * @param array $relations
     *
     * @return void
     */
      public function paginate($limit, $relations = []){
          return $this->model::with($relations)->orderBy('id', 'DESC')->paginate($limit);
      }

    /**
     * Get all result from the model
     *
     * @return void
     */
      public function all(){
          return $this->model
                      ->select()
                      ->orderBy('id', 'DESC')
                      ->get();
      }

    /**
     * Get single result from the model
     *
     * @param int $id
     * @param array $relations
     *
     * @return Model
     */
      public function find($id, $relations = []){
          return $this->model
                      ->where('id', $id)
                      ->with($relations)
                      ->first();
      }

    /**
     * Get all result with the specified relationship from the model
     *
     * @param int $id
     *
     * @return Model
     */
      public function allWith(Array $relations = []){
          return $this->model
                      ->with($relations)
                      ->get();
      }

      /**
       * Get count from the model
       *
       * @param Array $where
       *
       * @return Model
       */
      public function getCount()
      {
        return $this->model()->count();
      }

      /**
       * find the resource by the array
       */
      public function findMany(array $data)
      {
          return $this->model->findMany($data);
      }
}
