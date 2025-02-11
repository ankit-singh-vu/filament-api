<?php

namespace App\Http\Controllers;


use App\Models\Admin;
use App\Models\AdminAccessToken;
use App\Models\Catalog;
use App\Models\TenantCatalogItem;
use App\Models\GlobalCatalogItem;
use App\Models\PrivateCatalogItem;
use App\Models\PartnerCatalogItem;
use App\Models\Partners;
use App\Models\CatalogCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CatalogController extends Controller
{

    public static function list($request)
    {
        $tenant_id = $request->query('tenant_id');
        $catalog = Catalog::where('tenant_id', $tenant_id)
            ->first();

        $data = json_decode($catalog->catalog);
        // dd($data);
        if (!empty($catalog)) {
            return response()->json($data);
        } else {
            $response = array();
            $response["message"] = "Catalog not found";
            $response["error"] = true;
            return response()->json($response, 404);  //401 Not Found
        }
    }

    public static function tenantcataloglist($request, $tenant_id)
    {
        $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
            ->get();
        $response = array();
        $services = array();
        foreach ($catalog as $key => $value) {
            if ($value->global_catalog_item_id != 0) {
                $catalogitem = GlobalCatalogItem::find($value->global_catalog_item_id);
                if ($catalogitem) {
                    $catalogarr = array();
                    $catalogarr["name"] = $catalogitem->name;
                    $catalogarr["imagesrc2"] = $catalogitem->imagesrc2;
                    $catalogarr["imagesrc"] = $catalogitem->imagesrc;
                    $catalogarr["src"] = $catalogitem->src;
                    $catalogarr["description"] = $catalogitem->description;
                    $catalogarr["category"] = CatalogCategory::find($catalogitem->category);
                    array_push($services, $catalogarr);
                }
            }
        }
        foreach ($catalog as $key => $value) {
            if ($value->private_catalog_item_id != 0) {
                $catalogitem = PrivateCatalogItem::find($value->private_catalog_item_id);
                if ($catalogitem) {
                    $catalogarr = array();
                    $catalogarr["name"] = $catalogitem->name;
                    $catalogarr["imagesrc2"] = $catalogitem->imagesrc2;
                    $catalogarr["imagesrc"] = $catalogitem->imagesrc;
                    $catalogarr["src"] = $catalogitem->src;
                    $catalogarr["description"] = $catalogitem->description;
                    $catalogarr["category"] = CatalogCategory::find($catalogitem->category);
                    array_push($services, $catalogarr);
                }
            }
        }
        if (!empty($catalog)) {
            $response['services'] = $services;
            return response()->json($response);
        } else {
            $response = array();
            $response["message"] = "Catalog not found";
            $response["error"] = true;
            return response()->json($response, 404);  //401 Not Found
        }
    }

    public static function tenantcataloglistedit($request, $tenant_id)
    {
        $status = $request->input('status');
        $catalog_item_id = $request->input('catalog_item_id');
        $type = $request->input('type');
        if ($status == 0 && $type == 'global') {
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
            ->where('global_catalog_item_id',  $catalog_item_id)
            ->first();
            if(!empty($catalog)){
                $catalog->delete();
            }
        }
        if ($status == 0 && $type == 'private') {
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
            ->where('private_catalog_item_id',  $catalog_item_id)
            ->first();
            if(!empty($catalog)){
                $catalog->delete();
            }
        }
        if ($status == 1 && $type == 'global') {
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
            ->where('global_catalog_item_id',  $catalog_item_id)
            ->first();
            if(empty($catalog)){
                $user = TenantCatalogItem::create(array(
                    'tenant_id' => $tenant_id,
                    'global_catalog_item_id' => $catalog_item_id
                ));
            }
        }
        if ($status == 1 && $type == 'private') {
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
            ->where('private_catalog_item_id',  $catalog_item_id)
            ->first();
            if(empty($catalog)){
                $user = TenantCatalogItem::create(array(
                    'tenant_id' => $tenant_id,
                    'private_catalog_item_id' => $catalog_item_id
                ));
            }
        }

        $response['message'] = "Status Updated Succesfully";
        return response()->json($response);

    }

    public static function partnercataloglistedit($request, $partner_id)
    {
        $status = $request->input('status');
        $catalog_item_id = $request->input('catalog_item_id');
        $type = $request->input('type');
        if ($status == 0 && $type == 'global') {
            $catalog = PartnerCatalogItem::where('partner_id',  $partner_id)
            ->where('global_catalog_item_id',  $catalog_item_id)
            ->first();
            if(!empty($catalog)){
                $catalog->delete();
            }
        }

        if ($status == 1 && $type == 'global') {
            $catalog = PartnerCatalogItem::where('partner_id',  $partner_id)
            ->where('global_catalog_item_id',  $catalog_item_id)
            ->first();
            if(empty($catalog)){
                $user = PartnerCatalogItem::create(array(
                    'partner_id' => $partner_id,
                    'global_catalog_item_id' => $catalog_item_id
                ));
            }
        }
        $response['message'] = "Status Updated Succesfully";
        return response()->json($response);
    }

    public static function globalcataloglist($request)
    {
        $catalog = GlobalCatalogItem::all();
        return response()->json($catalog);
    }

    public static function privatecataloglist($request, $tenant_id)
    {
        $catalog = PrivateCatalogItem::where('tenant_id',  $tenant_id)->get();
        return response()->json($catalog);
    }

    public static function tenantcataloglistenabled($request, $tenant_id)
    {
        $globalcatalog = GlobalCatalogItem::all();
        $proccessedCatalog =  array();
        $c = 0;
        foreach ($globalcatalog as $key => $globalcatalogitem) {
            $proccessedCatalog[$c] = $globalcatalogitem;
            $proccessedCatalog[$c]->type = "global";
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
                ->where('global_catalog_item_id',  $globalcatalogitem->id)
                ->first();

            if (!empty($catalog)) {
                $proccessedCatalog[$c]->status = 1; //enabled
            } else {
                $proccessedCatalog[$c]->status = 0; //disabled
            }
            $c++;
        }

        $privatecatalog = PrivateCatalogItem::where('tenant_id',  $tenant_id)->get();
        foreach ($privatecatalog as $key => $privatecatalogitem) {
            $proccessedCatalog[$c] = $privatecatalogitem;
            $proccessedCatalog[$c]->type = "private";
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
                ->where('private_catalog_item_id',  $privatecatalogitem->id)
                ->first();

            if (!empty($catalog)) {
                $proccessedCatalog[$c]->status = 1; //enabled
            } else {
                $proccessedCatalog[$c]->status = 0; //disabled
            }
            $c++;
        }
        return response()->json($proccessedCatalog);
    }

    public static function tenantcataloglistadd(Request $request, $tenant_id) {
        $allRequestData = $request->all();
        // dd($allRequestData["name"]);
        $data = PrivateCatalogItem::create(array(
            // "tenant_id" => $allRequestData["tenant_id"],
            "name" => $allRequestData["name"],
            "imagesrc2" => $allRequestData["imagesrc"],
            "imagesrc" => $allRequestData["imagesrc"],
            "src" => $allRequestData["src"],
            "description" => $allRequestData["description"],
            "category" => $allRequestData["category"]
        ));
        return response()->json($data);
    }

    //--------  WEB ----------------------
    public function cataloglistforupdate(Request $request) {
        $catalog = GlobalCatalogItem::all();
        return view('catalog.list', ['catalog' => $catalog]);
    }

    public static function tenantcataloglistenabledweb(Request $request,  $tenant_id)
    {

        $partner = Partners::where('tenant_id',  $tenant_id)
        ->first();

        $partnercatalog = PartnerCatalogItem::where('partner_id',  $partner->id)
        ->get();

        $globalcatalog =  array();
        $c = 0;
        foreach ($partnercatalog as $key => $partnercatalogitem) {
            $catalog = GlobalCatalogItem::where('id',  $partnercatalogitem->global_catalog_item_id)
                ->first();
            if (!empty($catalog)) {
                $globalcatalog[$c] = $catalog;
                $c++;
            }
        }

        $proccessedCatalog =  array();
        $c = 0;
        foreach ($globalcatalog as $key => $globalcatalogitem) {
            $proccessedCatalog[$c] = $globalcatalogitem;
            $proccessedCatalog[$c]->type = "global";
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
                ->where('global_catalog_item_id',  $globalcatalogitem->id)
                ->first();

            if (!empty($catalog)) {
                $proccessedCatalog[$c]->status = 1; //enabled
            } else {
                $proccessedCatalog[$c]->status = 0; //disabled
            }
            $c++;
        }

        $privatecatalog = PrivateCatalogItem::where('tenant_id',  $tenant_id)->get();
        foreach ($privatecatalog as $key => $privatecatalogitem) {
            $proccessedCatalog[$c] = $privatecatalogitem;
            $proccessedCatalog[$c]->type = "private";
            $catalog = TenantCatalogItem::where('tenant_id',  $tenant_id)
                ->where('private_catalog_item_id',  $privatecatalogitem->id)
                ->first();

            if (!empty($catalog)) {
                $proccessedCatalog[$c]->status = 1; //enabled
            } else {
                $proccessedCatalog[$c]->status = 0; //disabled
            }
            $c++;
        }
        // return response()->json($proccessedCatalog);
        return view('partner.catalog.listswitch', ['catalog' => $proccessedCatalog]);
    }

    public function getcatalogitem(Request $request) {
        $id = $request->query('id');
        $catalogitem = GlobalCatalogItem::where('id',  $id)
        ->first();
        return view('admin.catalog.view', ['catalogitem' => $catalogitem]);
    }

    //from admin
    public static function partnercataloglistadminweb(Request $request)
    {
        $partner_id = 1;
        $globalcatalog = GlobalCatalogItem::all();
        $proccessedCatalog =  array();
        $c = 0;
        foreach ($globalcatalog as $key => $globalcatalogitem) {
            $proccessedCatalog[$c] = $globalcatalogitem;
            $proccessedCatalog[$c]->type = "global";
            $catalog = PartnerCatalogItem::where('partner_id',  $partner_id)
                ->where('global_catalog_item_id',  $globalcatalogitem->id)
                ->first();

            if (!empty($catalog)) {
                $proccessedCatalog[$c]->status = 1; //enabled
            } else {
                $proccessedCatalog[$c]->status = 0; //disabled
            }
            $c++;
        }
        // return response()->json($proccessedCatalog);
        // dd($proccessedCatalog);
        return view('admin.catalog.list', ['catalog' => $proccessedCatalog]);
    }

    //from admin
    public static function partnercataloglistadminwebswitch(Request $request, $partner_id)
    {
        // $partner_id = 1;
        $globalcatalog = GlobalCatalogItem::all();
        $proccessedCatalog =  array();
        $c = 0;
        foreach ($globalcatalog as $key => $globalcatalogitem) {
            $proccessedCatalog[$c] = $globalcatalogitem;
            $proccessedCatalog[$c]->type = "global";
            $catalog = PartnerCatalogItem::where('partner_id',  $partner_id)
                ->where('global_catalog_item_id',  $globalcatalogitem->id)
                ->first();

            if (!empty($catalog)) {
                $proccessedCatalog[$c]->status = 1; //enabled
            } else {
                $proccessedCatalog[$c]->status = 0; //disabled
            }
            $c++;
        }
        // return response()->json($proccessedCatalog);
        // dd($proccessedCatalog);
        return view('admin.catalog.listswitch', ['catalog' => $proccessedCatalog]);
    }


    public static function partnercataloglistweb(Request $request)
    {
        $partner_id = userData()->id;
        $partnercatalog = PartnerCatalogItem::where('partner_id',  $partner_id)
        ->get();
        // dd($partnercatalog);
        $proccessedCatalog =  array();
        $c = 0;
        foreach ($partnercatalog as $key => $partnercatalogitem) {

            $catalog = GlobalCatalogItem::where('id',  $partnercatalogitem->global_catalog_item_id)
                ->first();
                // dd($catalog);
            if (!empty($catalog)) {
                $proccessedCatalog[$c] = $catalog;
                // $proccessedCatalog[$c]->type = "global";
                // $proccessedCatalog[$c]->status = 1; //enabled
                $c++;
            } else {
                // $proccessedCatalog[$c]->status = 0; //disabled
            }
           
        }

                // dd($proccessedCatalog);

        // return response()->json($proccessedCatalog);
        return view('partner.catalog.list', ['catalog' => $proccessedCatalog]);
    }


    public function getcatalogitempartner(Request $request) {
        $id = $request->query('id');
        $catalogitem = GlobalCatalogItem::where('id',  $id)
        ->first();
        return view('partner.catalog.view', ['catalogitem' => $catalogitem]);
    }
    
    public function importcatalogtenant(Request $request, $tenant_id) {
        $allRequestData = $request->all();
        $data = PrivateCatalogItem::create(array(
            "tenant_id" => $tenant_id,
            "name" => "",
            "imagesrc2" => "",
            "imagesrc" => "",
            "src" => $allRequestData["src"],
            "description" => "",
            "category" => 0
        ));
        return response()->json($data);
    }    

}
