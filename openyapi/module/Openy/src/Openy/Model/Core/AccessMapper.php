<?php

namespace Openy\Model\Core;

use Openy\Model\AbstractMapper;
use Openy\Interfaces\MapperInterface;
use Openy\Model\Hydrator\Strategy\CurrentTimestampStrategy;

class AccessMapper
	extends AbstractMapper
	implements MapperInterface
{

    protected $tableName      = 'remote_access';
    protected $primary        = 'idremoteaccess';
    //protected $tableAliasName ;//= substr('tablename',0,3);
    protected $entity         = 'Openy\Model\Core\AccessEntity';

    protected function insertGetHydratorInstance(){
    	$hydrator = parent::insertGetHydratorInstance();
    	$hydrator->addStrategy('time', new CurrentTimestampStrategy('Y-m-d H:i:s'));
    	return $hydrator;
    }


   /**
     * Method NOT ALLOWED
     *
     * {@inheritDoc}
     *
     */
    public function update($id,$data){
      return new ApiProblem(405, 'The update method is not allowed');
  }



   /**
     * Method NOT ALLOWED
     *
     * {@inheritDoc}
     *
     */
    public function delete($id){
      return new ApiProblem(405, 'The delete method is not allowed');
  }


    /**
     * Method NOT ALLOWED
     *
     * {@inheritDoc}
     *
     */
    public function replace($id,$data){
      return new ApiProblem(405, 'The replace method is not allowed');
  }



}