<?php

namespace App\Http\Controllers;

use App\Model\OrderAddress;
use App\Model\District;
use App\Model\Village;
use Illuminate\Http\Request;

class AddressOrderController extends Controller
{
    public function getAddressSeller(Request $request){
        $idSeller = $request->input('user_id');
        if(!empty($idSeller)){
            $address = OrderAddress::where('user_id',$idSeller)->get();
            return response([
                'code' => 200,
                'message' => "Success",
                'data' => $address
            ]);
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
    public function setAddressSeller(Request $request){
        $idSeller = $request->input('user_id');
        $province = $request->input('province');
        $district = $request->input('district');
        $village = $request->input('village');
        $street = $request->input('street');
        if(!empty($idSeller)&&!empty($province)&&!empty($district)&&!empty($village)&&!empty($street)){
            $add = OrderAddress::create([
                'user_id'=>$idSeller,
                'province'=>$province,
                'district'=>$district,
                'village'=>$village,
                'street'=>$street
            ]);
            if(!empty($add)){
                return response([
                    'code'=>200,
                    'message'=>'Success',
                ]);

            }
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }
    public function getDistricts(Request $request){
        $districts = District::query()->orderBy('name','asc')->get();
        return response([
            'code'=>200,
            'message'=>'Success',
            'data'=>$districts
        ]);

    }
    public function searchDistrict(Request $request){
        $district = $request->input('district');
        $result = District::query()->where('name','like','%'.$district.'%')
            ->orderBy('name','asc')->get();
        return response([
            'code'=>200,
            'message'=>'Success',
            'data'=>$result
        ]);
    }
    public function getVillages(Request $request){
        $idDistrict = $request->input('district_id');
        $villages = Village::query()->where('id_district',$idDistrict)->orderBy('name','asc')->get();
        return response([
            'code'=>200,
            'message'=>'Success',
            'data'=>$villages
        ]);
    }
    public function searchVillage(Request $request){
        $village = $request->input('village');
        $idDistrict = $request->input('district_id');
        if(!empty($village)&&!empty($idDistrict)){
            $result = Village::query()->where('id_district',$idDistrict)->where('name','like','%'.$village.'%')
                ->orderBy('name','asc')->get();
            return response([
                'code'=>200,
                'message'=>'Success',
                'data'=>$result
            ]);
        }
        return response([
            'code' => 1002,
            'message' => 'Parameter is no enough',
        ]);
    }

}
