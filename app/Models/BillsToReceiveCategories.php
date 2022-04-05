<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillsToReceiveCategories extends Model
{
    use HasFactory;
    protected $table = 'bills_to_receive_categories';

    public function BillsReceive()
    {
        return $this->belongsTo('App\Models\BillsToReceive');
    }

    /**
     * Get's the category by id 
     *
     * @param [type] $query
     * @param [type] $id
     * @return void
     */
    public function scopeFindById($query, $id) {

        $query->where('id', $id);

        return $query;
    } 

    /**
     * search and returns data for asseble groups of categories with tree root's
     *
     * @param [type] $query
     * @return void
     */
    public function scopeAllFirstByParent($query){
        
        $query->from('bills_to_receive_categories as btpc_father')
        ->join('bills_to_receive_categories as btpc_child', 'btpc_child.parent_id', 'btpc_father.id' );
        
        return $query;
    }

    /**
     * Search a category by parentId
     *
     * @param [type] $query
     * @param [type] $parentId
     * @return void
     */
    public function scopeFindByParentId($query, $parentId = null){

        $query->where('bills_to_receive_categories.parent_id', $parentId);

        return $query;
    }

    /**
     * Search for the informed element 
     *
     * @param [type] $query
     * @param [type] $search
     * @return void
     */
    public function scopeSearch($query, $search){

        if ($search) {
			
			$search = trim($search);
			
			if (isInteger($search)) {
				$query->where('bills_to_receive_categories.id', $search);
				
			} else {
                $query->whereRaw(likePtBr('bills_to_receive_categories.name', $search));
			}
		}
		
		return $query;
    }

}
