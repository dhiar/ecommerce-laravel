<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use App\Base;
use Illuminate\Support\Facades\DB;

class BaseRequest extends FormRequest
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

    public function createDescription()
    {
        $count = Base::count();

        if ($count == 0) {

            DB::beginTransaction();
            try {
                $base = Base::create([
                    'description'  => $this->description
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'process' => 'Create',
                    'data' => $base,
                    'message' => 'Success create base : ' . $this->description,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'process' => 'Create',
                    'data' => [],
                    'message' => 'Failed create base',
                ]);
                DB::rollback();
            }
        } else {
            $base = Base::first();

            DB::beginTransaction();
            try {
                $base->update(request()->all());
                DB::commit();

                return response()->json([
                    'success' => true,
                    'process' => 'Update',
                    'data' => $base,
                    'message' => 'Success update base : ' . $this->description,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'process' => 'Update',
                    'data' => [],
                    'message' => 'Failed create base',
                ]);
                DB::rollback();
            }
        }
    }
}
