<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use App\Item;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    	switch($this->method())
    	{
    		case 'POST':
    		{
    			return [
            'barcode' => 'nullable|unique:items',
      			'name' => 'required',
      			'description' => 'nullable',
      			'retail_price' => 'required|numeric|min:1|max:999.99',
      			'wholesale_price' => 'required|numeric|min:1|max:999.99',
      			'last_purchase_price' => 'nullable|numeric|min:0.1|max:999.99' //technically not nullable since default value is 1. should be automatically populated whenever you create a purchase - same for updates
        	];
    		}
    		case 'PATCH':
    		{
    			$item = Item::find($this->segment(2));
    			if ($this->get('barcode') == $item['barcode'])
    			{
    				return [
            'barcode' => 'nullable|unique:items,id'.$this->get('id'),
      			'name' => 'required',
      			'description' => 'nullable',
      			'retail_price' => 'required|numeric|min:1|max:999.99',
      			'wholesale_price' => 'required|numeric|min:1|max:999.99',
      			'last_purchase_price' => 'nullable|numeric|min:0.1|max:999.99'
        		];
    			}
    			else
    			{
    				return [
            'barcode' => 'nullable|unique:items',
      			'name' => 'required',
      			'description' => 'nullable',
      			'retail_price' => 'required|numeric|min:1|max:999.99',
      			'wholesale_price' => 'required|numeric|min:1|max:999.99',
      			'last_purchase_price' => 'nullable|numeric|min:0.1|max:999.99'
        		];
    			}
    		}
    		default:break;
    	}
        
    }
}
