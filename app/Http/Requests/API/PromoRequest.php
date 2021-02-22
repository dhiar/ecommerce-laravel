<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use App\Promo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PromoRequest extends FormRequest
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
        return [
            //
        ];
    }

    public function store()
    {
        $count = Promo::count();

        if ($count == 0) {
            $promo = Promo::create(request()->all());
            DB::beginTransaction();
            try {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'process' => 'Create',
                    'data' => $promo,
                    'message' => 'Success create promo',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'process' => 'Create',
                    'data' => [],
                    'message' => 'Failed create promo',
                ]);
                DB::rollback();
            }
        } else {
            $promo = Promo::first();

            // remov un-used attributes
            $model = new Promo();
            $table = $model->getTable();
            $columns = Schema::getColumnListing($table);
            $params = collect(request()->all())->only($columns);
            $params = collect($params)->except(['id', 'created_at', 'updated_at', 'deleted_at' ]);
            $params = $params->toArray();

            DB::beginTransaction();
            try {
                $promo->update(request()->all());
                DB::commit();

                $promo = Promo::first();

                return response()->json([
                    'success' => true,
                    'process' => 'Update',
                    'data' => $promo,
                    'message' => 'Success update promo',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'process' => 'Update',
                    'data' => [],
                    'message' => 'Failed update promo',
                ]);
                DB::rollback();
            }
        }
    }

}
